<?
/*!
    $Id: main.php,v 1.6 2000/10/17 14:19:16 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <28-Jul-2000 13:49:14 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "classes/template.inc" );

$t = new Template( $DOC_ROOT . "admin/templates/" );

$t->set_file( Array( "main" => "main.tpl" ) );

$t->set_var( "docroot", $DOC_ROOT );

$t->pparse( "output", "main" );
