<?php
//
// $Id: datasupplier.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
//
// Created on: <17-Oct-2001 14:23:46 pkej>
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


$Action = addslashes( $url_array[3] );
$ObjectID = addslashes( $url_array[4] );

if ( !isset( $RedirectURL ) )
{
    $RedirectURL = $HTTP_REFERRER;
}

switch ( $url_array[2] )
{
    case "license":
    {
        switch ( $Action )
        {
            case "list":
            {
                include( "ezlicense/admin/licenselist.php" );
            }
            break;
            
            case "edit":
            case "new":
            {
                include( "ezlicense/admin/licenseedit.php" );
            }
            break;

            case "view":
            {
                include( "ezlicense/admin/licenseview.php" );
            }
            break;
        }
    }
    break;
    
    case "licensetype":
    {
        switch ( $Action )
        {
            case "list":
            {
                include( "ezlicense/admin/licensetypelist.php" );
            }
            break;
        }
    }
    break;
    
    case "program":
    {
        switch( $Action )
        {
            case "list":
            {
                include( "ezlicense/admin/programlist.php" );
            }
            break;
            
            case "edit":
            {
                include( "ezlicense/admin/programedit.php" );
            }
            break;
        }
    }
    break;
    
    case "version":
    {
        switch( $Action )
        {
            case "edit":
            {
                include( "ezlicense/admin/versionedit.php" );
            }
            break;
        }
    }
    break;
    
    case "reseller":
    {
        switch( $Action )
        {
            case "list":
            {
                include( "ezlicense/admin/resellerlist.php" );
            }
            break;
            
            case "edit" :
            {
                include( "ezlicense/admin/reselleredit.php" );
            }
            break;
        }
    }
    break;
}

?>
