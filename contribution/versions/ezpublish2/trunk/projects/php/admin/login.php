<?php
ob_start();

include_once( "classes/ezdb.php" );
include_once( "classes/ezuser.php" );
include_once( "classes/ezsession.php" );

$user = new eZUser();
$tmp = $user->validateUser( $Login, $Password );

if ($tmp != 0 )
{
    $session = new eZSession();
    $session->setUserID( $tmp );
    $session->store();
    
    Header( "Location: index.php" );
    exit();
}
else
{
    $message = "Feil brukernavn/passord!";
    include( "index.php" );
}

ob_end_flush();
?>
