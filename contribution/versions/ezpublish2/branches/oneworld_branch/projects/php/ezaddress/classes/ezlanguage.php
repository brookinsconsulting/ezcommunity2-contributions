<?php
// 
// $Id: ezlanguage.php,v 1.1.2.1 2002/06/04 11:25:47 br Exp $
//
// Definition of eZCountry class
//
// Created on: <15-May-2002 12:12:35 br>
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

//!! eZAddress
//! eZCountryName handles speaken languages.
/*!
  
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );

class eZLanguage
{
    /*!
      Constructs a new eZCountry object.
    */
    function eZLanguage( $id = "" )
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
            $db->lock( "eZAddress_Language" );
            $this->ID = $db->nextID( "eZAddress_Language", "ID" );

            $res[] = $db->query( "INSERT INTO eZAddress_Language
                    ( ID, Name )
                    VALUES ( '$this->ID', '$name' )" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZAddress_Language
                    SET Name='$name'
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
            $db->array_query( $country_array, "SELECT * FROM eZAddress_Language WHERE ID='$id'" );
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
        $this->Name =& $country_array[ $db->fieldName( "Name" ) ];
    }

    /*!
      Returns the total number of country names
    */
    function &getAllCount( $search = "" )
    {
        $db =& eZDB::globalDatabase();

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name" ), $search );
            $search_arg = "WHERE " . $query->buildQuery();
        }

        $db->query_single( $countries, "SELECT COUNT( ID ) as Count FROM eZAddress_Language $search_arg" );
        return $countries[ $db->fieldName("Count") ];
    }

    /*!
      Returns the total number of country names to an id
    */
    function &getCountByID( $id = "" )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $languages, "SELECT ID FROM eZAddress_Language ORDER BY Name" );
        $count = 0;
        if ( count( $languages ) > 0 )
        {
            foreach( $languages as $language )
            {
                if ( $language[$db->fieldName("ID")] == $id )
                {
                    break;
                }
                
                $count++;
            }
        }
        
        return $count;

    }
        
    /*!
      Returns every country as a eZLanguage object
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
            $query = new eZQuery( array( "Name" ), $search );
            $search_arg = "AND " . $query->buildQuery();
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $country_array, "SELECT $select FROM eZAddress_Language
                                           ORDER BY Name", $limit );
        
        if ( $as_object )
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = new eZLanguage( $country[ $db->fieldName( "ID" ) ] );
            }
        }
        else
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = $country[$db->fieldName( "ID" )];
            }
        }
        return $return_array;
    }

    /*!
      Returns every country as an array. This function is faster then the one above.
    */
    function &getAllArray( $offset = 0, $limit = -1 )
    {
        $db =& eZDB::globalDatabase();
        $country_array = 0;
        $return_array = array();

        if ( $limit >= 0 && is_numeric( $offset ) && is_numeric( $limit ) )
        {
            $limitArray = array( "Limit" => $limit,
                            "Offset" => $offset );
        }

        
        $db->array_query( $country_array, "SELECT * FROM eZAddress_Language
                                           ORDER BY Name", $limitArray );
        foreach ( $country_array as $country )
        {
            $return_array[] = array( "ID" =>      $country[ $db->fieldName( "ID" ) ],
                                     "Name" =>    $country[ $db->fieldName( "Name" ) ] );
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
        $res[] = $db->query( "DELETE FROM eZAddress_Language WHERE ID='$id'" );
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
      Returns the name of the country.
    */
    function name( )
    {
        return $this->Name;
    }


    /*!
      Sets the name of the country.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }


  
    var $ID;
    var $Name;
}

?>
