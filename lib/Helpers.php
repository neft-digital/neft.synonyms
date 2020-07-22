<?php

namespace Neft\Synonyms;

use Bitrix\Main\Loader;
use Neft\Synonyms\SynonymsTable;

/**
 * Вспомогательные функции
 */
class Helpers
{
  /**
   * Меняет раскладку клавиатуры на русскую
   *
   * @param string $word Слово с потенциально неправильной раскладкой
   * @return string Слово с исправленной раскладкой
   */
  public static function layoutSwitchRu($word)
  {
    $converter = array(
      "'" => "э",
      '"' => 'Э',
      '#' => '№',
      '$' => ';',
      '&' => '?',
      ',' => 'б',
      '.' => 'ю',
      '/' => '.',
      ':' => 'Ж',
      ';' => 'ж',
      '<' => 'Б',
      '>' => 'Ю',
      '?' => ',',
      '@' => '"',
      'A' => 'Ф',
      'B' => 'И',
      'C' => 'С',
      'D' => 'В',
      'E' => 'Е',
      'E' => 'У',
      'F' => 'А',
      'G' => 'П',
      'H' => 'Р',
      'I' => 'Ш',
      'J' => 'О',
      'K' => 'Л',
      'L' => 'Д',
      'M' => 'Ь',
      'N' => 'Т',
      'O' => 'Щ',
      'P' => 'З',
      'Q' => 'Й',
      'R' => 'К',
      'S' => 'Ы',
      'U' => 'Г',
      'V' => 'М',
      'W' => 'Ц',
      'X' => 'Ч',
      'Y' => 'Н',
      'Z' => 'Я',
      '[' => 'х',
      ']' => 'ъ',
      '^' => ':',
      '`' => 'ё',
      'a' => 'ф',
      'b' => 'и',
      'c' => 'с',
      'd' => 'в',
      'e' => 'у',
      'f' => 'а',
      'g' => 'п',
      'h' => 'р',
      'i' => 'ш',
      'j' => 'о',
      'k' => 'л',
      'l' => 'д',
      'm' => 'ь',
      'n' => 'т',
      'o' => 'щ',
      'p' => 'з',
      'q' => 'й',
      'r' => 'к',
      's' => 'ы',
      't' => 'е',
      'u' => 'г',
      'v' => 'м',
      'w' => 'ц',
      'x' => 'ч',
      'y' => 'н',
      'z' => 'я',
      '{' => 'Х',
      '}' => 'Ъ',
      '~' => 'Ё',
    );
    return strtr($word, $converter);
  }

  /**
   * Меняет раскладку клавиатуры на английскую
   *
   * @param string $word Слово с потенциально неправильной раскладкой
   * @return string Слово с исправленной раскладкой
   */
  public static function layoutSwitchEn($word)
  {
    $converter = array(
      '"' => '@',
      ',' => '?',
      '.' => '/',
      ':' => '^',
      ';' => '$',
      '?' => '&',
      'Ё' => '~',
      'А' => 'F',
      'Б' => '<',
      'В' => 'D',
      'Г' => 'U',
      'Д' => 'L',
      'Е' => 'T',
      'Ж' => ':',
      'З' => 'P',
      'И' => 'B',
      'Й' => 'Q',
      'К' => 'R',
      'Л' => 'K',
      'М' => 'V',
      'Н' => 'Y',
      'О' => 'J',
      'П' => 'G',
      'Р' => 'H',
      'С' => 'C',
      'Т' => 'N',
      'У' => 'E',
      'Ф' => 'A',
      'Х' => '{',
      'Ц' => 'W',
      'Ч' => 'X',
      'Ш' => 'I',
      'Щ' => 'O',
      'Ъ' => '}',
      'Ы' => 'S',
      'Ь' => 'M',
      'Э' => '"',
      'Ю' => '>',
      'Я' => 'Z',
      'а' => 'f',
      'б' => ',',
      'в' => 'd',
      'г' => 'u',
      'д' => 'l',
      'е' => 't',
      'ж' => ';',
      'з' => 'p',
      'и' => 'b',
      'й' => 'q',
      'к' => 'r',
      'л' => 'k',
      'м' => 'v',
      'н' => 'y',
      'о' => 'j',
      'п' => 'g',
      'р' => 'h',
      'с' => 'c',
      'т' => 'n',
      'у' => 'e',
      'ф' => 'a',
      'х' => '[',
      'ц' => 'w',
      'ч' => 'x',
      'ш' => 'i',
      'щ' => 'o',
      'ъ' => ']',
      'ы' => 's',
      'ь' => 'm',
      'э' => "'",
      'ю' => '.',
      'я' => 'z',
      'ё' => '`',
      '№' => '#',
    );
    return strtr($word, $converter);
  }

  /**
   * Проверяет, написано ли слово кириллицей
   *
   * @param string $word
   * @return boolean
   */
  public static function isCyrillic($word)
  {
    if (preg_match('/^[\p{Cyrillic}\p{Common}]+$/u', $word)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Проверяет, написано ли слово латиницей
   *
   * @param string $word
   * @return boolean
   */
  public static function isLatin($word)
  {
    if (preg_match('/^[\p{Latin}\p{Common}]+$/u', $word)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Меняет раскладку клавиатуры в зависимости от языка
   *
   * @uses Neft\Synonyms\Helpers::isCyrillic
   * @uses Neft\Synonyms\Helpers::isLatin
   * @uses Neft\Synonyms\Helpers::layoutSwitchEn
   * @uses Neft\Synonyms\Helpers::layoutSwitchRu
   * @param string $word
   * @return string
   */
  public static function layoutSwitch($word)
  {
    if (self::isCyrillic($word)) {
      return self::layoutSwitchEn($word);
    } elseif (self::isLatin($word)) {
      return self::layoutSwitchRu($word);
    } else {
      return false;
    }
  }

  /**
   * Транслителирует строку
   *
   * @link https://github.com/andre487/php_rutils/blob/master/Translit.php
   * TODO: Переделать функцию, добавить обработку 2-х и 3-х букв, добавить вывод нескольких вариантов транслитерации
   * @param string $word
   * @return string
   */
  public function translify($word)
  {
    $translationTable = array(
      array("‘", "'"),
      array("’", "'"),
      array("«", '"'),
      array("»", '"'),
      array("“", '"'),
      array("”", '"'),
      array("№", "#"),
      array('Щ', 'Shh'),
      array('щ', 'shh'),
      array('Ё', 'Yo'),
      array('ё', 'yo'),
      array('Ж', 'Zh'),
      array('ж', 'zh'),
      array('Ц', 'Cz'),
      array('ц', 'cz'),
      array('Ч', 'Ch'),
      array('ч', 'ch'),
      array('Ш', 'Sh'),
      array('ш', 'sh'),
      array('ъ', '``'),
      array('Ъ', '``'),
      array('Ы', 'Y`'),
      array('ы', 'y`'),
      array('Э', 'E`'),
      array('э', 'e`'),
      array('Ю', 'Yu'),
      array('ю', 'yu'),
      array('Я', 'Ya'),
      array('я', 'ya'),
      array('А', 'A'),
      array('а', 'a'),
      array('Б', 'B'),
      array('б', 'b'),
      array('В', 'V'),
      array('в', 'v'),
      array('Г', 'G'),
      array('г', 'g'),
      array('Д', 'D'),
      array('д', 'd'),
      array('Е', 'E'),
      array('е', 'e'),
      array('З', 'Z'),
      array('з', 'z'),
      array('И', 'I'),
      array('и', 'i'),
      array('Й', 'J'),
      array('й', 'j'),
      array('К', 'K'),
      array('к', 'k'),
      array('Л', 'L'),
      array('л', 'l'),
      array('М', 'M'),
      array('м', 'm'),
      array('Н', 'N'),
      array('н', 'n'),
      array('О', 'O'),
      array('о', 'o'),
      array('П', 'P'),
      array('п', 'p'),
      array('Р', 'R'),
      array('р', 'r'),
      array('С', 'S'),
      array('с', 's'),
      array('Т', 'T'),
      array('т', 't'),
      array('У', 'U'),
      array('у', 'u'),
      array('Ф', 'F'),
      array('ф', 'f'),
      array('Х', 'X'),
      array('х', 'x'),
      array('ь', '`'),
      array('Ь', '`'),
    );
    $correctionPattern = array(
      '#(\w)«#u',
      '#(\w)“#u',
      '#(\w)‘#u'
    );
    $correctionReplacement = array(
      '$1»',
      '$1”',
      '$1’'
    );
    $ruAlphabet = array();
    $enAlphabet = array();
    foreach ($translationTable as $pair) {
      $ruAlphabet[] = $pair[0];
      $enAlphabet[] = $pair[1];
    }
    $dirtyResult = str_replace($enAlphabet, $ruAlphabet, $word);
    if (self::isCyrillic($word)) {
      return str_replace($ruAlphabet, $enAlphabet, $word);
    } elseif (self::isLatin($word)) {
      return preg_replace($correctionPattern, $correctionReplacement, $dirtyResult);
    } else {
      return false;
    }
  }
}
