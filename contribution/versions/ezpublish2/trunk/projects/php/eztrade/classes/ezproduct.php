<?
// 
// $Id: ezproduct.php,v 1.1 2000/09/15 13:47:53 bf-cvs Exp $
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
//! eZProduct handles product information.
/*!

  Example code:
  \code
  // Create a new product and set some values.
  $product = new eZProduct();
  $product->setName( "Toothbrush" );
  $product->setBrief( "A nice and small toothbrush" );
  $product->setDescription( "Bla bla environment bla bla cheap bla bla must have." );
  $product->setProductNumber( "Jordan-A101" );
  $product->setKeywords( "teeth cheap cool brush" );
  $product->setPrice( 21.50 );

  // Store the product to the database
  $product->store();

  \endcode
  \sa eZProductCategory eZOption
*/

include_once( "classes/ezdb.php" );

class eZProduct
{
    /*!
      Constructs a new eZProduct object.

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
            
            // default verdier
            $this->ShowPrice = true;
            $this->ShowProduct = true;
            $this->Discontinued = false;
            $this->InheritOptions = false;
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( $this->ShowPrice == true )
            $showPrice = "true";
        else
            $showPrice = "false";            

        if ( $this->ShowProduct == true )
            $showProduct = "true";
        else
            $showProduct = "false";            

        if ( $this->Discontinued == true )
            $discontinued = "true";
        else
            $discontinued = "false";            

        if ( $this->InheritOptions == true )
            $inheritOptions = "true";
        else
            $inheritOptions = "false";            
        
        $this->Database->query( "INSERT INTO eZTrade_Product SET
		                         Name='$this->Name',
                                 Brief='$this->Brief',
                                 Description='$this->Description',
                                 Keywords='$this->Keywords',
                                 ProductNumber='$this->ProductNumber',
                                 Price='$this->Price',
                                 ShowPrice='$showPrice',
                                 ShowProduct='$showProduct',
                                 Discontinued='$discontinued',
                                 InheritOptions='$inheritOptions'
                                 " );

        $this->ID = mysql_insert_id();
        
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
            $this->Database->array_query( $category_array, "SELECT * FROM eZTrade_Product WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID = $category_array[0][ "ID" ];
                $this->Name = $category_array[0][ "Name" ];
                $this->Brief = $category_array[0][ "Breif" ];
                $this->Description = $category_array[0][ "Description" ];
                $this->Keywords = $category_array[0][ "Keywords" ];
                $this->ProductNumber = $category_array[0][ "ProductNumber" ];
                if ( $category_array[0][ "ShowPrice" ] == "true" )                    
                    $this->ShowPrice = true;
                else
                    $this->ShowPrice = false;                    

                if ( $category_array[0][ "ShowProdcut" ] == "true" )
                    $this->ShowProduct = true;
                else
                    $this->ShowProduct = false;

                if ( $category_array[0][ "Discontinued" ] == "true" )
                    $this->Discontinued = true;
                else
                    $this->Discontinued = false;
                
                if ( $category_array[0][ "InheritOptions" ] == "true" )
                    $this->InheritOptions = true;
                else
                    $this->InheritOptions = false;
                
                $this->State_ = "Coherent";                
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns the object ID to the product. This is the unique ID stored in the database.
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
      Sets the product name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name = $value;        
    }

    /*!
      Sets the brief description of the product.
    */
    function setBrief( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Brief = $value;
    }

    /*!
      Sets the product description.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Description = $value;       
    }

    /*!
      Sets the keywords.
    */
    function setKeywords( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Keywords = $value;
    }

    /*!
      Sets the product number.
    */
    function setProductNumber( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ProductNumber = $value;
    }

    /*!
      Sets the product number.
    */
    function setPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Price = $value;
       setType( $this->Price, "double" );
    }

    /*!
      Sets the ShowPrice value.
    */
    function setShowPrice( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ShowPrice = $value;
       setType( $this->ShowPrice, "bool" );
    }
    
    /*!
      Sets the ShowProduct value.
    */
    function setShowProduct( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ShowProduct = $value;
       setType( $this->ShowProduct, "bool" );
    }
    
    /*!
      Sets the Discontinued value. This indicates that the product is no longer
      available. The product is still shown in the store.
    */
    function setDiscontinued( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ShowProduct = $value;
       setType( $this->ShowProduct, "bool" );
    }

    /*!
      Sets the inheritoptions value. If this is true the product inherits the
      options of the product group.

    */
    function setInheritOptions( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->InheritOptions = $value;
       setType( $this->InheritOptions, "bool" );
    }


    /*!
      Adds a option to the product.
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
            
            $this->Database->query( "INSERT INTO eZTrade_ProductOptionLink SET ProductID='$this->ID', OptionID='$optionID'" );
        }
    }

    /*!
      Returns every option to a product as a array of eZOption objects.
    */
    function options()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $option_array = array();
       
       $this->Database->array_query( $option_array, "SELECT OptionID FROM eZTrade_ProductOptionLink WHERE ProductID='$this->ID'" );
       
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
            $this->Database = new eZDB( "site.ini", "eZTradeMain" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Brief;
    var $Description;
    var $Keywords;
    var $ProductNumber;
    var $ShowPrice;
    var $ShowProduct;
    var $Discontinued;
    var $InheritOptions;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
