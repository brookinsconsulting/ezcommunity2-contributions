<?
// $Id: forumedit.php,v 1.14 2001/01/23 13:16:57 jb Exp $
//
// Author: Lars Wilhelmsen <lw@ez.no>
// Created on: Created on: <14-Jul-2000 13:41:35 lw>
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

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];

$Language = $ini->read_var( "eZForumMain", "Language" );
$error = new INIFIle( "ezforum/admin/intl/" . $Language . "/forumedit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );
include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

require( "ezuser/admin/admincheck.php" );

if ( $Action == "insert" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "ForumAdd" ) )
    {
        if ( $Name != "" &&
        $Description != "" &&
        $CategorySelectID != "" )
        {
            $forum = new eZForum();
            $forum->setName( $Name );
            $forum->setDescription( $Description );

            $user = new eZUser( $ModeratorID );
            $forum->setModerator( $user );

            if ( $IsModerated == "on" )
                $forum->setIsModerated( true );
            else
                $forum->setIsModerated( false );            
            
            
            $forum->store();

            $category = new eZForumCategory( $CategorySelectID );
            $category->addForum( $forum );
            
            eZLog::writeNotice( "Forum created: $Name from IP: $REMOTE_ADDR" );                    

            eZHTTPTool::header( "Location: /forum/forumlist/$CategorySelectID" );
        }
        else
        {
            eZLog::writeWarning( "Forum not created: missing data from IP: $REMOTE_ADDR" );                    
                        
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
}

if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "ForumModify" ) )
    {
        if ( $Name != "" &&
        $Description != "" &&
        $CategorySelectID != "" )
        {
            $forum = new eZForum();
            $forum->get( $ForumID );

            $user = new eZUser( $ModeratorID );
            $forum->setModerator( $user );

            if ( $IsModerated == "on" )
                $forum->setIsModerated( true );
            else
                $forum->setIsModerated( false );            

            
            $forum->setName( $Name );
            $forum->setDescription( $Description );

            $forum->store();

            // remove all category assigmnents.
            $forum->removeFromForums();
                 
            $category = new eZForumCategory( $CategorySelectID );
            $category->addForum( $forum );
            
            eZLog::writeNotice( "Forum updated: $Name from IP: $REMOTE_ADDR" );
                        
            eZHTTPTool::header( "Location: /forum/forumlist/$CategorySelectID" );
        }
        else
        {
            eZLog::writeWarning( "Forum not updated: missing data from IP: $REMOTE_ADDR" ); 
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
}

if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "ForumDelete" ) )
    {
        if ( $ForumID != "" )
        {
            $forum = new eZForum();
            $forum->get( $ForumID );
            $forumName = $forum->name();

            $forum->delete();
            eZLog::writeNotice( "Forum deleted: $forumName from IP: $REMOTE_ADDR" );
            
            eZHTTPTool::header( "Location: /forum/forumlist/" );
        }
        else
        {
            eZLog::writeWarning( "Forum not deleted: id not found from IP: $REMOTE_ADDR" );
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
"ezforum//admin/" . "/intl", $Language, "forumedit.php" );
$t->setAllStrings();

$t->set_file( array( "forum_page" => "forumedit.tpl"
                   ) );

$t->set_block( "forum_page", "category_item_tpl", "category_item" );
$t->set_block( "forum_page", "moderator_item_tpl", "moderator_item" );

$languageIni = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumedit.php.ini", false );
$headline =  $languageIni->read_var( "strings", "head_line_insert" );

$t->set_var( "forum_name", "" );
$t->set_var( "forum_description", "" );
$action_value = "update";
$t->set_var( "forum_id", $ForumID );

if ( $Action == "new" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "ForumModifyAdd" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }

    $action_value = "insert";
}


if ( $Action == "edit" )
{
    $forum = new eZForum( $ForumID );
    $categories = $forum->categories();
    
    $languageIni = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumedit.php.ini", false );
    $headline =  $languageIni->read_var( "strings", "head_line_edit" );

    if ( !eZPermission::checkPermission( $user, "eZForum", "ForumModify" ) )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
    }
    else
    {
        $forum = new eZForum();
        $forum->get( $ForumID );

        $t->set_var( "forum_name", $forum->name() );
        $t->set_var( "forum_description", $forum->description() );
        $t->set_var( "forum_id", $ForumID);

        if ( $forum->isModerated() == true )
            $t->set_var( "forum_is_moderated", "checked" );
        else
            $t->set_var( "forum_is_moderated", "" );
            
        $action_value = "update";

    }
}


$category = new eZForumCategory();
$categoryList = $category->getAll();
foreach( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );


    if ( count( $categories ) > 0 )
    {
        if ( $categoryItem->id() == $categories[0]->id() )
            $t->set_var( "is_selected", "selected" );
        else
            $t->set_var( "is_selected", "" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }


    $t->parse( "category_item", "category_item_tpl", true );
}

$user = new eZUser();
$userList = $user->getAll();
foreach( $userList as $userItem )
{
    $t->set_var( "user_id", $userItem->id() );
    
    $t->set_var( "user_name", $userItem->firstName() . " " . $userItem->lastName() );


    if ( $Action == "edit" )
    {
        $user = $forum->moderator();
        if ( $user )
        {
            if ( $user->id() == $userItem->id() )
            {
                $t->set_var( "is_selected", "selected" );
            }
            else
            {
                $t->set_var( "is_selected", "" );            
            }
        }
        else
        {
            $t->set_var( "is_selected", "" );
        }
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }


    $t->parse( "moderator_item", "moderator_item_tpl", true );
}


$t->set_var( "action_value", $action_value );
$t->set_var( "error_msg", $error_msg );

$t->set_var( "category_id", $CategoryID );

$t->set_var( "headline", $headline );

$t->pparse( "output", "forum_page");
?>
