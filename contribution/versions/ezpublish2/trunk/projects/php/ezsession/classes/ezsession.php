<?
// 
// $Id: ezsession.php,v 1.13 2000/11/22 14:58:28 bf-cvs Exp $
//
// Definition of eZSession class
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Sep-2000 15:21:18 bf>
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
//!! eZSession
//! eZSession handles sessions and session variables.
/*!

  \code
  // Create a new session, store it to the database and set a cookie.
  $session = new eZSession( );
  $session->store();

  // get the session from the client
  // The page must reload before the session cookie is accessable.
  $session->fetch();

  // set a session variable
  $session->setVariable( "CartID", "422" );

  // fetch the CartID session variable
  $cartID = $session->variable( "CartID" );

  // check if the variable exists and print out the contents
  if ( $cartID )
  {
      print( "You have a shopping cart<br>" );
      print( "And the ID is: " . $cartID );
  }
  \endcode

*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );

class eZSession
{
    /*!
      Creates a new eZSession object.
    */
    function eZSession( $id="", $fetch=true  )
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
      Stores a product to the database and sets a cookie to identify the session later.
    */
    function store()
    {
        $this->dbInit();

        // set the cookie
        $this->Hash = md5( microTime() );

        setcookie ( "eZSession", $this->Hash, 0, "/",  "", 0 )
            or die( "Error: could not set cookie." );        
        
        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZSession_Session SET
                                 Created=now(),
                                 LastAccessed=now(),
                                 SecondLastAccessed=now(),
		                         Hash='$this->Hash'
                                 " );

            $this->ID = mysql_insert_id();

            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZSession_Session SET
                                 Created=Created,
                                 SecondLastAccessed=LastAccessed,
                                 LastAccessed=now(),
		                         Hash='$this->Hash'
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
            $this->Database->array_query( $session_array, "SELECT * FROM eZSession_Session WHERE ID='$id'" );
            if ( count( $session_array ) > 1 )
            {
                die( "Error: Session's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $session_array ) == 1 )
            {
                $this->ID = $session_array[0][ "ID" ];
                $this->Hash = $session_array[0][ "Hash" ];
                $this->LastAccessed = $session_array[0][ "LastAccessed" ];
                $this->SecondLastAccessed = $session_array[0][ "SecondLastAccessed" ];
                $this->Created = $session_array[0][ "Created" ];
                

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
      Fetches a session from cookie and database.

      Returnes false if unsuccessful.
    */
    function fetch( )
    {
        $this->dbInit();
        $ret = false;
        
        $hash = $GLOBALS["eZSession"];

        $this->Database->array_query( $session_array, "SELECT ID
                                      FROM eZSession_Session
                                      WHERE Hash='$hash'" );

        if ( count( $session_array ) == 1 )
        {
            $ret = $this->get( $session_array[0]["ID"] );

            $this->refresh();
        }
        
        return $ret;        
    }

    /*!
      This function refreshes the session timeout.
    */
    function refresh( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       // update session
       $this->Database->query( "UPDATE eZSession_Session SET
                                 Created=Created,
                                 SecondLastAccessed=LastAccessed,
                                 LastAccessed=now()
                                 WHERE ID='$this->ID'
                                 " );        

    }
        

    /*!
      Deletes an eZSession object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZSession_SessionVariable
                                    WHERE SessionID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZSession_Session WHERE ID='$this->ID'" );
        }
        
        return true;
    }    

    /*!
      Returns the id to the session.
    */
    function id( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->ID;
    }
    
    /*!
      Returns the hash to the session.
    */
    function hash( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
       return $this->Hash;
    }
    
    /*!
      Returns the last accessed time of the session.
    */
    function lastAccessed( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->LastAccessed );
       
       return $dateTime;
    }

    /*!
      Returns the cretation time of the session.
    */
    function created( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Created );
       
       return $dateTime;
    }
    
    /*!
      Sets the hash to the session.
    */
    function setHash( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Hash = $value;
    }

    /*!
      Returns the value of a session variable.

      If the variable does not exist 0 (false) is returned.
    */
    function variable( $name )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $ret = false;
       $this->dbInit();

       $this->Database->array_query( $value_array, "SELECT Value FROM eZSession_SessionVariable
                                                    WHERE SessionID='$this->ID' AND Name='$name'" );       

       if ( count( $value_array ) == 1 )
       {
           $ret = $value_array[0]["Value"];
       }

       return $ret;
    }

    /*!
      Returns an array of session ID's session variable name==$name.
      
    */    
    function getByVariable( $name )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $ret = array();
       $this->dbInit();

       $this->Database->array_query( $value_array, "SELECT eZSession_Session.ID
                                                    FROM eZSession_Session, eZSession_SessionVariable
                                                    WHERE eZSession_Session.ID=eZSession_SessionVariable.SessionID
                                                    AND eZSession_SessionVariable.Name='AuthenticatedUser'" );

       foreach ( $value_array as $value )
       {
           $ret[] =& $value["ID"];
       }

       return $ret;
    }
    
    /*!
      Returns the idle time of the session in seconds.
    */
    function idle( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $value_array = array();
       $this->Database->array_query( $value_array, "SELECT ID, LastAccessed, ( now() + 0 ) AS NOW, SecondLastAccessed
                                                    FROM eZSession_Session WHERE ID='$this->ID'" );
       
       $ret = false;            
       if ( count( $value_array ) == 1 )
       {
           $now = $value_array[0]["NOW"];
           $lastAccessed = $value_array[0]["SecondLastAccessed"];

           $diff = $now - $lastAccessed;

           $ret = $diff;
       }

       return $ret;
    }
    
    /*!
      Adds or updates a variable to the session.
    */
    function setVariable( $name, $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $this->dbInit();

       $this->Database->array_query( $value_array, "SELECT ID FROM eZSession_SessionVariable
                                                    WHERE SessionID='$this->ID' AND Name='$name'" );       
       if ( count( $value_array ) == 1 )
       {
           $valueID = $value_array[0]["ID"];
           $this->Database->query( "UPDATE eZSession_SessionVariable SET
		                         Value='$value' WHERE ID='$valueID'
                                 " );
       }
       else
       {
           $this->Database->query( "INSERT INTO eZSession_SessionVariable SET
		                         SessionID='$this->ID',
		                         Name='$name',
		                         Value='$value'
                                 " );
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
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Hash;
    var $Modified;
    var $Created;
    var $LastAccessed;
    var $SecondLastAccessed;
    

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
