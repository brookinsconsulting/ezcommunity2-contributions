<?
// $Id: forumedit.php,v 1.25 2001/05/04 12:47:06 ce Exp $
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

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );
$error = new INIFIle( "ezforum/admin/intl/" . $Language . "/forumedit.php.ini", false );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "ezuser/classes/ezusergroup.php" );

include_once( "ezforum/classes/ezforumcategory.php" );
include_once( "ezforum/classes/ezforum.php" );

require( "ezuser/admin/admincheck.php" );

if ( isset ( $DeleteForums ) )
{
    $Action = "DeleteForums";
}

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
            {
                $forum->setIsModerated( true );
            }
            else
            {
                $forum->setIsModerated( false );            
            }
            
            if ( $IsAnonymous == "on" )
            {
                $forum->setIsAnonymous( true );
            }
            else
            {
                $forum->setIsAnonymous( false );
            }
            

            $group = new eZUserGroup( $GroupID );
            $forum->setGroup( $group );
            
            $forum->store();

            $category = new eZForumCategory( $CategorySelectID );
            $category->addForum( $forum );
            
            eZLog::writeNotice( "Forum created: $Name from IP: $REMOTE_ADDR" );                    

            eZHTTPTool::header( "Location: /forum/forumlist/$CategorySelectID" );
            exit();
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
        exit();
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
            {
                $forum->setIsModerated( true );
            }
            else
            {
                $forum->setIsModerated( false );
            }
            
            if ( $IsAnonymous == "on" )
            {
                $forum->setIsAnonymous( true );
            }
            else
            {
                $forum->setIsAnonymous( false );
            }
            
            $forum->setName( $Name );
            $forum->setDescription( $Description );

            $group = new eZUserGroup( $GroupID );
            $forum->setGroup( $group );

            $forum->store();

            // remove all category assigmnents.
            $forum->removeFromForums();
                 
            $category = new eZForumCategory( $CategorySelectID );
            $category->addForum( $forum );
            
            eZLog::writeNotice( "Forum updated: $Name from IP: $REMOTE_ADDR" );
                        
            eZHTTPTool::header( "Location: /forum/forumlist/$CategorySelectID" );
            exit();
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
        exit();
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
            exit();
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
        exit();
    }
}

if ( $Action == "DeleteForums" )
{
    if ( count ( $ForumArrayID ) != 0 )
    {
        foreach( $ForumArrayID as $ForumID )
        {
            $forum = new eZForum( $ForumID );
            $forumName = $forum->name();
            $categories = $forum->categories();

            $categoryID = $categories[0]->id();
            
            $forum->delete();

            eZLog::writeNotice( "Forum deleted: $forumName from IP: $REMOTE_ADDR" );
            
        }
        eZHTTPTool::header( "Location: /forum/forumlist/$categoryID/" );
        exit();
    }
}

$t = new eZTemplate( "ezforum/admin/" . $ini->read_var( "eZForumMain", "AdminTemplateDir" ),
"ezforum//admin/" . "/intl", $Language, "forumedit.php" );
$t->setAllStrings();

$t->set_file( array( "forum_page" => "forumedit.tpl"
                   ) );

$t->set_block( "forum_page", "category_item_tpl", "category_item" );
$t->set_block( "forum_page", "moderator_item_tpl", "moderator_item" );
$t->set_block( "forum_page", "group_item_tpl", "group_item" );

$languageIni = new INIFile( "ezforum/admin/" . "intl/" . $Language . "/forumedit.php.ini", false );
$headline =  $languageIni->read_var( "strings", "head_line_insert" );

$t->set_var( "forum_name", "" );
$t->set_var( "forum_description", "" );
$action_value = "update";
$t->set_var( "forum_id", $ForumID );

if ( $Action == "new" )
{
    if ( eZPermission::checkPermission( $user, "eZForum", "ForumModifyAdd" ) == false )
    {
        eZHTTPTool::header( "Location: /forum/norights" );
        exit();
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
        exit();
    }
    else
    {
        $forum = new eZForum();
        $forum->get( $ForumID );

        $t->set_var( "forum_name", $forum->name() );
        $t->set_var( "forum_description", $forum->description() );
        $t->set_var( "forum_id", $ForumID);

        if ( $forum->isModerated() == true )
        {
            $t->set_var( "forum_is_moderated", "checked" );
        }
        else
        {
            $t->set_var( "forum_is_moderated", "" );
        }

        if ( $forum->isAnonymous() == true )
        {
            $t->set_var( "forum_is_anonymous", "checked" );
        }
        else
        {
            $t->set_var( "forum_is_anonymous", "" );
        }
            

        $groupUser =& $forum->group();
            
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

$t->set_var( "user_id", 0 );
$t->set_var( "user_name", "testing" );
$noModeratorString = $t->Ini->read_var( "strings", "no_moderator" );
$t->set_var( "user_name", $noModeratorString );

$t->set_var( "is_selected", "" );

if( $forum )
{
    $moderator = $forum->moderator();
}
else
{
    $moderator = 0;
}

if( $moderator == 0 )
{
    $t->set_var( "is_selected", "selected" );
}

$t->parse( "moderator_item", "moderator_item_tpl", true );

foreach( $userList as $userItem )
{
    $t->set_var( "user_id", $userItem->id() );
    $t->set_var( "user_name", $userItem->firstName() . " " . $userItem->lastName() );

    if( $Action == "edit" )
    {
        if( $moderator )
        {
            if ( $moderator->id() == $userItem->id() )
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

$group = new eZUserGroup();
$groupList =& $group->getAll();

foreach( $groupList as $group )
{
    $t->set_var( "group_name", $group->name() );
    $t->set_var( "group_id", $group->id() );

    $t->set_var( "is_selected", "" );
    if ( get_class( $group ) == "ezusergroup" && get_class( $groupUser ) == "ezusergroup" )
    {
        if ( $groupUser->id() == $group->id() )
            $t->set_var( "is_selected", "selected" );
    }
    
    $t->parse( "group_item", "group_item_tpl", true );
}


$t->set_var( "action_value", $action_value );
$t->set_var( "error_msg", $error_msg );

$t->set_var( "category_id", $CategoryID );

$t->set_var( "headline", $headline );

$t->pparse( "output", "forum_page");
?>
