<?
// 
// $Id: eznewsitem.php,v 1.1 2000/09/15 10:50:30 pkej-cvs Exp $
//
// Definition of eZNewsItem class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <14-Sep-2000 10:46:38 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsItem handles eZNews items.
/*!
  An eZNewsItem object can be of many types. It is stored in a hiearchy.
  
  Example of usage:

  \code
    $item=new eZNewsItem();
    $item->setName("New item");
    $item->setItemTypeID('1');

    if ( $item->store() == true )
    {
        echo "The object was stored. The object contains this data: <P>";
        echo $item->objectHeader();
        echo $item->objectInfo();
    }
    else
    {
        echo "<br>The object wasn't store, the number one reason given was: <p>";
        foreach ( $item->InvariantError as $something )
        {
            echo $something . "<br>";       
        }
    }

    // Show all items

    $items = $item->getAll("name", "forward");

    echo "<h1>Here are all items in the db.</h1>";
    echo $item->objectHeader();

    foreach( $items as $something )
    {
        print( $something->objectInfo() );
    }


  \endcode

  \sa 
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezsession.php" );       

class eZNewsItem
{
    /*!
      Constructs a new eZNewsItem object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZNewsItem( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->noLog = false;        

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
      Stores a eZNewsItem object to the database.

      Returns the ID to the stored News item.
    */
    function store( $update=false, $arguments=0 )
    {
        $returnValue=false;
        
        if( $this->checkInvariant() == true )
        {
            $GLOBALS["AuthenticatedSession"];
            $UserID = 0;
            $reason="created";
            $returnValue=true;

            $session = new eZSession();

            if( $session->get( $AuthenticatedSession ) == 0 )
            { 
                $UserID = $session->userID();
            }

            $this->dbInit();

            $query=sprintf($this->SQL["insert_item"], $this->Name);

            $this->Database->query( $query );
            $this->ID = mysql_insert_id();

            if($this->noLog == false)
            {
                $query="
                    SELECT ID FROM eZNews_ChangeType
                    WHERE Name='$reason'
                ";

                $this->ChangeTypeID = $this->Database->query( $query );

                $query="
                    INSERT INTO eZNews_ChangeTicket SET
                    ChangeType='$this->ChangeTypeID',
                    ChangeText='Class eZNewsItem $reason this item',
                    ChangedBy='$UserID'
                ";

                $this->Database->query( $query );
                $this->ChangeTicketID = mysql_insert_id();

                $query="
                    INSERT INTO eZNews_ItemLog SET
                    ItemID='$this->ID',
                    ChangeTicketID='$this->ChangeTicketID'
                ";

                $this->Database->query( $query );
            }
        }
        
        return $returnValue;
    }



    function getChangeLog( )
    {
        $this->dbInit();
        
        $return_array = array();

        $query="
        SELECT Ticket.ID FROM eZNews_ChangeTicket AS Ticket, eZNews_ItemLog AS Log
        WHERE
            Log.ItemID LIKE $this->ID
        ORDER BY Ticket.ChangedAt
        ";

        $this->Database->array_query( $category_array, $query );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZNewsChangeType( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    function getChangeTypes( )
    {
        $this->dbInit();
        
        $return_array = array();
        
        $query="
        SELECT ID FROM eZNews_ChangeType
        ORDER BY Name
        ";

        $this->Database->array_query( $category_array, $query );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZNewsChangeType( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $returnValue=false;
        $this->dbInit();
        if ( $id != "" )
        {
            $query = "
                SELECT * FROM eZNews_Item
                WHERE ID='$id'
            ";
            
            $this->Database->array_query( $newsitem_array, $query );
            $rowsFound = count( $newsitem_array );
            switch ( $rowsFound )
            {
                case (0):
                    $this->State_ = "Don't Exist";
                    break;
                case (1):
                    $this->ID = $newsitem_array[0][ "ID" ];
                    $this->Name = $newsitem_array[0][ "Name" ];
                    $this->ItemTypeID = $newsitem_array[0][ "ItemTypeID" ];
                    $this->isVisible = $newsitem_array[0][ "isVisible" ];
                    $this->State_ = "Coherent";
                    $returnValue="true";
                    break;
                default:
                    die( "Error: News item's with the same ID was found in the database. This shouldent happen." );
                    break;
            }
                 
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $returnValue;
    }



    /*!
      Returns all the news items found in the database.

      The categories are returned as an array of eZNewsItem objects.
      
      $orderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>id
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAll( $orderBy = "name", $direction="forward" )
    {
        $this->dbInit();
        
        $return_array = array();
        $newsitem_array = array();
        
        $query=sprintf( "SELECT ID FROM eZNews_Item %s %s", $this->orderBy["$orderBy"], $this->orderBy["$direction"]);
        
        $this->Database->array_query( $newsitem_array, $query );
        
        for ( $i=0; $i<count($newsitem_array); $i++ )
        {
            $return_array[$i] = new eZNewsItem( $newsitem_array[$i]["ID"], true );
        }
        
        return $return_array;
    }



    /*!
      Returns all the news items found in the database which are visible.

      The news items are returned as an array of eZNewsItem objects.

      $orderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>id
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllVisible( $orderBy = "name", $direction="forward" )
    {
        $this->dbInit();
        
        $return_array = array();
        $newsitem_array = array();
        
        $query="
            SELECT ID FROM eZNews_Item
            WHERE isVisible = 'Y'
            ORDER BY Name
        ";
        
        $this->Database->array_query( $newsitem_array, $query );
        
        for ( $i=0; $i<count($newsitem_array); $i++ )
        {
            $return_array[$i] = new eZNewsItem( $newsitem_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns all the news items found in the database which aren't visible.

      The news items are returned as an array of eZNewsItem objects.

      $orderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>id
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllNonVisible( $orderBy = "name", $direction="forward" )
    {
        $this->dbInit();
        
        $return_array = array();
        $newsitem_array = array();
        
        $query="
            SELECT ID FROM eZNews_Item
            WHERE isVisible = 'N'
            ORDER BY Name
        ";
        
        $this->Database->array_query( $newsitem_array, $query );
        
        for ( $i=0; $i<count($newsitem_array); $i++ )
        {
            $return_array[$i] = new eZNewsItem( $newsitem_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns all the parent items to this item.

      The news items are returned as an array of eZNewsItem objects.
      
      $orderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>id
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllParents( $orderBy = "name", $direction="forward" )
    {
        $this->dbInit();
        
        $return_array = array();
        $newsitem_array = array();
        
        $query="
            SELECT ParentID FROM eZNews_Hiearchy
            WHERE ItemID = '$this->ID'
            ORDER BY Name
        ";
        
        $this->Database->array_query( $newsitem_array, $query );
        
        for ( $i=0; $i<count($newsitem_array); $i++ )
        {
            $return_array[$i] = new eZNewsItem( $newsitem_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns the canonical parent item to this item.

      The news items are returned as an array of eZNewsItem objects.
      
      $orderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>id
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getCanonicalParents( $orderBy = "name", $direction="forward" )
    {
        $this->dbInit();
        
        $return_array = array();
        $newsitem_array = array();
        
        $query="
            SELECT ParentID FROM eZNews_Hiearchy
            WHERE ItemID = '$this->ID'
            AND isCanonical = 'Y'
            ORDER BY Name
        ";
        
        $this->Database->array_query( $newsitem_array, $query );
        
        for ( $i=0; $i<count($newsitem_array); $i++ )
        {
            $return_array[$i] = new eZNewsItem( $newsitem_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }



    /*!
      Returns the object ID of the news item. This is the unique ID stored in the database.
    */
    function id()
    {
        $returnValue = 0;
        if ( $this->State_ != "New" )
        {
            $returnValue=$this->ID;
        }
       
        return $returnValue;
    }



    /*!
      Returns the name of the news item.
    */
    function name()
    {
        if ( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->Name;
    }



    /*!
      Sets the name of the news item.
    */
    function setName( $value )
    {
        if ( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->Name = $value;
        
        if ( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }


    /*!
      Sets the type of the news item.
    */
    function setItemTypeID( $value )
    {
        if ( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        $this->ItemTypeID = $value;
        
        if ( $this->State_ != "New" )
        {
            $this->State_ == "Altered";
        }
    }


    function checkInvariant()
    {
        $returnValue=false;
        unset( $this->InvariantError ); 
               
        if( !isset( $this->Name ) )
        {
            $this->InvariantError[]="Object is missing: Name";
        }
        
        if( !isset( $this->ItemTypeID ) )
        {
            $this->InvariantError[]="Object is missing: ItemTypeID";
        }

        if( !isset( $this->InvariantError ) )
        {
            $returnValue = true;
        }
        
        return $returnValue;        
    }

    function objectInfo()
    {
        if( $this->checkInvariant() == true )
        {
            printf("<TABLE WIDTH=100%%><TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=3%%>%s</TD></TR></TABLE>",
                    $this->ID,
                    $this->Name,
                    $this->ItemTypeID,
                    $this->isVisible
                   );
            if( $this->noLog == false )
            {
                // here comes a print statement for sub objects in the log.
            }
        }
        else
        {
            foreach ( $this->InvariantError as $something )
            {
                echo $something . "<br>";       
            }
        }
    }
    
    function objectHeader()
    {
        if( $this->checkInvariant() == true )
        {
            printf("<TABLE WIDTH=100%%><TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=3%%>%s</TD></TR></TABLE><hr>",
                    "ID",
                    "Name",
                    "TypeID",
                    "vis"
                   );
        }
    }

    /*!
      Private function.
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "eZNewsMain" );
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $ItemTypeID;
    var $Name;
    var $isVisible = "N";
    
    /// Error variables
    
    var $InvariantError = array();
    
    /// SQL Queries and such.
    
    var $SQL = array(
        "insert_item" => "INSERT INTO eZNews_Item SET ItemTypeID=0, Name='%s', isVisible='N'"
        );

    var $orderBy = array(
        "none" => "",
        "name" => "ORDER BY Name",
        "id" => "ORDER BY ID",
        "visibility" => "ORDER BY isVisible",
        "type" => "ORDER BY ItemTypeID",
        "forward" => "ASC",
        "reverse" => "DESC",
        );
    
    /// Preferences
    
    /// Turn on/off logging of changes to articles. Default is on.
    var $noLog;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
