<?
// 
// $Id: ezcountry.php,v 1.3 2001/04/05 09:12:15 fh Exp $
//
// Definition of eZCountry class
//
// Bård Farstad <bf@ez.no>
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
    function eZCountry( $id="", $fetch=true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a country to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        $name = addslashes( $this->Name );
        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZAddress_Country
                    SET ISO='$this->ISO',
                    Name='$name'" );            

            $this->ID = mysql_insert_id();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_Country
                    SET ISO='$this->ISO',
                    Name='$name'
                    WHERE ID='$this->ID'" );            

            $ret = true;            
        }        

        
        return $ret;
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
            if ( count( $country_array ) > 1 )
            {
                die( "Feil: Flere countryer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $country_array ) == 1 )
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
        $this->ID =& $country_array[ "ID" ];
        $this->ISO =& $country_array[ "ISO" ];
        $this->Name =& $country_array[ "Name" ];
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

        $db->query_single( $countries, "SELECT count( ID ) as Count FROM eZAddress_Country
                                        WHERE Removed=0 $search_arg
                                        ORDER BY Name" );
        return $countries["Count"];
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
            $limit = "LIMIT $offset, $max";
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
                                           ORDER BY Name $limit" );

        if ( $as_object )
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = new eZCountry( $country );
            }
        }
        else
        {
            foreach ( $country_array as $country )
            {
                $return_array[] = $country["ID"];
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
    
        $db->array_query( $country_array, "SELECT * FROM eZAddress_Country
                                           WHERE Removed=0
                                           ORDER BY Name" );
    
        return $country_array;
    }
    
    /*!
      Sletter adressen med ID == $id;
     */
    function delete( $id = false)
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();    
        $db->query( "DELETE FROM eZAddress_Country WHERE ID='$id'" );
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
