<?php

################################################################################
# Includes - INIFile.php to read FlagExpiredStats setting
#            class:DateTime to get today's timestamp
#            class:ezpageview for stats info
# Section 1 - Summary
#
################################################################################
include_once("classes/INIFile.php");
$ini = &INIFile::globalINI();
$Flagged = $ini->read_var("FlagExpiredStats", "eZProcurementMain");
include_once("classes/ezdatetime.php");
include_once("ezstats/classes/ezpageview.php");
include_once("ezstats/classes/ezpageviewquery.php");

$today = new eZDateTime();
$todayStamp = $today->mysqlTimeStamp();
die('today is '.$todayStamp);



php?>
