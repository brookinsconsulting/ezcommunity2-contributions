<?php
//
// $Id: subscriptionlist.php,v 1.10.2.2.4.2 2002/04/10 12:00:53 ce Exp $
//
// Created on: <18-Apr-2001 13:36:21 fh>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
include_once( "ezbulkmail/classes/ezbulkmailusersubscripter.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

$Language = $ini->read_var( "eZBulkMailMain", "Language" );
$TemplateDir = $ini->read_var( "eZBulkMailMain", "TemplateDir" );

if ( $ini->read_var( "eZBulkMailMain", "UseEZUser" ) == "enabled" )
{
    $user = eZUser::currentUser();
    $subscriptionaddress = new eZBulkMailUserSubscripter( $user );
    if( !is_object ( $user ) )
    {
        eZHTTPTool::header( "Location: /user/login/?RedirectURL=/bulkmail/subscriptionlist/" );
        exit();
    }
}
else
{
    $subscriptionaddress = eZBulkMailSubscriptionAddress::getByEmail( $session->variable( "BulkMailAddress" ) );
    if( is_object( $subscriptionaddress ) == false )
    {
        eZHTTPTool::header( "Location: /bulkmail/login" );
        exit();
    }

}

if( isset ( $Ok ) )
{
    $subscriptionaddress->unsubscribe( true );

    if ( count ( $Multimedia ) > 0 )
    {
        foreach( $Multimedia as $multi )
        {
            $subscriptionaddress->subscribe( $multi );
        }
    }
    if ( count ( $DVD ) > 0 )
    {
        foreach( $DVD as $DVDItem )
        {
            $subscriptionaddress->subscribe( $DVDItem );
        }
    }
    if ( count ( $Musikk ) > 0 )
    {
        foreach( $Musikk as $MusikkItem )
        {
            $subscriptionaddress->subscribe( $MusikkItem );
        }
    }

    if ( count ( $hifi ) > 0 )
    {
        foreach( $hifi as $hifiitem )
        {
            $subscriptionaddress->subscribe( $hifiitem );
        }
    }

    for( $i=0;$i<count($CategoryAll);$i++ )
    {
        $subscriptionaddress->addDelay( $CategoryAll[$i], $SendDelay[$i] );
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

//$t->set_block( "subscription_list_tpl", "category_tpl", "category" );
//$t->set_block( "category_tpl", "category_item_tpl", "category_item" );
$t->set_block( "subscription_list_tpl", "no_categories_tpl", "no_categories" );

$t->set_block( "subscription_list_tpl", "multimedia_tpl", "multimedia" );
$t->set_block( "multimedia_tpl", "consoll_tpl", "consoll" );
$t->set_block( "multimedia_tpl", "multimedia_sub_category_tpl", "multimedia_sub_category" );
$t->set_block( "multimedia_sub_category_tpl", "button_tpl", "button" );

$t->set_block( "subscription_list_tpl", "musikk_tpl", "musikk" );
$t->set_block( "musikk_tpl", "musikk_item_tpl", "musikk_item" );

$t->set_block( "subscription_list_tpl", "dvd_tpl", "dvd" );
$t->set_block( "dvd_tpl", "dvd_item_tpl", "dvd_item" );

$t->set_block( "subscription_list_tpl", "hifi_tpl", "hifi" );
$t->set_block( "hifi_tpl", "hifi_item_tpl", "hifi_item" );

$t->set_var( "category", "" );
$t->set_var( "category_item", "" );
$t->set_var( "email_value", "" );
$t->set_var( "current_email", "" );
$t->set_var( "no_categories", "" );

// List all the avaliable categories if there is a valid current address
$haystack = $subscriptionaddress->subscriptions( false );

if ( !$CategoryID )
    $CategoryID = 0;

$catArray = eZBulkMailCategory::getByParent(0 );
foreach( $catArray as $cat )
{
    // Multimedia
    if ( $cat->id() == 39 )
    {
        $multimedia = eZBulkMailCategory::getByParent( 39 );
        $printed = true;

        for( $i=0; $i < count ( $multimedia ); $i++ )
        {
            $tmpArray = eZBulkMailCategory::getByParent( $multimedia[$i]->id() );
            $j = 0;
            foreach( $tmpArray as $tmp )
            {
                $categoryNames[$i][$j] = array( "Name" => $tmp->name(),
                                            "ID" => $tmp->id(),
                                            "ParentID" => $multimedia[$i]->id() );
                $j++;
            }
            $t->set_var( "consoll_name", $multimedia[$i]->name() );
            $t->parse( "consoll", "consoll_tpl", true );
        }

        for( $j=0; $j < count ( $categoryNames[0] ); $j++ )
        {
            $i=0;
            $printed = true;
            $t->set_var( "button", "" );
            foreach( $multimedia as $consoll )
            {
                if ( $categoryNames[$i][$j]["ParentID"] == $consoll->id() )
                {
                    if( isset ( $haystack ) && in_array ( $categoryNames[$i][$j]["ID"], $haystack ) )
                        $t->set_var( "is_checked", "checked" );
                    else
                        $t->set_var( "is_checked", "" );

                    $t->set_var( "button_id", $categoryNames[$i][$j]["ID"] );
                    $t->parse( "button", "button_tpl", true );
                }
                $i++;
            }
            $t->set_var( "multimedia_sub_category_name", $categoryNames[0][$j]["Name"] );
            if ( $printed )
                $t->parse( "multimedia_sub_category", "multimedia_sub_category_tpl", true );
            $printed = false;
        }
        $t->parse( "multimedia", "multimedia_tpl" );
    }

    // CD
    if ( $cat->id() == 19 )
    {
        $musikkArray = eZBulkMailCategory::getByParent( 19 );

        foreach( $musikkArray as $cd )
        {
            if( isset ( $haystack ) && in_array ( $cd->id(), $haystack ) )
                $t->set_var( "is_checked", "checked" );
            else
                $t->set_var( "is_checked", "" );

            $t->set_var( "musikk_name", $cd->name() );
            $t->set_var( "musikk_id", $cd->id() );
            $t->parse( "musikk_item", "musikk_item_tpl", true );
        }
        $t->parse( "musikk", "musikk_tpl" );
    }

    // DVD
    if ( $cat->id() == 19 )
    {
        $DVDArray = eZBulkMailCategory::getByParent( 7 );

        foreach( $DVDArray as $dvd )
        {
            if( isset ( $haystack ) && in_array ( $dvd->id(), $haystack ) )
                $t->set_var( "is_checked", "checked" );
            else
                $t->set_var( "is_checked", "" );

            $t->set_var( "dvd_name", $dvd->name() );
            $t->set_var( "dvd_id", $dvd->id() );
            $t->parse( "dvd_item", "dvd_item_tpl", true );
        }
        $t->parse( "dvd", "dvd_tpl" );
    }
    // HiFi
    if ( $cat->id() == 100 )
    {
        $hifi = $cat;
        if( isset ( $haystack ) && in_array ( $hifi->id(), $haystack ) )
            $t->set_var( "is_checked", "checked" );
        else
            $t->set_var( "is_checked", "" );

        $t->set_var( "hifi_name", $hifi->name() );
        $t->set_var( "hifi_id", $hifi->id() );
        $t->parse( "hifi_item", "hifi_item_tpl", true );
        $t->parse( "hifi", "hifi_tpl" );
    }
}



/*
$categories = eZBulkMailCategory::getByParent( $CategoryID );
foreach ( $categories as $categoryitem )
{
    $t->set_var( "category_name", $categoryitem->name() );
    $t->set_var( "category_description", $categoryitem->description() );
    $t->set_var( "category_id", $categoryitem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    if( isset ( $haystack ) && in_array ( $categoryitem->id(), $haystack ) )
        $t->set_var( "is_checked", "checked" );
    else
        $t->set_var( "is_checked", "" );

    $setting = $categoryitem->settings( $subscriptionaddress );

    $t->set_var( "delay_0", "" );
    $t->set_var( "delay_1", "" );
    $t->set_var( "delay_2", "" );
    $t->set_var( "delay_3", "" );

    if ( ( get_class ( $setting ) == "ezbulkmailcategorysettings" ) || ( get_class ( $setting ) == "ezbulkmailusercategorysettings" ) )
    {
        $delay = $setting->delay();
        if ( $delay == 1 )
            $t->set_var( "delay_1", "selected" );
        if ( $delay == 2 )
            $t->set_var( "delay_2", "selected" );
        if ( $delay == 3 )
            $t->set_var( "delay_3", "selected" );
        if ( $delay == 4 )
            $t->set_var( "delay_4", "selected" );
    }
    else
        $t->set_var( "delay_0", "selected" );


    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}
if ( $i > 0 )
{
    $t->set_var( "no_categories", "" );
    $t->parse( "category", "category_tpl" );
}
else
{
    $t->parse( "no_categories", "no_categories_tpl" );
}
*/

$t->pparse( "output", "subscription_list_tpl" );
?>
