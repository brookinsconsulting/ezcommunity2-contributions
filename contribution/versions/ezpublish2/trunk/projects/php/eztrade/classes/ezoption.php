<?
// 
// $Id: ezoption.php,v 1.1 2000/09/12 13:53:10 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Sep-2000 15:01:53 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//


//!! eZTrade
//! The eZOption class handles options for products and product categories.
/*!

  Example code:
  \code
  // Create a new option object
  $option = new eZOption();

  $option->setName( "Color" );
  $option->setDescription( "The color of the product" );

  // Store the option to the database
  $option->store();

  // Fetch all the options from the database.
  $optionArray = $option->getAll();

  //print out all the options.
  foreach ( $optionArray as $optionItem )
  {
    print( "Option: " . $optionItem->name() . "<br>" );
  }

  \endcode  
  \sa eZProductCategory
*/

include_once( "classes/ezdb.php" );

class eZOption
{
    /*!
      Constructs a new eZOption object. Retrieves the data from the database
      if a valid id is given as an argument.
    */
    function eZOption( $id=-1, $fetch=true )
    {
        $IsConnected = false;
        if ( $id != -1 )
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
      Stores a eZOption object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZTrade_Option SET
		                         Name='$this->Name',
                                 Description='$this->Description'" );
        
        return mysql_insert_id();
    }

    /*!
      Fetches the option object values from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $option_array, "SELECT * FROM eZTrade_Option WHERE ID='$id'" );
            if ( count( $option_array ) > 1 )
            {
                die( "Error: Option's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $option_array ) == 1 )
            {
                $this->ID = $option_array[0][ "ID" ];
                $this->Name = $option_array[0][ "Name" ];
                $this->Description = $option_array[0][ "Description" ];
            }                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!

    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $option_array = array();
        
        $this->Database->array_query( $option_array, "SELECT ID FROM eZTrade_Option ORDER BY Name" );
        
        for ( $i=0; $i<count($option_array); $i++ )
        {
            $return_array[$i] = new eZOption( $option_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    

    /*!
      Returns the name of the option.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the option description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }    

    /*!
      Sets the name of the option.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the option.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    
    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZTradeMain" );
            $IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Description;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
