<?
// 
// $Id: ezproductcategory.php,v 1.14 2000/10/06 09:39:42 bf-cvs Exp $
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

  // Create a option
  $option = new eZOption();

  $option->setName( "Color" );
  $option->setDescription( "This is the color of the product" );  

  // Add a option to the category
  $category->addOption( $option );
  
  $pathArray = $category->path();

  // Get the current path of a category
  $pathArray = $category->path();

  // print the number of levels in the path
  print( count( $pathArray ) );

  // print out the path with slashes if you use $path[0] you get
  // the id of the path
  foreach ( $pathArray as $path )
  {
    print( $path[1] . " / " );    
  }
  
  \endcode

  \sa eZProduct eZOption eZOptionValue
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
        $this->IsConnected = false;
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

    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Parent='$this->Parent'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Parent='$this->Parent' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZProductGroup object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZTrade_Category WHERE ID='$this->ID'" );
        }
        
        return true;
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
                $this->Parent = $category_array[0][ "Parent" ];
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
        $this->dbInit();
        
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
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZProductCategory objects.
    */
    function getByParent( $parent, $sortby=name )
    {
        if ( get_class( $parent ) == "ezproductcategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();
                 
            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZTrade_Category WHERE Parent='$parentID' ORDER BY Name" );
        
            for ( $i=0; $i<count($category_array); $i++ )
            {
                $return_array[$i] = new eZProductCategory( $category_array[$i]["ID"], 0 );
            }
            
            return $return_array;
        }
        else
        {
            return 0;
        }
    }

    /*!
      Returns the current path as an array of arrays.

      The array is built up like: array( array( id, name ), array( id, name ) );

      See detailed description for an example of usage.
    */
    function path( $categoryID=0 )
    {
        if ( $categoryID == 0 )
        {
            $categoryID = $this->ID;
        }
            
        $category = new eZProductCategory( $categoryID );

        $path = array();

        $parent = $category->parent();
        if ( $parent != 0 )
        {
            $path = array_merge( $path, $this->path( $parent->id() ) );
        }
        else
        {
//              array_push( $path, $category->name() );
        }

        if ( $categoryID != 0 )
            array_push( $path, array( $category->id(), $category->name() ) );                                
        
        return $path;
    }
    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        if ( $this->State_ == "New" )
            $ret = 1;
        else
            $ret = $this->ID;
       
        return $ret;
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

       if ( $this->Parent != 0 )
       {
           return new eZProductCategory( $this->Parent );
       }
       else
       {
           return 0;           
       }
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

       if ( get_class( $value ) == "ezproductcategory" )
       {
           $this->Parent = $value->id();
       }
    }


    /*!
      Adds a product to the category.
    */
    function addProduct( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezproduct" )
       {
            $this->dbInit();

            $prodID = $value->id();
            print( $prodID );
            
            $query = "INSERT INTO
                           eZTrade_ProductCategoryLink
                      SET
                           CategoryID='$this->ID',
                           ProductID='$prodID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns every product to a category as a array of eZProduct objects.
    */
    function products()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $product_array = array();
       
       $this->Database->array_query( $product_array, "SELECT ProductID FROM eZTrade_ProductCategoryLink WHERE CategoryID='$this->ID'" );

       for ( $i=0; $i<count($product_array); $i++ )
       {
           $return_array[$i] = new eZProduct( $product_array[$i]["ProductID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      Returns every active product to a category as a array of eZProduct objects.
    */
    function activeProducts()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $product_array = array();
       
       $this->Database->array_query( $product_array, "SELECT eZTrade_Product.ID AS ProductID from eZTrade_ProductCategoryLink,
                                                             eZTrade_Product WHERE eZTrade_ProductCategoryLink.CategoryID='$this->ID' AND
                                                             eZTrade_Product.ShowProduct='true'
                                                             AND eZTrade_ProductCategoryLink.ProductID=eZTrade_Product.ID
                                                             GROUP BY eZTrade_Product.ID" );
       for ( $i=0; $i<count($product_array); $i++ )
       {
           $return_array[$i] = new eZProduct( $product_array[$i]["ProductID"], false );
       }
       
       return $return_array;
    }
    

    /*!
      Adds a option to the category.
    */
    function addOption( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezoption" )
        {
            $this->dbInit();

            $optionID = $value->id();
            $value->store();

            $this->Database->query( "INSERT INTO eZTrade_CategoryOptionLink SET CategoryID='$this->ID', OptionID='$optionID'" );
        }
    }

    /*!
      Returns every option to a category as a array of eZOption objects.
    */
    function options()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $option_array = array();
       
       $this->Database->array_query( $option_array, "SELECT OptionID FROM eZTrade_CategoryOptionLink WHERE CategoryID='$this->ID'" );
       
       for ( $i=0; $i<count($option_array); $i++ )
       {
           $return_array[$i] = new eZOption( $option_array[$i]["OptionID"], false );
       }
       
       return $return_array;
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
    var $Name;
    var $Parent;
    var $Description;
    var $OptionArray;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
