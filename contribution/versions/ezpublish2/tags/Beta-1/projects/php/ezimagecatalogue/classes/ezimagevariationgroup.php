<?
// 
// $Id: ezimagevariationgroup.php,v 1.3 2000/10/06 13:46:24 bf-cvs Exp $
//
// Definition of eZCompany class
//
// B�rd Farstad <bf@ez.no>
// Created on: <21-Sep-2000 17:28:47 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
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
        $this->Database->array_query( $group_array, "SELECT * FROM eZImageCatalogue_ImageVariationGroup
                                                     WHERE Width='$width' AND Height='$height'" );
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
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Width;
    var $Height;

}

?>
