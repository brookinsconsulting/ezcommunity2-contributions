<?

// site information
include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

// fetch the latest newsheadlines.
include_once( "classes/ezmail.php" );

include( "eznewsfeed/admin/cron.php" );

//  $mail = new eZMail();
//  $mail->setSubject( "cron job done" );
//  $mail->setSender( "nospam@ez.no" );
//  $mail->setReceiver( "nospam@ez.no" );
//  $mail->send();

?>
