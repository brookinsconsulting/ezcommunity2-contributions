<?
/*!
    $Id: editmessage.php,v 1.1 2000/07/25 15:37:05 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <25-Jul-2000 15:13:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "template.inc" );
include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );
include_once( "$DOCROOT/classes/ezuser.php" );

$t = new Template( "." );
$t->set_file(Array( "edit" => "$DOCROOT/admin/templates/editmessage.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl" ) );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

$t->parse( "navigation-bar", "navigation", true);

// rest


$t->set_var( "link1-url", "main.php");
$t->set_var( "link1-caption", "Gå til topp");
$t->set_var( "link2-url", "search.php");
$t->set_var( "link2-caption", "Søk");

$t->set_var( "back-url", "main.php");
$t->parse( "navigation-bar-bottom", "navigation-bottom", true);

$t->pparse( "output", "edit" );
?>
