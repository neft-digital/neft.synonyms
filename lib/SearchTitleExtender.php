<?php
/**
 * @link https://burlaka.studio/lab/search_taged_by_reference/
 */

namespace Neft\Synonyms;

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Neft\Synonyms\SynonymsTable;

class SearchTitleExtender
{
  public function __construct()
  {
  }

  public static function test()
  {
    AddMessage2Log("Проверка класса", "neft_synonyms");
    return 11111;
  }

  /**
   * Расширяет массив слов, добавляя в него синонимы из таблицы
   *
   * @param array $words Массив изначальных слов
   * @param boolean $includeOrig Включать ли изначальные слова в итоговый массив
   * @return array Массив дополненных слов
   */
  public static function CompleteWords($words, $includeOrig = false)
  {
    $words = array_map('strtolower', $words);
    $wordsEx = $words;
    $synonyms = SynonymsTable::getList(array(
        'select' => array('WORD', 'SYNONYMS', 'TRANSLIT', 'TYPOS', 'LAYOUT', 'MORPHOLOGY'),
        'filter' => array('ACTIVE' => 'Y'),
    ))->fetchAll();

    foreach ($synonyms as $synonym) {
      if (in_array(strtolower($synonym['WORD']), $words)) {
        $synonym['SYNONYMS'] = str_replace(',', ' ', $synonym['SYNONYMS']);
        $synonym['SYNONYMS'] = preg_replace('|[\s]+|s', ' ', $synonym['SYNONYMS']);
        $wordsEx = array_merge($wordsEx, explode(' ', $synonym['SYNONYMS']));
      }
    }

    $wordsEx = array_unique($wordsEx);
    if (!$includeOrig) {
      $wordsEx = array_diff($wordsEx, $words);
    }

    return $wordsEx;
  }

  public static function OnAfterIndexAdd($searchContentId, &$arFields)
  {
    $targetIblocks = [9, 8]; // TODO: Вынести в настройки

    if ($arFields['MODULE_ID'] !== 'iblock' ||
      !$arFields['ITEM_ID'] ||
      !in_array($arFields['PARAM2'], $targetIblocks)
    ) {
      return;
    }

    $additionalWords = static::CompleteWords(explode(' ', $arFields["TITLE"]), false);
    // AddMessage2Log($additionalWords, "neft_synonyms");

    if (!empty($additionalWords)) {
      \CSearch::IndexTitle(
          $arFields["SITE_ID"],
          $searchContentId,
          implode(' ', $additionalWords)
      );

      $arFields["TITLE"] .= implode(' ', $additionalWords);
      \CSearchFullText::getInstance()->replace($searchContentId, $arFields);
    }
  }
}
