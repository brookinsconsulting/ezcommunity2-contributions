<?
// 
// $Id: ezuser.php,v 1.1 2000/10/02 15:47:07 ce-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <26-Sep-2000 18:47:27 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZUser
//! eZUser handles users.
/*!
  
  Example code:
  \code
  // create a new user and set some variables.
  $user = new eZUser();
  $user->setLogin( "bf" );
  $user->setPassword( "secret" );
  $user->setEmail( "bf@ez.no" );
  $user->setFirstName( "Bård" );
  $user->setLastName( "Farstad" );

  // check if a user with that username exists
  if ( !$user->exists( $user->login() ) )
  {
      // Store the user to the database.
      echo "Username is not used, creating user.<br>";      
      $user->store();        
  }

  // validate a userlogin and password.
  $user = $user->validateUser( "bf", "secret" );

  if ( $user )
  {
      print( "Password and username are ok!<br>" );
      print( $user->firstName() );
      print( $user->lastName() );      
  }
  
  \endcode

  \sa eZUserGroup eZPermission eZModule
*/

include_once( "classes/ezdb.php" );

class eZUser
{
    /*!
      Constructs a new eZUser object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZUser( $id=-1, $fetch=true )
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
      Stores or updates a eZUser object in the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZUser_User SET
		                         Login='$this->Login',
                                 Password=PASSWORD('$this->Password'),
                                 Email='$this->Email',
                                 FirstName='$this->FirstName',
                                 LastName='$this->LastName'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZUser_User SET
		                         Login='$this->Login',
                                 Password=PASSWORD('$this->Password'),
                                 Email='$this->Email',
                                 FirstName='$this->FirstName',
                                 LastName='$this->LastName'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZUser object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZUser_UserGroupLink WHERE UserID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZUser_User WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != "" )
        {
            $this->Database->array_query( $user_array, "SELECT * FROM eZUser_User WHERE ID='$id'" );
            if ( count( $user_array ) > 1 )
            {
                die( "Error: User's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $user_array ) == 1 )
            {
                $this->ID = $user_array[0][ "ID" ];
                $this->Login = $user_array[0][ "Login" ];
                $this->Password = $user_array[0][ "Password" ];
                $this->Email = $user_array[0][ "Email" ];
                $this->FirstName = $user_array[0][ "FirstName" ];
                $this->LastName = $user_array[0][ "LastName" ];

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
      Fetches the user id from the database. And returns a array of eZUser objects.
    */
    function getAll()
    {
        $this->dbInit();

        $return_array = array();
        $user_array = array();

        $this->Database->array_query( $user_array, "SELECT ID FROM eZUser_User ORDER By Login" );

        print( count ( $user_array ) );

        for ( $i=0; $i<count ( $user_array ); $i++ )
        {
            $return_array[$i] = new eZUser( $user_array[$i][ "ID" ], 0 );
        }

        return $return_array;
    }
      

    /*!
      Returns the correct eZUser object if the user is validated.

      False (0) is returned if the users isn't validated.
    */
    function validateUser( $login, $password )
    {
        $this->dbInit();
        $ret = false;
        
        $this->Database->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'
                                                    AND Password=PASSWORD('$password')" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0]["ID"] );
        }

        return $ret;        
    }

    /*!
      Returns the eZUser object if a user with that login exits.

      Falst (0) is returned if not.
    */
    function exists( $login )
    {
        $this->dbInit();
        $ret = false;
        
        $this->Database->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0]["ID"] );
        }

        return $ret;        
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the users login.
    */
    function login( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Login;
    }


    /*!
      Returns the users e-mail address.
    */
    function email( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Email;
    }

    /*!
      Returns the users fist name.
    */
    function firstName( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->FirstName;
    }

    /*!
      Returns the users last name.
    */
    function lastName( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->LastName;
    }
    
    /*!
      Sets the login.
    */
    function setLogin( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Login = $value;
    }

    /*!
      Sets the password.
    */
    function setPassword( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Password = $value;
    }
    
    /*!
      Sets the email address to the user.
    */
    function setEmail( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->Email = $value;
    }
    
    /*!
      Sets the users first name.
    */
    function setFirstName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->FirstName = $value;
    }

    /*!
      Sets the users last name.
    */
    function setLastName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->LastName = $value;
    }

    /*!
      \static
      Logs in the user given argument. The $user argument must be a eZUser object.

      Returns false if unsuccess ful, true if successful.
    */
    function loginUser( $user )
    {
        $ret = false;

        if ( get_class( $user ) == "ezuser" )
        {
            $session = new eZSession();
            
            $session->fetch();
            
            $session->setVariable( "AuthenticatedUser", $user->id() );
            $ret = true;            
        }
        return $ret;
    }

    /*!
      \static
      Logs out an user.
    */
    function logout( )
    {
        $session = new eZSession();
        $session->fetch();
        $session->setVariable( "AuthenticatedUser", "" );
    }

    

    /*!
      \static
      Returns the current logged on user as a eZUser object.

      False is returned if unseccessful.
    */
    function currentUser()
    {
        $session = new eZSession();

        $ret = false;
        $session->fetch();
        
        $user = new eZUser( $session->variable( "AuthenticatedUser" ) );
        
        if ( ( $user->id() != 0 ) && ( $user->id() != "" ) )
        {
            $ret = $user;
        }        

        return $ret;
    }
    
    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit( )
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Login;
    var $Password;
    var $Email;
    var $FirstName;
    var $LastName;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
