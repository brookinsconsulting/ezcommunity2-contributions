<?
// 
// $Id: ezsession.php,v 1.52 2001/07/13 11:55:32 bf Exp $
//
// Definition of eZSession class
//
// Bård Farstad <bf@ez.no>
// Created on: <25-Sep-2000 15:21:18 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
  $session =& eZSession::globalSession( );
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
        $this->IsFetched = false;

        if ( $id != "" )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a product to the database and sets a cookie to identify the session later.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );
    
        // lock the table
        $db->lock( "eZSession_Session" );

        // set the cookie
        $this->Hash = md5( microTime() );

        if ( $GLOBALS["UsePHPSessions"] == true )
        {
            session_register( "eZSession" );
            $GLOBALS["eZSession"] =& $this->Hash;
        }
        setcookie ( "eZSessionCookie", $this->Hash, 0, "/",  "", 0 )
            or print( "Error: could not set cookie." );        

        $remoteIP = $GLOBALS["REMOTE_ADDR"];
        
        if ( !isset( $this->ID ) )
        {
            $nextID = $db->nextID( "eZSession_Session", "ID" );

            $timeStamp =& eZDateTime::timeStamp( true );

            $res = $db->query( "INSERT INTO eZSession_Session
                                    ( ID, Created, LastAccessed, Hash )
                             VALUES ( '$nextID',
                                      '$timeStamp',
                                      '$timeStamp',
                                      '$this->Hash'
                                    )" );
            if ( $res == false )
                $dbError = true;

			$this->ID = $nextID;
            $this->HasRefreshed = true;
        }
        else
        {
            $dateTime = new eZDateTime( );
            $timeStamp = $dateTime->timeStamp();
            
            $db->query( "UPDATE eZSession_Session SET
                                 Created=Created,
                                 LastAccessed='$timeStamp',
		                         Hash='$this->Hash'
                                 WHERE ID='$this->ID'
                                 " );
            $this->HasRefreshed = true;
        }

        $db->unlock();
    
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        $this->setVariable( "SessionIP", $remoteIP );
        
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
            return true;
        }

        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $session_array, "SELECT * FROM eZSession_Session WHERE ID='$id'" );
            if ( count( $session_array ) > 1 )
            {
                die( "Error: Session's with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $session_array ) == 1 )
            {
                $this->fill( $session_array[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    /*!
      Fills in object information from the database array.
    */
    function fill( $session_array )
    {
        $db =& eZDB::globalDatabase();

        $this->ID =& $session_array[$db->fieldName("ID")];
        $this->Hash =& $session_array[$db->fieldName("Hash")];
        $this->LastAccessed =& $session_array[$db->fieldName("LastAccessed")];
        $this->Created =& $session_array[$db->fieldName( "Created") ];
    }

    /*!
      Fetches a session from cookie and database.

      Fetches a session and stores the result in the global session object. 

      Returnes false if unsuccessful.
    */
    function fetch( $refresh=true )
    {
        $ret = false;
        
        if ( $this->IsFetched != true )
        {
            $db =& eZDB::globalDatabase();
            $ret = false;

            // prefer cookie
            if ( isset( $GLOBALS["eZSessionCookie"] ) )
            {
                $hash = $GLOBALS["eZSessionCookie"];
            }
            else
            {
                if ( $GLOBALS["UsePHPSessions"] == true )
                {                    
                    $hash = $GLOBALS["eZSession"];
                }
            }

            $db->array_query( $session_array, "SELECT *
                                      FROM eZSession_Session
                                      WHERE Hash='$hash'" );

            if ( count( $session_array ) == 1 )
            {
                $ret = $this->get( $session_array[0] );

                if ( $ret == true )
                {
                    $this->IsFetched = true;
                }

                if ( $refresh == true )
                {
                    $this->refresh();
                }
            }
        }
        else
        {
            if ( $refresh == true )
            {
                $this->refresh();
            }
            $ret = true;
        }

        return $ret;
    }

    /*!
      This function refreshes the session timeout.
    */
    function refresh( )
    {
        if ( !$this->HasRefreshed )
        {
            $db =& eZDB::globalDatabase();
            
            $db->begin( );
            
            $timeStamp = eZDateTime::timeStamp( true );
            
            // update session
            $ret = $db->query( "UPDATE eZSession_Session SET
                                 Created=Created,
                                 LastAccessed='$timeStamp'
                                 WHERE ID='$this->ID'
                                 " );

            if ( $ret == false )
            {
                $db->rollback( );

            }
            else
            {
                $db->commit();            
                $this->HasRefreshed = true;
            }
        }
    }
        

    /*!
      Deletes an eZSession object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isset( $this->ID ) )
        {
            $db->query( "DELETE FROM eZSession_SessionVariable
                                    WHERE SessionID='$this->ID'" );
            
            $db->query( "DELETE FROM eZSession_Session WHERE ID='$this->ID'" );
        }
        
        return true;
    }    

    /*!
      Returns the id to the session.
    */
    function id( )
    {
       return $this->ID;
    }
    
    /*!
      Returns the hash to the session.
    */
    function hash( )
    {
       return $this->Hash;
    }
    
    /*!
      Returns the last accessed time of the session.
    */
    function lastAccessed( )
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->LastAccessed );
       
       return $dateTime;
    }

    /*!
      Returns the cretation time of the session.
    */
    function created( )
    {
       $dateTime = new eZDateTime();
       $dateTime->setTimeStamp( $this->Created );
       
       return $dateTime;
    }
    
    /*!
      Sets the hash to the session.
    */
    function setHash( $value )
    {
        $this->Hash = $value;
    }

    /*!
      Returns the value of a session variable.

      If the variable does not exist 0 (false) is returned.
    */
    function variable( $name, $group = false )
    {
        if ( isset( $this->StoredVariables[$group][$name] ) )
        {
            return $this->StoredVariables[$group][$name];
        }
        $ret = false;
        $db =& eZDB::globalDatabase();

        if ( !is_bool( $group ) )
            $group_sql = "GroupName='$group'";
        else
            $group_sql = "GroupName=''";
        $db->array_query( $value_array, "SELECT Value FROM eZSession_SessionVariable
                                                    WHERE SessionID='$this->ID' AND Name='$name'
                                                    AND $group_sql" );

        if ( count( $value_array ) == 1 )
        {
            $ret = trim( $value_array[0][$db->fieldName("Value")] );
            $this->StoredVariables[$group][$name] = $ret;
        }
        return $ret;
    }

    /*!
      Returns an array of session ID's session variable name==$name.
      
    */    
    function getByVariable( $name )
    {
        $ret = array();
        $db =& eZDB::globalDatabase();

        $db->array_query( $value_array, "SELECT eZSession_Session.ID
                                                    FROM eZSession_Session, eZSession_SessionVariable
                                                    WHERE eZSession_Session.ID=eZSession_SessionVariable.SessionID
                                                    AND eZSession_SessionVariable.Name='AuthenticatedUser'" );

        foreach ( $value_array as $value )
        {
            $ret[] =& $value[$db->fieldName("ID")];
        }

        return $ret;
    }
    
    /*!
      Returns the idle time of the session in seconds.
    */
    function idle( )
    {
        if ( is_numeric( $this->StoredIdle ) )
            return $this->StoredIdle;

        $db =& eZDB::globalDatabase();

        $value_array = array();

        $timeStamp = eZDateTime::timeStamp( true );
        $db->array_query( $value_array, "SELECT ( $timeStamp - LastAccessed ) AS Idle 
                                                    FROM eZSession_Session WHERE ID='$this->ID'" );
        $ret = false;            
        if ( count( $value_array ) == 1 )
        {
            $ret = $value_array[0][$db->fieldName("Idle")];

            $this->StoredIdle = $ret;
        }

        return $ret;
    }

    /*!
      Cleanup function. Will remove old sessions from the database.

      The default value is to remove sessions which are older than 48 hours.

      This function should be run in a cron job.      
    */
    function cleanup( $maxIdle=48 )
    {
        $db =& eZDB::globalDatabase();

        $value_array = array();
        $timeStamp = eZDateTime::timeStamp( true );
        
        $db->array_query( $value_array, "SELECT ID, ( $timeStamp- LastAccessed  ) AS Idle
                          FROM eZSession_Session
                          HAVING Idle>(60*60*$maxIdle)" );

        foreach ( $value_array as $session )
        {
            $sid = $session["ID"];
            $db->query( "DELETE FROM eZSession_SessionVariable WHERE SessionID='$sid'" );
            $db->query( "DELETE FROM eZSession_Session WHERE ID='$sid'" );            
        }        
    }
    
    /*!
      Adds or updates a variable to the session.
    */
    function setVariable( $name, $value, $group = false )
    {
//          print( "setvar: " . (is_bool( $group ) ? ($group ? "true" : "false") : $group ) . "<br>" );
        
        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        $value = $db->escapeString( $value );
    
        // lock the table
        $db->lock( "eZSession_SessionVariable" );

        if ( isset( $this->StoredVariables[$group][$name] ) )
        {
            $this->StoredVariables[$group][$name] = $value;
        }

        if ( !is_bool( $group ) )
            $group_sql = "GroupName='$group'";
        else
        {
            $group_sql = "GroupName=''";
        }

        $query = "SELECT ID FROM eZSession_SessionVariable
         WHERE SessionID='$this->ID' AND Name='$name'
         AND $group_sql";

        $db->array_query( $value_array, $query );
        
        if ( count( $value_array ) == 1 )
        {
            $valueID = $value_array[0][$db->fieldName("ID")];
            $res = $db->query( "UPDATE eZSession_SessionVariable SET
		                         Value='$value' WHERE ID='$valueID'
                                 " );
        }
        else
        {
            if ( is_bool( $group ) )
                $group_sql = "";
            else
                $group_sql = "'$group'";

            $nextID = $db->nextID( "eZSession_SessionVariable", "ID" );

            $res = $db->query( "INSERT INTO eZSession_SessionVariable ( ID, SessionID, Name, Value, GroupName ) VALUES
                                      ( '$nextID',
                                        '$this->ID',
		                                '$name',
		                                '$value',
                                        '$group_sql' )
                                 " );
            if ( $res == false )
                $dbError = true;
        }

        $db->unlock();
    
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();
        
    }

    /*!
      \static
      Returns a reference to the global session object, if it doesn't exists it is initialized.
      This is safe to call without an object since it does not access member variables.

      Do not call this method unless you want to fetch the global session variable.
    */
    function &globalSession( $id="", $fetch=true )
    {
        $session =& $GLOBALS["eZSessionObject"];
        if ( get_class( $session ) != "ezsession" )
        {
            $session = new eZSession();
            if ( !$session->fetch() )
            {
                $session->store();
            }
        }

        return $session;
    }

    var $ID;
    var $Hash;
    var $Modified;
    var $Created;
    var $LastAccessed;

    var $StoredVariables;
    var $StoredIdle;

    var $HasRefreshed;
    var $IsFetched;
}

?>
