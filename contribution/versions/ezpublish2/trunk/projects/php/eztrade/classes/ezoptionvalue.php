<?
// 
// $Id: ezoptionvalue.php,v 1.5 2000/09/14 18:04:47 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <12-Sep-2000 15:52:19 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! Handles product option values.
/*!

  Example:
  \code
  // Create a new eZOptionValue object and store it to the database
  $value = new eZOptionValue();
  $value->setName( "Red" );
  $value->store();

  // Fetch a value from the database, and print out the contents.
  $value->get( 2 );

  print( $value->name() );
    
  \endcode
  \sa eZProductCategory eZOption
*/

include_once( "classes/ezdb.php" );
include_once( "eztrade/classes/ezoption.php" );

class eZOptionValue
{
    /*!
      Constructs a new eZOptionValue object.
    */
    function eZOptionValue( $id=-1, $fetch=true )
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
      Stores a eZOptionValue object to the database.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZTrade_OptionValue SET
		                         Name='$this->Name',
                                 OptionID='$this->OptionID'" );

        $this->ID = mysql_insert_id();
        
        return true;
    }

    /*!
      Fetches the option object values from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $optionValue_array, "SELECT * FROM eZTrade_OptionValue WHERE ID='$id'" );
            if ( count( $optionValue_array ) > 1 )
            {
                die( "Error: OptionValue's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $optionValue_array ) == 1 )
            {
                $this->ID = $optionValue_array[0][ "ID" ];
                $this->Name = $optionValue_array[0][ "Name" ];
                $this->OptionID = $optionValue_array[0][ "OptionID" ];
            }                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns every optionValue stored in the database.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $optionValue_array = array();
        
        $this->Database->array_query( $optionValue_array, "SELECT ID FROM eZTrade_OptionValue ORDER BY Name" );
        
        for ( $i=0; $i<count($optionValue_array); $i++ )            
        {
            $return_array[$i] = new eZOptionValue( $optionValue_array[$i]["ID"], 0 );            
        }
        
        return $return_array;
    }

    /*!
      Returns every optionValue connected to a certain Option.

      The values are sorted by name. Returns 0 if no values are found.
    */
    function getByOption( $value )
    {
        if ( get_class( $value ) == "ezoption" )
        {        
            $this->dbInit();
        
            $return_array = array();
            $optionValue_array = array();

            $id = $value->id(); 
        
            $this->Database->array_query( $optionValue_array, "SELECT ID FROM eZTrade_OptionValue WHERE OptionID='$id' ORDER BY Name" );
        
            for ( $i=0; $i<count($optionValue_array); $i++ )            
            {
                $return_array[$i] = new eZOptionValue( $optionValue_array[$i]["ID"], 0 );            
            }
        
            return $return_array;
        }
        else
        {
            return 0;
        }
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
      Returns the option connected to the value.
    */
    function option()
    {
        return new eZOption( $this->OptionID );
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
      
    */
    function setOptionID( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->OptionID = $value;       
       setType( $this->OptionID, "integer" );
    }
    
    /*!
      Private function.
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZTradeMain" );
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $Name;
    var $OptionID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
