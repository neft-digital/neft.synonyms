<?php
if ($_REQUEST["edit"] === "Y") {
  require("neft_synonyms_edit.php");
} else {
  require("neft_synonyms_list.php");
}
