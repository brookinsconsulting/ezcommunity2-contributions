<?php
//
// $Id: supportcategoryedit.php,v 1.3 2001/12/04 14:14:28 jhe Exp $
//
// Created on: <05-Nov-2001 18:30:10 jhe>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

include_once( "ezbug/classes/ezbugsupportcategory.php" );
include_once( "ezbug/classes/ezbugmodule.php" );
include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/eztemplate.php" );

require( "ezuser/admin/admincheck.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZBugMain", "Language" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "supportcategoryedit.php" );

$t->set_file( "support_edit_tpl", "supportcategoryedit.tpl" );

$t->setAllStrings();

$t->set_block( "support_edit_tpl", "category_element_tpl", "category_element" );
$t->set_block( "support_edit_tpl", "empty_error_tpl", "empty_error" );
$t->set_block( "support_edit_tpl", "email_error_tpl", "email_error" );

$locale = new eZLocale( $Language );

$t->set_var( "site_style", $SiteStyle );
$t->set_var( "empty_error", "" );
$t->set_var( "email_error", "" );

if ( isSet( $Cancel ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/support/category/list/" );
    exit();
}

switch ( $Action )
{
    case "edit":
    {
        $supportCategory = new eZBugSupportCategory( $id );
        $t->set_var( "id", $supportCategory->id() );
        $t->set_var( "name", $supportCategory->name() );
        $t->set_var( "email", $supportCategory->email() );
        $t->set_var( "replyto", $supportCategory->replyTo() );
        $t->set_var( "password", "dummy" );
        $t->set_var( "mailserver", $supportCategory->mailServer() );
        $t->set_var( "mailserverport", $supportCategory->mailServerPort() );
        $t->set_var( "supportno_checked", $supportCategory->supportNo() ? "checked" : "" );
        $t->set_var( "action", "update" );
        
        $categoryList = eZBugModule::getAll();
        foreach ( $categoryList as $bugCategory )
        {
            $t->set_var( "category_name", $bugCategory->name( false ) );
            $t->set_var( "category_id", $bugCategory->id() );
            if ( $supportCategory->bugModuleID() == $bugCategory->id() )
                $t->set_var( "selected", "selected" );
            else
                $t->set_var( "selected", "" );
            $t->parse( "category_element", "category_element_tpl", true );
        }
    }
    break;

    case "new":
    {
        $t->set_var( "id", "" );
        $t->set_var( "name", "" );
        $t->set_var( "email", "" );
        $t->set_var( "replyto", "" );
        $t->set_var( "password", "" );
        $t->set_var( "mailserver", "" );
        $t->set_var( "mailserverport", "110" );
        $t->set_var( "supportno_checked", "" );

        $t->set_var( "action", "insert" );
        
        $categoryList = eZBugModule::getAll();
        foreach ( $categoryList as $bugCategory )
        {
            $t->set_var( "category_name", $bugCategory->name( false ) );
            $t->set_var( "category_id", $bugCategory->id() );
            $t->set_var( "selected", "" );

            $t->parse( "category_element", "category_element_tpl", true );
        }
    }
    break;
    
    case "update":
    {
        $supportCategory = new eZBugSupportCategory( $id );
    }
    break;

    case "insert":
    {
        $supportCategory = new eZBugSupportCategory();
    }
    break;
}

$error = array( "Email" => false, "Empty" => false );

if ( $Action == "update" || $Action == "insert" )
{
    if ( $Email != "" && !eZMail::validate( $Email ) )
        $error["Email"] = true;
    if ( $Name == "" || $Password == "" || $MailServer == "" || $Email == "" )
        $error["Empty"] = true;
}

if ( in_array( true, $error ) )
{
    if ( $error["Email"] )
        $t->parse( "email_error", "email_error_tpl" );
    if ( $error["Empty"] )
        $t->parse( "empty_error", "empty_error_tpl" );
        
    $t->set_var( "id", $ID );
    $t->set_var( "name", $Name );
    $t->set_var( "email", $Email );
    $t->set_var( "replyto", $ReplyTo );
    $t->set_var( "password", "" );
    $t->set_var( "mailserver", $MailServer );
    $t->set_var( "mailserverport", $MailServerPort );
    $t->set_var( "supportno_checked", $SupportNo == "on" ? "checked" : "" );
    $categoryList = eZBugModule::getAll();
    foreach ( $categoryList as $bugCategory )
    {
        $t->set_var( "category_name", $bugCategory->name( false ) );
        $t->set_var( "category_id", $bugCategory->id() );
        if ( $BugModuleID == $bugCategory->id() )
            $t->set_var( "selected", "selected" );
        else
            $t->set_var( "selected", "" );
        
        $t->parse( "category_element", "category_element_tpl", true );
    }
    $t->set_var( "action", $Action );
}

if ( ( $Action == "update" || $Action == "insert" ) && !in_array( true, $error ) )
{
    $supportCategory->setName( $Name );
    $supportCategory->setBugModuleID( $BugModuleID );
    $supportCategory->setEmail( $Email );
    $supportCategory->setReplyTo( $ReplyTo );
    if ( $Password != "dummy" )
        $supportCategory->setPassword( $Password );
    $supportCategory->setMailServer( $MailServer );
    $supportCategory->setMailServerPort( $MailServerPort );
    $supportCategory->setSupportNo( $SupportNo );
    $supportCategory->store();

    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /bug/support/category/list/" );
    exit();
}

$t->pparse( "output", "support_edit_tpl" );

?>
