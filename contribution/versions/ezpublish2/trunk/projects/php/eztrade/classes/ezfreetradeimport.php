<?php
// 
// $Id: ezfreetradeimport.php,v 1.3 2001/07/29 23:31:11 kaid Exp $
//
// ezfreetradeimport class
//
// Created on: <12-Jul-2001 12:23:54 ce>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! ezfreetradeimport
//! ezfreetradeimport documentation.
/*!

  Example code:
  \code
  \endcode

*/
include_once( "classes/ezdb.php" );
include_once( "classes/ezmysqldb.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezproductcategory.php" );

class eZFreeTradeImport
{

    /*!
      constructor
    */
    function eZFreeTradeImport()
    {
    }

    function importCategories( ) 
    {
        set_time_limit( 0 );
        $categories =& $this->getCategoriesFromImport();
        $db = eZDB::globalDatabase();

        if ( is_array ( $categories ) )
        {
            foreach( $categories as $importCategory )
            {
                $importID = $importCategory["ID"];
                $parentID = $importCategory["Parent"];
                $db->array_query( $ParentFromRemoteID, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$parentID'" );

                if ( count ( $ParentFromRemoteID ) == 1 )
                    $parentID = $ParentFromRemoteID[0]["ID"];
                else
                    $parentID = 0;

                $category = new eZProductCategory();
                $category->setName( mysql_escape_string( $importCategory["Name"] ) );
                $category->setDescription( mysql_escape_string( $importCategory["Description"] ) );
                $category->setParent( mysql_escape_string( $parentID ) );
                $category->setRemoteID( mysql_escape_string( $importID ) );

                if ( $importCategory["Graphic"] )
                {
                    $imageFromFS = "freetrade/images/departments/" . $importCategory["Graphic"];
                    if ( eZFile::file_exists( $imageFromFS ) )
                    {
                        $image = new eZImage();
                        $file = new eZImageFile();
                        if ( $file->getFile( $imageFromFS ) )
                        {
                            $image->setName( "Image" );
                            $image->setImage( $file );
                            
                            $image->store();
                            $category->setImage( $image );
                        }
                    }
                }
                $category->store();
            }
            return true;
        }
        else
            return false;
    }

    function importProducts( )
    {
        set_time_limit( 0 );
        $products =& $this->getProductsFromImport();

        $db =& eZDB::globalDatabase();
        if ( is_array ( $products ) )
        {
            $i=0;
            foreach( $products as $importProduct )
            {
                $product = new eZProduct();
                $product->setName( mysql_escape_string( $importProduct["Name"] ) );
                $product->setDescription( mysql_escape_string( $importProduct["Description"] ) );
                $product->setKeywords( mysql_escape_string( $importProduct["Keywords"] ) );
                $product->setRemoteID( $importProduct["ID"] );

                if ( $importProduct["Active"] == "Y" )
                    $product->setShowProduct( true );
                else
                    $product->setShowProduct( false );

                $product->store();

                if ( $importProduct["Graphic"] )
                {
                    $imageFromFS = "freetrade/images/items/" . $importProduct["Graphic"];
                    if ( eZFile::file_exists( $imageFromFS ) )
                    {
                        $image = new eZImage();
                        $file = new eZImageFile();
                        if ( $file->getFile( $imageFromFS ) )
                        {
                            $image->setName( "Image" );
                            $image->setImage( $file );
                            
                            $image->store();
                            $product->addImage( $image );
                            $product->setThumbnailImage( $image );
                            $product->setMainImage( $image );
                            $product->store();
                        }
                    }
                }
                
                $depID = $importProduct["Department"];
                $db->array_query( $CategoryIDFromRemoteID, "SELECT ID FROM eZTrade_Category WHERE RemoteID='$depID'" );

                $category = new eZProductCategory( $CategoryIDFromRemoteID[0][0] );
                
                if ( is_numeric( $category->id() ) )
                {
                    $category->addProduct( &$product );
                    $product->setCategoryDefinition( &$category );
                }

                $this->importOptions( &$product );

//                print( "Lagt til produkt: " . $product->name() . "<br>" );
                $i++;

            }
//            print( $i );
            return true;
        }
        else
            return false;
    }

    function importOptions( &$product )
    {
        if ( get_class( $product ) == "ezproduct" )
        {
            $productID = $product->id();
            
            $option = new eZOption();
            $option->setName( "Options" );
            $option->store();
            $product->addOption( $option );

            $options =& $this->getOptionsFromImport( $productID );
            
            foreach( $options as $importOption )
            {
                $value = new eZOptionValue();
                $value->setPrice( $importOption["SalePrice" ] );
                $value->setRemoteID( $importOption["ID"] );
                $value->store();
                $value->addDescription( mysql_escape_string( $importOption["Name"] ) );
                $option->addValue( $value );

                $quantityArray =& $this->getQuantity( $importOption["ID"] );

                $value->setTotalQuantity( $quantityArray["Available"] );

//                print( "ID: " . $value->id() . " - Price: ". $importOption["SalePrice" ] . "<br>");
            }
        }
    }

    function &getOptionsFromImport( $id )
    {
        $this->dbInit();

        if ( is_numeric( $id ) )
        {
            $db =& eZDB::globalDatabase();
            $db->array_query( $RemoteIDFromProductID, "SELECT RemoteID FROM eZTrade_Product WHERE ID='$id'" );

            $remoteID = $RemoteIDFromProductID[0][0];

            $this->dbImport->array_query( $array, "SELECT * FROM sku WHERE Item='$remoteID'" );
        }
        else
            $this->dbImport->array_query( $array, "SELECT * FROM sku" );

        $this->dbImport->close();

        return $array;
    }

    function &getCategoriesFromImport()
    {
        $this->dbInit();
        $this->dbImport->array_query( $array, "SELECT * FROM department ORDER BY Parent" );

        $this->dbImport->close();

        return $array;
    }

    function &getProductsFromImport()
    {
        $this->dbInit();
        $this->dbImport->array_query( $array, "SELECT * FROM item" );

        $this->dbImport->close();
      
        return $array;
    }

    function &getQuantity( $id )
    {
        $this->dbInit();

        if ( is_numeric( $id ) )
        {
            $this->dbImport->array_query( $array, "SELECT * FROM inventory WHERE SKU='$id'" );
            
            $this->dbImport->close();
            
            return $array[0];
        }
        else
            return false;
    }

    function setDatabaseImport( $host="localhost", $username="import", $password="import", $database="import" )
    {
        $this->Host = $host;
        $this->UserName = $username;
        $this->Password = $password;
        $this->Database = $database;
    }

    function dbInit()
    {
        $this->dbImport = new eZMySQLDB( $this->Host, $this->UserName, $this->Password, $this->Database );
    }

    var $Host;
    var $UserName;
    var $Password;
    var $Database;
    var $dbImport;
}

?>
