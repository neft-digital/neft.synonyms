<?php

/**
 * @author Alexander Balya <alexander.balya@gmail.com>
 * @link https://balya.ru
 * @copyright 2020 NEFT.digital
 */

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Config as Conf;
use Bitrix\Main\Config\Option;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\IO\Directory;
use Bitrix\Main\Entity\Base;
use Bitrix\Main\EventManager;

Loc::loadMessages(__FILE__);

class neft_synonyms extends CModule
{
  public function __construct()
  {
    $this->MODULE_ID = str_replace("_", ".", get_class($this));
    $arModuleVersion = array();
    include(__DIR__ . "/version.php");
    $this->MODULE_VERSION = $arModuleVersion['VERSION'];
    $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
    $this->MODULE_NAME = Loc::getMessage("NEFT_SYNONYMS_MODULE_NAME");
    $this->MODULE_DESCRIPTION = Loc::getMessage("NEFT_SYNONYMS_MODULE_DESCRIPTION");
    $this->PARTNER_NAME = Loc::getMessage("NEFT_SYNONYMS_PARTNER_NAME");
    $this->PARTNER_URI = Loc::getMessage("NEFT_SYNONYMS_PARTNER_URI");
    $this->SHOW_SUPER_ADMIN_GROUP_RIGHTS = 'Y';
    $this->MODULE_GROUP_RIGHTS = "Y";
  }

  /**
   * Определяет путь размещения модуля.
   *
   * @param boolean $notDocumentRoot
   * @return string
   */
  public function GetPath($notDocumentRoot = false)
  {
    if ($notDocumentRoot) {
      return str_ireplace(Application::getDocumentRoot(), '', dirname(__DIR__));
    } else {
      return dirname(__DIR__);
    }
  }

  /**
   * Проверяет, поддерживает ли система D7.
   *
   * @return boolean
   */
  public function isVersionD7()
  {
    return CheckVersion(ModuleManager::getVersion('main'), '14.00.00');
  }

  /**
   * Запускает процедуру инсталляции модуля.
   *
   * @link https://dev.1c-bitrix.ru/api_help/main/reference/cmodule/doinstall.php
   * @return bool
   */
  public function DoInstall()
  {
    global $APPLICATION;
    if ($this->isVersionD7()) {
      ModuleManager::registerModule($this->MODULE_ID);
      $this->InstallDB();
      $this->InstallEvents();
      $this->InstallFiles();
    } else {
      $APPLICATION->ThrowException(
          Loc::getMessage("NEFT_SYNONYMS_INSTALL_ERROR_VERSION")
      );
    }

    $APPLICATION->IncludeAdminFile(
        Loc::getMessage("NEFT_SYNONYMS_INSTALL_TITLE"),
        $this->GetPath() . "/install/step1.php"
    );
  }

  /**
   * Запускает процедуру деинсталляции модуля.
   *
   * @link https://dev.1c-bitrix.ru/api_help/main/reference/cmodule/douninstall.php
   * @return bool
   */
  public function DoUninstall()
  {
    global $APPLICATION;
    $context = Application::getInstance()->getContext();
    $request = $context->getRequest();

    if ($request["step"] < 2) {
      $APPLICATION->IncludeAdminFile(
          Loc::getMessage("NEFT_SYNONYMS_UNINSTALL_TITLE"),
          $this->GetPath() . "/install/unstep1.php"
      );
    } elseif ($request["step"] == 2) {
      $this->UnInstallEvents();
      $this->UnInstallFiles();
      if ($request["savedata"] != "Y") {
        $this->UnInstallDB();
      }
      ModuleManager::unRegisterModule($this->MODULE_ID);
      $APPLICATION->IncludeAdminFile(
          Loc::getMessage("NEFT_SYNONYMS_UNINSTALL_TITLE"),
          $this->GetPath() . "/install/unstep2.php"
      );
    }
  }

  /**
   * Создает таблицы в базе данных при инсталяции модуля.
   *
   * @see DoInstall()
   * @return void
   */
  public function InstallDB()
  {
    Loader::includeModule($this->MODULE_ID);
    if (!Application::getConnection(\Neft\Synonyms\SynonymsTable::getConnectionName())->isTableExists(
        Base::getInstance("\Neft\Synonyms\SynonymsTable")->getDBTableName()
    )) {
      Base::getInstance("\Neft\Synonyms\SynonymsTable")->createDbTable();
    }
  }

  /**
   * Удаляет таблицы из базы данных при деинсталяции модуля.
   *
   * @see DoUninstall()
   * @return void
   */
  public function UnInstallDB()
  {
    Loader::includeModule($this->MODULE_ID);
    Application::getConnection(\Neft\Synonyms\SynonymsTable::getConnectionName())
      ->queryExecute("drop table if exists " . Base::getInstance("\Neft\Synonyms\SynonymsTable")
      ->getDBTableName());
  }

  public function InstallEvents()
  {
    EventManager::getInstance()->registerEventHandler(
        'search',
        'OnAfterIndexAdd',
        $this->MODULE_ID,
        '\Neft\Synonyms\SearchTitleExtender',
        'OnAfterIndexAdd'
    );
    EventManager::getInstance()->registerEventHandler(
        'search',
        'BeforeIndex',
        $this->MODULE_ID,
        '\Neft\Synonyms\SearchTitleExtender',
        'BeforeIndex'
    );
    return true;
  }

  public function UnInstallEvents()
  {
    EventManager::getInstance()->unRegisterEventHandler(
        'search',
        'OnAfterIndexAdd',
        $this->MODULE_ID,
        '\Neft\Synonyms\SearchTitleExtender',
        'OnAfterIndexAdd'
    );
    EventManager::getInstance()->unRegisterEventHandler(
        'search',
        'BeforeIndex',
        $this->MODULE_ID,
        '\Neft\Synonyms\SearchTitleExtender',
        'BeforeIndex'
    );
    return true;
  }

  /**
   * Копирует файлы модуля в систему при установке модуля.
   *
   * @return boolean
   */
  public function InstallFiles($arParams = array())
  {
    CopyDirFiles(
        $this->GetPath() . "/install/admin/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/",
        true,
        true
    );
    CopyDirFiles(
        $this->GetPath() . "/install/themes/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/",
        true,
        true
    );
    CopyDirFiles(
        $this->GetPath() . "/install/components/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/",
        true,
        true
    );
    return true;
  }

  /**
   * Удаляет файлы модуля из системы при удалении модуля.
   *
   * @return void
   */
  public function UnInstallFiles($arParams = array())
  {
    DeleteDirFiles(
        $this->GetPath() . "/install/admin/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/admin/"
    );
    DeleteDirFiles(
        $this->GetPath() . "/install/themes/.default/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/"
    );
    DeleteDirFiles(
        $this->GetPath() . "/install/themes/.default/images/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/themes/.default/images/"
    );
    DeleteDirFiles(
        $this->GetPath() . "/install/components/",
        $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/"
    );
    DeleteDirFilesEx(
        "/bitrix/components/neft/synonyms.edit"
    );
    return true;
  }
}
