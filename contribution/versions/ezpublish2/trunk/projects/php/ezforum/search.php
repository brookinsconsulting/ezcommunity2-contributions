<?
/*!
    $Id: search.php,v 1.2 2000/07/26 17:03:13 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <26-Jul-2000 17:22:47 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include_once( "ezphputils.php" );
include_once( "template.inc" );

include_once( "$DOCROOT/classes/ezdb.php" );
include_once( "$DOCROOT/classes/ezuser.php" );
include_once( "$DOCROOT/classes/ezforummessage.php" );

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
