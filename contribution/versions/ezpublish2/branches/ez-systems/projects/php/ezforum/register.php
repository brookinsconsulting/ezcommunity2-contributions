<?
/*!
    $Id: register.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:47:17 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "template.inc" );
include( "ezforum/dbsettings.php" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );


$t = new Template(".");
$t->set_file(Array( "register" => "templates/register.tpl",
                    "finish" => "templates/register-finished.tpl"));
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
        //error message - retry
        die("register: duplicate nickname or missing arguments, dying...");
    }

    $user->newUser();
    $user->setNickName( $nickName );
    $user->setFirstName( $firstName );
    $user->setLastName( $lastName );
    $user->setEmail( $email );
    $user->setPassword( $password );
    $user->enableUser( );

    $user->store( );

    $t->pparse( "output", "finish" );
}
else
{
    $t->pparse( "output", "register" );
}
?>
