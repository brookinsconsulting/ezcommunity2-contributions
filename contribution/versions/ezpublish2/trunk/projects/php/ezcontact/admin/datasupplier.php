<?php
//
// $Id: datasupplier.php,v 1.48 2001/07/20 12:01:50 jakobn Exp $
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

include_once( "classes/ezuritool.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );

$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZContact", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = eZURITool::split( $REQUEST_URI );
$ListType = $url_array[2];
switch ( $ListType )
{
    case "nopermission":
    {
        $Type = $url_array[3];
        switch( $Type )
        {
            case "company":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "category":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "person":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "login":
            case "consultation":
            {
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "type":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "setup":
    {
        include( "ezcontact/admin/setup.php" );
        break;
    }

    case "company":
    {
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            {
                if ( isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $NewCompanyCategory = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "view":
            {
                if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $CompanyID = $url_array[4];
                $PersonOffset = $url_array[5];
                include( "ezcontact/admin/companyview.php" );
                break;
            }
            case "stats":
            {
                $Year = $url_array[6];
                $Month = $url_array[7];
                $Day = $url_array[8];
                $DateType = $url_array[4];
                if ( !isset( $CompanyID ) and isset( $url_array[5] ) and is_numeric( $url_array[5] ) )
                    $CompanyID = $url_array[5];
                include( "ezcontact/admin/companystats.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                $Offset = $url_array[5];
                $ShowStats = true;
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "companycategory" :
    {
        $TypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            {
                $NewParentID = $url_array[4];
                unset( $TypeID );
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }
    case "person":
    {
        $PersonID = $url_array[4];
        $Action = $url_array[3];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/personview.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "consultation":
    {
        if ( !isset( $ConsultationID ) or !is_numeric( $ConsultationID ) )
            $ConsultationID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/consultationedit.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/consultationview.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/consultationlist.php" );
                break;
            }
            case "company":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                if ( !isset( $CompanyID ) or !is_numeric( $CompanyID ) )
                    $CompanyID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
                break;
            }
            case "person":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                if ( !isset( $PersonID ) )
                    $PersonID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
                    case "insert":
                    {
                        include( "ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "consultationtype":
    {
        $ConsultationTypeID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/consultationtypeedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/consultationtypelist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezcontact/admin/consultationtypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "projecttype":
    {
        $ProjectTypeID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/projecttypeedit.php" );
                break;
            }
            case "list":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/projecttypelist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
                include( "ezcontact/admin/projecttypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "error":
    {
        include( "ezcontact/admin/error.php" );
        break;
    }

    default :
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
        break;
}

?>
