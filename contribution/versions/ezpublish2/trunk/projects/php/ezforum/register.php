<?
/*!
    $Id: register.php,v 1.4 2000/07/20 19:44:36 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:47:17 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "template.inc" );
include( "ezphputils.php" );
include( "ezforum/dbsettings.php" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
//include( "$DOCROOT/classes/ezsession.php" );

$t = new Template( "." );
$t->set_file(Array( "register" => "$DOCROOT/templates/register.tpl",
                    "finish" => "$DOCROOT/templates/register-finished.tpl"));
$t->set_var( "docroot", $DOCROOT);

$user = new eZUser;

if ( $registrer )
{
    if ( ( $user->searchNickName( $nickName ) ) ||
         ( $firstName == "" ) ||
         ( $lastName == "" ) ||
         ( $email == "" ) ||
         ( $password == "" ) ||
         ( $password != $password2 ) )
    {
        // Retry
        // redirect to self - with variables -
        die();
    }

    $user->newUser();
    $user->setNickName( $nickName );
    $user->setFirstName( $firstName );
    $user->setLastName( $lastName );
    $user->setEmail( $email );
    $user->setPassword( $password );
    $user->enableUser( );

    $user->store();

    //$session = new eZSession();
    //$session->setUserID( $tmp );
    //$session->store();

    //printRedirect( "/index.php?page=$DOCROOT/main.php" );
    $t->pparse( "output", "finish" );
}
else
{
    $t->pparse( "output", "register" );
}
?>
