<?
// 
// $Id: categorylist.php,v 1.5 2001/04/20 12:57:05 fh Exp $
//
// Frederik Holljen <fh@ez.no>
// Created on: <18-Apr-2001 10:26:26 fh>
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
include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );

if( isset( $New ) )
{
    eZHTTPTool::header( "Location: /bulkmail/categoryedit/0" );
    exit();
}

if( isset( $Delete ) )
{
    if( count( $CategoryArrayID ) > 0 )
    {
        foreach( $CategoryArrayID as $categoryID )
        {
            eZBulkMailCategory::delete( $categoryID );
        }
    }
    if( count( $BulkMailArrayID ) > 0 )
    {
        foreach( $BulkMailArrayID as $bulkmailID )
        {
            eZBulkMail::delete( $bulkmailID );
        }
    }
}

$t = new eZTemplate( "ezbulkmail/admin/" . $ini->read_var( "eZBulkMailMain", "AdminTemplateDir" ),
                     "ezbulkmail/admin/intl", $Language, "categorylist.php" );
$iniLanguage = new INIFile( "ezbulkmail/admin/intl/" . $Language . "/categorylist.php.ini", false );

$locale = new eZLocale( $Language ); 
$t->set_file( array(
    "category_list_tpl" => "categorylist.tpl"
    ) );

$t->setAllStrings();
$t->set_var( "site_style", $SiteStyle );

$t->set_block( "category_list_tpl", "category_tpl", "category" );
$t->set_block( "category_tpl", "category_item_tpl", "category_item" );
$t->set_block( "category_list_tpl", "bulkmail_tpl", "bulkmail" );
$t->set_block( "bulkmail_tpl", "bulkmail_item_tpl", "bulkmail_item" );
$t->set_var( "category", "" );
$t->set_var( "category_item", "" );
$t->set_var( "bulkmail", "" );
$t->set_var( "bulkmail_item", "" );
$t->set_var( "current_category_name", "" );
$t->set_var( "current_category_id", "" );

/** List all the avaliable categories **/
$categories = eZBulkMailCategory::getAll();
$i = 0;
foreach( $categories as $categoryitem )
{
    $t->set_var( "category_name", $categoryitem->name() );
    $t->set_var( "category_description", $categoryitem->description() );
    $t->set_var( "subscription_count", $categoryitem->subscriberCount() );
    $t->set_var( "category_id", $categoryitem->id() );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}
if( $i > 0 )
    $t->parse( "category", "category_tpl" );

if( is_numeric( $CategoryID ) && $CategoryID > 0 )
{
    $category = new eZBulkMailCategory( $CategoryID );
    $t->set_var( "current_category_name", $category->name() );
    $t->set_var( "current_category_id", $category->id() );
    $mail = $category->mail(0, 100000);
    $i = 0;
    foreach( $mail as $mailItem )
    {
        $t->set_var( "bulkmail_id", $mailItem->id() );
        $t->set_var( "bulkmail_subject", $mailItem->subject() );

        if( !$mailItem->isDraft() )
        {
            $t->set_var( "sent_date", $locale->format( $mailItem->date() ) );
        }
        else
        {
            $t->set_var( "sent_date", $iniLanguage->read_var( "strings", "not_sent" ) );
        }
        ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    
        $t->parse( "bulkmail_item", "bulkmail_item_tpl", true );
        $i++;
    }
    if( $i > 0 )
    $t->parse( "bulkmail", "bulkmail_tpl" );

}

$t->pparse( "output", "category_list_tpl" );
?>
