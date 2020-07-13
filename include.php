<?php
require_once("vendor/autoload.php");

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    "neft.synonyms",
    array(
      "\Neft\Synonyms"                      => "lib/synonyms.php",
      "\Neft\Synonyms\SearchTitleExtender"  => "lib/SearchTitleExtender.php",
    )
);

// TODO: Удалить
// AddMessage2Log("include.php", "neft_synonyms");




// AddEventHandler("search", "BeforeIndex", "BeforeIndexHandler");
// function BeforeIndexHandler($arFields)
// {
//    if(!CModule::IncludeModule("iblock")) // подключаем модуль
//       return $arFields;
//    if($arFields["MODULE_ID"] == "iblock")
//    {
//       $db_props = CIBlockElement::GetProperty(                        // Запросим свойства индексируемого элемента
//                                     $arFields["PARAM2"],         // BLOCK_ID индексируемого свойства
//                                     $arFields["ITEM_ID"],          // ID индексируемого свойства
//                                     array("sort" => "asc"),       // Сортировка (можно упустить)
//                                     Array("CODE"=>"CML2_ARTICLE")); // CODE свойства (в данном случае артикул)
//       if($ar_props = $db_props->Fetch())
//          $arFields["TITLE"] .= " ".$ar_props["VALUE"];   // Добавим свойство в конец заголовка индексируемого элемента
//    }
//    return $arFields; // вернём изменения
// }










// $eventManager = \Bitrix\Main\EventManager::getInstance();
// $eventManager->addEventHandlerCompatible('search', 'BeforeIndex', ['\\CatalogProductIndexer', 'handleBeforeIndex']);

// class CatalogProductIndexer
// {
//   /**
//    * @var int Идентификатор инфоблока каталога
//    */
//   const IBLOCK_ID = '30';

//   /**
//    * Дополняет индексируемый массив нужными значениями
//    * подписан на событие BeforeIndex модуля search
//    * @param array $arFields
//    * @return array
//    */
//   public static function handleBeforeIndex($arFields = [])
//   {
//     if (!static::isInetesting($arFields)) {
//       return $arFields;
//     }

//     /**
//      * @var array Массив полей элемента, которые нас интересуют
//      */
//     $arSelect = [
//       'ID',
//       'IBLOCK_ID',
//       'PROPERTY_TEST1',
//       'PROPERTY_TEST2'
//     ];

//     /**
//      * @var CIblockResult Массив описывающий индексируемый элемент
//      */
//     $resElements = \CIBlockElement::getList(
//         [],
//         [
//           'IBLOCK_ID' => $arFields['PARAM2'],
//           'ID'        => $arFields['ITEM_ID']
//         ],
//         false,
//         [
//           'nTopCount' => 1
//         ],
//         $arSelect
//     );

//     /**
//      * В случае, если элемент найден мы добавляем нужные поля 
//      * в соответсвующие столбцы поиска
//      */
//     if ($arElement = $resElements->fetch()) {
//       $arFields['TITLE'] .= ' ' . $arElement['PROPERTY_TEST1_VALUE'];
//       $arFields['BODY'] .= ' ' . $arElement['PROPERTY_TEST2_VALUE'];
//     }

//     return $arFields;
//   }

//   /**
//    * Возвращает true, если это интересующий нас элемент
//    * @param array $fields 
//    * @return boolean
//    */
//   public static function isInetesting($fields = [])
//   {
//     return ($fields["MODULE_ID"] == "iblock" && $fields['PARAM2'] == static::IBLOCK_ID);
//   }
// }
