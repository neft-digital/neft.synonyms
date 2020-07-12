<?php
if ($_REQUEST["EDIT"] === "Y") {
  require("neft_synonyms_edit.php");
} else {
  require("neft_synonyms_list.php");
}
