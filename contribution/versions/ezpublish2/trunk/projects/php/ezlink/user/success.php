<?
/*!
    $Id: success.php,v 1.1 2000/10/19 09:32:09 ce-cvs Exp $

    Author: Christoffer A. Elo <ce@ez.no>
    
    Created on: <14-Sep-2000 19:37:17 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFile( "site.ini" );

$Language = $ini->read_var( "eZLinkMain", "Language" );
$DOC_ROOT = $ini->read_var( "eZLinkMain", "DocumentRoot" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
"ezlink/user/intl", $Language, "success.php" );
$t->setAllStrings();

$t->set_file( array(
    "success" => "success.tpl"
    ));

$t->pparse( "output", "success" );

?>
