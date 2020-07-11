<?php

namespace Neft\Synonyms;

use Bitrix\Main\Entity;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\FloatField;      // Число
use Bitrix\Main\ORM\Fields\StringField;     // Строка
use Bitrix\Main\ORM\Fields\DatetimeField;   // Дата/Время
use Bitrix\Main\ORM\Fields\DateField;       // Дата
use Bitrix\Main\ORM\Fields\IntegerField;    // Целое число
use Bitrix\Main\ORM\Fields\TextField;       // Текст
use Bitrix\Main\ORM\Fields\BooleanField;    // Да/Нет
use Bitrix\Main\Type\DateTime;
use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

class SynonymsTable extends DataManager
{
  public static function getTableName()
  {
    return 'neft_synonyms';
  }

  /**
   * Структура таблицы данных.
   * @link https://dev.1c-bitrix.ru/learning/course/index.php?COURSE_ID=43&LESSON_ID=4803&LESSON_PATH=3913.5062.5748.4803
   *
   * @return void
   */
  public static function getMap()
  {
    return array(
      new IntegerField('ID', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_ID'),
        'primary' => true,
        'autocomplete' => true
      )),

      new BooleanField('ACTIVE', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_ACTIVE'),
        'values' => array('N', 'Y'),
        'default_value' => 'Y',
      )),

      new StringField('WORD', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_WORD'),
        'required' => true,
        'validation' => function () {
          return array(
              new Entity\Validator\Unique,
          );
        }
      )),

      new TextField('SYNONYMS', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_SYNONYMS'),
      )),

      new IntegerField('SORT', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_SORT'),
        'default_value' => 100,
      )),

      new DatetimeField('UPDATED', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_UPDATED'),
        'default_value' => function () {
          return new DateTime();
        },
      )),

      new BooleanField('TRANSLIT', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_TRANSLIT'),
        'values' => array('N', 'Y'),
        'default_value' => 'N',
      )),

      new BooleanField('TYPOS', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_TYPOS'),
        'values' => array('N', 'Y'),
        'default_value' => 'N',
      )),

      new BooleanField('LAYOUT', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_LAYOUT'),
        'values' => array('N', 'Y'),
        'default_value' => 'N',
      )),

      new BooleanField('MORPHOLOGY', array(
        'title' => Loc::getMessage('NEFT_SYNONYMS_DB_TITLE_MORPHOLOGY'),
        'values' => array('N', 'Y'),
        'default_value' => 'N',
      )),
    );
  }

  public static function getUserId()
  {
    global $USER;
    return $USER->GetID();
  }

  public static function update($primary, array $data)
  {
    $data['MODIFIED_BY'] = static::getUserId();
    return parent::update($primary, $data);
  }
}
