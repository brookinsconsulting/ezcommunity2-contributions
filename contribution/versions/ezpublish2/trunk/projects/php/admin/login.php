<?php

include_once( "../classes/ezdb.php" );
include_once( "../ezpublish/settings/dbconnect.php" );
include_once( "class.INIFile.php" );
include_once( "template.inc" );
include_once( "../classes/ezuser.php" );
include_once( "../dbsettings.php" );
include_once( "../classes/ezsession.php" );
include_once( "ezphputils.php" );

$Ini = new INIFile( "../ezpublish/ezpublish.ini" );
selectDB( $Ini->read_var( "MAIN", "Database" ) );

$user = new eZUser( );


$tmp = $user->validateUser( $userid, $passwd);
if ($tmp != 0)
{
    $session = new eZSession();
    $session->setUserID( $tmp );
    $session->store();
    Header( "Location: index.php" );
}
else
{
    $message = "Feil brukernavn/passord!";
    include( "index.php" );
}

?>
