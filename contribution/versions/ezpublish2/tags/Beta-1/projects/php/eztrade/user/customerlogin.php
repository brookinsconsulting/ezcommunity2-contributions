<?
// 
// $Id: customerlogin.php,v 1.2 2000/10/22 10:46:20 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <03-Oct-2000 16:45:30 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );

if ( eZUser::currentUser() )
{
    Header( "Location: /trade/checkout/" );
}
else
{
    $t = new eZTemplate( "eztrade/user/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                         "eztrade/user/intl/", $Language, "customerlogin.php" );

    $t->setAllStrings();

    $t->set_file( array(        
        "customer_login_tpl" => "customerlogin.tpl"
        ) );

    $t->set_var( "redirect_url", "/trade/customerlogin/" );
    $t->pparse( "output", "customer_login_tpl" );
}

?>
