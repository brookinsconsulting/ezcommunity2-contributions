<?php
// 
// $Id: yearview.php,v 1.1 2001/01/12 17:32:09 gl Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Dec-2000 11:29:22 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "classes/ezdatetime.php" );

$ini = new INIFIle( "site.ini" );

$Language = $ini->read_var( "eZCalendarMain", "Language" );

$t = new eZTemplate( "ezcalendar/user/" . $ini->read_var( "eZCalendarMain", "TemplateDir" ),
                     "ezcalendar/user/intl/", $Language, "monthlist.php" );

$t->set_file( "month_list_page_tpl", "monthlist.tpl" );

$t->setAllStrings();

$t->set_block( "month_list_page_tpl", "month_tpl", "month" );
$t->set_block( "month_tpl", "week_tpl", "week" );
$t->set_block( "week_tpl", "day_tpl", "day" );

print( "Showing: $Year - $Month <br>" );

$datetime = new eZDateTime( );

$datetime->setYear( $Year );

$t->set_var( "year_number", $Year );

for ( $month=1; $month<13; $month++ )
{
    $datetime->setMonth( $month );
    $t->set_var( "month_number", $month );

    $t->set_var( "week", "" );
    for ( $week=0; $week<6; $week++ )
    {
        $t->set_var( "day", "" );
        
        for ( $day=1; $day<=7; $day++ )
        {
                
            $datetime->setDay( 1 );
            $firstDay = $datetime->dayOfWeek();

            $currentDay = $day + ( $week * 7 ) - $firstDay + 1;

            if ( ( ( $day + ( $week * 7 ) )  >= $firstDay ) &&
                 ( $currentDay <= $datetime->daysInMonth() ) )
            {
                $datetime->setDay( $currentDay );

                $t->set_var( "td_class", "bglight" );
                $t->set_var( "day_number", $currentDay );
            }
            else
            {
                $t->set_var( "td_class", "bglight" );                
                $t->set_var( "day_number", "*" );
            }
            $t->parse( "day", "day_tpl", true );            
        }
        $t->parse( "week", "week_tpl", true );
    }
    $t->parse( "month", "month_tpl", true );
}

$t->pparse( "output", "month_list_page_tpl" );


?>
