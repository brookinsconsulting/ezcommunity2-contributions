<?php
//
// $Id: configure.php,v 1.8 2001/07/20 11:18:28 jakobn Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezmail/classes/ezmailfolder.php" );
include_once( "ezmail/classes/ezmailfilterrule.php" );

if( isset( $NewAccount ) )
{
    eZHTTPTool::header( "Location: /mail/accountedit" );
    exit();
}

if( isset( $NewFilter) )
{
    eZHTTPTool::header( "Location: /mail/filteredit" );
    exit();
}

if( isset( $DeleteAccounts ) &&  count( $AccountArrayID ) > 0  )
{
    foreach( $AccountArrayID as $accountID )
    {
        eZMailAccount::delete( $accountID );
    }
}
if( isset( $DeleteAccounts ) &&   count( $FilterArrayID ) > 0 ) 
{
    foreach( $FilterArrayID as $filterID )
    {
        eZMailFilterRule::delete( $filterID );
    }
}
if( isset( $Ok ) )
{
    $accounts = eZMailAccount::getByUser( eZUser::currentUser() );
    foreach( $accounts as $account )
    {
        if( count( $AccountActiveArrayID ) > 0 &&
            in_array( $account->id(), $AccountActiveArrayID ) )
            $account->setIsActive( true );
        else
            $account->setIsActive( false );

        $account->store();
    }
}

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZMailMain", "Language" ); 

$t = new eZTemplate( "ezmail/user/" . $ini->read_var( "eZMailMain", "TemplateDir" ),
                     "ezmail/user/intl/", $Language, "configure.php" );
$t->setAllStrings();

$t->set_file( array(
    "mail_configure_page_tpl" => "configure.tpl"
    ) );

$t->set_var( "site_style", $SiteStyle );
$t->set_block( "mail_configure_page_tpl", "account_item_tpl", "account_item" );
$t->set_block( "mail_configure_page_tpl", "filter_item_tpl", "filter_item" );
$t->set_var( "account_item", "" );
$t->set_var( "filter_item" ,"" );


$user = eZUser::currentUser();
$accounts = eZMailAccount::getByUser( $user->id() );
foreach( $accounts as $account )
{
    $t->set_var( "account_id", $account->id() );
    $t->set_var( "account_name", htmlspecialchars( $account->name() ) );
    $t->set_var( "account_type", $account->serverType() );
    $t->set_var( "account_folder", "" );
    $account->isActive() ? $t->set_var( "account_active_checked", "checked" ) : $t->set_var( "account_active_checked", "" );
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "account_item", "account_item_tpl", true );
}

$filters = eZMailFilterRule::getByUser( $user->id() );
foreach( $filters as $filter )
{
    $t->set_var( "filter_id", $filter->id() );
    $t->set_var( "filter_name", htmlspecialchars( buildFilterName( $filter, $Language) ) );
    
    ( $i % 2 ) ? $t->set_var( "td_class", "bgdark" ) : $t->set_var( "td_class", "bglight" );
    $t->parse( "filter_item", "filter_item_tpl", true );
}

$t->pparse( "output", "mail_configure_page_tpl" );

function buildFilterName( &$filter, $Language )
{
    $localINI = new INIFile( "ezmail/user/intl/" . $Language . "/configure.php.ini" );
    $if = $localINI->read_var( "strings", "if" );
    $move = $localINI->read_var( "strings", "move" );
    $headerName = "";
    switch( $filter->headerType() )
    {
        case FILTER_MESSAGE: $headerName = $localINI->read_var( "strings", "message"); break;
        case FILTER_BODY: $headerName = $localINI->read_var( "strings", "body"); break;
        case FILTER_ANY: $headerName = $localINI->read_var( "strings", "any_header"); break;
        case FILTER_TOCC: $headerName = $localINI->read_var( "strings", "tocc"); break;
        case FILTER_SUBJECT: $headerName = $localINI->read_var( "strings", "subject"); break;
        case FILTER_FROM: $headerName = $localINI->read_var( "strings", "from"); break;
        case FILTER_TO: $headerName = $localINI->read_var( "strings", "to"); break;
        case FILTER_CC: $headerName = $localINI->read_var( "strings", "cc"); break;
    }

    $checkName = "";
    switch( $filter->checkType() )
    {
        case FILTER_EQUALS: $checkName = $localINI->read_var( "strings", "equals"); break;
        case FILTER_NEQUALS: $checkName = $localINI->read_var( "strings", "nequals"); break;
        case FILTER_CONTAINS: $checkName = $localINI->read_var( "strings", "contains"); break;
        case FILTER_NCONTAINS: $checkName = $localINI->read_var( "strings", "ncontains"); break;
        case FILTER_REGEXP: $checkName = $localINI->read_var( "strings", "regexp"); break;
    }
    $folder = new eZMailFolder( $filter->folderID() );

    return $if ." " . $headerName ." " . $checkName . " " . " \"". $filter->match() . "\" " . $move . " \"" . $folder->name() . "\"";
}

?>
