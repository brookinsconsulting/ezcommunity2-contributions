<?
// 
// $Id: subscriptionlist.php,v 1.2 2001/04/24 12:19:03 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <18-Apr-2001 13:36:21 fh>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );
include_once( "ezbulkmail/classes/ezbulkmailsubscriptionaddress.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );


$subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $session->variable( "BulkMailAddress" ) );

if( !is_object( $subscriptionaddress ) )
{
    eZHTTPTool::header( "Location: /bulkmail/login" );
    exit();
}


if( isset( $Ok ) && count( $CategoryArrayID ) > 0 )
{
    $subscriptionaddress->unsubscribe( true );
    foreach( $CategoryArrayID as $categoryID )
    {
        $subscriptionaddress->subscribe( $categoryID );
    }
    /** TODO: Create a confirmation dialog to send the user to... let him either edit or do nothing...**/
    eZHTTPTool::header( "Location: /bulkmail/subscriptionlist/" );
    exit();
}


$t = new eZTemplate( "ezbulkmail/user/" . $ini->read_var( "eZBulkMailMain", "TemplateDir" ),
                     "ezbulkmail/user/intl", $Language, "subscriptionlist.php" );

$t->set_file( array(
    "subscription_list_tpl" => "subscriptionlist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteStyle );

$t->set_block( "subscription_list_tpl", "category_tpl", "category" );
$t->set_block( "category_tpl", "category_item_tpl", "category_item" );
$t->set_var( "category", "" );
$t->set_var( "category_item", "" );
$t->set_var( "email_value", "" );
$t->set_var( "current_email", "" );

/** List all the avaliable categories if there is a valid current address **/
$haystack = $subscriptionaddress->subscriptions( false );
        
$categories = eZBulkMailCategory::getAll();
foreach( $categories as $categoryitem )
{
    $t->set_var( "category_name", $categoryitem->name() );
    $t->set_var( "category_description", $categoryitem->description() );
    $t->set_var( "category_id", $categoryitem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    if( isset( $haystack ) && in_array( $categoryitem->id(), $haystack ) )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );
    
    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}
if( $i > 0 )
    $t->parse( "category", "category_tpl" );

$t->pparse( "output", "subscription_list_tpl" );
?>
