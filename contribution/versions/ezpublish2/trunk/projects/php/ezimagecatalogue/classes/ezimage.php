<?
// 
// $Id: ezimage.php,v 1.1 2000/09/21 12:42:23 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 11:22:21 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZImageCatalogue
//! The eZImage class hadles images in the image catalogue.
/*!
  
*/

include_once( "classes/ezdb.php" );

class eZImage
{
    /*!
      Constructs a new eZImage object.
    */
    function eZImage( $id="", $fetch=true )
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

    /*!
      Stores a eZImage object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZImageCatalogue_Image SET
                                 Name='$this->Name',
                                 Caption='$this->Caption',
                                 Description='$this->Description',
                                 FileName='$this->FileName',
                                 " );

        $this->State_ = "Coherent";
    }

    /*!
      Returns the name of the image.
    */
    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the caption of the image.
    */
    function caption()
    {
        return $this->Caption;
    }

    /*!
      Returns the description of the image.
    */
    function description()
    {
        return $this->Description;
    }    

    /*!
      Returns the filename of the image.
    */
    function fileName()
    {
        return $this->FileName;
    }

    /*!
      Sets the image name.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the image caption.
    */
    function setCaption( $value )
    {
        $this->Caption = $value;
    }

    /*!
      Sets the image description.
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the image file name.
    */
    function setFileName( $value )
    {
        $this->FileName = $value;
    }
    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZImageCatalogueMain" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
}

?
