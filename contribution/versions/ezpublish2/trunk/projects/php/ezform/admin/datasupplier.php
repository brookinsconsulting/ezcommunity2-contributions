<?php
//
// $Id: datasupplier.php,v 1.14 2002/01/18 14:05:57 jhe Exp $
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
include_once( "ezform/classes/ezformreport.php" );

$Operation = $url_array[2];
$Action = $url_array[3];

switch ( $Operation )
{
    case "export":
    {
        if ( is_numeric( $Action ) )
        {
            $FormID = $Action;
            include( "ezform/admin/exportform.php" );
        }
        else
        {
            include( "ezform/admin/exportformlist.php" );
        }
    }
    break;
    
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

    case "report":
    {
        $Action = $url_array[3];
        $ReportID = $url_array[4];
        if ( $Action == "edit" )
        {
            if ( isSet( $NewReport ) )
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /form/report/new" );
                exit();
            }
            else if ( $DeleteSelected )
            {
                foreach ( $reportDelete as $rep )
                {
                    eZFormReport::delete( $rep );
                }
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /form/report/" );
                exit();
            }
            else if ( $url_array[4] > 0 )
            {
                include( "ezform/admin/reportedit.php" );
            }
        }
        else if ( $Action == "new" )
        {
            include( "ezform/admin/reportedit.php" );
        }
        else if ( $Action == "store" )
        {
            include( "ezform/admin/reportedit.php" );
        }
        else if ( $Action == "setup" )
        {
            $TableID = $url_array[5];
            if ( $ReportID == "store" )
            {
                $ReportID = $url_array[5];
                $Action = "store";
                $TableID = $url_array[6];
            }
            include( "ezform/admin/reportsetup.php" );
        }
        else
        {
            include( "ezform/admin/formreportlist.php" );
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
