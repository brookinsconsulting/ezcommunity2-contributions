<?
// 
// $Id: ezimagevariationgroup.php,v 1.8 2001/04/05 14:07:23 bf Exp $
//
// Definition of eZImageVariationGroup class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 17:28:47 bf>
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
//!! eZImageCatalogue
//! The eZImageVariationGroup class hadles images variation groups.
/*!

  \sa eZImage eZImageVariation
*/

include_once( "classes/ezdb.php" );

class eZImageVariationGroup
{
    /*!
      Constructs a new eZImageVariationGroup object.
    */
    function eZImageVariationGroup( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZImageVariationGroup object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZImageCatalogue_ImageVariationGroup SET
                                 Width='$this->Width',
                                 Height='$this->Height'
                                 " );
        
        $this->ID = mysql_insert_id();

        $this->State_ = "Coherent";
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $image_variation_array, "SELECT * FROM eZImageCatalogue_ImageVariationGroup WHERE ID='$id'" );
            if ( count( $image_variation_array ) > 1 )
            {
                die( "Error: ImageVariations's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $image_variation_array ) == 1 )
            {
                $this->ID = $image_variation_array[0][ "ID" ];
                $this->Width = $image_variation_array[0][ "Width" ];
                $this->Height = $image_variation_array[0][ "Height" ];

                $this->State_ = "Coherent";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns the ID the the group if there exists a image group with the requested size, false if not.
    */
    function groupExists( $width, $height )
    {
        $this->dbInit();        
        
        $ret = false;
        
        $query = ( "SELECT * FROM eZImageCatalogue_ImageVariationGroup WHERE Width='$width' AND Height='$height'" );

        $this->Database->array_query( $group_array, $query );
                                                     
        if ( count( $group_array ) == 1 )
        {
            $ret = $group_array[0]["ID"];
        }

        return $ret;
    }

    /*!
      Returns the id of the image group.
    */
    function id()
    {
       return $this->ID;
    }
    
    /*!
      Returns the width of the image group.
    */
    function width()
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );

       return $this->Width;
    }

    /*!
      Returns the height of the image group.
    */
    function height()
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );

       return $this->Height;
    }

    /*!
      Sets the width of the image group.
    */
    function setWidth( $value )
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );

       $this->Width = $value;
    }

    /*!
      Sets the height of the image group.
    */
    function setHeight( $value )
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );

       $this->Height = $value;
    }

    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database =& eZDB::globalDatabase();
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Width;
    var $Height;

}

?>
