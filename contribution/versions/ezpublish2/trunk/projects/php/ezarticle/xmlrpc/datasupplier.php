<?php
//
// $Id: datasupplier.php,v 1.17 2001/11/02 08:56:02 jb Exp $
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

define( "EZARTICLE_NONEXISTING_ARTICLE", 1 );
define( "EZARTICLE_NONEXISTING_CATEGORY", 2 );
define( "EZARTICLE_NONEXISTING_PARENT", 3 );
define( "EZARTICLE_WRONG_CATEGORY_COUNT", 4 );
define( "EZARTICLE_WRONG_ARTICLE_COUNT", 5 );

switch ( $RequestType )
{
    case "tag" :
    {
        switch( $Command )
        {
            case "list":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/tag.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
        }
    } break;
    case "type" :
    {
        switch( $Command )
        {
            case "list":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/typelist.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/type.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
        }
    } break;
    case "topic" :
    {
        switch( $Command )
        {
            case "list":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/topiclist.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            case "data":
            case "storedata":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/topic.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
        }
    } break;

    case "category" :
    {
        switch( $Command )
        {
            case "search":
            case "list":
            case "tree":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                     include( "ezarticle/xmlrpc/categorylist.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            case "info":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/category.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            default:
                $Error = true;
        }
    } break;
    case "article" :
    {
        switch( $Command )
        {
            case "search":
            case "data":
            case "storedata":
            case "delete":
            case "info":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/article.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            default:
                $Error = true;
        }
    } break;

    default :
    {
        switch( $Command )
        {
            case "search":
            {
                if ( eZPermission::checkPermission( $User, "eZArticle", "ModuleEdit" ) )
                    include( "ezarticle/xmlrpc/search.php" );
                else
                    $Error = createErrorMessage( EZERROR_NO_PERMISSION );
                break;
            }
            default:
                $Error = true;
        }
    } break;
}

?>
