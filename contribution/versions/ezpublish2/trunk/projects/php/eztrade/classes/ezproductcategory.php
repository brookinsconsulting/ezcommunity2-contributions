<?
// 
// $Id: ezproductcategory.php,v 1.2 2000/09/12 11:41:03 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZTrade
//! eZProductCategory handles product categories.
/*!
  The eZProductCategory class handles product groups, the relation to products
  in the productgroup and the options connected to the product group.
  
  Example of usage:

  \code
  // inserts a new Category to the database:
  $category = new eZProductCategory();

  $category->setName( "Category name" );
  $category->setDescription( "This is a brief description of the category" );
  
  $category->store();

  // Fetches every category found in the database

  $categoryArray = $category->getAll();

  // Prints out every category's name and description , notice it's an array 
  // of objects.
  foreach ( $categoryArray as $catItem )
  {
    print( $catItem->name() . "<br>" . $catItem->description() . "..<br>" );
  }
  
  \endcode

  \sa eZProdct eZOption eZOptionValue
*/

include_once( "classes/ezdb.php" );


class eZProductCategory
{
    /*!
      Constructs a new eZProductCategory object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZProductCategory( $id=-1, $fetch=true )
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
      Stores a eZProductGroup object to the database.

      Returns the ID to the stored group.
    */
    function store()
    {
        $this->dbInit();

        $this->Database->query( "INSERT INTO eZTrade_Category SET
                                 ID='$this->ID',
		                         Name='$this->Name',
                                 Description='$this->Description'" );
        
        return mysql_insert_id();
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZTrade_Category WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->Description = $category_array[0][ "Description" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZProductCategory objects.
    */
    function getAll()
    {
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZTrade_Category ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZProductCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the name of the category.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }
    
    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function parent()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        // implement
    }


    /*!
      Sets the name of the category.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the parent category.
    */
    function setParent( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        print( get_class( $value ) );
        // implement        
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
    var $Parent;
    var $Description;
    var $OptionArray;

    var $Database;
    
    var $State_;
    var $IsConnected;
}

?>
