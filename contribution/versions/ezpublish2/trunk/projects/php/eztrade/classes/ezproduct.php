<?
// 
// $Id: ezproduct.php,v 1.14 2000/10/19 10:43:43 bf-cvs Exp $
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

/*!TODO
  Add query builder to search. Use same as in eZLink.
*/

include_once( "classes/ezdb.php" );

include_once( "eztrade/classes/ezoption.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

class eZProduct
{
    /*!
      Constructs a new eZProduct object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZProduct( $id="", $fetch=true )
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
        
        if ( !isset( $this->ID ) )
        {
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

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Product SET
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
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $category_array, "SELECT * FROM eZTrade_Product WHERE ID='$id'" );
            if ( count( $category_array ) > 1 )
            {
                die( "Error: Category's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $category_array ) == 1 )
            {
                $this->ID =& $category_array[0][ "ID" ];
                $this->Name =& $category_array[0][ "Name" ];
                $this->Brief =& $category_array[0][ "Brief" ];
                $this->Description =& $category_array[0][ "Description" ];
                $this->Keywords =& $category_array[0][ "Keywords" ];
                $this->ProductNumber =& $category_array[0][ "ProductNumber" ];
                $this->Price =& $category_array[0][ "Price" ];

                if ( $category_array[0][ "ShowPrice" ] == "true" )                    
                    $this->ShowPrice = true;
                else
                    $this->ShowPrice = false;                    

                if ( $category_array[0][ "ShowProduct" ] == "true" )
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
                $ret = true;
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Deletes a eZProduct object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE ProductID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZTrade_Product WHERE ID='$this->ID'" );
        }
        
        return true;
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
      Returns the name of the product.
    */
    function &name( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Name );
    }    

    /*!
      Returns the price of the product.
    */
    function price( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Price;
    }    

    /*!
      Returns the keywords of the product.
    */
    function &keywords( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Keywords );
    }    

    /*!
      Returns the product number of the product.
    */
    function &productNumber( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->ProductNumber );
    }    

    /*!
      Returns the introduction to the product.
    */
    function &brief( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Brief );
    }    

    /*!
      Returns the description of the product.
    */
    function &description( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->Description );
    }

    /*!
      Returns the ShowPrice value. The Price should not be shown if this value
      is false.
    */
    function showPrice()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ShowPrice;
    }

    /*!
      Returns true if the product should be shown. False if not.
    */
    function showProduct()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ShowProduct;
    }
    
    /*!
      Returns true if the product should inherit options from the product group.
    */
    function inheritOptions()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->InheritOptions;
    }
    
    /*!
      Sets the product name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Name =& $value;        
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
       setType( $this->ShowPrice, "integer" );
    }
    
    /*!
      Sets the ShowProduct value.
    */
    function setShowProduct( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ShowProduct = $value;
       setType( $this->ShowProduct, "integer" );
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
       setType( $this->ShowProduct, "integer" );
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
       setType( $this->InheritOptions, "integer" );
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
      Adds an image to the product.
    */
    function addImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $value->id();
            
            $this->Database->query( "INSERT INTO eZTrade_ProductImageLink SET ProductID='$this->ID', ImageID='$imageID'" );
        }
    }

    /*!
      Deletes an image from the product.

      NOTE: the image does not get deleted from the image catalogue.
    */
    function deleteImage( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $value ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $value->id();
            
            $this->Database->query( "DELETE FROM eZTrade_ProductImageLink WHERE ProductID='$this->ID' AND ImageID='$imageID'" );
        }
    }
    
    /*!
      Returns every image to a product as a array of eZImage objects.
    */
    function images()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $image_array = array();
       
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZTrade_ProductImageLink WHERE ProductID='$this->ID'" );
       
       for ( $i=0; $i<count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       
       return $return_array;
    }

    /*!
      Sets the main image for the product.

      The argument must be a eZImage object.
    */
    function setMainImage( $image )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='$this->ID'
                                   " );

            if ( $res_array[0]["Number"] == "1" )
            {            
                $this->Database->query( "UPDATE eZTrade_ProductImageDefinition
                                     SET
                                     MainImageID='$imageID'
                                     WHERE
                                     ProductID='$this->ID'" );
            }
            else
            {
                $this->Database->query( "INSERT INTO eZTrade_ProductImageDefinition
                                     SET
                                     ProductID='$this->ID',
                                     MainImageID='$imageID'" );
            }
        }
    }    

    /*!
      Sets the thumbnail image for the product.

      The argument must be a eZImage object.
    */
    function setThumbnailImage( $image )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='$this->ID'
                                   " );

            if ( $res_array[0]["Number"] == "1" )
            {            
                $this->Database->query( "UPDATE eZTrade_ProductImageDefinition
                                     SET
                                     ThumbnailImageID='$imageID'
                                     WHERE
                                     ProductID='$this->ID'" );
            }
            else
            {
                $this->Database->query( "INSERT INTO eZTrade_ProductImageDefinition
                                     SET
                                     ProductID='$this->ID',
                                     ThumbnailImageID='$imageID'" );
            }
        }
    }

    /*!
      Returns the main image of the product as a eZImage object.

      false (0) is returned if no main image is found.
    */
    function mainImage( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='$this->ID'
                                   " );
       
       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["MainImageID"] != "NULL" )
           {
               $ret = new eZImage( $res_array[0]["MainImageID"], false );
           }               
       }
       
       return $ret;
    }

    /*!
      Returns the thumbnail image of the product as a eZImage object.
    */
    function thumbnailImage( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $this->dbInit();
       
       $this->Database->array_query( $res_array, "SELECT * FROM eZTrade_ProductImageDefinition
                                     WHERE
                                     ProductID='$this->ID'
                                   " );
       
       if ( count( $res_array ) == 1 )
       {
           if ( $res_array[0]["ThumbnailImageID"] != "NULL" )
           {
               $ret = new eZImage( $res_array[0]["ThumbnailImageID"], false );
           }               
       }
       
       return $ret;
       
    }

    /*!
      Searches through every product and returns the result as an array
      of eZProduct objects.
    */
    function activeProductSearch( $query, $offset, $limit )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = array();
       $this->dbInit();

       $this->Database->array_query( $res_array, "SELECT ID FROM eZTrade_Product
                                     WHERE
                                     Name LIKE '%$query%' LIMIT $offset, $limit
                                   " );

       foreach ( $res_array as $product )
       {
           $ret[] = new eZProduct( $product["ID"] );
       }
       
       return $ret;
    }


    /*!
      Removes every category assignments from the current product.
    */
    function removeFromCategories()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $this->Database->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE ProductID='$this->ID'" );       
        
    }

    /*!
      Returns true if the product is assigned to the category given
      as argument. False if not.
     */
    function existsInCategory( $category )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( get_class( $category ) == "ezproductcategory" )
       {
           $this->dbInit();
           $catID = $category->id();
        
           $this->Database->array_query( $ret_array, "SELECT ID FROM eZTrade_ProductCategoryLink
                                    WHERE ProductID='$this->ID' AND CategoryID='$catID'" );

           if ( count( $ret_array ) == 1 )
           {
               $ret = true;
           }           
       }
       return $ret;
    }
    
    /*!
      \private
      
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
