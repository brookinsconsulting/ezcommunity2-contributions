<?
// 
// $Id: ezquery.php,v 1.7 2000/10/02 11:58:14 bf-cvs Exp $
//
// Definition of eZQuery class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZLink
//! The eZQuery class builds SQL queries.
/*!
  En klasse som håndterer SQL queries. Lager query setninger fra
  tekststrenger. 
  
*/

class eZQuery
{
    /*!
      
    */
    function eZQuery( $fields, $queryText )
    {
        $this->Fields = $fields;
        $this->QueryText = $queryText;        
    }

    /*!
      
    */
    function buildQuery( )
    {
        $field = "KeyWords";

        $QueryText = $this->QueryText;
        
        $QueryText = trim( $QueryText );
        $QueryText = ereg_replace( "[ ]+", " ", $QueryText );
        $queryArray = explode( " ", $QueryText );

        $query = "";
        for ( $i=0; $i<count($queryArray); $i++ )            
        {
            for ( $j=0; $j<count($this->Fields); $j++ )
            {
                $queryItem = $queryArray[$i];
                if ( $queryItem[0] == "-" )
                {
                    $queryItem = ereg_replace( "^-", "", $queryItem );
                    $not = "NOT";
                }
                else
                    $not = "";
                
                $queryItem = $this->Fields[$j] ." " . $not . " LIKE '%" . $queryItem . "%' ";

                if ( $j > 0 )                    
                    $queryItem = "OR " . $queryItem . " ";
                    
            
                $query .= $queryItem;
            }

            if (  count( $queryArray) != ($i+1) )
                $query = " (" . $query . ") AND ";
            else
                $query = " (" . $query . ") ";
            
        }
        return $query;
    }

    var $Fields;
    var $QueryText;    
}
?>
