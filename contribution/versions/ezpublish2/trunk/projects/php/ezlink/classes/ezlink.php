<?
// 
// $Id: ezlink.php,v 1.25 2000/10/10 11:42:00 ce-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZLink
//! The eZLink class handles URL links.
/*!

  Example code:
  \code
  // Create a new link and set some values.
  $link = new eZLink();
  $link->setTitle( "ZEZ website" );
  $link->Description( "zez.org is a page dedicated to all kinds of computer programming." );
  $link->KeyWords( "code programing c++ php sql python" );
  $setModified( date() );
  $setAccepted( "Y" );
  $setUrl( "zez.org" );

  // Store the link to the datavase.
  $link->store();

  // Check if the url exist in the database.
  $link->checkUrl( "zez.org" );

  // Get all the links in a group.
  $link->getByGroup( $linkGroupID );

  // Get all the not accepted links.
  $link->getNotAccepted();

  \endcode
  
  \sa eZLinkGroup eZHit eZQuery
*/
  
class eZLink
{
    /*!
      Constructor
    */
    function eZLink( )
    {

    }

    /*!
      Stores a link to the database.
    */
    function store()
    {
        $this->dbInit();
       // Sets the created to the system clock
        $this->Created = date( "Y-m-d G:i:s" );        
        query( "INSERT INTO eZLink_Link SET
                ID='$this->ID',
                Title='$this->Title',
                Description='$this->Description',
                LinkGroup='$this->LinkGroup',
                KeyWords='$this->KeyWords',
                Created='$this->Created',
                Url='$this->Url',
                Accepted='$this->Accepted'" );
    }

    /*!
      Update to the database.
    */
    function update()
    {
        $this->dbInit();
        query( "UPDATE eZLink_Link SET
                Title='$this->Title',
                LinkGroup='$this->LinkGroup',
                KeyWords='$this->KeyWords',
                Url='$this->Url',
                Accepted='$this->Accepted'
                WHERE ID='$this->ID'" );
    }

    /*!
      Remove the link and the hits that belongs to the link.
    */
    function delete( )
    {
        $this->dbInit();
        query( "DELETE FROM eZLink_Hit WHERE Link='$this->ID'" );        
        query( "DELETE FROM eZLink_Link WHERE ID='$this->ID'" );
    }

    /*!
      Fetches out informasjon from the daatabase where ID=$id
    */
    function get ( $id )
    {
        $this->dbInit();
        if ( $id != "" )
        {
            array_query( $link_array, "SELECT * FROM eZLink_Link WHERE ID='$id'" );
            if ( count( $link_array ) > 1 )
            {
                die( "Feil: flere linker med samme ID ble funnet i databasen, dette skal ikke være mulig." );
            }
            else if ( count( $link_array ) == 1 )
            {
                $this->ID = $link_array[ 0 ][ "ID" ];
                $this->Title = $link_array[ 0 ][ "Title" ];
                $this->Description = $link_array[ 0 ][ "Description" ];
                $this->LinkGroup = $link_array[ 0 ][ "LinkGroup" ];
                $this->KeyWords = $link_array[ 0 ][ "KeyWords" ];
                $this->Created = $link_array[ 0 ][ "Created" ];
                $this->Modified = $link_array[ 0 ][ "Modified" ];
                $this->Accepted = $link_array[ 0 ][ "Accepted" ];
                $this->Url = $link_array[ 0 ][ "Url" ];

            }
        }
    }

    /*!
      Fetchs out the links where the linkgroup=$id. Fetchs only accepted links.
    */
    function getByGroup( $id )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE LinkGroup='$id' AND Accepted='Y' ORDER BY Title" );

        return $link_array;
    }

    /*!
      Fetches out the links that is not accepted.
    */
    function getNotAccepted( )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='N' ORDER BY Title" );

        return $link_array;
    }

    /*!
      Fetches out the last teen accpeted links.
    */
    function getLastTenDate( $limit, $offset )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='Y' ORDER BY Created DESC LIMIT $offset, $limit" );

        return $link_array;
    }

    /*!
      Fetches out the last teen accpeted links.
    */
    function getLastTen( $limit, $offset )
    {
        $this->dbInit();
        $link_array = 0;
        
        array_query( $link_array, "SELECT * FROM eZLink_Link WHERE Accepted='Y' ORDER BY Title DESC LIMIT $offset, $limit" );

        return $link_array;
    }

    /*!
      Fetches the links that matches the $query.
    */
    function getQuery( $query, $limit=20, $offset = 0 )
    {
        $this->dbInit();
        $link_array = 0;

        $query = new eZQuery( array( "KeyWords", "Title", "Description" ), $query );
        
        $query_str = "SELECT * FROM eZLink_Link WHERE (" .
             $query->buildQuery()  .
             ") AND Accepted='Y' ORDER BY Title";

        if ( $limit != -1 )
        {
//              $query_str .= "  LIMIT $offset, $limit";
        }

        array_query( $link_array, $query_str );

//          print( "<br>" . $query_str . "<br>". count( $link_array ) );
        
        return $link_array;
    }

    /*!
      Fetches all the links.
    */
    function getAll()
    {
        $this->dbInit();
        $group_array = 0;

        array_query( $group_array, "SELECT * FROM eZLink_Link ORDER BY Title" );

        return $group_array;
    }

    /*!
      Check if the url exists.
    */
    function checkUrl( $url )
    {
        $this->dbInit();

        array_query( $url_array, "SELECT url FROM eZLink_Link WHERE url='$url'" );

        return count( $url_array );
    }    

    /*!
      Sets the link title.
    */
    function setTitle( $value )
    {
        $this->Title = $value;
    }

    /*!
      Sets the link description
    */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the linkgroupID.
    */
    function setLinkGroup( $value )
    {
        $this->LinkGroup = ( $value );
    }

    /*!
      Sets the link keywords.
    */    
    function setKeyWords( $value )
    {
        $this->KeyWords = ( $value );
    }

    /*!
       Sets the modified date of the link.
    */
    function setModified( $value )
    {
        $this->Modified = ( $value );
    }

    /*!
      Sets if the link is accepted.
    */
    function setAccepted( $value )
    {
        $this->Accepted = ( $value );
    }

    /*!
      Sets the link URL.
    */
    function setUrl( $value )
    {
        $this->Url = ( $value );
    }

    /*!
      Returns the link title.
    */
    function title()
    {
        return $this->Title;
    }


    /*!
      Returns the link description.
    */
    function description()
    {
        return $this->Description;
    }

    /*!
      Returns the linkgroupID.
    */
    function linkGroup()
    {
        return $this->LinkGroup;
    }

    /*!
      Returns the link keywords.
    */
    function keyWords()
    {
        return $this->KeyWords;
    }

    /*!
      Returns the date when the link was created.
    */
    function created()
    {
        return $this->Created;
    }

    /*!
      Returns the date when the link was modified.
    */
    function modified()
    {
        return $this->Modified;
    }

    /*!
      Returns if the link is Accepted.
    */
    function accepted()
    {
        return $this->Accepted;
    }

    /*!
      Retruns the url of the link.
    */
    function url()
    {
        return $this->Url;
    }

    /*!
      Returns the id of the link.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      \private
      \static
      
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        include_once( "classes/INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "eZLinkMain", "Server" );
        $DATABASE = $ini->read_var( "eZLinkMain", "Database" );
        $USER = $ini->read_var( "eZLinkMain", "User" );
        $PWD = $ini->read_var( "eZLinkMain", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    var $ID;
    var $Title;
    var $Description;
    var $LinkGroup;
    var $KeyWords;
    var $Created;
    var $Modified;
    var $Accepted;
    var $Url;
    var $url_array;
}
?>
