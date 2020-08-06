<?php
/**
 * @var array $arResult
 * @var array $arParams
 */
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) {
  die();
}

use Bitrix\Main\Localization\Loc;
?>


<form action="/bitrix/admin/neft_synonyms.php" method="post">
  <?php echo bitrix_sessid_post() ?>
  <input type="hidden" name="EDIT" value="Y">
  <input type="hidden" name="IS_SAVED" value="Y">
  <input type="hidden" name="IFRAME" value="Y">
  <input type="hidden" name="IFRAME_TYPE" value="SIDE_SLIDER">
  <input type="hidden" name="ID" value="<?php echo trim(htmlspecialcharsbx($arResult['ID'])) ?>">

  <?php if ($arResult['IS_SAVED'] && !$arResult['ERRORS']) { ?>
    <div class="neft-form-field ui-ctl-row">
      <div class="ui-alert ui-alert-success">
        <span class="ui-alert-message">
          <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_SUCCESS') ?>
        </span>
      </div>
      <div class="ui-alert ui-alert-primary">
        <span class="ui-alert-message">
          <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_INFO_ALERT') ?>
        </span>
      </div>
    </div>
    <script>
      window.parent.BX.Main.gridManager.getInstanceById('neft_synonyms').reloadTable();
    </script>
  <?php }
  if ($arResult['ERRORS']) { ?>
    <div class="neft-form-field ui-ctl-row">
      <div class="ui-alert ui-alert-danger">
        <span class="ui-alert-message">
          <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ERROR') ?>
        </span>
      </div>
    </div>
  <?php } ?>


  <div class="neft-form-field ui-ctl-row">
    <label class="ui-ctl ui-ctl-checkbox" for="ACTIVE">
      <input
        type="checkbox"
        class="ui-ctl-element"
        id="ACTIVE"
        name="ACTIVE"
        value="Y"
        <?php echo ($arResult['ACTIVE'] === 'Y' || $arResult['ID'] === 0 ? 'checked' : '') ?>
      >
      <div class="ui-ctl-label-text">
        <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ACTIVE') ?>
      </div>
    </label>
  </div>


  <div class="neft-form-field ui-ctl-row neft-synonyms-field-word">
    <div class="ui-text-1 ui-color-medium">
      <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_KEYWORD') ?>:
    </div>

    <?php if ($arResult['SUGGESTION']) { ?>
      <div class="ui-alert ui-alert-primary">
        <span class="ui-alert-message">
          <?php foreach ($arResult['SUGGESTION'] as $suggestion) {
            echo '<a class="ui-link ui-link-secondary ui-link-dashed neft-suggestion" title="'
              .Loc::getMessage('NEFT_SYNONYMS_EDIT_ADD_NOTE')
              .'">'
              .$suggestion
              .'</a> ';
          } ?>
        </span>
      </div>
      <script>
        document.querySelectorAll('.neft-suggestion').forEach(item => {
          item.addEventListener('click', event => {
            event.preventDefault();
            document.getElementById("neft-input-word").value = item.textContent;
          })
        })
      </script>
    <?php } ?>

    <div class="ui-ctl ui-ctl-textbox ui-ctl-block">
      <input
        id="neft-input-word"
        type="text"
        name="WORD"
        value="<?php echo htmlspecialcharsbx($arResult['WORD']) ?>"
        class="ui-ctl-element"
        placeholder="<?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_KEYWORD') ?>"
      >
    </div>
  </div>


  <div class="neft-form-field ui-ctl-row">
    <div class="ui-text-1 ui-color-medium">
      <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_SYNONYMS') ?>:
    </div>
    <div class="ui-ctl ui-ctl-textarea">
      <textarea
        name="SYNONYMS"
        class="ui-ctl-element ui-ctl-resize"
        placeholder="<?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_SYNONYMS') ?>"
      ><?php echo trim(htmlspecialcharsbx($arResult['SYNONYMS'])) ?></textarea>
    </div>
  </div>


  <div class="neft-form-field ui-ctl-row">
    <div class="ui-text-1 ui-color-medium">
      <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ADDITIONAL') ?>:
    </div>

    <label class="ui-ctl ui-ctl-checkbox">
      <input
        id="TRANSLIT"
        name="TRANSLIT"
        type="checkbox"
        class="ui-ctl-element"
        value="Y"
        <?php echo ($arResult['TRANSLIT'] === 'Y' ? 'checked' : '') ?>
      >
      <div class="ui-ctl-label-text">
        <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ADD_TRANSLIT') ?>
      </div>
    </label>

    <label class="ui-ctl ui-ctl-checkbox">
      <input
        id="LAYOUT"
        name="LAYOUT"
        type="checkbox"
        class="ui-ctl-element"
        value="Y"
        <?php echo ($arResult['LAYOUT'] === 'Y' ? 'checked' : '') ?>
      >
      <div class="ui-ctl-label-text">
        <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ADD_LAYOUT') ?>
      </div>
    </label>

    <?php
    /*
    <label class="ui-ctl ui-ctl-checkbox">
      <input
        id="TYPOS"
        name="TYPOS"
        type="checkbox"
        class="ui-ctl-element"
        value="Y"
        <?php echo ($arResult['TYPOS'] === 'Y' ? 'checked' : '') ?>
      >
      <div class="ui-ctl-label-text">
        <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ADD_TYPOS') ?>
      </div>
    </label>

    <label class="ui-ctl ui-ctl-checkbox">
      <input
        id="MORPHOLOGY"
        name="MORPHOLOGY"
        type="checkbox"
        class="ui-ctl-element"
        value="Y"
        <?php echo ($arResult['MORPHOLOGY'] === 'Y' ? 'checked' : '') ?>
      >
      <div class="ui-ctl-label-text">
        <?php echo Loc::getMessage('NEFT_SYNONYMS_EDIT_ADD_MORPHOLOGY') ?>
      </div>
    </label>
    */
    ?>
  </div>


  <?php
  $APPLICATION->IncludeComponent(
      'bitrix:ui.button.panel',
      '',
      [
        'BUTTONS' => [
          'save',
          'close'
        ]
      ]
  );
  ?>
</form>
