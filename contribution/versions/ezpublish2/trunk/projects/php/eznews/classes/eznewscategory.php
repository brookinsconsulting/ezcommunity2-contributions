<?
// 
// $Id: eznewscategory.php,v 1.1 2000/09/28 08:27:14 pkej-cvs Exp $
//
// Definition of eZNewsCategory class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <20-Sep-2000 16:44:53 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//



//!! eZNews
//! eZNewsCategory handles eZNews categories.
/*!
 */
 
include_once( "classes/ezdb.php" );
include_once( "eznews/classes/eznewsitem.php" );

class eZNewsCategory extends eZNewsItem
{
    function eZNewsCategory( $inID=-1, $fetch=true )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsCategory::eZNewsCategory( inID = $inID, fetch = $fetch )";
        }

        eZNewsItem::eZNewsItem( $inID, $fetch );
    }
    
    function store( $update = 'create' )
    {
        eZNewsItem::store();
        
        $query =
        "
            INSERT INTO
                eZNews_Article
            SET
                ID                      = '%s',
                PublicDescriptionID     = '%s',
                PrivateDescriptionID    = '%s'
        ";
        
        $query = sprintf( $query, $this->ID, $this->PublicDescriptionID, $this->PrivateDescriptionID );
        
        $this->Database->query( $query );
    }
    
    function get( $inID=-1 )
    {
        if( $GLOBAL["NEWSDEBUG"] == true )
        {
            echo "eZNewsCategory::get( inID = $inID ), State_ = " . $this->State_ . " <br>";
        }
        unset( $returnError );
        $returnError = array();
        unset( $categoryArray );
        $categoryArray = array();

        if( $this->State_ != "Coherent" && $inID != -1)
        {
            $this->dbInit();
            
            $query =
            "
                SELECT
                    *
                FROM
                    eZNews_Category
                WHERE
                    ID = '%s'
            ";
            
            $query=sprintf( $query, $inID );
            $this->Database->array_query( $categoryArray, $query );
            $rowsFound = count( $categoryArray );
            switch ( $rowsFound )
            {
                case (0):
                    $this->State_ = "Don't Exist";
                    break;
                case (1):
                    $this->ID                   = $categoryArray[0][ "ID" ];
                    $this->PublicDescriptionID  = $categoryArray[0][ "PublicDescriptionID" ];
                    $this->PrivateDescriptionID = $categoryArray[0][ "PrivateDescriptionID" ];
                    $returnError                = eZNewsItem::get( $this->ID );
                    break;
                default:
                    die( "Error: Category item's with the same ID was found in the database. This shouldent happen." );
                    break;
            }
            
        
        }
        else if( $this->State_ = -1 )
        {
            $this->State_ = "Dirty";
            $returnError[] = "State changed";
        }

        return $returnError;
    }
    
    function getAllChildrenCategories( $inOrderBy = "name", $direction = "forward", $fetch=true )
    {
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();
        
        $query =
        "
            SELECT
                Item.ID AS ID,
                Item.Name AS Name
            FROM
                eZNews_Hiearchy AS Hier,
                eZNews_Item AS Item,
                eZNews_ItemType AS Type
            WHERE
                Item.ItemTypeID = Type.ID
            AND
                Type.Name = 'Category'
            AND
                Hier.ParentID = '%s'
            AND
                Item.ID = Hier.ItemID
            %s
        ";
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
        
        $query=sprintf( $query, $this->ID, $orderBy );

        $this->Database->array_query( $itemArray, $query );
        
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsCategory( $itemArray[$i][ "ID" ], $fetch );
       }
        
        return $returnArray;
    }

    function getAllParentCategories( $orderBy = "name", $direction = "forward", $fetch = true )
    {
        $this->dbInit();
        
        $returnArray = array();
        $itemArray = array();

        $query =
        "
            SELECT
                Item.ID AS ID,
                Item.Name AS Name
            FROM
                eZNews_Hiearchy AS Hier,
                eZNews_Item AS Item,
                eZNews_ItemType AS Type
            WHERE
                Item.ItemTypeID = Type.ID
            AND
                Type.Name = 'Category'
            AND
                Hier.ItemID = '%s'
            AND
                Item.ID = Hier.ParentID
            %s
        ";
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );

        $query=sprintf( $query, $this->ID, $orderBy );
        $this->Database->array_query( $itemArray, $query );
        for( $i = 0; $i < count( $itemArray ); $i++ )
        {
            $returnArray[$i] = new eZNewsCategory( $itemArray[$i][ "ID" ], $fetch );
        }
        
        return $returnArray;
    }
        
    function makeRoot( $type="Root", $fetch = true )
    {
        $returnError;
        
        if( $this->State_ != "Altered" )
        {
            $queryResults = array();
            $this->dbInit();

            $query =
            "
                SELECT
                    Item.ID AS ID,
                    Item.Name AS Name
                FROM
                    eZNews_Item AS Item,
                    eZNews_ItemType AS Type
                WHERE
                    Item.Name='%s'
                AND
                    Type.Name = 'Category'
                AND
                    Type.ID = Item.ItemTypeID
            ";
            
            $query=sprintf( $query, $type );

            $this->Database->array_query( $queryResults, $query );
            $this->ID = $queryResults[0][ "ID" ];
            $this->get( $this->ID, true );
            
        }
        
        return $returnError;
    }
    
    function getCanonicalParentCategories( $inOrderBy = "name", $direction = "forward", $fetch = true )
    {
        $this->dbInit();
        
        $i = 0;
        $count = 1;
        $id = $this->ID;
        
        $orderBy = $this->createOrderBy( $inOrderBy, $direction );
            
        while( $count == 1 )
        {
            $query =
            "
                SELECT
                    Item.ID AS ID,
                    Item.Name AS Name
                FROM
                    eZNews_Hiearchy AS Hier,
                    eZNews_Item AS Item
                WHERE
                    Item.ID = Hier.ParentID
                AND
                    Hier.ItemID = '%s'
                AND
                    Hier.isCanonical = 'Y'
                %s
            ";
            
            $query=sprintf( $query, $id, $this->OrderBy[ "$orderBy" ], $this->OrderBy[ "$direction" ]);
            $this->Database->array_query( $newsitemArray, $query );
            $count=count( $newsitemArray );
            
            if( $count == 1 )
            {            
                $returnArray[$i] = new eZNewsCategory( $newsitemArray[0][ "ID" ], $fetch );
                $id = $newsitemArray[0][ "ID" ];
            }
            $i++;
        }
        return $returnArray;
    }
    
    function template(  )
    {
        empty( $returnError );
        
        return $returnError;
    }
    
    /*!
      Sets the news item ID of the public description of this category.
    */
    function setPublicDescriptionID( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->PublicDescriptionID = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }

    /*!
      Sets the news item ID of the private description of this category.
    */
    function setPrivateDescriptionID( $value )
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->PrivateDescriptionID = $value;
        
        if( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }
    
    /*!
      Returns the  news item ID of the public description of this category.
    */
    function publicDescriptionID()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->PublicDescriptionID;
    }
    
    /*!
      Returns the  news item ID of the private description of this category.
    */
    function privateDescriptionID()
    {
        if( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->PrivateDescriptionID;
    }
    
    function checkInvariant()
    {
        $returnValue=false;
        unset( $this->InvariantError ); 
               
        if( !isset( $this->PublicDescriptionID ) )
        {
            $this->InvariantError[]="Object is missing: PublicDescriptionID";
        }
        
        if( !isset( $this->PrivateDescriptionID ) )
        {
            $this->InvariantError[]="Object is missing: PrivateDescriptionID";
        }

        eZNewsItem::checkInvariant();
        
        if( !isset( $this->InvariantError ) )
        {
            $returnValue = true;
        }
        
        return $returnValue;        
    }
    
    var $PublicDescriptionID = 0;
    var $PrivateDescriptionID = 0;
    
};
?>
