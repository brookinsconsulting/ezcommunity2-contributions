<?php
//
// $Id: userlogin.php,v 1.14.8.2 2002/03/07 13:59:03 ce Exp $
//
// Created on: <14-Oct-2000 15:41:17 bf>
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
include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "ezuser/classes/ezuser.php" );

if ( eZUser::currentUser() )
{
    if ( isset( $RedirectURL ) )
    {
        $AdditionalURLInfo="?RedirectURL=$RedirectURL&ProductID=$ProductID&ProductName=" . urlencode( $ProductName ) . "";
    }

    if ( $Action == "newsimple" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
    }

    if ( $Action == "replysimple" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/reply/$ReplyToID/$ForumID/$AdditionalURLInfo" );
    }

    if ( $Action == "new" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
    }

    if ( $Action == "edit" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/edit/$MessageID/$AdditionalURLInfo" );
    }

    if ( $Action == "delete" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/delete/$MessageID/$AdditionalURLInfo" );
    }

    if ( $Action == "reply" )
    {
        eZHTTPTool::header( "Location: /forum/messageedit/reply/$ReplyToID/$AdditionalURLInfo" );
    }
}
else
{
    $Anonymous == false;

    switch ( $Action )
    {
        case "new":
        {
            include_once( "ezforum/classes/ezforum.php" );
            include_once( "ezforum/classes/ezforummessage.php" );

            $CheckForumID = $ForumID;

            include( "ezforum/user/messagepermissions.php" );

            if ( $ForumPost == true )
            {
                eZHTTPTool::header( "Location: /forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
            }
        }

        case "reply":
        {
            include_once( "ezforum/classes/ezforum.php" );
            include_once( "ezforum/classes/ezforummessage.php" );

            $msg = new eZForumMessage( $ReplyToID );

            $CheckForumID = $msg->forumID();

            include( "ezforum/user/messagepermissions.php" );

            if ( $ForumPost == true )
            {
                eZHTTPTool::header( "Location: /forum/messageedit/reply/$ReplyToID/$AdditionalURLInfo" );
            }
        }
    }


    if ( $Anonymous == false )
    {
        if ( isset( $RedirectURL ) )
        {
            $AdditionalURLInfo="&ProductID=$ProductID&ProductName=" . urlencode( $ProductName ) . "";
        }

        if ( $Action == "newsimple" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
            exit();
        }

        if ( $Action == "replysimple" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
            exit();
        }

        if ( $Action == "new" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/new/$ForumID/$AdditionalURLInfo" );
            exit();
        }

        if ( $Action == "edit" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/edit/$MessageID/$AdditionalURLInfo" );
            exit();
        }

        if ( $Action == "delete" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/delete/$MessageID/$AdditionalURLInfo" );
            exit();

        }

        if ( $Action == "reply" )
        {
            eZHTTPTool::header( "Location: /user/login/?RedirectURL=/forum/messageedit/reply/$MessageID/$AdditionalURLInfo" );
            exit();
        }
        eZHTTPTool::header( "Location: /user/login/" );
        exit();
    }
}

?>
