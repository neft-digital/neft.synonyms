<?php
$_SERVER["DOCUMENT_ROOT"] = realpath(__DIR__ . "/../../../..");

define("SITE_ID", "s1");
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
define("BX_NO_ACCELERATOR_RESET", true);
define("CHK_EVENT", true);

$level = ob_get_level();
require_once $_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php";

Bitrix\Main\Loader::includeModule("neft.synonyms");

while (ob_get_level() > $level) {
  ob_end_clean();
}
