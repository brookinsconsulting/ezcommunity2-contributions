<?php
//
// $Id: datasupplier.php,v 1.13 2001/07/19 12:19:22 jakobn Exp $
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

switch ( $RequestType )
{
    case "type" :
    {
        switch( $Command )
        {
            case "list":
            {
                include( "ezarticle/xmlrpc/typelist.php" );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezarticle/xmlrpc/type.php" );
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
                include( "ezarticle/xmlrpc/topiclist.php" );
                break;
            }
            case "data":
            case "storedata":
            {
                include( "ezarticle/xmlrpc/topic.php" );
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
                include( "ezarticle/xmlrpc/categorylist.php" );
                break;
            }
            case "data":
            case "storedata":
            case "delete":
            {
                include( "ezarticle/xmlrpc/category.php" );
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
            {
                include( "ezarticle/xmlrpc/article.php" );
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
                include( "ezarticle/xmlrpc/search.php" );
                break;
            }
            default:
                $Error = true;
        }
    } break;
}

?>
