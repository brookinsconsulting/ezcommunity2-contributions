<?
/*!
    $Id: search.php,v 1.1 2000/07/24 13:58:25 lw Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <creation-tag>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "template.inc" );

include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezforummessage.php" );

//preliminary setup
$usr = new eZUser;

$t = new Template(".");
$t->set_file( array("main" => "$DOCROOT/templates/search.tpl",
                    "search" => "$DOCROOT/templates/main-search.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl"
                    ) );

$t->set_var( "docroot", $DOCROOT);

$t->parse( "navigation-bar", "navigation", true);

$t->parse( "searchfield", "search", true );

$t->pparse("output","main");
?>
