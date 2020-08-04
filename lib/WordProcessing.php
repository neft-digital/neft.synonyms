<?php
namespace Neft\Synonyms;

use Neft\Synonyms\SynonymsTable;
use Neft\Synonyms\Helpers;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Config\Option;
use Bitrix\Iblock\IblockTable;
use Bitrix\Iblock\ElementTable;

Loc::loadMessages(__FILE__);

class WordProcessing
{
  protected $synonyms = array();
  protected $data = array();
  public $exceptionsRu = array('а', 'авось', 'ага', 'аж', 'б', 'без', 'безо', 'благо', 'благодаря', 'близко', 'более', 'будто', 'буквально', 'бы', 'бывает', 'было', 'быть', 'в', 'вблизи', 'ввиду', 'вглубь', 'вдогон', 'вдоль', 'ведь', 'верно', 'вероятно', 'вестимо', 'взамен', 'виде', 'видом', 'виду', 'вишь', 'включая', 'вкруг', 'вместо', 'вне', 'внизу', 'внутри', 'внутрь', 'во', 'вовнутрь', 'возле', 'вокруг', 'вон', 'вообще', 'вопреки', 'вослед', 'вот', 'впереди', 'вплоть', 'впредь', 'вразрез', 'вроде', 'вряд', 'все', 'всего', 'вслед', 'вследствие', 'встречу', 'всё', 'выключая', 'вычетом', 'глазах', 'да', 'давай', 'даже', 'действительно', 'дескать', 'для', 'до', 'довольно', 'допустим', 'других', 'его', 'едва', 'если', 'ещё', 'ж', 'же', 'за', 'зависимости', 'заместо', 'зрения', 'и', 'из', 'изнутри', 'изо', 'или', 'иль', 'имеет', 'имени', 'именно', 'имя', 'инда', 'индо', 'интересах', 'исключая', 'исключением', 'исключительно', 'исходя', 'к', 'кажется', 'как', 'касаемо', 'касательно', 'качестве', 'ко', 'когда', 'конечно', 'которая', 'которые', 'кроме', 'кругом', 'куда', 'ладно', 'ли', 'либо', 'линии', 'лица', 'лице', 'лицом', 'лицу', 'лишь', 'лучше', 'любых', 'меж', 'между', 'мере', 'мимо', 'могут', 'можно', 'мол', 'на', 'наверху', 'навроде', 'навстречу', 'над', 'надо', 'назад', 'назади', 'назло', 'накануне', 'наместо', 'наоборот', 'наперекор', 'наперерез', 'наперехват', 'наподобие', 'наподобье', 'направлению', 'напросто', 'напротив', 'наряду', 'насчёт', 'начиная', 'не', 'небось', 'невзирая', 'недалеко', 'независимо', 'неравно', 'несмотря', 'нет', 'нету', 'неужели', 'ни', 'ниже', 'никак', 'ничего', 'ништо', 'ништяк', 'ну', 'о', 'об', 'обо', 'обок', 'обочь', 'около', 'окрест', 'окроме', 'окромя', 'округ', 'они', 'опосля', 'опричь', 'от', 'откуда', 'отличие', 'относительно', 'отношении', 'ото', 'очевидно', 'перед', 'передо', 'по', 'поблизости', 'повдоль', 'поверх', 'поводу', 'под', 'подле', 'подо', 'подобно', 'пожалуй', 'пожалуйста', 'позади', 'позадь', 'позднее', 'пока', 'полно', 'полноте', 'положим', 'положительно', 'пользу', 'помимо', 'помощи', 'помощью', 'понимаешь', 'понятно', 'поперёд', 'поперёк', 'порядка', 'посверху', 'посереди', 'посередине', 'посередь', 'посерёдке', 'после', 'посреди', 'посредине', 'посредством', 'походу', 'похоже', 'правда', 'пред', 'преддверии', 'предмет', 'предо', 'предовольно', 'преж', 'прежде', 'при', 'применительно', 'прицелом', 'причине', 'про', 'продолжение', 'промеж', 'промежду', 'простите', 'просто', 'против', 'противу', 'прям', 'прямо', 'пускай', 'пусть', 'путём', 'ради', 'разве', 'разумеется', 'результате', 'решительно', 'ровно', 'роли', 'рядом', 'с', 'сверх', 'сверху', 'связи', 'силу', 'скорее', 'славу', 'словно', 'случае', 'случаю', 'собственно', 'соответствии', 'спасибо', 'сравнению', 'считая', 'так', 'также', 'таки', 'течение', 'типа', 'то', 'того', 'тоже', 'только', 'точки', 'точнее', 'угодно', 'уж', 'уже', 'ужели', 'ужель', 'ужли', 'хорошо', 'хоть', 'хотя', 'целью', 'целях', 'через', 'честь', 'что', 'чтоб', 'чтобы', 'чуть', 'штоб', 'эгидой', 'эдак', 'этак', 'это', 'этом', 'якобы');
  public $exceptionsEn = array('abaat', 'aback', 'abaft', 'abaht', 'abaout', 'abeam', 'ablow', 'aboard', 'aboat', 'aboon', 'aboot', 'about', 'aboutes', 'abouts', 'above', 'abowt', 'abreast', 'abroad', 'absent', 'abt', 'abun', 'abune', 'abv', 'according', 'across', 'from', 'acrosst', 'acrost', 'adjacent', 'adown', 'affor', 'afoor', 'afore', 'afront', 'afta', 'aftah', 'after', 'afther', 'aftre', 'again', 'againest', 'against', 'agaynest', 'agaynst', 'ageinest', 'ageinst', 'agen', 'agenest', 'agenst', 'ageynest', 'ageynst', 'agin', 'aginst', 'ago', 'agyen', 'ahead', 'ahind', 'ahint', 'ahn', 'ajax', 'aka', 'ala', 'alang', 'alangst', 'along', 'for', 'over', 'all', 'with', 'the', 'lines', 'alongest', 'alongside', 'alongst', 'aloof', 'alow', 'amid', 'amiddest', 'amiddst', 'amidest', 'amidst', 'among', 'amonge', 'amongest', 'amongst', 'amoung', 'amoungest', 'amoungst', 'amyddest', 'amyddst', 'amydst', 'anear', 'anearst', 'anenst', 'anent', 'anigh', 'anighst', 'anti', 'aout', 'apart', 'apres', 'après', 'aprés', 'apropos', 'apropos of', 'apud', 'around', 'arownd', 'arter', 'far', 'opposed', 'per', 'regards', 'soon', 'well', 'ascr', 'aside', 'aslant', 'astern', 'astraddle', 'astride', 'cost', 'feet', 'hand', 'hands', 'risk', 'sight', 'athwart', 'atop', 'att', 'atween', 'atwix', 'awaye', 'ayein', 'ayen', 'ayond', 'ayont', 'back', 'baft', 'bang on', 'bar', 'barring', 'bating', 'because', 'befo', 'befoir', 'befor', 'before', 'befure', 'behind', 'behinde', 'behine', 'behither', 'belong', 'below', 'ben', 'beneath', 'beneathe', 'benorth', 'beside', 'besides', 'besyde', 'bet', 'betune', 'between', 'betweene', 'betwene', 'betwixen', 'betwixt');

  public function __construct()
  {
    $tableData = SynonymsTable::getList(array(
        'select' => array(
          'ID',
          'WORD',
          'SYNONYMS',
          'TRANSLIT',
          'TYPOS',
          'LAYOUT',
          'MORPHOLOGY'
        ),
        'filter' => array(
          'ACTIVE' => 'Y'
        ),
    ))->fetchAll();

    foreach ($tableData as $rowData) {
      $rowData['SYNONYMS'] = strtolower($rowData['SYNONYMS']);
      $rowData['SYNONYMS'] = str_replace(',', ' ', $rowData['SYNONYMS']);
      $rowData['SYNONYMS'] = preg_replace('|[\s]+|s', ' ', $rowData['SYNONYMS']);
      $arSynonyms = explode(' ', $rowData['SYNONYMS']);
      $arSynonyms[] = strtolower($rowData['WORD']);
      $arSynonyms = array_unique($arSynonyms);
      $this->data[] = array(
        'ID'         => $rowData['ID'],
        'WORD'       => trim(strtolower($rowData['WORD'])),
        'SYNONYMS'   => $arSynonyms,
        'TRANSLIT'   => $rowData['TRANSLIT'],
        'TYPOS'      => $rowData['TYPOS'],
        'LAYOUT'     => $rowData['LAYOUT'],
        'MORPHOLOGY' => $rowData['MORPHOLOGY'],
      );
    }
  }

  public function dataAddTranslit()
  {
    foreach ($this->data as $k => $dataItem) {
      if ($dataItem['TRANSLIT'] == 'Y') {
        $arTranslit = array();
        foreach ($dataItem['SYNONYMS'] as $synonym) {
          $arTranslit[] = Helpers::translify($synonym);
        }
        $this->data[$k]['SYNONYMS'] = array_unique(
            array_merge($this->data[$k]['SYNONYMS'], $arTranslit)
        );
      }
    }
  }

  public function dataAddLayoutSwitch()
  {
    foreach ($this->data as $k => $dataItem) {
      if ($dataItem['LAYOUT'] == 'Y') {
        $arLayout = array();
        foreach ($dataItem['SYNONYMS'] as $synonym) {
          $arLayout[] = Helpers::layoutSwitch($synonym);
        }
        $this->data[$k]['SYNONYMS'] = array_unique(
            array_merge($this->data[$k]['SYNONYMS'], $arLayout)
        );
      }
    }
  }

  public function dataFinalProcessing()
  {
    foreach ($this->data as $k => $item) {
      $this->data[$k]['SYNONYMS'] = array_unique(
          array_filter($this->data[$k]['SYNONYMS'])
      );
      $this->data[$k]['WORD'] = strtolower($this->data[$k]['WORD']);
      foreach ($item['SYNONYMS'] as $s => $synonyms) {
        if (strlen($synonyms) < 3) {
          unset($this->data[$k]['SYNONYMS'][$s]);
        }
      }
    }
  }

  public function getWords()
  {
    $this->dataAddTranslit();
    $this->dataAddLayoutSwitch();
    $this->dataFinalProcessing();
    return $this->data;
  }

  /**
   * Расширяет входящую строку добавляя варианты слов
   *
   * Функция должна получать на входе строку (например, заголовок), разбивать ее на слова и добавлять к ним синонимы
   * Если задан второй параметр, входящие слова включаются в результат
   *
   * @param string $string
   * @param boolean $includeOrig
   * @param boolean $returnString
   * @return string
   */
  public function expandWords($string, $includeOrig = false, $returnString = false)
  {
    if ($string == "") {
      return false;
    }

    $this->dataAddTranslit();
    $this->dataAddLayoutSwitch();
    $this->dataFinalProcessing();

    $string = strtolower($string);
    $string = str_replace([',', '!', '-', '"', '«', '»', '.', '/', '\\'], ' ', $string);
    $string = preg_replace('|[\s]+|s', ' ', $string);
    $arWords = explode(' ', $string);
    $additionalWords = array();

    foreach ($arWords as $word) {
      if (strlen($word) < 3) {
        continue;
      }

      if ($dataKey = array_search($word, array_column($this->data, 'WORD'))) {
        $additionalWords = array_merge($additionalWords, $this->data[$dataKey]['SYNONYMS']);
      }

      $additionalWords[] = Helpers::layoutSwitch($word);
      $additionalWords[] = Helpers::translify($word);
      $additionalWords = array_unique($additionalWords);
    }

    if (!$includeOrig) {
      $additionalWords = array_diff($additionalWords, $arWords);
    }

    if (!$returnString) {
      return $additionalWords;
    } else {
      return implode(' ', $additionalWords);
    }
  }

  public function getWordsTop()
  {
    $max = Option::get('neft.synonyms', 'suggestion_max');
    $includePreview = (Option::get('neft.synonyms', 'include_preview') == 'Y') ? true : false;
    $includeDetail = (Option::get('neft.synonyms', 'include_detail') == 'Y') ? true : false;

    $iblockItems = ElementTable::getList(array(
        'select' => array(
          'ID',
          'NAME',
          'IBLOCK_ID',
          'PREVIEW_TEXT',
          'DETAIL_TEXT'
        ),
        'filter' => array(
          'IBLOCK_ID' => explode(',', Option::get("neft.synonyms", "indexed_iblocks"))
        )
    ))->fetchAll();

    $top = array();
    foreach ($iblockItems as $item) {
      $wordsString = $item['NAME'];
      if ($includePreview) {
        $wordsString .= ' ' . $item['PREVIEW_TEXT'];
      }
      if ($includeDetail) {
        $wordsString .= ' ' . strip_tags($item['DETAIL_TEXT']);
      }
      $wordsString = trim(strtolower($wordsString));
      $wordsString = preg_replace('/[^ a-zа-яё]/ui', ' ', $wordsString);
      $wordsString = preg_replace('|[\s]+|s', ' ', $wordsString);
      $wordsArray = array_unique(
          explode(' ', $wordsString)
      );
      $wordsArray = array_diff($wordsArray, array(''));
      foreach ($wordsArray as $word) {
        if (strlen($word) < 3) {
          continue;
        }
        if (array_key_exists($word, $top)) {
          $top[$word]++;
        } else {
          $top[$word] = 1;
        }
      }
    }

    foreach ($this->data as $dataItem) {
      if (array_key_exists($dataItem['WORD'], $top)) {
        unset($top[$dataItem['WORD']]);
      }
    }

    foreach (array_merge($this->exceptionsRu, $this->exceptionsEn) as $exception) {
      if (array_key_exists($exception, $top)) {
        unset($top[$exception]);
      }
    }

    uasort($top, function ($a, $b) {
      return ($b - $a);
    });

    $count = 0;
    foreach ($top as $word => $freq) {
      if ($count >= $max) {
        break;
      }
      $result[] = $word;
      $count++;
    }

    return $result;
  }
}
