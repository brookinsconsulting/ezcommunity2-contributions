<?php
//
// $Id: datasupplier.php,v 1.18.10.1 2002/06/04 11:57:56 jhe Exp $
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

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZCalendarMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "yearview" :
    {
        $CalID = $url_array[3];
        $Year = $url_array[4];

        include( "ezcalendar/user/yearview.php" );
    }
    break;

    case "monthview" :
    {
        $CalID = $url_array[3];
        $Year = $url_array[4];
        $Month = $url_array[5];

        include( "ezcalendar/user/monthview.php" );
    }
    break;

    case "dayview" :
    {
        $CalID = $url_array[3];
        $Year = $url_array[4];
        $Month = $url_array[5];
        $Day = $url_array[6];

        include( "ezcalendar/user/dayview.php" );
    }
    break;
    
    case "appointmentedit" :
    {
        $CalID = $url_array[3];
        switch ( $url_array[4] )
        {
            case "new" :
            {
                $Action = "New";
                $Year = $url_array[5];
                $Month = $url_array[6];
                $Day = $url_array[7];
                $StartTime = $url_array[8];
            }
            break;

            case "edit" :
            {
                $Action = "Edit";
                $AppointmentID = $url_array[5];
            }
            break;

            case "update" :
            {
                $Action = "Update";
                $AppointmentID = $url_array[5];
            }
            break;

            case "insert" :
            {
                $Action = "Insert";
                $AppointmentID = $url_array[5];
            }
            break;

            default :
            {
                $Action = $url_array[5];
            }
        }
        if ( isSet( $ChangeView ) )
            $Action = "New";
        include( "ezcalendar/user/appointmentedit.php" );
    }
    break;

    case "appointmentview" :
    {
        $CalID = $url_array[3];
        $AppointmentID = $url_array[4];
        include( "ezcalendar/user/appointmentview.php" );
    }
    break;
}

?>
