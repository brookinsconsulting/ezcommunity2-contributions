<?

// site information
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

// do session cleanup
include( "ezsession/admin/cron.php" );

// fetch the latest newsheadlines.
include_once( "ezmail/classes/ezmail.php" );

// uncomment the next line to fetch news by cron

// include( "eznewsfeed/admin/cron.php" );


?>
