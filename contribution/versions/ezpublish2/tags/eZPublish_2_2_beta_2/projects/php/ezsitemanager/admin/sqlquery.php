<?php
// 
// $Id: sqlquery.php,v 1.3 2001/09/26 16:53:19 bf Exp $
//
// Created on: <26-Sep-2001 19:23:56 bf>
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

include_once( "classes/ezdb.php" );

$Language = $ini->read_var( "eZSiteManagerMain", "Language" );

$t = new eZTemplate( "ezsitemanager/admin/" . $ini->read_var( "eZSiteManagerMain", "AdminTemplateDir" ),
                     "ezsitemanager/admin/intl", $Language, "sqlquery.php" );

$t->set_file( "sql_query_tpl", "sqlquery.tpl" );

$t->setAllStrings();

$db =& eZDB::globalDatabase();

$QueryRes   = '';
$QueryError = '';

if ( ! $QueryText )
{
    $QueryError = 'No query specified';        
}
else
{
    $db->array_query( $return_array, $QueryText );

    
    if ( $return_array )
    {
        $QueryRes .= "<table width=\"100%\" border=\"1\" >";

        $QueryRes .= "<tr>";

        // print the column names
        reset( $return_array[0] );
        while ( list( $key, $val ) = each ( $return_array[0] ) )
        {
            $res = each ( $return_array[0] );
            $QueryRes .= "<th>" . $res[0] . "</th>";
        }    
        
        $QueryRes .= "</tr>";
	
        for ( $i = 0; $i < count( $return_array ); $i++ )
        {
            
            $QueryRes .= "<tr>";
	    
            for ( $j = 0; $j <  count( $return_array[$i]) / 2 ; $j++ )
            {
                $QueryRes .= "<td>" . htmlspecialchars( $return_array[$i][$j] ) . "</td>";
            }
            $QueryRes .= "</tr>";
        }	
        $QueryRes .= "</table>";
	
    }
    else
    {
        $QueryError = "SQL error occured";
    }

    

}

$t->set_var( "query_text",   $QueryText  );
$t->set_var( "query_result", $QueryRes   );
$t->set_var( "error", 	     $QueryError );

$t->pparse( "output", "sql_query_tpl" );

?>
