<?php

namespace Neft\Synonyms;

use Bitrix\Main\Loader;
use Neft\Synonyms\SynonymsTable;

/**
 * Вспомогательные функции
 */
class Helpers
{
  public static function layoutSwitchRu($word)
  {
    $converter = array(
      'f' => 'а',  ',' => 'б',  'd' => 'в',  'u' => 'г',  'l' => 'д',  't' => 'е',  '`' => 'ё',
      ';' => 'ж',  'p' => 'з',  'b' => 'и',  'q' => 'й',  'r' => 'к',  'k' => 'л',  'v' => 'м',
      'y' => 'н',  'j' => 'о',  'g' => 'п',  'h' => 'р',  'c' => 'с',  'n' => 'т',  'e' => 'у',
      'a' => 'ф',  '[' => 'х',  'w' => 'ц',  'x' => 'ч',  'i' => 'ш',  'o' => 'щ',  'm' => 'ь',
      's' => 'ы',  ']' => 'ъ',  "'" => "э",  '.' => 'ю',  'z' => 'я',
      'F' => 'А',  '<' => 'Б',  'D' => 'В',  'U' => 'Г',  'L' => 'Д',  'E' => 'Е',  '~' => 'Ё',
      ':' => 'Ж',  'P' => 'З',  'B' => 'И',  'Q' => 'Й',  'R' => 'К',  'K' => 'Л',  'V' => 'М',
      'Y' => 'Н',  'J' => 'О',  'G' => 'П',  'H' => 'Р',  'C' => 'С',  'N' => 'Т',  'E' => 'У',
      'A' => 'Ф',  '{' => 'Х',  'W' => 'Ц',  'X' => 'Ч',  'I' => 'Ш',  'O' => 'Щ',  'M' => 'Ь',
      'S' => 'Ы',  '}' => 'Ъ',  '"' => 'Э',  '>' => 'Ю',  'Z' => 'Я',
      '@' => '"',  '#' => '№',  '$' => ';',  '^' => ':',  '&' => '?',  '/' => '.',  '?' => ',',
    );
    return strtr($word, $converter);
  }

  public static function layoutSwitchEn($word)
  {
    $converter = array(
      'а' => 'f',  'б' => ',',  'в' => 'd',  'г' => 'u',  'д' => 'l',  'е' => 't',  'ё' => '`',
      'ж' => ';',  'з' => 'p',  'и' => 'b',  'й' => 'q',  'к' => 'r',  'л' => 'k',  'м' => 'v',
      'н' => 'y',  'о' => 'j',  'п' => 'g',  'р' => 'h',  'с' => 'c',  'т' => 'n',  'у' => 'e',
      'ф' => 'a',  'х' => '[',  'ц' => 'w',  'ч' => 'x',  'ш' => 'i',  'щ' => 'o',  'ь' => 'm',
      'ы' => 's',  'ъ' => ']',  'э' => "'",  'ю' => '.',  'я' => 'z',
      'А' => 'F',  'Б' => '<',  'В' => 'D',  'Г' => 'U',  'Д' => 'L',  'Е' => 'T',  'Ё' => '~',
      'Ж' => ':',  'З' => 'P',  'И' => 'B',  'Й' => 'Q',  'К' => 'R',  'Л' => 'K',  'М' => 'V',
      'Н' => 'Y',  'О' => 'J',  'П' => 'G',  'Р' => 'H',  'С' => 'C',  'Т' => 'N',  'У' => 'E',
      'Ф' => 'A',  'Х' => '{',  'Ц' => 'W',  'Ч' => 'X',  'Ш' => 'I',  'Щ' => 'O',  'Ь' => 'M',
      'Ы' => 'S',  'Ъ' => '}',  'Э' => '"',  'Ю' => '>',  'Я' => 'Z',
      '"' => '@',  '№' => '#',  ';' => '$',  ':' => '^',  '?' => '&',  '.' => '/',  ',' => '?',
    );
    return strtr($word, $converter);
  }

  /**
   * Проверяет, является ли слово кириллическим
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

  public static function isLatin($word)
  {
    if (preg_match('/^[\p{Latin}\p{Common}]+$/u', $word)) {
      return true;
    } else {
      return false;
    }
  }

  public static function layoutSwitch($word)
  {
    if (self::isCyrillic($word)) {
      return self::layoutSwitchEn($word);
    } elseif (self::isLatin($word)) {
      return self::layoutSwitchRu($word);
    } else {
      return $word;
    }
  }
}
