<?
// 
// $Id: ezproductcategory.php,v 1.35 2001/03/26 18:35:47 jb Exp $
//
// Definition of eZProductCategory class
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

  $categoryArray = $category->geAll();

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
include_once( "eztrade/classes/ezproduct.php" );

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
                                 SortMode='$this->SortMode',
                                 RemoteID='$this->RemoteID',
                                 Parent='$this->Parent'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Category SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 SortMode='$this->SortMode',
                                 RemoteID='$this->RemoteID',
                                 Parent='$this->Parent' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZProductGroup object from the database.

    */
    function delete( $catID=-1 )
    {
        $this->dbInit();

        if ( $catID == -1 )
            $catID = $this->ID;

        $category = new eZProductCategory( $catID );

        $categoryList = $category->getByParent( $category );

        foreach( $categoryList as $categoryItem )
        {
            $this->delete( $categoryItem->id() );
        }

        $categoryID = $category->id();
        
        foreach( $this->products() as $product )
        {
            $categoryDefinition = $product->categoryDefinition();

            if ( $categoryDefinition->id() == $category->id() )
            {
                $this->Database->query( "DELETE FROM eZTrade__ProductCategoryDefinition WHERE CategoryID='$categoryID'" );
                $this->Database->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );
               
                $product->delete();
            }
            else
            {
                $this->Database->query( "DELETE FROM eZTrade_ProductCategoryLink WHERE CategoryID='$categoryID'" );
            }
        }

        $this->Database->query( "DELETE FROM eZTrade_Category WHERE ID='$categoryID'" );
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
                $this->ID =& $category_array[0][ "ID" ];
                $this->Name =& $category_array[0][ "Name" ];
                $this->Description =& $category_array[0][ "Description" ];
                $this->Parent =& $category_array[0][ "Parent" ];
                $this->SortMode =& $category_array[0][ "SortMode" ];
                $this->RemoteID =& $category_array[0][ "RemoteID" ];
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
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZTrade_Category ORDER BY Name" );
        
        for ( $i=0; $i < count($category_array); $i++ )
        {
            $return_array[$i] = new eZProductCategory( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the categories with the category given as parameter as parent.

      The categories are returned as an array of eZProductCategory objects.
    */
    function &getByParent( $parent, $sortby=name )
    {
        if ( get_class( $parent ) == "ezproductcategory" )
        {
            $this->dbInit();
        
            $return_array = array();
            $category_array = array();

            $parentID = $parent->id();
                 
            $this->Database->array_query( $category_array, "SELECT ID, Name FROM eZTrade_Category WHERE Parent='$parentID' ORDER BY Name" );
        
            for ( $i=0; $i < count($category_array); $i++ )
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
    function &path( $categoryID=0 )
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
      \static
    */
    function &getTree( $parentID=0, $level=0 )
    {
        $category = new eZProductCategory( $parentID );

        $categoryList = $category->getByParent( $category, true );
        
        $tree = array();
        $level++;
        foreach ( $categoryList as $category )
        {
            array_push( $tree, array( $return_array[] = new eZProductCategory( $category->id() ), $level ) );
            
            if ( $category != 0 )
            {
                $tree = array_merge( $tree, eZProductCategory::getTree( $category->id(), $level ) );
            }
            
        }

        return $tree;
    }

    
    /*!
      Returns the object ID to the category. This is the unique ID stored in the database.
    */
    function id()
    {
        $ret = $this->ID;
       
        return $ret;
    }

    
    /*!
      Returns the name of the category.
    */
    function &name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the remote ID of the category.
    */
    function remoteID()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->RemoteID;
    }

    /*!
      Returns the group description.
    */
    function &description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Description );
    }
    
    /*!
      Returns the parent if one exist. If not 0 is returned.
    */
    function &parent()
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
      Returns the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      3 - absolute placement      
    */
    function &sortMode()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       switch( $this->SortMode )
       {
           case 1 :
           {
               $SortMode = "time";
           }
           break;
           
           case 2 :
           {
               $SortMode = "alpha";
           }
           break;
           
           case 3 :
           {
               $SortMode = "alphadesc";
           }
           break;
           
           case 4 :
           {
               $SortMode = "absolute_placement";
           }
           break;
           
           default :
           {
               $SortMode = "time";
           }           
       }
       
       return $SortMode;
    }

    /*!
      Sets the name of the category.
    */
    function setName( &$value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the remote ID of the category.
    */
    function setRemoteID( &$value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->RemoteID = $value;
    }

    /*!
      Sets the description of the category.
    */
    function setDescription( &$value )
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
       else
       {
           $this->Parent = $value;
       }
    }

    /*!
      Sets the sort mode.

      1 - publishing date
      2 - alphabetic
      3 - alphabetic desc
      3 - absolute placement      
    */
    function setSortMode( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->SortMode = $value;
    }

    /*!
      \static
      Adds a product to the category.
      Can be used as a static function if $categoryid is supplied
    */
    function addProduct( &$value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezproduct" )
            $prodID = $value->id();
        else if ( is_numeric( $value ) )
            $prodID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->array_query( $qry, "SELECT Placement FROM eZTrade_ProductCategoryLink
                                             ORDER BY Placement DESC LIMIT 1", 0, 1 );
        $placement = count( $qry ) == 1 ? $qry[0]["Placement"] + 1 : 1;

        print( "her" );
        $query = "INSERT INTO eZTrade_ProductCategoryLink
                  SET CategoryID='$categoryid',
                      ProductID='$prodID',
                      Placement='$placement'";
        $db->query( $query );
    }

    /*!
      \static
      Removes a product from the category.
      Can be used as a static function if $categoryid is supplied
    */
    function removeProduct( &$value, $categoryid = false )
    {
        if ( get_class( $value ) == "ezproduct" )
            $prodID = $value->id();
        else if ( is_numeric( $value ) )
            $prodID = $value;
        else
            return false;

        if ( !$categoryid )
            $categoryid = $this->ID;

        $db =& eZDB::globalDatabase();
        $query = "DELETE FROM eZTrade_ProductCategoryLink
                  WHERE CategoryID='$categoryid' AND
                        ProductID='$prodID'";
        $db->query( $query );
    }

    /*!
      Returns the total number of products in a category.
    */
    function &productCount( $sortMode="time",
                        $fetchNonActive=false,
                        $fetchDiscontinued=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       switch( $sortMode )
       {
           case "time" :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
           break;

           case "alpha" :
           {
               $OrderBy = "eZTrade_Product.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $OrderBy = "eZTrade_Product.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $OrderBy = "eZTrade_ProductCategoryLink.Placement ASC";
           }
           break;
           
           default :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
       }       

       $nonActiveCode = $fetchNonActive ? "" : " eZTrade_Product.ShowProduct='true' AND";
       $discontinuedCode = "";
       if ( !$fetchDiscontinued )
           $discontinuedCode = " eZTrade_Product.Discontinued='false' AND";

       $this->Database->query_single( $products, "
                SELECT count( eZTrade_Product.ID ) AS Count
                FROM eZTrade_Product, eZTrade_ProductCategoryLink
                WHERE 
                eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                AND
                $nonActiveCode
                $discontinuedCode
                eZTrade_ProductCategoryLink.CategoryID='$this->ID'" );

       return $products["Count"];
    }

    /*!
      Returns every product to a category as a array of eZProduct objects.
    */
    function &products( $sortMode="time",
                        $fetchNonActive=false,
                        $offset=0,
                        $limit=50,
                        $fetchDiscontinued=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();

       switch( $sortMode )
       {
           case "time" :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
           break;

           case "alpha" :
           {
               $OrderBy = "eZTrade_Product.Name ASC";
           }
           break;

           case "alphadesc" :
           {
               $OrderBy = "eZTrade_Product.Name DESC";
           }
           break;

           case "absolute_placement" :
           {
               $OrderBy = "eZTrade_ProductCategoryLink.Placement ASC";
           }
           break;
           
           default :
           {
               $OrderBy = "eZTrade_Product.Published DESC";
           }
       }       
       
       $return_array = array();
       $product_array = array();

       if ( $fetchNonActive  == true )
       {
           $nonActiveCode = "";
       }
       else
       {
           $nonActiveCode = " eZTrade_Product.ShowProduct='true' AND";
       }
       $discontinuedCode = "";
       if ( !$fetchDiscontinued )
           $discontinuedCode = " eZTrade_Product.Discontinued='false' AND";
       $this->Database->array_query( $product_array, "
                SELECT eZTrade_Product.ID AS ProductID, eZTrade_Product.Name, eZTrade_Category.ID, eZTrade_Category.Name
                FROM eZTrade_Product, eZTrade_Category, eZTrade_ProductCategoryLink
                WHERE 
                eZTrade_ProductCategoryLink.ProductID = eZTrade_Product.ID
                AND
                $nonActiveCode
                $discontinuedCode
                eZTrade_Category.ID = eZTrade_ProductCategoryLink.CategoryID
                AND
                eZTrade_Category.ID='$this->ID'
                GROUP BY eZTrade_Product.ID ORDER BY $OrderBy LIMIT $offset,$limit" );
       
       for ( $i=0; $i < count($product_array); $i++ )
       {
           $return_array[$i] = new eZProduct( $product_array[$i]["ProductID"], false );
       }
       
       return $return_array;
    }
    
    /*!
      Returns every active product to a category as a array of eZProduct objects.
    */
    function &activeProducts( $sortMode="time",
                              $offset=0,
                              $limit=50 )
    {
       return $this->products( $sortMode, false, $offset, $limit );
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
    function &options()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $option_array = array();
       
       $this->Database->array_query( $option_array, "SELECT OptionID FROM eZTrade_CategoryOptionLink WHERE CategoryID='$this->ID'" );
       
       for ( $i=0; $i < count($option_array); $i++ )
       {
           $return_array[$i] = new eZOption( $option_array[$i]["OptionID"], false );
       }
       
       return $return_array;
    }

    /*!
      Moves the product placement with the given ID up.
    */
    function moveUp( $id )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZTrade_ProductCategoryLink
                                  WHERE ProductID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry["ID"] ) )
       {
           $linkID = $qry["ID"];
           
           $placement = $qry["Placement"];
           
           $db->query_single( $qry, "SELECT ID, Placement FROM eZTrade_ProductCategoryLink
                                    WHERE Placement<'$placement' AND
                                    eZTrade_ProductCategoryLink.CategoryID='$this->ID'
                                    ORDER BY Placement DESC LIMIT 1" );

           $newPlacement = $qry["Placement"];
           $listid = $qry["ID"];

           if ( is_numeric( $listid ) )
           {           
               $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
               $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );
           }           
       }
    }

    /*!
      Moves the product placement with the given ID down.
    */
    function moveDown( $id )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $db =& eZDB::globalDatabase();

       $db->query_single( $qry, "SELECT * FROM eZTrade_ProductCategoryLink
                                  WHERE ProductID='$id' AND CategoryID='$this->ID'" );

       if ( is_numeric( $qry["ID"] ) )
       {
           $linkID = $qry["ID"];
           
           $placement = $qry["Placement"];
           
           $db->query_single( $qry_2, "SELECT ID, Placement FROM eZTrade_ProductCategoryLink
                                    WHERE Placement>'$placement' AND eZTrade_ProductCategoryLink.CategoryID='$this->ID' ORDER BY Placement ASC LIMIT 1" );

           $newPlacement = $qry_2["Placement"];
           $listid = $qry_2["ID"];

           if ( is_numeric( $listid ) )
           {
               $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$placement' WHERE ID='$listid'" );
               $db->query( "UPDATE eZTrade_ProductCategoryLink SET Placement='$newPlacement' WHERE ID='$linkID'" );               
           }
       }       
    }


    /*!
      Check if there are a category where RemoteID == $id. Return the category if true.
    */
    function getByRemoteID( $id )
    {
        $this->dbInit();

        $category = false;
               
        $this->Database->array_query( $res, "SELECT ID FROM
                                            eZTrade_Category
                                            WHERE RemoteID='$id'" );

       if ( count( $res ) == 1 )
       {
           $category = new eZProductCategory( $res[0]["ID"] );
       }

       return $category;
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
    var $Parent;
    var $Description;
    var $OptionArray;
    var $SortMode;
    var $RemoteID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
