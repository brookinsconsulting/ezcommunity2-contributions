<?php

chdir( "/var/www/mygold/" );

include_once( "ezcc/classes/ezcclog.php" );
include_once( "classes/ezdatetime.php" );

$mail = "sascha@sf-fox.de";

$dateTime = new eZDateTime();
$date = $dateTime->year() . $dateTime->addZero( $dateTime->month() ) .  $dateTime->addZero( $dateTime->day() );
$time = $dateTime->addZero( $dateTime->hour() ) . $dateTime->addZero( $dateTime->minute() ) .  $dateTime->addZero( $dateTime->second() );

$taID = md5( microtime() );


$xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <ICMessage IC_SHOP_ID=\"65 019\" IC_SHOP_TA_ID=\"$taID\" IC_TA_TYPE=\"910\" IC_DATE=\"$date\" IC_TIME=\"$time\" IC_PROCESSING_CODE=\"1\" />";


$execString = "checkout/socket.pl " . EscapeShellArg( $xml);


$count = count( eZCCLog::getAllELV() );

if ( $count > 0 )
{
    $ret = system( $execString, $ret_var );
    eZCCLog::setELVasCutovered();
    mail( "$mail", "Cutover OK", "Cutover OK" );
}

?>
