<?php
// 
// $Id: ezcountry.php,v 1.8 2001/07/13 14:48:18 jhe Exp $
//
// Definition of eZCountry class
//
// Created on: <31-Oct-2000 11:49:30 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! eZAddress
//! eZCountry handles countries.
/*!
  
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );

class eZCountry
{
    /*!
      Constructs a new eZCountry object.
    */
    function eZCountry( $id = "" )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a country to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $name = $db->escapeString( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZAddress_Country" );
            $this->ID = $db->nextID( "eZAddress_Country", "ID" );

            $res[] = $db->query( "INSERT INTO eZAddress_Country
                    ( ID, ISO, Name )
                    VALUES ( '$this->ID', '$this->ISO', '$name' )" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAddress_Country
                    SET ISO='$this->ISO',
                    Name='$name'
                    WHERE ID='$this->ID'" );            

        }        
        eZDB::finish( $res, $db );
        return $dbError;
    }
  
    /*!
      Fetches an country with object id==$id;
    */  
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $country_array, "SELECT * FROM eZAddress_Country WHERE ID='$id'" );
            if ( count( $country_array ) == 1 )
            {
                $this->fill( $country_array[0] );
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$country_array )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID =& $country_array[ $db->fieldName( "ID" ) ];
        $this->ISO =& $country_array[ $db->fieldName( "ISO" ) ];
        $this->Name =& $country_array[ $db->fieldName( "Name" ) ];
    }

    /*!
      Returns the total number of countries
    */
    function &getAllCount( $search = "" )
    {
        $db =& eZDB::globalDatabase();

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name", "ISO" ), $search );
            $search_arg = "AND " . $query->buildQuery();
//              $search_arg = "WHERE Name like '%$search%'";
        }

        $db->query_single( $countries, "SELECT COUNT( ID ) as Count FROM eZAddress_Country
                                        WHERE Removed=0 $search_arg
                                        " );
        return $countries[ $db->fieldName("Count") ];
    }

    /*!
      Returns every country as a eZCountry object
    */
    function &getAll( $as_object = true, $search = "", $offset = 0, $max = -1 )
    {
        $db =& eZDB::globalDatabase();

        $country_array = 0;
        $return_array = array();
    
        if ( $max >= 0 && is_numeric( $offset ) && is_numeric( $max ) )
        {
            $limit = array( "Limit" => $max,
                            "Offset" => $offset );
        }

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name", "ISO" ), $search );
            $search_arg = "AND " . $query->buildQuery();
//              $search_arg = "WHERE Name like '%$search%'";
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $country_array, "SELECT $select FROM eZAddress_Country
                                           WHERE Removed=0 $search_arg
                                           ORDER BY Name", $limit );
        
        if ( $as_object )
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = new eZCountry( $country[ $db->fieldName( "ID" ) ] );
            }
        }
        else
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = $country[ $db->fieldName( "ID" ) ];
            }
        }
        return $return_array;
    }

    /*!
      Returns every country as an array. This function is faster then the one above.
    */
    function &getAllArray( )
    {
        $db =& eZDB::globalDatabase();
        $country_array = 0;
        $return_array = array();
        
        $db->array_query( $country_array, "SELECT * FROM eZAddress_Country
                                           WHERE Removed=0
                                           ORDER BY Name" );
        foreach ( $country_array as $country )
        {
            $return_array[] = array( "ID" =>      $country[ $db->fieldName( "ID" ) ],
                                     "ISO" =>     $country[ $db->fieldName( "ISO" ) ],
                                     "Name" =>    $country[ $db->fieldName( "Name" ) ],
                                     "Removed" => $country[ $db->fieldName( "Removed" ) ] );
        }
        return $return_array;
    }
    
    /*!
      Sletter adressen med ID == $id;
     */
    function delete( $id = false)
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();

        $db->begin();
        $res[] = $db->query( "DELETE FROM eZAddress_Country WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }    
    

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }
    
    /*!
      Returns the ISO code of the country.
    */
    function iso( )
    {
        return $this->ISO;
    }

    /*!
      Returns the name of the country.
    */
    function name( )
    {
        return $this->Name;
    }


    /*!
      Sets the ISO code of the country.
    */
    function setISO( $value )
    {
       $this->ISO = $value;
    }

    /*!
      Sets the name of the country.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }
  
    var $ID;
    var $ISO;
    var $Name;
}

?>
