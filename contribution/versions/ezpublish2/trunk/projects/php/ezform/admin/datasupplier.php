<?php
//
// $Id: datasupplier.php,v 1.12 2001/12/19 16:23:55 pkej Exp $
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

include_once( "classes/ezhttptool.php" );

$Operation = $url_array[2];
$Action = $url_array[3];

switch ( $Operation )
{
    case "form":
    {
        $FormID = $url_array[4];
        
        switch ( $Action )
        {
            case "edit":
            case "insert":
            case "update":
            case "delete":
            case "up":
            case "down":
            case "new":
            {
                include( "ezform/admin/formedit.php" );
            }
            break;
            
            case "list":
            {
                $Offset = $url_array[5];
                include( "ezform/admin/formlist.php" );
            }
            break;
            
            case "view":
            case "process":
            {
                include( "ezform/admin/formview.php" );
            }
            break;
            
            case "preview":
            {
                include( "ezform/admin/formpreview.php" );
            }
            break;

            case "fixedvalues":
            {
                $FormID = $url_array[4];
                $PageID = $url_array[5];
                $ElementID = $url_array[6];
                include( "ezform/admin/fixedvalues.php" );
            }
            break;

            case "numericaledit":
            {
                $FormID = $url_array[4];
                $PageID = $url_array[5];
                $ElementID = $url_array[6];
                $TableID = $url_array[7];
                include( "ezform/admin/numericaledit.php" );
            }
            break;

            case "textedit":
            {
                $FormID = $url_array[4];
                $PageID = $url_array[5];
                $ElementID = $url_array[6];
                $TableID = $url_array[7];
                include( "ezform/admin/textedit.php" );
            }
            break;

            case "tableedit":
            {
                if ( $url_array[7] == "up" )
                {
                    $Action = "up";
                }
                else if ( $url_array[7] == "down" )
                {
                    $Action = "down";
                }

                $FormID = $url_array[4];
                $PageID = $url_array[5];
                $TableID = $url_array[6];
                include( "ezform/admin/tableedit.php" );
            }
            break;

            case "pageedit":
            {
                if ( $url_array[7] == "up" )
                {
                    $Action = "up";
                }
                else if ( $url_array[7] == "down" )
                {
                    $Action = "down";
                }
                
                $FormID = $url_array[4];
                $PageID = $url_array[5];
                include( "ezform/admin/pageedit.php" );
            }
            break;
            
            default:
            {
                eZHTTPTool::header( "Location: /error/404" );
            }
            break;
        }
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
    }
    break;
}

?>
