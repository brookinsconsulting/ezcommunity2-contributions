<?
/*!
    $Id: main.php,v 1.2 2000/08/22 09:35:02 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <28-Jul-2000 13:49:14 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "class.INIFile.php" );
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );

include_once( "template.inc" );

$t = new Template( $DOC_ROOT . "admin/templates/" );

$t->set_file( Array( "main" => "main.tpl" ) );

$t->set_var( "docroot", $DOC_ROOT );

$t->pparse( "output", "main" );
