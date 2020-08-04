<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Extension::load("ui.common");
Extension::load("ui.forms");
Extension::load("ui.alerts");
Extension::load("ui.buttons");
Extension::load("ui.buttons.icons");
Extension::load("ui.icons");
Extension::load("ui.blocks");

$module_id = "neft.synonyms";
Loader::includeModule($module_id);
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) == "D") {
  $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}


// TODO: Удалить
// CopyDirFiles(
//     $_SERVER["DOCUMENT_ROOT"] . "/local/modules/neft.synonyms/install/components/",
//     $_SERVER["DOCUMENT_ROOT"] . "/bitrix/components/",
//     true,
//     true
// );


require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->IncludeComponent(
    'bitrix:ui.sidepanel.wrapper',
    '',
    [
      'POPUP_COMPONENT_NAME' => 'neft:synonyms.edit',
      'POPUP_COMPONENT_TEMPLATE_NAME' => '.default',
      'POPUP_COMPONENT_PARAMS' => [
        'SET_TITLE' => 'Y',
      ],
      'CLOSE_AFTER_SAVE' => true,
      'RELOAD_GRID_AFTER_SAVE' => true,
      'RELOAD_PAGE_AFTER_SAVE' => true,
      'USE_UI_TOOLBAR' => 'Y',
      'USE_PADDING' => true
    ]
);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
