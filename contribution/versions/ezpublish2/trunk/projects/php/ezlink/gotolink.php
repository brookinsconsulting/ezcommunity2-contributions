<?
/*
  Legger inn hits
*/


include_once( "template.inc" );
require "ezlink/dbsettings.php";
include_once( "ezphputils.php" );

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";


print( $HTTP_REFERER );

if ( $Action == "addhit" )
{
    $hit = new eZHit();
    $hit->setLink( $LID );
    $hit->setRemoteIP( $REMOTE_ADDR );
    $hit->store();

}

printRedirect( "http://" . $Url );



?>
