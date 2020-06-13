<?php
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");

use \Bitrix\Main\Loader;

$path = Loader::getLocal("modules/neft.synonyms/admin/neft_synonyms.php");
if (file_exists($path)) {
  include $path;
}
