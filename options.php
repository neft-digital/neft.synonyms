<?php
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Iblock\IblockTable;
use Neft\Synonyms\Helpers;

$module_id = 'neft.synonyms';
Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "/modules/main/options.php");
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) < "S") {
  $APPLICATION->AuthForm(Loc::getMessage("ACCESS_DENIED"));
}

Loader::includeModule($module_id);
Loader::includeModule('iblock');

$request = HttpApplication::getInstance()->getContext()->getRequest();

$iblockMultiselect = array();
foreach (IblockTable::getList()->fetchAll() as $key => $iblock) {
  $iblockMultiselect += array(
    $iblock['ID'] => $iblock['NAME']
  );
}

// @link https://gist.github.com/maxsbelt/4476270
$aTabs = array(
  array(
    'DIV' => 'neft_synonyms_options',
    'TAB' => Loc::getMessage('NEFT_SYNONYMS_TAB_SETTINGS'),
    'OPTIONS' => array(
      array(
        'active',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_ACTIVE'),
        '',
        array(
          'checkbox',
          'Y'
        )
      ),
      Loc::getMessage('NEFT_SYNONYMS_OPTIONS_IBLOCK_TITLE'),
      array(
        "note" => Loc::getMessage('NEFT_SYNONYMS_OPTIONS_IBLOCK_DESCRIPTION')
      ),
      array(
        'indexed_iblocks',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_IBLOCK'),
        '',
        array(
          'multiselectbox',
          $iblockMultiselect
        )
      ),
      Loc::getMessage('NEFT_SYNONYMS_OPTIONS_SUGGESTION'),
      array(
        'suggestion_max',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_SUGGESTION_MAX'),
        '',
        array(
          'selectbox',
          array(
            '0'   => Loc::getMessage('NEFT_SYNONYMS_OPTIONS_NOT_SHOW'),
            '10'  => '10',
            '50'  => '50',
            '100' => '100',
            '150' => '150',
            '300' => '300',
          )
        )
      ),
      array(
        "note" => Loc::getMessage('NEFT_SYNONYMS_OPTIONS_SUGGESTION_CLARIFICATION')
      ),
      array(
        'include_preview',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_SUGGESTION_PREVIEW'),
        '',
        array(
          'checkbox',
          'Y',
        )
      ),
      array(
        'include_detail',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_SUGGESTION_DETAIL'),
        '',
        array(
          'checkbox',
          '',
        )
      ),
      Loc::getMessage('NEFT_SYNONYMS_OPTIONS_TAGS'),
      array(
        'add_tags',
        Loc::getMessage('NEFT_SYNONYMS_OPTIONS_TAGS_ADD'),
        '',
        array(
          'checkbox',
          'Y',
        )
      ),
    )
  ),
  array(
    "DIV" => "neft_synonyms_rights",
    "TAB" => Loc::getMessage("MAIN_TAB_RIGHTS"),
    "TITLE" => Loc::getMessage("MAIN_TAB_TITLE_RIGHTS")
  ),
);


if ($request->isPost() && $request['Update'] && check_bitrix_sessid()) {
  foreach ($aTabs as $aTab) {
    foreach ($aTab['OPTIONS'] as $arOption) {
      if (!is_array($arOption) || $arOption['note']) {
        continue;
      }
      $optionName = $arOption[0];
      $optionValue = $request->getPost($optionName);
      Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
    }
  }
}

$tabControl = new CAdminTabControl('tabControl', $aTabs);
$tabControl->Begin();
?>


<form
  method='post'
  action='<?=$APPLICATION->GetCurPage()?>?mid=<?=htmlspecialcharsbx($request['mid'])?>&amp;lang=<?=$request['lang']?>'
  name='academy_d7_settings'
>
  <?php foreach ($aTabs as $aTab) {
    if ($aTab['OPTIONS']) {
      $tabControl->BeginNextTab();
      __AdmSettingsDrawList($module_id, $aTab['OPTIONS']);
    }
  }
  $tabControl->BeginNextTab();
  require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/admin/group_rights.php");
  $tabControl->Buttons();
  ?>

  <input type="submit" name="Update" value="<?php echo GetMessage('MAIN_SAVE') ?>">
  <input type="reset" name="reset" value="<?php echo GetMessage('MAIN_RESET') ?>">
  <?php echo bitrix_sessid_post() ?>
</form>
<?php $tabControl->End(); ?>
