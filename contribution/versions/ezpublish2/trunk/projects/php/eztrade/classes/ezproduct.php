<?
// 
// $Id: ezproduct.php,v 1.47 2001/03/15 19:41:36 ce Exp $
//
// Definition of eZProduct class
//
// Bård Farstad <bf@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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
include_once( "eztrade/classes/ezproducttype.php" );
include_once( "eztrade/classes/ezvattype.php" );


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

        if ( isset( $this->Price ) and $this->Price != "" and is_numeric( $this->Price ) )
        {
            $price = "'$this->Price'";
        }
        else
        {
            $price = "NULL";
        }

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_Product SET
		                         Name='$this->Name',
                                 Brief='$this->Brief',
                                 Description='$this->Description',
                                 Keywords='$this->Keywords',
                                 ProductNumber='$this->ProductNumber',
                                 Price=$price,
                                 ShowPrice='$showPrice',
                                 ShowProduct='$showProduct',
                                 Discontinued='$discontinued',
                                 ExternalLink='$this->ExternalLink',
                                 RemoteID='$this->RemoteID',
                                 IsHotDeal='$this->IsHotDeal',
                                 VATTypeID='$this->VATTypeID',
                                 ShippingGroupID='$this->ShippingGroupID'
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
                                 Price=$price,
                                 ShowPrice='$showPrice',
                                 ShowProduct='$showProduct',
                                 Discontinued='$discontinued',
                                 ExternalLink='$this->ExternalLink',
                                 IsHotDeal='$this->IsHotDeal',
                                 VATTypeID='$this->VATTypeID',
                                 ShippingGroupID='$this->ShippingGroupID'
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
                $this->ExternalLink =& $category_array[0][ "ExternalLink" ];
                $this->Price =& $category_array[0][ "Price" ];
                $this->IsHotDeal =& $category_array[0][ "IsHotDeal" ];
                $this->RemoteID =& $category_array[0][ "RemoteID" ];
                $this->VATTypeID =& $category_array[0][ "VATTypeID" ];
                $this->ShippingGroupID =& $category_array[0][ "ShippingGroupID" ];
                if ( $this->Price == "NULL" )
                    unset( $this->Price );

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
            $this->Database->query( "DELETE FROM eZTrade_ProductTypeLink WHERE ProductID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZTrade_AttributeValue WHERE ProductID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE ProductID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZTrade_ProductCategoryDefinition WHERE ProductID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZTrade_ProductImageLink WHERE ProductID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZTrade_ProductImageDefinition WHERE ProductID='$this->ID'" );

            $this->Database->array_query( $qry_array, "SELECT QuantityID FROM eZTrade_ProductQuantityDict
                                                       WHERE ProductID='$this->ID'" );
            foreach( $qry_array as $row )
            {
                $id = $row["QuantityID"];
                $this->Database->query( "DELETE FROM eZTrade_Quantity WHERE ID='$id'" );
            }
            $this->Database->query( "DELETE FROM eZTrade_ProductQuantityDict WHERE ProductID='$this->ID'" );

            $options = $this->options();
            foreach ( $options as $option )
            {
                $option->delete();
            }            

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
      Returns the remote ID of the product.
    */
    function &remoteID( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->RemoteID;
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
      Returns the price of the product.
    */
    function hasPrice()
    {
       return isset( $this->Price );
    }    

    /*!
      Returns the price of the product exclusive VAT ( prive - VAT value ).

      If a value is given as argument this value is used for VAT calculation.
      This is used in carts where you have multiple products and prices on options.
    */
    function priceExVAT( $price="" )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $price == "" )
       {
           $calcPrice = $this->Price;
       }
       else
       {
           $calcPrice = $price;
       }
       
       $vatType =& $this->vatType();

       $vat = 0;
       
       if ( $vatType )
       {
           $value =& $vatType->value();

           $vat = ( $calcPrice / ( $value + 100  ) ) * $value;
       }
      
       $priceExVat = $calcPrice - $vat;

       return $priceExVat;
    }

    /*!
      Returns the VAT value of the product.

      If a value is given as argument this value is used for VAT calculation.
      This is used in carts where you have multiple products and prices on options.
    */
    function vat( $price="" )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $price == "" )
       {
           $calcPrice = $this->Price;
       }
       else
       {
           $calcPrice = $price;
       }
       
       $vatType =& $this->vatType();
       $vat = 0;
       if ( $vatType )
       {
           $value =& $vatType->value();
           $vat = ( $calcPrice / ( $value + 100  ) ) * $value;        
       }
       return $vat;
    }

    /*!
      Sets the total quantity of the product.
    */
    function setTotalQuantity( $quantity )
    {
        $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array,
                          "SELECT Q.ID
                           FROM eZTrade_Quantity AS Q, eZTrade_ProductQuantityDict AS PQD
                           WHERE Q.ID=PQD.QuantityID AND ProductID='$id'" );
        $db->query( "DELETE FROM eZTrade_ProductQuantityDict WHERE ProductID='$id'" );
        foreach( $qry_array as $row )
        {
            $q_id = $row["ID"];
            $db->query( "DELETE FROM eZTrade_Quantity WHERE ID='$q_id'" );
        }
        if ( is_bool( $quantity ) and !$quantity )
            return;
        $db->query( "INSERT INTO eZTrade_Quantity VALUES('','$quantity')" );
        $q_id = $db->insertID();
        $db->query( "INSERT INTO eZTrade_ProductQuantityDict VALUES('$id','$q_id')" );
    }

    /*!
      \static
      Returns the total quantity of this product.
    */
    function totalQuantity( $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();
        $db->array_query( $qry_array,
                          "SELECT Q.Quantity
                           FROM eZTrade_Quantity AS Q, eZTrade_ProductQuantityDict AS PQD
                           WHERE Q.ID=PQD.QuantityID AND ProductID='$id'" );
        $quantity = 0;
        if ( count( $qry_array ) > 0 )
        {
            foreach( $qry_array as $row )
            {
                if ( $row["Quantity"] == "NULL" )
                    return false;
                $quantity += $row["Quantity"];
            }
        }
        else
            return false;
        return $quantity;
    }

    /*!
      Returns a textual version of the quantity, this allows a site to hide the
      exact quantity but instead give indications.
      If no named quantity can be found the quantity is returned.
    */
    function namedQuantity( $quantity )
    {
        $db =& eZDB::globalDatabase();
        if ( is_bool( $quantity ) and !$quantity )
        {
            $db->array_query( $qry_array, "SELECT Name FROM eZTrade_QuantityRange
                                       WHERE MaxRange=-1 LIMIT 1", 0, 1 );
            $name = $qry_array[0]["Name"];
        }
        else
        {
            $db->array_query( $qry_array, "SELECT Name FROM eZTrade_QuantityRange
                                       WHERE MaxRange IS NOT NULL AND MaxRange>=$quantity
                                       ORDER BY MaxRange LIMIT 1", 0, 1 );
            $name = "";
            if ( count( $qry_array ) == 1 )
            {
                $name = $qry_array[0]["Name"];
            }
            else
            {
                $db->array_query( $qry_array, "SELECT Name FROM eZTrade_QuantityRange
                                           WHERE MaxRange IS NULL LIMIT 1", 0, 1 );
                if ( count( $qry_array ) == 1 )
                    $name = $qry_array[0]["Name"];
                else
                    $name = $quantity;
            }
        }
        return $name;
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
      Returns the external link to the product.
    */
    function externalLink()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return htmlspecialchars( $this->ExternalLink );
    }

    /*!
      Returns true if the product is a hot deal.
      False if not.
    */
    function isHotDeal()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsHotDeal == "true" )
       {
           $ret = true;
       }

       return $ret;
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
      Sets the remote ID.
    */
    function setRemoteID( $remoteID )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->RemoteID = $remoteID;        
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
      Sets the external link.
    */
    function setExternalLink( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->ExternalLink = $value;
    }

    /*!
      Set the product to be a hot deal or not. True makes
      the product a hot deal, false it it just as an ordinary
      product.
    */
    function setIsHotDeal( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsHotDeal = "true";
       }
       else
       {
           $this->IsHotDeal = "false";
       }
        
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

       for ( $i=0; $i < count($option_array); $i++ )
       {
           $return_array[$i] = new eZOption( $option_array[$i]["OptionID"], true );
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
            $this->Database->query( "DELETE FROM eZTrade_ProductImageDefinition WHERE ProductID='$this->ID' AND MainImageID='$imageID'" );
            $this->Database->query( "DELETE FROM eZTrade_ProductImageDefinition WHERE ProductID='$this->ID' AND ThumbnailImageID='$imageID'" );
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
       
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZTrade_ProductImageLink WHERE ProductID='$this->ID' ORDER BY Created" );
       
       for ( $i=0; $i < count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       
       return $return_array;
    }

    /*!
      Sets the main image for the product.

      The argument must be a eZImage object, or false to unset the main image.
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
                                                       ProductID='$this->ID'" );

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
        else if ( $image == false )
        {
            $this->dbInit();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZTrade_ProductImageDefinition
                                                       WHERE
                                                       ProductID='$this->ID'" );

            if ( $res_array[0]["Number"] == "1" )
            {
                $this->Database->query( "UPDATE eZTrade_ProductImageDefinition
                                         SET
                                         MainImageID='0'
                                         WHERE
                                         ProductID='$this->ID'" );
            }
        }
    }    

    /*!
      Sets the thumbnail image for the product.

      The argument must be a eZImage object, or false to unset the thumbnail image.
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
                                                       ProductID='$this->ID'" );

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
        else if ( $image == false )
        {
            $this->dbInit();

            $this->Database->array_query( $res_array, "SELECT COUNT(*) AS Number FROM eZTrade_ProductImageDefinition
                                                       WHERE
                                                       ProductID='$this->ID'" );

            if ( $res_array[0]["Number"] == "1" )
            {
                $this->Database->query( "UPDATE eZTrade_ProductImageDefinition
                                         SET
                                         ThumbnailImageID='0'
                                         WHERE
                                         ProductID='$this->ID'" );
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
           if ( is_numeric( $res_array[0]["ThumbnailImageID"] ) )
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
                                     ( Name LIKE '%$query%' ) OR
                                     ( Description LIKE '%$query%' ) OR
                                     ( Keywords LIKE '%$query%' ) 
                                     LIMIT $offset, $limit
                                   " );

       foreach ( $res_array as $product )
       {
           $ret[] = new eZProduct( $product["ID"] );
       }
       
       return $ret;
    }

    /*!
      Searches through every product and returns the result count
    */
    function activeProductSearchCount( $query )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = array();
       $this->dbInit();

       $this->Database->array_query( $res_array, "SELECT count(ID) AS Count FROM eZTrade_Product
                                     WHERE
                                     ( Name LIKE '%$query%' ) OR
                                     ( Description LIKE '%$query%' ) OR
                                     ( Keywords LIKE '%$query%' )
                                   " );
       
       return $res_array[0]["Count"];
    }
    
    /*!
      Returns the products set to hot deal.
    */
    function &hotDealProducts( $limit = false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( is_numeric( $limit ) and $limit >= 0 )
       {
           $limit_text = "LIMIT $limit";
       }

       $ret = array();
       $this->dbInit();

       $this->Database->array_query( $res_array, "SELECT ID FROM eZTrade_Product
                                     WHERE
                                     IsHotDeal='true' ORDER BY Name $limit_text" );

       foreach ( $res_array as $product )
       {
           $ret[] = new eZProduct( $product["ID"] );
       }
       
       return $ret;

    }

    /*!
      Returns the categrories a product is assigned to.

      The categories are returned as an array of eZProductCategory objects.
    */
    function categories( $as_object = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       $ret = array();
       $this->Database->array_query( $category_array, "SELECT * FROM eZTrade_ProductCategoryLink WHERE ProductID='$this->ID'" );

       if ( $as_object )
       {
           foreach ( $category_array as $category )
           {
               $ret[] = new eZProductCategory( $category["CategoryID"] );
           }
       }
       else
       {
           foreach ( $category_array as $category )
           {
               $ret[] = $category["CategoryID"];
           }
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
      Set's the products defined category. This is the main category for the product.
      Additional categories can be added with eZProductCategory::addProduct();
    */
    function setCategoryDefinition( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $value ) == "ezproductcategory" )
       {
            $this->dbInit();

            $categoryID = $value->id();

            $this->Database->query( "DELETE FROM eZTrade_ProductCategoryDefinition
                                     WHERE ProductID='$this->ID'" );
            
            $query = "INSERT INTO
                           eZTrade_ProductCategoryDefinition
                      SET
                           CategoryID='$categoryID',
                           ProductID='$this->ID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns the product's definition category.
    */
    function categoryDefinition( $as_object = true )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $res, "SELECT CategoryID FROM
                                            eZTrade_ProductCategoryDefinition
                                            WHERE ProductID='$this->ID'" );

       $category = false;
       if ( count( $res ) == 1 )
       {
           if ( $as_object )
               $category = new eZProductCategory( $res[0]["CategoryID"] );
           else
               $category = $res[0]["CategoryID"];
       }
       else
       {
           print( "<br><b>Failed to fetch product category definition for ID $this->ID</b><br>" );
       }

       return $category;
    }

    /*!
      Sets the products type.
    */
    function setType( $type )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $type ) == "ezproducttype" )
       {
            $this->dbInit();

            $typeID = $type->id();

            $this->Database->query( "DELETE FROM eZTrade_AttributeValue
                                     WHERE ProductID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZTrade_ProductTypeLink
                                     WHERE ProductID='$this->ID'" );

            $query = "INSERT INTO
                           eZTrade_ProductTypeLink
                      SET
                           TypeID='$typeID',
                           ProductID='$this->ID'";
            
            $this->Database->query( $query );
       }       
    }

    /*!
      Returns the product's type.
    */
    function type( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $res, "SELECT TypeID FROM
                                            eZTrade_ProductTypeLink
                                            WHERE ProductID='$this->ID'" );

       $type = false;
       
       if ( count( $res ) == 1 )
       {
           $type = new eZProductType( $res[0]["TypeID"] );
       }

       return $type;
    }

    /*!
      Removes the products type definition.
    */
    function removeType()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       // delete values
       $this->Database->query( "DELETE FROM eZTrade_AttributeValue
                                     WHERE ProductID='$this->ID'" );

       $this->Database->query( "DELETE FROM eZTrade_ProductTypeLink
                                     WHERE ProductID='$this->ID'" );
            
    }

    /*!
      Check if there are a product where RemoteID == $id. Return the product if true.
    */
    function getByRemoteID( $id )
    {
        $this->dbInit();
        
        $product = false;
        
        $this->Database->array_query( $res, "SELECT ID FROM
                                            eZTrade_Product
                                            WHERE RemoteID='$id'" );
        
        if ( count( $res ) == 1 )
        {
            $product = new eZProduct( $res[0]["ID"] );
        }
        
        return $product;
    }

    /*!
      Returns the name of the product with the given id.
    */
    function productName( $id )
    {
        $this->dbInit();
        $ret = false;
        
        if ( $id != "" )
        {
            $this->Database->array_query( $product_array, "SELECT * FROM eZTrade_Product WHERE ID='$id'" );
            
            if( count( $product_array ) == 1 )
            {
                $ret =& $product_array[0][ "Name" ];
            }
        }
        
        return $ret;
    }

    /*!
      Sets the VAT type.
    */
    function setVATType( $type )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       if ( get_class( $type ) == "ezvattype" )
       {
           $this->VATTypeID = $type->id();
       }
    }


    /*!
      Returns the VAT type.

      False if no type is assigned.
    */
    function vatType( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $ret = false;
       if ( is_numeric( $this->VATTypeID ) and ( $this->VATTypeID > 0 ) )
       {
           $ret = new eZVATType( $this->VATTypeID );
       }

       return $ret;
    }

    /*!
      Sets the shipping group
    */
    function setShippingGroup( $group )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       if ( get_class( $group ) == "ezshippinggroup" )
       {
           $this->ShippingGroupID = $group->id();
       }
    }


    /*!
      Returns the shipping group as a eZShippingGroup object.

      False if no type is not assigned.
    */
    function shippingGroup( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $ret = false;
       if ( is_numeric( $this->ShippingGroupID ) and ( $this->ShippingGroupID > 0 ) )
       {
           $ret = new eZShippingGroup( $this->ShippingGroupID );
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
            $this->Database =& eZDB::globalDatabase();
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
    var $ExternalLink;
    var $IsHotDeal;
    var $RemoteID;
    var $VATTypeID;
    var $ShippingGroupID;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
