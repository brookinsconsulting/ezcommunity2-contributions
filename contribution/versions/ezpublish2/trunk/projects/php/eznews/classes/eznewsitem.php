<?
// 
// $Id: eznewsitem.php,v 1.4 2000/09/15 14:03:46 pkej-cvs Exp $
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

    Examples:
  
    \code
    // Example 1: Creating an object:

    $item=new eZNewsItem();
    $item->setName("New item");
    $item->setItemTypeID('1');
    
    // Example 2: Storing an object:
    
    $returnMessage = $item->store();
    
    if ( empty( $returnMessage ) )
    {
        echo "The object was stored. The object contains this data: <P>";
        echo $item->objectHeader();
        echo $item->objectInfo();
        echo $item->objectFooter();
    }
    else
    {
        echo "<br>The object wasn't stored, the reason(s) given was/were: <p>";
        foreach ( $item->InvariantError as $something )
        {
            echo $something . "<br>";       
        }
    }
    
    // Example 3: Listing all objects of this type:
    
    // Show all items

    $items = $item->getAll("name", "forward");

    echo "<h1>Here are all items in the db.</h1>";
    echo $item->objectHeader();

    foreach( $items as $something )
    {
        print( $something->objectInfo() );
    }
    echo $item->objectFooter();
  \endcode

  \sa 
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezsession.php" );       
include_once( "eznews/classes/eznewschangetype.php" );       
include_once( "eznews/classes/eznewsitemtype.php" );       

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
    function store( $update='create' )
    {
        unset($errorMessage);
        
        if( $this->checkInvariant() == true && isset( $this->InsertItemType["$update"] ) )
        {
            $reason=$this->InsertItemType["$update"];
            if ( $this->OverrideCreator == false )
            {
                $GLOBALS["AuthenticatedSession"];
                $this->CreatedBy = 0;
                $returnValue=true;

                $session = new eZSession();

                if( $session->get( $AuthenticatedSession ) == 0 )
                { 
                    $this->CreatedBy = $session->userID();
                }
            }

            $this->dbInit();

            $query=sprintf($this->SQL["insert_item"], $this->Name, $this->CreatedBy);

            $this->Database->query( $query );
            $this->ID = mysql_insert_id();
            $this->get($this->ID);

            if($this->Log == true)
            {
                $query=sprintf($this->SQL["get_change_type"], $reason);
                $result = $this->Database->query( $query );
                $row = mysql_fetch_row( $result );
                $this->ChangeTypeID = $row[0];

                $query = sprintf($this->SQL["create_change_ticket"], $this->ChangeTypeID, $reason, $this->CreatedBy);

                $this->Database->query( $query );
                $this->ChangeTicketID = mysql_insert_id();

                $query = sprintf($this->SQL["create_log_entry"], $this->ID, $this->ChangeTicketID );
                $this->Database->query( $query );
            }
        }
        else
        {
            $errorMessage = "Perhaps the invariant check failed, or perhaps you supplied a wrong argument ($reason)?";
        }
        
        return $errorMessage;
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
                    $this->IsVisible = $newsitem_array[0][ "isVisible" ];
                    $this->CreatedBy = $newsitem_array[0][ "CreatedBy" ];
                    $this->CreatedAt = $newsitem_array[0][ "CreatedAt" ];
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
      
      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
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
        
        $query=sprintf( "SELECT ID FROM eZNews_Item %s %s", $this->OrderBy["$orderBy"], $this->OrderBy["$direction"]);
        
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

      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllVisible( $OrderBy = "name", $direction="forward" )
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

      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllNonVisible( $OrderBy = "name", $direction="forward" )
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
      
      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getAllParents( $OrderBy = "name", $direction="forward" )
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
      
      $OrderBy can be:
      <ul>
      <li>name
      <li>visibility
      <li>type
      <li>creatorID
      <li>date
      </ul>
      $direction can be:
      <ul>
      <li>forward
      <li>reverse
      </ul>
    */
    function getCanonicalParents( $OrderBy = "name", $direction="forward" )
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
      Returns the name of the creator
    */
    function createdBy()
    {
        if ( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        return $this->CreatedBy;
    }

    /*!
        Allow creator override.
     */
    function overrideCreator()
    {
        $this->OverrideCreator = true;
    }

    /*!
        Disable creator override.
     */
    function NoOverrideCreator()
    {
        $this->OverrideCreator = false;
    }

    /*!
        Start logging changes.
     */
    function startLog()
    {
        $this->Log = true;
    }

    /*!
        Stop logging changes.
     */
    function stopsLog()
    {
        $this->Log = false;
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

    /*!
      Sets the user id of creator.
    */
    function setCreatedBy( $value )
    {
        $returnValue = false;
        
        if ( $this->State_ == "Dirty" )
        {
            $this->get( $this->ID );
        }
        
        if ( $this->OverrideCreator == true )
        {
            $this->CreatedBy = $value;
            $returnValue = true;
        
            if ( $this->State_ != "New" )
            {
                $this->State_ == "Altered";
            }
        }
        
        return $returnValue;
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

        if( !isset( $this->CreatedBy ) && $this->OverrideCreator == true)
        {
            $this->InvariantError[]="Object is missing: CreatedBy";
        }

        if( !isset( $this->InvariantError ) )
        {
            $returnValue = true;
        }
        
        return $returnValue;        
    }

    function objectInfo()
    {
        $output;
        if( $this->checkInvariant() == true )
        {
            $output = sprintf("<TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=3%%>%s</TD></TR>",
                    $this->ID,
                    $this->Name,
                    $this->ItemTypeID,
                    $this->CreatedBy,
                    $this->CreatedAt,
                    $this->IsVisible
                   );
            if( $this->Log == true )
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
        
        return $output;
    }
    
    function objectHeader()
    {
        return sprintf("<TABLE WIDTH=100%%><TR><TD WIDTH=5%%>%s</TD><TD>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=5%%>%s</TD><TD WIDTH=10%%>%s</TD><TD WIDTH=3%%>%s</TD></TR></TABLE><hr><TABLE WIDTH=100%%>",
                "ID",
                "Name",
                "TypeID",
                "CreatedBy",
                "CreatedAt",
                "vis"
               );
    }
    function objectFooter()
    {
        return sprintf("</TABLE>");
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
    var $IsVisible = "N";
    var $CreatedBy;
    var $CreatedAt;
    
    /// Error variables
    
    var $InvariantError = array();
    
    /// SQL Queries and such.
    
    var $SQL = array(
        "insert_item" => "INSERT INTO eZNews_Item SET ItemTypeID='0', Name='%s', isVisible='N', CreatedBy='%s'",
        "get_change_type" => "SELECT ID FROM eZNews_ChangeType WHERE Name='%s'",
        "create_change_ticket" => "INSERT INTO eZNews_ChangeTicket SET ChangeTypeID='%s', ChangeText='Class eZNewsItem %s this item', ChangedBy='%s'",
        "create_log_entry" => "INSERT INTO eZNews_ItemLog SET ItemID='%s', ChangeTicketID='%s'"
        );

    var $InsertItemType = array(
        "other" => "other",
        "create" => "created",
        "update" => "updated",
        "draft" => "drafted",
        "refuse" => "refused",
        "publish" => "published",
        "translate" => "translated",
        "retract" => "retracted",
        "delete" => "deleted"        
        );

    var $OrderBy = array(
        "none" => "",
        "name" => "ORDER BY Name",
        "id" => "ORDER BY ID",
        "visibility" => "ORDER BY isVisible",
        "type" => "ORDER BY ItemTypeID",
        "authorID" => "ORDER BY CreatedBy",
        "date" => "ORDER BY CreatedAt",
        "forward" => "ASC",
        "reverse" => "DESC",
        );
    
    /// Preferences
    
    /// Turn on/off logging of changes to articles. Default is on.
    var $Log = true;
    var $OverrideCreator = false;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has a database connection, false if not.
    var $IsConnected;
}

?>
