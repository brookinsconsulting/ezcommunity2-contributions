
<?
// $Id: unacceptededit.php,v 1.1 2001/02/12 12:13:43 ce Exp $
//
// Author: Bård Farstad <bf@ez.no>
// Created on: <21-Jan-2001 13:34:48 bf>
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

include_once( "classes/ezhttptool.php" );
include_once( "ezlink/classes/ezlink.php" );

require( "ezuser/admin/admincheck.php" );

for( $i=0; $i < count ( $LinkArrayID ); $i++ )
{
    unset( $link );
    $link = new eZLink( $LinkArrayID[$i] );
    $link->setTitle( $Name[$i] );
    $link->setDescription( $Description[$i] );
    $link->setLinkGroupID( $LinkGroupID[$i] );
    $link->setKeyWords( $Keywords[$i] );
    $link->setAccepted( "N" );
    $link->setUrl( $Url[$i] );

    if ( $ActionValueArray[$i] == "Defer" )
    {
    }
    if ( $ActionValueArray[$i] == "Accept" )
    {
        $link->setAccepted( "Y" );
        $link->update();
    }
    if ( $ActionValueArray[$i] == "Delete" )
    {
        $link->delete();
    }
    if ( $ActionValueArray[$i] == "Update" )
    {
        $link->update();
    }
}

eZHTTPTool::header( "Location: /link/unacceptedlist/" );
exit();



?>
