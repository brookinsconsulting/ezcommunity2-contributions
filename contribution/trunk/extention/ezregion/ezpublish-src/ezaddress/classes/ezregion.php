<?
// 
// $Id: ezregion.php,v 1.1.5.1.6.1.4.1 2002/02/05 00:06:45 graham Exp $
//
// Definition of eZRegion class
//
// Graham Brookins <graham@brookinsconsulting.com>
// Created on: <15-Aug-2001 08:19:30 graham>
//
// This source file is an addition of eZ publish, publishing software.
// Copyright (C) 2001 katalyst as
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
//
// example php doc switches found : \private , \static, \sa (class), \code & \endcode, ?
//
//!! eZAddress
//! eZRegion handles countries.

/*!
  Example code: 
  \code
  $Region = new eZRegion();
  $Name =& $Region->name();
  $ID =& $Region->id();

  echo("<br><br>");
  echo("Region ID: " . $ID . "<br>");
  echo("Region Name: " . $Name . "<br><br>"); 
  \endcode
*/

/*!TODO
x)  Review & Update Php-Doc Comments

x)  Review & Update Php Comments

x)  Review & Test & Update Example Code

x)  Add line feeds in php-doc comments between lines
*/
AbbreviationRegionAbrev;

include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );

class eZRegion
{
    /*!
        Constructs a new eZRegion object.
    */
    function eZRegion( $id="", $fetch=true )
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
        Stores a region to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        $Name = addslashes( $this->Name );

        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZAddress_Region
                    SET CountryID='$this->CountryID',
			Abbreviation='$this->Abbreviation',
			Name='$this->Name',
			UserAdded='$this->UserAdded',
			Removed='$this->Removed'" );            

			$this->ID = $db->insertID();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_Region
                    SET CountryID='$this->CountryID',
			Abbreviation='$this->Abbreviation',
			Name='$this->Name',
			UserAdded='$this->UserAdded',
			Removed='$this->Removed'
                    WHERE ID='$this->ID'" );            

            $ret = true;            
        }                
        return $ret;
    }

  
    /*!
      Fetches an region with object id==$id;
    */  
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $region_array, "SELECT * FROM eZAddress_Region WHERE ID='$id'" );
            if ( count( $region_array ) > 1 )
            {
                die( "Feil: Flere regioner med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if ( count( $region_array ) == 1 )
            {
                $this->fill( $region_array[0] );
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$region_array )
    {
        $this->ID =& $region_array[ "ID" ];
	$this->CountryID =& $region_array[ "CountryID" ];
        $this->Abbreviation =& $region_array[ "Abbreviation" ];
        $this->Name =& $region_array[ "Name" ];
        $this->UserAdded =& $region_array[ "UserAdded" ];
        $this->Removed =& $region_array[ "Removed" ];
        $this->Name =& $region_array[ "Name" ];
    }

    /*!
      Returns the total number of countries
    */
    function &getAllCount( $search = "" )
    {
        $db =& eZDB::globalDatabase();

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name", "Abbreviation" ), $search );
            $search_arg = "AND " . $query->buildQuery();
//              $search_arg = "WHERE Name like '%$search%'";
        }

        $db->query_single( $region_countries, "SELECT count( ID ) as Count FROM eZAddress_Region
                                        WHERE Removed=0 $search_arg
                                        ORDER BY Name" );
        return $region_countries["Count"];
    }

    /*!
      Returns every region as a eZRegion object
    */
    function &getAll( $as_object = true, $search = "", $offset = 0, $max = -1 )
    {
        $db =& eZDB::globalDatabase();

        $region_array = 0;
        $return_array = array();
    
        if ( $max >= 0 && is_numeric( $offset ) && is_numeric( $max ) )
        {
            $limit = "LIMIT $offset, $max";
        }

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name", "Abbreviation" ), $search );
            $search_arg = "AND " . $query->buildQuery();
//              $search_arg = "WHERE Name like '%$search%'";
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $region_array, "SELECT $select FROM eZAddress_Region
                                           WHERE Removed=0 $search_arg
                                           ORDER BY Name $limit" );

        if ( $as_object )
        {
            foreach ( $region_array as $region )
            {
                $return_array[] = new eZRegion( $region );
            }
        }
        else
        {
            foreach ( $region_array as $region )
            {
                $return_array[] = $region["ID"];
            }
        }
    
        return $return_array;
    }

    /*!
      Returns every region as an array. This function is faster then the one above.
    */
    function &getAllArray( )
    {
        $db =& eZDB::globalDatabase();
        $region_array = 0;
    
        $db->array_query( $region_array, "SELECT * FROM eZAddress_Region
                                           WHERE Removed=0
                                           ORDER BY Name" );
    
        return $region_array;
    }
    
    /*!
      Delete Region where ID == $id;
     */
    function delete( $id = false)
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();    
        $db->query( "DELETE FROM eZAddress_Region WHERE ID='$id'" );
    }        

    /*!
      Returns the object ID.
    */
    function id( )
    {
        return $this->ID;
    }
    
    /*!
      Returns the name of a region.
    */

    function name( )
    {
        return $this->Name;
    }

    /*!
      Sets the name of the region.
    */
    function setName( $value )
    {
       $this->Name = $value;
    }
    
    /*!      
      Returns the Id of the country.
    */
    function countryId( )
    {
        return $this->CountryID;
    }

    /*!      
      Sets the Id of the country.
    */
    function setCountryId( $value )
    {
       $this->CountryID = $value;
    }

    /*!
      Returns the abrev of a region.
    */
    function Abbreviation( )
    {
        return $this->Abbreviation;
    }

    /*!
      Sets the Abreviation of the region.
    */
    function setAbbreviation( $value )
    {
       $this->Abbreviation = $value;
    }

    /*!      
      Returns a boolian value (yes or no, 1 or 0).
    */
    function userAdded( )
    {
        return $this->UserAdded;
    }

    /*!
      Sets the boolian for UserAdded for the region.
    */
    function setUserAdded( $value )
    {
       $this->UserAdded = $value;
    }

    /*!      
      Returns a boolian value (yes or no, 1 or 0) if the region is removed from use.
    */
    function removed( )
    {
        return $this->Removed;
    }

    /*!
      Sets the boolian for Removed for the region if it is removed from use.
    */
    function setRemoved( $value )
    {
       $this->Removed = $value;
    }

    var $ID;
    var $CountryID;
    var $Name;
    var $Abbreviation;
    var $UserAdded;
    var $Removed;
}

?>
