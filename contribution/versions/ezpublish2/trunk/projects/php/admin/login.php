<?php

include_once( "../classes/ezdb.php" );

include_once( "class.INIFile.php" );
include_once( "template.inc" );
include_once( "../classes/ezuser.php" );


include_once( "../classes/ezsession.php" );
include_once( "ezphputils.php" );

$user = new eZUser();

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
