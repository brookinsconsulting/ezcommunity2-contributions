<?
/*!
    $Id: forgotten.php,v 1.2 2000/07/26 15:05:41 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <17-Jul-2000 15:11:39 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezmail.php" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "forgotten" => "$DOCROOT/templates/forgotten.tpl",
              "finished" => "$DOCROOT/templates/forgotten-finished.tpl",
              "failure" => "$DOCROOT/templates/forgotten-failure.tpl") );

$t->set_var( "docroot", $DOCROOT);

if ( $newpassword )
{
    $usr = new eZUser();
    if ( $usr->passwordEmail( $email ) == 0)
    {
        //success - user found - email sendt.
        $t->pparse( "output", "finished" );
    }
    else
    {
        // failure. user not found :-(
        $t->pparse( "output", "failure" );
    }
}
else
{
    $t->pparse( "output", "forgotten" );
}
?>
