<?php
//
// $Id: datasupplier.php,v 1.9 2001/07/19 12:36:31 jakobn Exp $
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

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZBulkmailMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "subscriptionlist":
    {
        include( "ezbulkmail/user/subscriptionlist.php" );
    }
    break;

    case "newsubscription" :
        $New = "new";
    case "login" :
    {
        include( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "confirmsubscription" :
    {
        $Hash = $url_array[3];
        include( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "singlelist" :
    {
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;

    case "singlelistsubscribe" :
    {
        $Subscribe = "yes";
        $Hash = $url_array[3];
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;
    
    case "singlelistunsubscribe" :
    {
        $UnSubscribe = "yes";
        $Hash = $url_array[3];
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;
    
    case "successfull" :
    {
        $mailConfirm = "";
        include( "ezbulkmail/user/usermessages.php" );
    }
    break;

    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>
