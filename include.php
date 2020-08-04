<?php
require_once("vendor/autoload.php");

use Bitrix\Main\Loader;

Loader::registerAutoLoadClasses(
    "neft.synonyms",
    array(
      "\Neft\Synonyms"                      => "lib/synonyms.php",
      "\Neft\Synonyms\Helpers"              => "lib/Helpers.php",
      "\Neft\Synonyms\WordProcessing"       => "lib/WordProcessing.php",
      "\Neft\Synonyms\SearchTitleExtender"  => "lib/SearchTitleExtender.php",
    )
);
