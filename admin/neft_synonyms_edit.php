<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main\UI\Extension;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

Extension::load("ui.buttons");
Extension::load("ui.buttons.icons");
Extension::load("ui.forms");
Extension::load("ui.alerts");
Extension::load("ui.icons");
Extension::load("ui.common");
Extension::load("sidepanel");


$module_id = "neft.synonyms";
Loader::includeModule($module_id);
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) == "D") {
  $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}







$APPLICATION->IncludeComponent(
  'bitrix:ui.sidepanel.wrapper',
  '',
  [
    'POPUP_COMPONENT_NAME' => 'neft:synonyms.edit',
    'POPUP_COMPONENT_TEMPLATE_NAME' => '.default',
    'POPUP_COMPONENT_PARAMS' => [
      'PATH_TO_BUTTON_LIST' => $arResult['PATH_TO_BUTTON_LIST'],
      'PATH_TO_BUTTON_EDIT' => $arResult['PATH_TO_BUTTON_EDIT'],
      'PATH_TO_BUTTON_FILL' => $arResult['PATH_TO_BUTTON_FILL'],
      'PATH_TO_USER_PROFILE' => $arResult['PATH_TO_USER_PROFILE'],
      'ELEMENT_ID' => $_REQUEST["id"]
    ],
    'CLOSE_AFTER_SAVE' => true,
    'RELOAD_GRID_AFTER_SAVE' => true,
    'NOTIFICATION' => 'Успешно сохранено!',
  ]
);









require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$APPLICATION->SetTitle("324324532");
$APPLICATION->IncludeComponent("bitrix:ui.toolbar", '', []);

?>



<form action="/bitrix/admin/neft_synonyms.php" method="POST">
  <input type="hidden" name="edit" value="Y">
  <input type="hidden" name="IFRAME" value="Y">
  <input type="hidden" name="IFRAME_TYPE" value="SIDE_SLIDER">





  <div class="ui-alert ui-alert-success">
    <span class="ui-alert-message">Запись добавлена успешно.</span>
  </div>



  <div class="ui-alert ui-alert-danger">
    <span class="ui-alert-message">Что-то пошло не так.</span>
  </div>






  <div class="ui-ctl ui-ctl-textbox ui-ctl-block">
    <input type="text" class="ui-ctl-element" placeholder="Название">
  </div>


  <div class="ui-ctl ui-ctl-textarea">
    <textarea class="ui-ctl-element ui-ctl-resize-y" placeholder="Синонимы"></textarea>
  </div>


  <label class="ui-ctl ui-ctl-checkbox">
    <input type="checkbox" class="ui-ctl-element">
    <div class="ui-ctl-label-text">Получать информацию о скидках и акциях</div>
  </label>






  <?php
  $APPLICATION->IncludeComponent('bitrix:ui.button.panel', '', [
    'BUTTONS' => [
      'save',
      // 'cancel' => '/bitrix/admin/neft_synonyms.php?lang=ru',
      'close'
    ]
  ]);
  ?>
</form>


<?php 
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
