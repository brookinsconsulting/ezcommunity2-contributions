<?
/*!
    $Id: recreate.php,v 1.2 2000/07/18 13:46:00 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <18-Jul-2000 12:26:33 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include( "ezforum/dbsettings.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file( Array( "recreate" => "$DOCROOT/templates/recreate.tpl",
                     "fixed" => "$DOCROOT/templates/recreate-fixed.tpl",
                     "error" => "$DOCROOT/templates/recreate-error.tpl" ));

$t->set_var( "docroot", $DOCROOT);
$usr = new eZUser;

if ( $hash )
{
    $usr->getByAuthHash( $hash );    

    if ( ($password == $password2) && ($password != "") )
        $usr->setPassword( $password );

    $usr->generateAuthHash(); // to prevent double-trouble (2 new password assignments)
    $usr->store();
    
    $t->pparse( "output", "fixed", true);
}
else
{
    if ( $usr->getByAuthHash( $id ) == 0) // OK
    {
        $t->set_var( "hash", $id);
        $t->pparse( "output", "recreate", true);
    }
    else // wrong-O ;-/
    {
        $t->pparse( "output", "error", true);
    }
}
?>
