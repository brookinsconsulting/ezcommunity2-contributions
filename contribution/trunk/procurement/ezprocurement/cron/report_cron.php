<?php

include_once("classes/INIFile.php");
$ini = &INIFile::globalINI();
$Flagged = $ini->read_var("eZProcurementMain", "FlagExpiredStats");
include_once("classes/ezdatetime.php");
include_once("ezstats/classes/ezpageview.php");
include_once("ezstats/classes/ezpageviewquery.php");

eZPageViewQuery::clean($Flagged, true);

php?>
