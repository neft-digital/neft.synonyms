<?php
namespace Neft\Synonyms;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Config\Option;
use Neft\Synonyms\Helpers;
use Neft\Synonyms\WordProcessing;

class SearchTitleExtender
{
  /**
   * @link https://burlaka.studio/lab/search_taged_by_reference/
   */
  public static function OnAfterIndexAdd($searchContentId, $arFields)
  {
    $module_id = 'neft.synonyms';
    $targetIblocks = explode(',', Option::get($module_id, "indexed_iblocks"));

    if (Option::get($module_id, "active") != "Y") {
      return;
    }

    if ($arFields['MODULE_ID'] !== 'iblock' ||
      !$arFields['ITEM_ID'] ||
      !in_array($arFields['PARAM2'], $targetIblocks)
    ) {
      return;
    }

    $wordsObj = new WordProcessing();
    $additionalWords = $wordsObj->expandWords($arFields["TITLE"], false, true);

    if (!empty($additionalWords)) {
      \CSearch::IndexTitle(
          $arFields["SITE_ID"],
          $searchContentId,
          $additionalWords
      );

      $arFields["TITLE"] .= $additionalWords;
      \CSearchFullText::getInstance()->replace($searchContentId, $arFields);
    }
  }

  /**
   * @link https://burlaka.studio/lab/search_taged_by_reference/
   */
  // public static function BeforeIndex($arFields = [])
  // {
  //   if ($arFields['MODULE_ID'] !== 'iblock' ||
  //     !$arFields['ITEM_ID']
  //   ) {
  //     return;
  //   }
  //   global $DB;
  //   $DB->StartTransaction();
  //   $result = $DB->Query(
  //       sprintf(
  //           'SELECT ID
  //                   FROM b_search_content
  //                   WHERE ITEM_ID="%s"',
  //           $arFields['ITEM_ID']
  //       )
  //   );
  //   $arr = $result->Fetch();
  //   if (empty($arr)) {
  //     return;
  //   }
  //   $DB->Query(
  //       sprintf(
  //           '
  //           DELETE b_search_content, b_search_content_title
  //           FROM b_search_content
  //               INNER JOIN b_search_content_title
  //                   ON b_search_content_title.SEARCH_CONTENT_ID = b_search_content.ID
  //           WHERE
  //               b_search_content.ITEM_ID = "%s";
  //           ',
  //           $arFields['ITEM_ID']
  //       )
  //   );
  //   $DB->Commit();
  //   $emptyKeys = ['URL', 'TITLE', 'BODY'];
  //   array_walk($arFields, static function (&$item, $key) use ($emptyKeys) {
  //     if (in_array($key, $emptyKeys)) {
  //       $item = '';
  //     }
  //   });
  //   \CSearchFullText::getInstance()->replace($arr['ID'], $arFields);
  // }


  public static function BeforeIndex($arFields = [])
  {
    $module_id = 'neft.synonyms';

    if (Option::get($module_id, "active") != "Y") {
      return;
    }

    if (Option::get($module_id, "add_tags") != "Y") {
      return;
    }

    $targetIblocks = explode(',', Option::get($module_id, "indexed_iblocks"));

    if (!Loader::includeModule("iblock")) {
      return $arFields;
    }

    if ($arFields['MODULE_ID'] == 'iblock' && in_array($arFields['PARAM2'], $targetIblocks)) {
      $wordProcessing = new WordProcessing();
      $newTags = array_merge(
          explode(',', $arFields["TAGS"]),
          $wordProcessing->expandWords($arFields["TITLE"], false, false)
      );
      $newTags = array_unique($newTags);
      $arFields["TAGS"] = implode(',', $newTags);
    }

    return $arFields;
  }
}
