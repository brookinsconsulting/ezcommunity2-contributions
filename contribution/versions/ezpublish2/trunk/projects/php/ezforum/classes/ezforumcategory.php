<?
// 
// $Id: ezforumcategory.php,v 1.15 2000/10/11 11:43:34 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//include_once( "$DOCROOT/classes/ezdb.php" );

//!! eZForum
//! The eZForumCategory class handles forum categories.
/*!
  
  \sa eZForumForum
*/

class eZForumCategory
{
    /*!
      Constructs a new eZForumCategory object.
    */
    function eZForumCategory( $id="", $fetch=true )
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
        }
    }

    /*!
      Stores a eZForumCategory object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZTrade_Order SET
		                         UserID='$this->UserID',
		                         AddressID='$this->AddressID',
		                         ShippingCharge='$this->ShippingCharge'
                                 " );

            $this->ID = mysql_insert_id();

            // store the status
            $statusType = new eZOrderStatusType( );
            $statusType = $statusType->getByName( "Initial" );

            $status = new eZOrderStatus();
            $status->setType( $statusType );

            $status->setOrderID( $this->ID );

//              $user = eZUser::currentUser();
//              print( $user->id() );
            
            $status->setAdmin( $user );
            $status->store();            

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZTrade_Order SET
		                         UserID='$this->UserID',
		                         AddressID='$this->AddressID',
		                         ShippingCharge='$this->ShippingCharge'
                                 WHERE ID='$this->ID'
                                 " );

            $this->State_ = "Coherent";
        }
        
        return true;
    }

    /*!
      Deletes a eZOrder object from the database.
    */
    function delete()
    {
        $this->dbInit();

        $items = $this->items();

        if  ( $items )
        {
            $i = 0;
            foreach ( $items as $item )
            {
                $item->delete();
            }
        }

        $this->Database->query( "DELETE FROM eZTrade_OrderStatus WHERE OrderID='$this->ID'" );

        
        $this->Database->query( "DELETE FROM eZTrade_Order WHERE ID='$this->ID'" );
            
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
            $this->Database->array_query( $cart_array, "SELECT * FROM eZTrade_Order WHERE ID='$id'" );
            if ( count( $cart_array ) > 1 )
            {
                die( "Error: Cart's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cart_array ) == 1 )
            {
                $this->ID = $cart_array[0][ "ID" ];
                $this->UserID = $cart_array[0][ "UserID" ];
                $this->AddressID = $cart_array[0][ "AddressID" ];
                $this->ShippingCharge = $cart_array[0][ "ShippingCharge" ];

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
      
    */
    function newCategory()
    {
        unset( $this->Id );
    }
    
    
        
    /*!
      
    */
    function get( $Id )
    {
        $this->openDB();
            
        $query_id = mysql_query("SELECT Name, Description, Private FROM ezforum_CategoryTable WHERE Id='$Id'")
             or die("eZforumCategory::get($id) failed, dying...");

        if ( mysql_num_rows( $query_id ) == 1 )
        {
            $this->Id = $Id;
            $this->Name = mysql_result($query_id, 0, "Name" );
            $this->Description = mysql_result($query_id, 0, "Description" );
            $this->Private = mysql_result($query_id, 0, "Private" );
        }
    }
        
    /*!
      
    */
    function getAllCategories()
    {
        $this->openDB();

        $query_id = mysql_query( "SELECT * FROM ezforum_CategoryTable" )
             or die("eZforumCategory::getAllCategories() failed, dying...");
            
        for ($i = 0;$i < mysql_num_rows( $query_id ); $i++ )
        {
            $returnArray[$i] = mysql_fetch_array($query_id);   
        }
        return $returnArray;
    }
        
    /*!
      
    */
    function store()
    {
        $this->openDB();
            
        $this->Name = addslashes($this->Name);
        $this->Description = addslashes($this->Description);
        $this->Private = addslashes($this->Private);            

        if ( $this->Id )
        {
            $query_id = mysql_query("UPDATE ezforum_CategoryTable SET Name='$this->Name',
                                                             Description='$this->Description',
                                                             Private='$this->Private'
                                         WHERE Id='$this->Id'")
                 or die("store() near UPDATE...");
        }
        else
        {
            $query_id = mysql_query("INSERT INTO ezforum_CategoryTable(Name, Description, Private)
                                                     VALUES('$this->Name', '$this->Description', '$this->Private')")
                 or die("store() near INSERT...");
            return mysql_insert_id();
        }
    }
        
    /*!
      
    */
    function delete($Id)
    {
        $this->openDB();
        
        mysql_query("DELETE FROM ezforum_CategoryTable WHERE Id='$Id'")
            or die("delete($Id)");
    }
        
    /*!
      
    */
    function id()
    {
        return $this->Id;
    }
        
    /*!
      
    */
    function setName($newName)
    {
        $this->Name = $newName;
    }

    /*!
      
    */
    function name()
    {
        return $this->Name;
    }
        
    /*!
      
    */
    function setDescription($newDescription)
    {
        $this->Description = $newDescription;
    }
        
    /*!
      
    */
    function description()
    {
        return $this->Description;
    }
        
    /*!
      
    */
    function setPrivate($newPrivate)
    {
        $this->Private = $newPrivate;
    }
        
    /*!
      
    */
    function private()
    {
        return $this->Private;
    }

    /*!
      
    */
    function categoryForumInfo($Id)
    {
        $this->openDB();
    
        $query_id = mysql_query("SELECT ezforum_ForumTable.Name AS ForumName,
                                ezforum_CategoryTable.Name AS CategoryName
                                FROM ezforum_ForumTable, ezforum_CategoryTable
                                WHERE ezforum_CategoryTable.Id = ezforum_ForumTable.CategoryId
                                AND ezforum_ForumTable.Id = '$Id'")
             or die("categoryForumInfo()");
        
        return mysql_fetch_array($query_id);
    }

    /*!
      \private
      Opens the database for read and write.
    */
    function openDB( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }
    
    var $Id;
    var $Name;
    var $Description;
    var $Private;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
?>
