<?php
// 
// $Id: linkmove.php,v 1.2 2001/05/04 10:09:54 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <03-May-2001 17:57:46 amos>
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

// ** Please see the linklist.php for variables to set before including this file. **

include_once( "classes/ezhttptool.php" );

switch( $ObjectType )
{
    case "section":
    {
        include_once( "classes/ezlinksection.php" );
        if ( $MoveUp )
            eZLinkSection::moveUp( $ItemID, $ObjectID, $ClientModuleName, $ClientModuleType );
        else
            eZLinkSection::moveDown( $ItemID, $ObjectID, $ClientModuleName, $ClientModuleType );

        $Funcs["delete"]( $ItemID );

        eZHTTPTool::header( "Location: " . sprintf( $URLS["linklist"], $ItemID ) );
        exit();
        break;
    }
    case "link":
    {
        include_once( "classes/ezlinkitem.php" );
        if ( $MoveUp )
            eZLinkItem::moveUp( $ObjectID, $LinkID, $ClientModuleName );
        else
            eZLinkItem::moveDown( $ObjectID, $LinkID, $ClientModuleName );

        $Funcs["delete"]( $ItemID );

        eZHTTPTool::header( "Location: " . sprintf( $URLS["linklist"], $ItemID ) );
        exit();
        break;
    }
    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
        break;
    }
}

?>
