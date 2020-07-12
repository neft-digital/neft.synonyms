<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use Bitrix\Main;
use Bitrix\Main\Application;
use Bitrix\Main\Entity;
use Bitrix\Main\Type;
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Grid\Options as GridOptions;
use Bitrix\Main\UI\Filter\Options as FilterOptions;
use Bitrix\Main\UI\PageNavigation;
use Bitrix\Main\UI\Extension;
use Neft\Synonyms\SynonymsTable;
use Bitrix\Main\ORM\Query\Query;

$module_id = "neft.synonyms";
Loader::includeModule($module_id);
Loader::includeModule('ui');
Loc::loadMessages(__FILE__);

if ($APPLICATION->GetGroupRight($module_id) == "D") {
  $APPLICATION->AuthForm(GetMessage("ACCESS_DENIED"));
}

$APPLICATION->SetTitle(Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_TITLE"));

Extension::load("ui.buttons");
Extension::load("ui.buttons.icons");
Extension::load("ui.forms");
Extension::load("ui.alerts");
Extension::load("sidepanel");

// Обработка удаления
if (isset($_REQUEST["DEL"]) && $_REQUEST["ID"]) {
  if ($_REQUEST["DEL"] === "Y") {
    SynonymsTable::delete($_REQUEST["ID"]);
  }
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$listId = SynonymsTable::getTableName();

$filterOption = new FilterOptions($listId);
$filterData = $filterOption->getFilter([]);
$filter = Query::filter();
foreach ($filterData as $k => $f) {
  if ($filterData['ACTIVE']) {
    $filter = $filter->where('ACTIVE', $filterData['ACTIVE']);
  }
  if ($filterData['WORD']) {
    $filter = $filter->whereLike('WORD', "%" . $filterData['WORD'] . "%");
  }
  if ($filterData['FIND']) {
    $filter = $filter->where(
        Query::filter()
          ->logic('or')
          ->whereLike('WORD', "%" . $filterData['FIND'] . "%")
          ->whereLike('SYNONYMS', "%" . $filterData['FIND'] . "%")
    );
  }
  if ($filterData['UPDATED_from'] && $filterData['UPDATED_to']) {
    $filter = $filter->whereBetween(
        'UPDATED',
        new DateTime($filterData['UPDATED_from']),
        new DateTime($filterData['UPDATED_to'])
    );
  }
}

$gridOptions = new GridOptions($listId);
$sort = $gridOptions->GetSorting([
  'sort' => ['ID' => 'DESC'],
  'vars' => ['by' => 'by', 'order' => 'order']
]);

$nav = new PageNavigation($listId);
$nav->allowAllRecords(true)
  ->setPageSize($gridOptions->GetNavParams()['nPageSize'])
  ->initFromUri();

$result = SynonymsTable::getList([
  'filter'      => $filter,
  'select'      => ["*"],
  'offset'      => $nav->getOffset(),
  'limit'       => $nav->getLimit(),
  'order'       => $sort['sort'],
  'count_total' => true,
]);

$nav->setRecordCount($result->getCount());
?>



<div class="pagetitle-inner-container">
  <div class="pagetitle-container pagetitle-flexible-space">
    <?php
    // date, list, number, quick, custom
    $APPLICATION->IncludeComponent('bitrix:main.ui.filter', '', [
      'FILTER_ID' => $listId,
      'GRID_ID' => $listId,
      'FILTER' => [
        [
          'id' => 'WORD',
          'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_WORD"),
          'type' => 'text',
          'default' => true
        ],
        [
          'id' => 'UPDATED',
          'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_UPDATED"),
          'type' => 'date',
          'default' => true
        ],
        [
          'id' => 'ACTIVE',
          'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_ACTIVE"),
          'type' => 'checkbox',
          'default' => true
        ],
      ],
      'ENABLE_LIVE_SEARCH' => true,
      'ENABLE_LABEL' => true
    ]);
    ?>
  </div>
  <div class="pagetitle-container pagetitle-align-right-container">
    <a onclick="BX.SidePanel.Instance.open('?EDIT=Y', {autoFocus: true, allowChangeHistory: false, requestMethod: 'post'})" href="#" class="ui-btn ui-btn-primary ui-btn-icon-add">
      <?php echo Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_ADD") ?>
    </a>
    <a id="" href="/bitrix/admin/search_reindex.php?lang=<?php echo LANGUAGE_ID ?>" class="ui-btn ui-btn-light-border ui-btn-icon-business">
      <?php echo Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_REINDEX") ?>
    </a>
  </div>
</div>



<?php
foreach ($result->fetchAll() as $row) {
  $list[] = [
    'data' => [
      "ACTIVE" => ($row['ACTIVE'] == "Y") ? Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_YES") : Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_NO"),
      "ID" => $row['ID'],
      "WORD" => $row['WORD'],
      "SYNONYMS" => $row['SYNONYMS'],
      "UPDATED" => FormatDate('x', $row['UPDATED'], time() + CTimeZone::GetOffset()),
    ],
    'actions' => [
      [
        'text'    => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_EDIT"),
        'default' => true,
        'onclick' => 'BX.SidePanel.Instance.open("?EDIT=Y&ID=' . $row['ID'] . '", {
          autoFocus: true,
          allowChangeHistory: false,
          requestMethod: "post"
        })'
      ],
      [
        'text'    => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_DELETE"),
        'default' => true,
        'onclick' => 'if(confirm("Точно?")){document.location.href="?DEL=Y&ID=' . $row['ID'] . '"}'
      ]
    ]
  ];
}

$APPLICATION->IncludeComponent('bitrix:main.ui.grid', '', [
  'GRID_ID' => $listId,
  'ROWS' => $list,
  'TOTAL_ROWS_COUNT' => $result->getCount(),
  'NAV_OBJECT' => $nav,
  'COLUMNS' => [
    [
      'id' => 'WORD',
      'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_WORD"),
      'sort' => 'WORD',
      'default' => true
    ],
    [
      'id' => 'SYNONYMS',
      'name' => 'Синонимы',
      'sort' => 'SYNONYMS',
      'default' => true
    ],
    [
      'id' => 'ACTIVE',
      'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_ACTIVE"),
      'sort' => 'ACTIVE',
      'default' => true,
    ],
    [
      'id' => 'ID',
      'name' => 'ID',
      'sort' => 'ID',
      'default' => true,
    ],
    [
      'id' => 'UPDATED',
      'name' => Loc::getMessage("NEFT_SYNONYMS_ADMIN_PAGE_UPDATED"),
      'sort' => 'UPDATED',
      'default' => true
    ],
  ],
  'PAGE_SIZES' => [
    [
      'NAME' => '10',
      'VALUE' => '10'
    ],
    [
      'NAME' => '50',
      'VALUE' => '50'
    ],
    [
      'NAME' => '100',
      'VALUE' => '100'
    ]
  ],
  'AJAX_MODE' => 'Y',
  'AJAX_ID' => \CAjax::getComponentID('bitrix:main.ui.grid', '.default', ''),
  'AJAX_OPTION_JUMP'          => 'N',
  'SHOW_CHECK_ALL_CHECKBOXES' => false,
  'SHOW_ROW_CHECKBOXES'       => false,
  'SHOW_ROW_ACTIONS_MENU'     => true,
  'SHOW_GRID_SETTINGS_MENU'   => false,
  'SHOW_NAVIGATION_PANEL'     => true,
  'SHOW_PAGINATION'           => true,
  'SHOW_SELECTED_COUNTER'     => false,
  'SHOW_TOTAL_COUNTER'        => true,
  'SHOW_PAGESIZE'             => true,
  'SHOW_ACTION_PANEL'         => false,
  'ALLOW_COLUMNS_SORT'        => true,
  'ALLOW_COLUMNS_RESIZE'      => true,
  'ALLOW_HORIZONTAL_SCROLL'   => true,
  'ALLOW_SORT'                => true,
  'ALLOW_PIN_HEADER'          => true,
  'AJAX_OPTION_HISTORY'       => 'N',
]);

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
