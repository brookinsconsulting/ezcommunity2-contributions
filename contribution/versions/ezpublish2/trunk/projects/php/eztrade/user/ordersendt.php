<?php
// 
// $Id: ordersendt.php,v 1.1 2000/10/22 10:33:23 bf-cvs Exp $
//
// 
//
// Bård Farstad <bf@ez.no>
// Created on: <06-Oct-2000 14:04:17 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/eztemplate.php" ); 

$t = new eZTemplate( "eztrade/" . $ini->read_var( "eZTradeMain", "TemplateDir" ),
                     "eztrade/intl/", $Language, "ordersendt.php" );

$t->setAllStrings();

$t->set_file( array(
    "order_sendt_tpl" => "ordersendt.tpl"
    ) );


$t->pparse( "output", "order_sendt_tpl" );

?>
