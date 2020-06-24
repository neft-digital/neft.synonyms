<?php
/**
 * Конфигурация административного меню модуля.
 * @link https://dev.1c-bitrix.ru/api_help/main/general/admin.section/menu.php
 */

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

/**
 * parent_menu
 *
 * global_menu_content - раздел "Контент"
 * global_menu_marketing - раздел "Маркетинг"
 * global_menu_store - раздел "Магазин"
 * global_menu_services - раздел "Сервисы"
 * global_menu_statistics - раздел "Аналитика"
 * global_menu_marketplace - раздел "Marketplace"
 * global_menu_settings - раздел "Настройки"
 */

return array(
  array(
    "parent_menu"   => "global_menu_marketing",
    "sort"          => 2000,
    "url"           => "neft_synonyms.php?lang=".LANGUAGE_ID,
    "more_url"      => array(
      "neft_synonyms.php",
      "neft_synonyms.php?edit=Y",
    ),
    "text"          => Loc::getMessage("NEFT_SYNONYMS_MAIN_MENU_TEXT"),
    "title"         => Loc::getMessage("NEFT_SYNONYMS_MAIN_MENU_TITLE"),
    "icon"          => "neft_synonyms_admin_menu_icon",
    "page_icon"     => "neft_synonyms_admin_page_icon",
    "module_id"     => "neft.synonyms",
  )
);
