<?php
// 
// $Id: ezuser.php,v 1.100.2.8 2003/04/09 13:09:07 jhe Exp $
//
// Definition of eZUser class
//
// Created on: <26-Sep-2000 18:47:27 bf>
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
  $user->setSimultaneousLogins( "10" );

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

  \sa eZUserGroup eZPermission eZModule eZForgot
*/

include_once( "classes/ezdb.php" );

include_once( "ezaddress/classes/ezaddress.php" );
include_once( "classes/ezdatetime.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezperson.php" );

include_once( "ezcontact/classes/ezcompany.php" );

/*
include_once( "classes/INIFile.php" );

$ini =& INIFile::globalINI();
$UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );
*/

class eZUser
{
    /*!
      Constructs a new eZUser object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZUser( $id=-1 )
    {
        $this->InfoSubscription = 0;
        $this->SimultaneousLogins = 0;
        $this->GroupString = false;
        $this->HasRoot = -1;
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores or updates a eZUser object in the database.
    */
    function store()
    {
        include_once( "classes/INIFile.php" );
        $ini =& INIFile::globalINI();
        $UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );

        $db =& eZDB::globalDatabase();

        $dbError = false;
        $db->begin( );

        $email = $db->escapeString( $this->Email );
        $firstname = $db->escapeString( $this->FirstName );
        $lastname = $db->escapeString( $this->LastName );
        $companies = $this->Companies;
        $companyname = $db->escapeString( $this->CompanyName );
        $signature = $db->escapeString( $this->Signature );
        $login = $db->escapeString( $this->Login );
        $personID = $db->escapeString( $this->PersonID );

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZUser_User" );

            $nextID = $db->nextID( "eZUser_User", "ID" );
            
            // backwards compatible passwords
            if ( $db->isA() == "mysql" )
            {
	                     $db->query( "INSERT INTO eZUser_User SET
                                 ID='$nextID',
                                 Login='$login',
                                 Password=PASSWORD('$this->Password'),
                                 Email='$email',
                                 InfoSubscription='$this->InfoSubscription',
                                 InfoDisclaimer='$this->InfoDisclaimer',
                                 DeadlineReminders='$this->DeadlineReminders',
                                 FirstName='$firstname',
                                 LastName='$lastname',
				 CompanyName='$comanyname',
				 AccountActive='$this->AccountActive',
				 Signature='$signature',
                                 CookieLogin='$this->CookieLogin',
                                 SimultaneousLogins='$this->SimultaneousLogins'" );
                            $this->ID = $nextID;

		// insert new ezperson !
		if ( ( $UserPersonLink == "enabled" ) )
		{
		      $person = new eZPerson();
		      $person->setUserID($this->ID);
                      $person->setContact($this->ID);

		      $person->setFirstName($firstname);
		      $person->setLastName($lastname);
		      $person->store();

                      $this->PersonID = $person->ID();
		      $person->addCompanies($companies);
                      $person->store();

		      //Set eZUser:PersonID Field
                      $db->query( "UPDATE eZUser_User SET
                           PersonID = '$this->PersonID'
                           WHERE ID='$this->ID'" );
		}
            }
            else
            {
                $password = md5( $this->Password );

                $db->query( "INSERT INTO eZUser_User
                ( ID, Login, Password, Email, InfoSubscription, InfoDisclaimer, FirstName, LastName, CompanyName, AccountActive, Signature, CookieLogin, SimultaneousLogins, DeadlineReminders )
                VALUES
                ( '$nextID',
                  '$login',
                  '$password',
                  '$email',
                  '$this->InfoSubscription',
                  '$this->InfoDisclaimer',
                  '$firstname',
                  '$lastname',
                  '$comanyname',								 
		  '$this->AccountActive',
                  '$signature',
                  '$this->CookieLogin',
                  '$this->SimultaneousLogins',
                  '$this->DeadlineReminders')" );
                
                $this->ID = $nextID;

		// insert new ezperson !
                if ( ( $UserPersonLink == "enabled" ) )
		{
		    $person = new eZPerson();
		    $person->setUserID($this->ID);
		    $person->setContact($this->ID);

		    $person->setFirstName($firstname);
		    $person->setLastName($lastname);
		    $person->addCompanies($companies);
		    $person->store();

		    $this->PersonID = $person->ID();

		    //Set eZUser:PersonID Field
		    $db->query( "UPDATE eZUser_User SET
                           PersonID = '$this->PersonID'
                           WHERE ID='$this->ID'" );
		}

            }

        }
        else
        {
	           $db->query( "UPDATE eZUser_User SET
                         Login='$login',
                                 Email='$email',
                                 InfoSubscription='$this->InfoSubscription',
                                 FirstName='$firstname',
                                 Signature='$signature',
                                 LastName='$lastname',							
				 CompanyName='$this->CompanyName',
				 AccountActive='$this->AccountActive',
                                 CookieLogin='$this->CookieLogin',
                                 SimultaneousLogins='$this->SimultaneousLogins',
	                         InfoDisclaimer='$this->InfoDisclaimer',
                                 DeadlineReminders='$this->DeadlineReminders'
                                 WHERE ID='$this->ID'" );


		   if ( ( $UserPersonLink == "enabled" ) )
		   {
                       $person = new eZPerson($this->PersonID);
                       $person->setFirstName($firstname);
                       $person->setLastName($lastname);
                       $person->addCompanies($companies);
                       $person->store();

		       $this->PersonID = $person->ID();

		       //Set eZUser:PersonID Field
		       $db->query( "UPDATE eZUser_User SET
                           PersonID = '$this->PersonID'
                           WHERE ID='$this->ID'" );
		   }

            // update password if set.
            if ( isSet( $this->Password ) )
            {
                // backwards compatible passwords
                if ( $db->isA() == "mysql" )
                {
                    $db->query( "UPDATE eZUser_User SET
                                 Password=PASSWORD('$this->Password')
                                 WHERE ID='$this->ID'" );
                }
                else
                {
                    $password = md5( $this->Password );

                    $db->query( "UPDATE eZUser_User SET
                                 Password='$password'
                                 WHERE ID='$this->ID'" );
                }
            }            
        }


	/*
         Insert / Update Associated eZ Person Records
	*/

        $db->unlock();
    
        if ( $dbError == true )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZUser object from the database.

    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isSet( $this->ID ) )
        {
            $db->query( "DELETE FROM eZUser_UserGroupLink WHERE UserID='$this->ID'" );
            $db->query( "DELETE FROM eZUser_UserAddressLink WHERE UserID='$this->ID'" );
            $db->query( "DELETE FROM eZUser_UserShippingLink WHERE UserID='$this->ID'" );

            $db->query( "DELETE FROM eZUser_User WHERE ID='$this->ID'" );
        }

        /*
         Delete / Update Associated eZ Person Records
	*/
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $user_array, "SELECT * FROM eZUser_User WHERE ID='$id'", 0, 1 );

            if ( count( $user_array ) == 1 )
            {
                $this->fill( $user_array[0] );
                $ret = true;
            }
            elseif ( count( $user_array ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$user_array )
    {
        $db =& eZDB::globalDatabase();

        $this->ID =& $user_array[$db->fieldName("ID")];
        $this->Login =& $user_array[$db->fieldName("Login")];
        $this->Email =& $user_array[$db->fieldName("Email")];
        $this->InfoSubscription =& $user_array[$db->fieldName("InfoSubscription")];
        $this->InfoDisclaimer =& $user_array[$db->fieldName("InfoDisclaimer")];

        $this->FirstName =& $user_array[$db->fieldName("FirstName")];
        $this->LastName =& $user_array[$db->fieldName("LastName")];
        $this->CompanyName =& $user_array[$db->fieldName("CompanyName")];
        $this->DeadlineReminders =& $user_array[$db->fieldName("DeadlineReminders")];
        $this->AccountActive =& $user_array[$db->fieldName("AccountActive")];
	$this->Name = $user_array[$db->fieldName("FirstName")] .' '. $user_array[$db->fieldName("LastName")];
        $this->Signature =& $user_array[$db->fieldName("Signature")];
        $this->CookieLogin =& $user_array[$db->fieldName("CookieLogin")];
        $this->SimultaneousLogins =& $user_array[$db->fieldName("SimultaneousLogins")];
        $this->PersonID =& $user_array[$db->fieldName("PersonID")];
    }

    /*!
      Returns the number of rows a getAll with the same search param would give.
    */
    function &getAllCount( $search = false, $active = false )
    {
        $db =& eZDB::globalDatabase();

        $query = new eZQuery( array( "FirstName", "LastName",
                                     "Login", "Email" ), $search );

        if ( $search != false ) {
            $db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User
           WHERE " . $query->buildQuery() . 'AND AccountActive = 1' );

	    /*
             $db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User WHERE " . $query->buildQuery() );
	    */
	}
        else {
            $db->query_single( $user_array, "SELECT count( ID ) AS Count FROM eZUser_User where AccountActive = 1" );
	}

        return $user_array[$db->fieldName("Count")];
    }

    /*!
      Fetches the user id from the database. And returns a array of eZUser objects.
    */
    function &getAll( $order="Login", $as_object = true, $search = false, $max = -1, $index = 0 )
    {
        $db =& eZDB::globalDatabase();

        switch ( $order )
        {
            case "name" :
            {
                $orderBy = "LastName, FirstName";
            }
            break;
            
            case "lastname" :
            {
                $orderBy = "LastName";
            }
            break;

            case "firstname" :
            {
                $orderBy = "FirstName";
            }
            break;
            
            case "lastname" :
            {
                $orderBy = "LastName";
            }
            break;

            case "email" :
            {
                $orderBy = "Email";
            }
            break;
            
            default :
                $orderBy = "Login";
            break;
        }
                
        $return_array = array();
        $user_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        if ( $search )
        {
            $db->array_query( $user_array, "SELECT $select FROM eZUser_User
                                            WHERE FirstName LIKE '%$search%' OR
                                            LastName LIKE '%$search%' OR
                                            Login LIKE '%$search%' OR
                                            Email LIKE '%$search%' AMD
					    AccountActive = 1
                                            ORDER By $orderBy",
                              array( "Limit" => $max,
                                     "Offset" => $index ) );
        }
        else
        {
            $db->array_query( $user_array, "SELECT $select FROM eZUser_User where AccountActive = 1
                                            ORDER By $orderBy",
                              array( "Limit" => $max,
                                     "Offset" => $index ) );
        }
        
        if ( $as_object )
        {
            foreach ( $user_array as $user )
            {
                $return_array[] = new eZUser( $user );
            }
        }
        else
        {
            foreach ( $user_array as $user )
            {
                $return_array[] = $user[ $db->fieldName( "ID" ) ];
            }
        }
        return $return_array;
    }

    /*!
      Returns the eZUser object where login = $login.

      False (0) is returned if the users isn't validated.
    */
    function getUser( $login )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'" );
        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
        }
        return $ret;
    }

    /*
      Get a person by ID == $id
    */
    function getPerson( $id )
      {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
	  {
            $db->array_query( $person_array, "SELECT * FROM eZContact_Person WHERE ID='$id'" );
            if ( count( $person_array ) > 1 )
	      {
                die( "Feil: Flere personer med samme ID funnet i database, dette skal ikke v?re mulig\. " );
	      }
            else if ( count( $person_array ) == 1 )
	      {
                $this->PersonID = $person_array[ 0 ][ $db->fieldName( "ID" ) ];
                // $this->FirstName = $person_array[ 0 ][ $db->fieldName( "FirstName" ) ];
                // $this->LastName = $person_array[ 0 ][ $db->fieldName( "LastName" ) ];
		// $this->ContactType = $person_array[ 0 ][ $db->fieldName( "ContactTypeID" ) ];
                // $this->BirthDate = $person_array[ 0 ][ $db->fieldName( "BirthDate" ) ];
                // $this->Comment = $person_array[ 0 ][ $db->fieldName( "Comment" ) ];
                // $this->UserID = $person_array[ 0 ][ $db->fieldName( "UserID" ) ];
	      }
            if ( $this->BirthDate == "NULL" )
	      unset( $this->BirthDate );
	  }
      }

    /*
        Fetches the person with the USER ID == $id
    */
    function getPersonByUserID( $id )
      {
        $db =& eZDB::globalDatabase();

        $query = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID='$id'";

        $return_item = 0;

        $db->array_query( $person_array, $query );
        foreach ( $person_array as $personItem )
	  {
            $return_item = new eZPerson( $personItem[ $db->fieldName( "PersonID" ) ], false );
	  }

        return $return_item;
      }


    /*!
      Returns the eZUser object where email = $email.

      False (0) is returned if the users isn't validated.
    */
    function getUserByEmail( $email )
      {
        $db =& eZDB::globalDatabase();
        $return = false;

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Email='$email'" );

	// how to limit multiple accounts with same email and different pw / user?
	// ideas: sql limit, if limit?, 

        if ( count( $user_array ) == 1 )
	  {
            $return = new eZUser( $user_array[0][$db->fieldName("ID")] );
	  }
        return $return;
      }


    /*!
      Returns the eZUser object where PersonID = $PersonID.

      False (0) is returned if the users isn't validated.
    */
    function getUserByPersonID( $personID )
      {
        $db =& eZDB::globalDatabase();
        $return = false;

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE PersonID='$personID'" );

        // how to limit multiple accounts with same email and different pw / user?
        // ideas: sql limit, if limit?,

        if ( count( $user_array ) == 1 )
          {
            $return = new eZUser( $user_array[0][$db->fieldName("ID")] );
          }
        return $return;
      }


    /*!
      Returns the eZUser object where email = $email.

      False (0) is returned if the users isn't validated.
    */
    function getUsersByEmail( $email , $as_object = true )
      {
        $db =& eZDB::globalDatabase();
        $return = false;

        $return_array = array();
        $user_array = array();

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Email='$email'" );

        // how to limit multiple accounts with same email and different pw / user?
        // ideas: sql limit, if limit?,

	if ( $as_object )
	  {
            foreach ( $user_array as $user )
	      {
                $return_array[] = new eZUser( $user );
	      }
	  }
        else
	  {
            foreach ( $user_array as $user )
	      {
                $return_array[] = $user[ $db->fieldName( "ID" ) ];
	      }
	  }
        return $return_array;


	//        if ( count( $user_array ) == 1 )
        //  {
	//     $return = new eZUser( $user_array[0][$db->fieldName("ID")] );
        //  }
        //return $return;
      }


    /*!
      Returns the eZUser object where group = $group.

      False (0) is returned if the users isn't validated.
    */
    function getUsersByGroup( $group, $as_object = true )
      {
        $db =& eZDB::globalDatabase();
        $return = false;

        $return_array = array();
        $user_array = array();

/*
        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Group='$group'" );
*/

        $db->array_query( $user_array, "SELECT * FROM eZUser_User, eZUser_UserGroupDefinition, eZUser_UserGroupLink
                                                    WHERE eZUser_UserGroupDefinition.GroupID='$group' AND eZUser_UserGroupLink.UserID = eZUser_User.ID" );


        // how to limit multiple accounts with same group and different pw / user?
        // ideas: sql limit, if limit?,

        if ( $as_object )
          {
            foreach ( $user_array as $user )
              {
                $return_array[] = new eZUser( $user );
              }
          }
        else
          {
            foreach ( $user_array as $user )
              {
                $return_array[] = $user[ $db->fieldName( "ID" ) ];
              }
          }
        return $return_array;


        //        if ( count( $user_array ) == 1 )
        //  {
        //     $return = new eZUser( $user_array[0][$db->fieldName("ID")] );
        //  }
        //return $return;
      }


    /*!
      Returns the correct eZUser object if the user is validated.

      False (0) is returned if the user isn't validated.
    */
    function validateUser( $login, $password )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $login = $db->escapeString( $login );
        $password = $db->escapeString( $password );
        

        if ( $db->isA() == "mysql" )
        {
            $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'
                                                    AND Password=PASSWORD('$password')" );
        }
        else
        {
            $password = md5( $password );

            $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'
                                                    AND Password='$password'" );            
        }
        
        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
            $GLOBALS["eZCurrentUserObject"] =& $ret;

        }
        return $ret;
    }
    
    /*!
      \static
      Returns the eZUser object if a user with that login exits.

      False (0) is returned if not.
    */
    function exists( $login )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->array_query( $user_array, "SELECT * FROM eZUser_User
                                                    WHERE Login='$login'" );

        if ( count( $user_array ) == 1 )
        {
            $ret = new eZUser( $user_array[0][$db->fieldName("ID")] );
        }

        return $ret;        
    }

    /*!
      Returns the object id.
    */
    function id()
    {
        if ( isSet( $this->ID ) )
            return $this->ID;
    }

    /*!
      Returns the object id.
    */
    function personID()
    {
      if ( isSet( $this->PersonID ) )
	return $this->PersonID;
    }

    /*!
      Sets the person ID
    */
    function setPersonID( $value )
    {
      $this->PersonID = $value;
    }
    
    /*!
      Returns the signature.
    */
    function signature()
    {
        return $this->Signature;
    }

    /*!
      Returns the users login.
    */
    function login( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->Login );
        else
            return $this->Login;
    }

    

    /*!
      Returns the users InfoSubscription.
    */
    function infoSubscription()
    {
       $ret = false;
       
       if ( $this->InfoSubscription == 1 )
       {
           $ret = true;
       }
       return $ret;
    }


    /*!
      Returns the users InfoDisclaimer.
    */
    function infoDisclaimer()
    {
       $ret = false;
       
       if ( $this->InfoDisclaimer == 1 )
       {
           $ret = true;
       }
       return $ret;
    }


    /*!
      Returns the users e-mail address.
    */
    function email()
    {
       return $this->Email;
    }

    /*!
      Returns the users name and email where the email is surrounded by <>.
    */
    function namedEmail()
    {
        return $this->name() . " <" . $this->email() . ">";
    }

    /*!
      Returns the users first and last name with a space in between.
    */
    function name()
    {
        return $this->firstName() . " " . $this->lastName();
    }

    /*!
      Returns the users first name.
    */
    function firstName( $html = true )
    {
        if ( $html )
            return htmlspecialchars( $this->FirstName );
        else
            return $this->FirstName;
    }

    /*!
      Returns the users last name.
    */
    function lastName( $html = true  )
    {
        if ( $html )
            return htmlspecialchars( $this->LastName );
        else
            return $this->LastName;
    }

    /*!
      Returns the users comany name.
    */
    function companyName( $html = true  )
    {
        if ( $html )
            return htmlspecialchars( $this->CompanyName );
        else
            return $this->CompanyName;
    }





    /*!
      Returns the accountActive value.
    */
    function accountActive()
    {
        $ret = false;
        if ( $this->AccountActive == 1 )
            $ret = true;
        return $ret;
    }
	
    /*!
      Returns the deadlineReminders value.
    */
    function deadlineReminders()
    {
        $ret = false;
        if ( $this->DeadlineReminders == 1 )
            $ret = true;
        return $ret;
    }

    /*!
      Returns the auto cookie login value.
    */
    function cookieLogin()
    {
        $ret = false;
        if ( $this->CookieLogin == 1 )
            $ret = true;
        return $ret;
    }

    
    /*!
      Returns the number og simultaneous logins allowed on this account.
    */
    function simultaneousLogins()
    {
        return htmlspecialchars( $this->SimultaneousLogins );
    }
    
    /*!
      Sets the signature.
    */
    function setSignature( $value )
    {
       $this->Signature = $value;
    }

    /*!
      Sets the login.
    */
    function setLogin( $value )
    {
       $this->Login = $value;
    }

    
    /*!
      Sets the password.
    */
    function setPassword( $value )
    {
       $this->Password = $value;
    }
    
    /*!
      Sets the email address to the user.
    */
    function setEmail( $value )
    {
        $this->Email = $value;
    }

    /*!
      Sets the infoSubscription to the user.

      This value indicates if the user wants to receive updates
      from the site. true and false are valid arguments.
    */
    function setInfoSubscription( $value )
    {
        if ( $value == true )
        {
            $this->InfoSubscription = 1;
        }
        else
        {
            $this->InfoSubscription = 0;
        }
    }

 
    /*!
      Sets the infoDisclaimer to the user.

      This value indicates if the user wants to receive updates
      from the site. true and false are valid arguments.
    */
    function setInfoDisclaimer( $value )
    {
        if ( $value == true )
        {
            $this->InfoDisclaimer = 1;
        }
        else
        {
            $this->InfoDisclaimer = 0;
        }
    }


    /*!
      Sets the users first name.
    */
    function setFirstName( $value )
    {
        $this->FirstName = $value;
    }

    /*!
      Sets the users last name.
    */
    function setLastName( $value )
    {
        $this->LastName = $value;
    }

	
    /*!
      Sets the users company name.
    */
    function setCompanyName( $value )
    {
        $this->CompanyName = $value;
    }

    /*!
      Sets the number of simultaneous connections on this account.
    */
    function setSimultaneousLogins ( $value )
    {
        $this->SimultaneousLogins = $value;
        
        setType( $this->SimultaneousLogins, "integer" );
    }

    /*!
      Sets the DeadlineReminders value on this account.
    */
    function setDeadlineReminders ( $value )
    {
        if ( $value == true )
            $this->DeadlineReminders = 1;
        else
            $this->DeadlineReminders = 0;
    }
	
    /*!
      Sets the AccountActive value on this account.
    */
    function setAccountActive ( $value )
    {
        if ( $value == true )
            $this->AccountActive = 1;
        else
            $this->AccountActive = 0;
    }
	
    /*!
      Sets the auto cookie login value on this account.
    */
    function setCookieLogin ( $value )
    {
        if ( $value == true )
            $this->CookieLogin = 1;
        else
            $this->CookieLogin = 0;
    }


    function setGroupDefinition( $group )
    {
        if ( get_class( $group ) == "ezusergroup" )
            $group = $group->ID();

        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZUser_UserGroupDefinition WHERE UserID='" . $this->ID . "'" );
        $db->lock( "eZUser_UserGroupDefinition" );
        $nextID = $db->nextID( "eZUser_UserGroupDefinition", "ID" );
        $res[] = $db->query( "INSERT INTO eZUser_UserGroupDefinition
                              (ID, UserID, GroupID)
                              VALUES
                              ('$nextID', '$this->ID', '$group')" );
        $db->unlock();
        eZDB::finish( $res, $db );
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
            $session =& eZSession::globalSession();

            if ( !$session->fetch() )
            {
                $session->store();
            }

            $session->refresh();

            $session->setVariable( "AuthenticatedUser", $user->id() );

            $GLOBALS["eZCurrentUserObject"] =& $user;

            $ret = true;
        }
        return $ret;
    }

    /*!
      \static
      Auto login in a user with a cookie. The function check the database for the same hash, if the hash is found the user is logged in.
     */
    function autoCookieLogin( $hash )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $hash )
        {
            $db->array_query( $userArray, "SELECT UserID FROM eZUser_Cookie WHERE Hash='$hash'" );
            if ( count ( $userArray ) == 1 )
            {
                $user = new eZUser( $userArray[0][$db->fieldName("UserID")] );
                if ( $user )
                {
                    eZUser::loginUser( $user );
                    return true;
                }
            }
        }
        return false;
    }

    function clearAutoCookieLogin()
    {
        $user =& eZUser::currentUser();
        $db =& eZDB::globalDatabase();
        
        setCookie( "eZUser_AutoCookieLogin", "", 0, "/",  "", 0 );
        setCookie( "eZUser_AutoCookieLogin" );
        
        if ( $user )
        {
            $userID = $user->id();
            $db->query( "DELETE FROM eZUser_Cookie WHERE UserID='$userID'" );
        }
    }

    /*!
      \static
      Logs out a user.
    */
    function logout()
    {
        $session =& eZSession::globalSession();
        if ( $session->fetch() )
        {
            $session->setVariable( "AuthenticatedUser", "" );
        }
    }

    

    /*!
      \static
      Returns the current logged on user as a eZUser object. If the user's session timeout
      has timedout the user is logged out.

      If the session timeout is set to 0 it is disabled.

      False is returned if unsuccessful.
    */
    function &currentUser()
    {
        $user =& $GLOBALS["eZCurrentUserObject"];

        if ( ( get_class( $user ) == "ezuser" ) and ( is_numeric( $user->id() ) ) )
        {
            return $user;
        }


        $session =& eZSession::globalSession();

        $returnValue = false;

        if ( $session->fetch( false ) )
        {
            $user = new eZUser( $session->variable("AuthenticatedUser" ) );

//            $val =& $session->variable( "AuthenticatedUser" );
//            $user = new eZUser( $val );

//              print( $session->variable( "AuthenticatedUser" ) );

            $idle = $session->idle();
            $idle = $idle / 60;

            if ( ( $idle > $user->timeoutValue() ) && ( $user->timeoutValue() != 0 ) )
            {
                $user->logout();
                $user = false;
            }
            else
            {
                if ( ( $user->id() != 0 ) && ( $user->id() != "" ) )
                {
                    $session->refresh( );
                    $returnValue = $user;
                }
            }
        }

        return $returnValue;
    }
    

    /*!
      \static
      Returns the currently logged in users with sessions. It is returned as an
      array of eZUser and eZSession objects.
    */
    function currentUsers()
    {
        $globalSession =& eZSession::globalSession();

        $ret = array();

        $sessionIDArray =& $globalSession->getByVariable( "AuthenticatedUser" );

        foreach ( $sessionIDArray as $sessionID )
        {
            $session = new eZSession( $sessionID );
            $user = new eZUser( $session->variable( "AuthenticatedUser" ) );

            $idle = $session->idle();
            $idle = $idle / 60;

            if ( ( $idle > $user->timeoutValue() ) && ( $user->timeoutValue() != 0 ) )
            {
                $session->delete( );
            }
            else
            {
                if ( ( $user->id() != 0 ) && ( $user->id() != "" ) )
                {
                    $ret[] = array( $user, $session );
                }
            }
        }
        
        return $ret;
    }

    /*!
      Returns the current number of logged in users on one userid.
    */
    function getLogins( $userId )
    {
        $userSessionList =& eZUser::currentUsers();
        $logins = 0;
        foreach ( $userSessionList as $userSessionItem )
        {
            if ( $userSessionItem[0]->id() == $userId )
                $logins++;
        }
        return $logins;
    }

    /*!
      Returns the member group id's of the user as a string sorted by id.

      e.g. 1-2-3-4-6
    */
    function groupString()
    {
        $groupStr = "";
        if ( $this->GroupString == false )
        {
            $groupIDArray =& $this->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupStr .= "$groupID" : $groupStr .= "-$groupID";
                $first = false;
            }
            $this->GroupString = $groupStr;
        }

        return $this->GroupString;
    }
    
    /*!
      Returns the user groups the current user is a member of.
      The result is returned as an array of eZUserGroup objects if $IDOnly = false. If not only an array with the ID's is returned.
    */
    function groups( $as_object = true )
    {
        $ret = array();
        $db =& eZDB::globalDatabase();
        
        $db->array_query( $user_group_array, "SELECT * FROM eZUser_UserGroupLink
                                                    WHERE UserID='$this->ID'" );

        foreach ( $user_group_array as $group )
        {
            !$as_object ? $ret[] = $group[$db->fieldName( "GroupID" )] : $ret[] = new eZUserGroup( $group[$db->fieldName( "GroupID" )] );
        }

        return $ret;
    }

    function groupDefinition( $as_object = false )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $groups, "SELECT * FROM eZUser_UserGroupDefinition WHERE UserID='" . $this->ID . "'" );

        if ( count( $groups ) > 1 )
            die( "Error in database" );
        else if ( count( $groups ) == 0 )
            return false;
        else if ( $as_object )
            return new eZUserGroup( $groups[0][$db->fieldName( "GroupID" )] );
        else
            return $groups[0][$db->fieldName( "GroupID" )];
    }

    /*!
      Static function..check if the user given has root access
     */
    function hasRootAccess()
    {
        if ( $this->HasRoot < 0 )
        {
            $db =& eZDB::globalDatabase();
            $db->query_single( $result, "SELECT count( * ) as Count FROM eZUser_UserGroupLink, eZUser_Group
                                                    WHERE eZUser_UserGroupLink.UserID='$this->ID'
                                                    AND eZUser_Group.ID=eZUser_UserGroupLink.GroupID
                                                    AND eZUser_Group.IsRoot='1'" );
            if ( $result[$db->fieldName( "Count" )] > 0 )
                $this->HasRoot = true;
            else
                $this->HasRoot = false;
        }
        return $this->HasRoot;
    }

    
    /*!
      Removes the user from every user group.
    */
    function removeGroups()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZUser_UserGroupLink
                                WHERE UserID='$this->ID'" );
    }

    /*!
      Adds an address to the current User.
    */
    function addAddress( $address )
    {
      include_once( "classes/INIFile.php" );

      $ini =& INIFile::globalINI();
      $UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );

      if ( ( $UserPersonLink == "enabled" ) )
      {
	$person = new eZPerson($this->PersonID);
        $person->addAddress($address);
	$person->store();
      }else{
        $db =& eZDB::globalDatabase();
        if ( get_class( $address ) == "ezaddress" )
        {
            $addressID = $address->id();
            $db->begin( );
            $db->lock( "eZUser_UserAddressLink" );

            $nextID = $db->nextID( "eZUser_UserAddressLink", "ID" );

            $res = $db->query( "INSERT INTO eZUser_UserAddressLink
                                ( ID, UserID, AddressID )
                                VALUES
                                ( '$nextID', '$this->ID', '$addressID' )" );

            $db->unlock();

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();
        }

      }
    }

    /*!
      Remove address from a user. The function also remove the address from the database.
    */
    function removeAddress( $address )
    {
        $db =& eZDB::globalDatabase();
        if ( get_class( $address ) == "ezaddress" )
        {
            $addressID = $address->id();
            $db->begin( );

            $res = $db->query( "DELETE FROM eZUser_UserAddressLink
                                WHERE AddressID='$addressID'" );

            if ( $res == false )
                $db->rollback();
            else
                $db->commit();

            eZAddress::delete( $addressID );
//              $db->query( "DELETE FROM eZContact_Address
//                                  WHERE ID='$addressID'" );


        }
    }

    /*!
      Remove all addresses from a user. The function also remove the addresses from the database.
      If the $id is supplied it is used for looking up addresses.
    */
    function removeAddresses( $id = false )
    {
      include_once( "classes/INIFile.php" );

      $ini =& INIFile::globalINI();
      $UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );
      // die($UserPersonLink);

      if ( ( $UserPersonLink == "enabled" ) )
      {
        $person = new eZPerson($this->PersonID);
        $person->removeAddresses();
        $person->store();
      }else{
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;

        $addresses = $this->addresses( $id, false );
        foreach ( $addresses as $address )
        {
            eZAddress::delete( $address );
        }
        $db->query( "DELETE FROM eZUser_UserAddressLink
                     WHERE UserID='$id'" );

      }

    }

    /*!
      Returns the addresses a user has. It is returned as an array of eZAddress objects.
      If the $id is supplied it is used for looking up addresses.
    */
    function addresses( $id = false, $as_object = true )
    {
      include_once( "classes/INIFile.php" );

      $ini =& INIFile::globalINI();
      $UserPersonLink = $ini->read_var( "eZUserMain", "UserPersonLink" );
      // die($UserPersonLink);

      if ( ( $UserPersonLink == "enabled" ) )
      {
        $person = new eZPerson($this->PersonID);
        $return_array = $person->addresses();
      }else{
        $return_array = array();
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        $PersonID = $this->PersonID;

	/*
        $db->array_query( $address_array, "SELECT AddressID FROM eZUser_UserAddressLink
                                WHERE UserID='$id' ORDER BY AddressID" );
	*/

        $db->array_query( $address_array, "SELECT AddressID FROM eZUser_UserAddressLink
                                WHERE UserID='$id' ORDER BY AddressID" );

        $db->array_query( $person_address_array, "SELECT PAD.AddressID
                                           FROM eZContact_PersonAddressDict AS PAD,
                                                eZAddress_Address AS A,
                                                eZAddress_AddressType AS AT
                                           WHERE PAD.AddressID = A.ID
                                                 AND A.AddressTypeID = AT.ID
                                                 AND PAD.PersonID='$PersonID'
                                                 AND AT.Removed=0" );

        //$ret = array();

        if ( $as_object )
        {
            foreach ( $address_array as $address )
            {
                $return_array[] = new eZAddress( $address[$db->fieldName( "AddressID" )] );
            }
        }
        else
        {
            foreach ( $address_array as $address )
            {
                $return_array[] = $address[$db->fieldName( "AddressID" )];
            }
        }

        foreach ( $person_address_array as $person_addressItem )
        {
            $return_array[] = new eZAddress( $person_addressItem[ $db->fieldName( "AddressID" ) ] );
        }

      }

        return $return_array;
    }

    /*!
      Returns the main address the user has. It is returned as an eZAddress objects.
      If the $id is supplied it is used for looking up addresses.
    */
    function mainAddress( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        $db->array_query( $address_array, "SELECT AddressID FROM eZAddress_AddressDefinition
                                WHERE UserID='$id' ORDER BY AddressID" );

        if ( $as_object )
        {
            if ( $address_array[0][$db->fieldName( "AddressID" )] != 0 )
                $ret = new eZAddress( $address_array[0][$db->fieldName( "AddressID" )] );
        }
        else
        {
            $ret = $address_array[0][$db->fieldName( "AddressID" )];
        }

        // Insert Code to Remove eZ Person Address Links

        return $ret;
    }

    /*!
      Returns an array of companies this person is related to.
    */
    function companies( $id = false, $as_object = true )
    {
        if ( !$id )
	  $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->array_query( $arr,
                          "SELECT CPD.CompanyID
                           FROM eZContact_CompanyPersonDict AS CPD, eZContact_Company AS C
                           WHERE CPD.PersonID='$id' AND CPD.CompanyID=C.ID
                           ORDER BY C.Name" );
        $ret = array();
        if ( $as_object )
	{
            foreach ( $arr as $row )
	    {
                $ret[] = new eZCompany( $row[ $db->fieldName( "CompanyID" ) ] );
	    }
	}
        else
	{
            foreach ( $arr as $row )
	    {
                $ret[] = $row[ $db->fieldName( "CompanyID" ) ];
	    }
	}
        return $ret;
    }

    function removeCompanies( $id = false )
      {
        if ( !$id )
	  $id = $this->ID;

        $db =& eZDB::globalDatabase();
        $res[] = $db->query( "DELETE FROM eZContact_CompanyPersonDict
                              WHERE PersonID='$id'" );
	eZDB::finish( $res, $db );
      }

    /*
      Adds Companies
    */
    function addCompanies($CompanyID)
    {
      $person = new eZPerson($this->PersonID);
      $person->removeCompanies();
      for ( $i = 0; $i < count( $CompanyID ); $i++ )
      {
	eZCompany::addPerson( $person->id(), $CompanyID[$i] );
      }
      return true;
    }

    /*
      Set Companies
    */
    function setCompanies($CompanyID)
    {
      foreach( $CompanyID as $company_id ){
        $companies[] = $company_id;
        // print($company_id);
      }
      $this->Companies = $companies;
    }

    /*!
      Returns the phones that belong to this eZPerson object.
    */
    function phones( $personID = false )
    {
        if ( !is_numeric( $personID ) )
	  $personID = $this->PersonID;

        $return_array = array();
        $db =& eZDB::globalDatabase();

        $PersonID = $this->PersonID;

        $db->array_query( $phone_array, "SELECT PPD.PhoneID
                                         FROM eZContact_PersonPhoneDict AS PPD, eZAddress_Phone AS P,
                                              eZAddress_PhoneType AS PT
                                         WHERE PPD.PhoneID = P.ID AND P.PhoneTypeID = PT.ID
                                               AND PersonID='$PersonID' AND PT.Removed=0" );

        foreach ( $phone_array as $phoneItem )
	{
            $return_array[] = new eZPhone( $phoneItem[$db->fieldName( "PhoneID" )] );
	}

        return $return_array;
    }

    /*!
      Adds an phone to the current Person.
    */
    function addPhone( $phone )
    {
      $person = new eZPerson($this->PersonID);
      $person->addPhone($phone);

      /*
      for ( $i = 0; $i < count( $PhoneID ); $i++ )
      {

	eZPerson::addPhone( $person->id(), $PhoneID[$i] );
      }
      return true;
      */
    }


    /*!
      Remove all phones to the current Person.
    */
    function removePhones()
    {
      $db =& eZDB::globalDatabase();
      $db->array_query( $phone_array, "SELECT PhoneID FROM
                                         eZContact_PersonPhoneDict WHERE PersonID='$this->PersonID'" );
      foreach ( $phone_array as $phone )
        {
	  $id = $phone[ $db->fieldName( "PhoneID" ) ];
	  eZPhone::delete( $id );
        }
      $db->begin();
      $res[] = $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$this->PersonID'" );
      $res[] = $db->query( "DELETE FROM eZContact_PersonIndex WHERE PersonID='$this->PersonID' AND Type='1'" );
      eZDB::finish( $res, $db );
    }

    /*!
      Returns the onlines that belong to this eZPerson object.
    */
    function onlines()
    {
        $return_array = array();
        $db =& eZDB::globalDatabase();

        $PersonID = $this->PersonID;

        $db->array_query( $online_array, "SELECT POD.OnlineID
                                          FROM eZContact_PersonOnlineDict AS POD, eZAddress_Online AS O,
                                               eZAddress_OnlineType AS OT
                                          WHERE POD.OnlineID = O.ID AND O.OnlineTypeID = OT.ID
                                                AND PersonID='$PersonID' AND OT.Removed=0" );

        foreach ( $online_array as $onlineItem )
	{
            $return_array[] = new eZOnline( $onlineItem[$db->fieldName( "OnlineID" )] );
	}

        return $return_array;
    }

    /*!
      Adds an online to the current Person.
    */
    function addOnline( $online )
      {
        $ret = false;

        $db =& eZDB::globalDatabase();

        if ( get_class( $online ) == "ezonline" )
	  {
            $onlineID = $online->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonOnlineDict WHERE OnlineID='$onlineID'";

            $db->array_query( $online_array, $checkQuery );
            $count = count( $online_array );

            if ( $count == 0 )
	      {
                $url = strtolower( $online->url() );
                $db->begin();
                $res[] = $db->query( "INSERT INTO eZContact_PersonOnlineDict
                                      (PersonID, OnlineID)
                                      VALUES
                                      ('$this->PersonID', '$onlineID')" );
                $res[] = $db->query( "INSERT INTO eZContact_PersonIndex
                                      (PersonID, Value, Type)
                                      VALUES
                                      ('$this->PersonID', '$url', '2')" );
		eZDB::finish( $res, $db );
	      }

            $ret = true;
	  }
        return $ret;
      }

    /*!
      Remove all onlines to the current Person.
    */
    function removeOnlines()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $online_array, "SELECT OnlineID FROM eZContact_PersonOnlineDict
                                          WHERE PersonID='$this->PersonID'" );
        foreach ( $online_array as $online )
	  {
            $id = $online[$db->fieldName( "OnlineID" )];
	    eZOnline::delete( $id );
	  }
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$this->PersonID'" );
        $res[] = $db->query( "DELETE FROM eZContact_PersonIndex WHERE PersonID='$this->PersonID' AND Type='2'" );
	eZDB::finish( $res, $db );

    }

    function trustees( $id = -1, $as_object = false )
    {
        $db =& eZDB::globalDatabase();
        if ( get_class( $id ) == "ezuser" )
            $id = $id->ID();
        if ( $id < 0 )
            $id = $this->ID;
        $select = $as_object ? "*" : "UserID";
        $db->array_query( $trusteeArray, "SELECT $select FROM eZUser_Trustees WHERE OwnerID='$id'" );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = new eZUser( $trustee[ $db->fieldName( "UserID" ) ] );
            }
        }
        else
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = $trustee[ $db->fieldName( "UserID" ) ];
            }
        }
        return $ret;
    }

    function getByTrustee( $id = -1, $as_object = false )
    {
        $db =& eZDB::globalDatabase();
        if ( get_class( $id ) )
            $id = $id->ID();
        if ( $id < 0 )
            $id = $this->ID;
        $select = $as_object ? "*" : "OwnerID";
        $db->array_query( $trusteeArray, "SELECT $select FROM eZUser_Trustees WHERE UserID='$id'" );
        $ret = array();
        if ( $as_object )
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = new eZUser( $trustee[ $db->fieldName( "OwnerID" ) ] );
            }
        }
        else
        {
            foreach ( $trusteeArray as $trustee )
            {
                $ret[] = $trustee[ $db->fieldName( "OwnerID" ) ];
            }
        }
        return $ret;
    }

    function addTrustee( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $user = $user->ID();
        }
        $db =& eZDB::globalDatabase();
        $db->begin();
        $db->lock( "eZUser_Trustees" );
        $nextID = $db->nextID( "eZUser_Trustees", "ID" );
        $res[] = $db->query( "INSERT INTO eZUser_Trustees (ID, OwnerID, UserID) VALUES
                              ('$nextID', '" . $this->ID() . "', '$user')" );
        $db->unlock();
        eZDB::finish( $res, $db );
    }

    function removeTrustee( $user )
    {
        if ( get_class( $user ) == "ezuser" )
        {
            $user = $user->ID();
        }
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZUser_Trustees WHERE OwnerID='" .
                             $this->ID() . "' AND UserID='$user'" );
        eZDB::finish( $res, $db );
    }
    
    function setCookieValues()
    {
        $user =& eZUser::currentUser();
        $db =& eZDB::globalDatabase();

        if ( $user )
        {
            $db->begin( );

            $db->lock( "eZUser_Cookie" );
            $nextID = $db->nextID( "eZUser_Cookie", "ID" );
            
            $userID = $user->id();
            $hash = md5( microTime() );

            $db->query( "DELETE FROM eZUser_Cookie WHERE UserID='$userID'" );
            $res = $db->query( "INSERT INTO eZUser_Cookie ( ID, Hash, UserID )
                                 VALUES ( '$nextID',
                               Hash='$hash',
                               UserID='$userID')" );

            setCookie( "eZUser_AutoCookieLogin", $hash, time()+1209600, "/",  "", 0 );

            $db->unlock();
            
            if ( $res == false )
                $db->rollback( );
            else
                $db->commit();
            
        }
    }

    /*!
      Returns the timeout for the user. If the user is not a member of any user groups the timeout
      is set to 30. If the user is a member of several user groups, the fastest timeout is returned.
    */
    function timeoutValue()
    {
        if ( isSet( $this->StoredTimeout ) && is_numeric( $this->StoredTimeout ) )
            return $this->StoredTimeout;

        $ret = 30;

        $db =& eZDB::globalDatabase();
        $db->array_query( $timeout_array, "SELECT eZUser_Group.SessionTimeout
                                                      FROM eZUser_User, eZUser_UserGroupLink, eZUser_Group
                                                      WHERE eZUser_User.ID=eZUser_UserGroupLink.UserID
                                                      AND eZUser_Group.ID=eZUser_UserGroupLink.GroupID
                                                      AND eZUser_User.ID='$this->ID'
                                                      ORDER BY eZUser_Group.SessionTimeout ASC",
                          array( "Limit" => 1 ) );

       if ( count( $timeout_array ) == 1 )
       {
           $ret = $timeout_array[0][$db->fieldName("SessionTimeout")];
       }
       $this->StoredTimeout = $ret;
       return $ret;
    }

    /*!
      Searches for users matching the queryText and returns them as an array of eZUser objects.
     */
    function search( $queryText, $order = false, $LastName="", $FirstName="", $EMail="", $Login="", $match="AND" ) // gwf
    {
        $db =& eZDB::globalDatabase();

        switch ( $order )
        {
            case "name" : $orderBy = "LastName, FirstName"; break;
            case "lastname" : $orderBy = "LastName"; break;
            case "firstname" : $orderBy = "FirstName"; break;
            case "lastname" : $orderBy = "LastName"; break;
            case "email" : $orderBy = "Email"; break;
            default : $orderBy = "Login"; break;
        }
        
        if ($match =="") $match = "AND";
        
        $query_ext = "";
        $i=0;
        if ($LastName != "") 
        {
        	$i = 1;
        	$query_ext = " LastName LIKE '%$LastName%' ";
        }
        if ($FirstName !="") 
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " FirstName LIKE '%$FirstName%' ";
        	$i = 1;
        }
        if ($EMail !="") 
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " Email LIKE '%$EMail%' ";
        	$i = 1;
        }
        
        if ($Login !="") 
        {
        	if ($i == 1) $query_ext .= " $match ";
        	$query_ext .= " Login LIKE '%$Login%' ";
        	$i = 1;
        }
        
        if ($i == 0 and $query_ext == "") 
        	$query_ext = " Login LIKE '%$queryText%' OR Email LIKE '%$queryText%'
                  			OR FirstName LIKE '%$queryText%' OR LastName LIKE '%$queryText%' ";
       
        $return_array = array();
        $user_array = array();
        $query = "SELECT * FROM eZUser_User WHERE $query_ext ORDER BY $orderBy";

        $db->array_query( $user_array, $query );
        for ( $i = 0; $i < count( $user_array ); $i++ )
        {
            $return_array[$i] = new eZUser( $user_array[$i][$db->fieldName("ID")] );
        }
        return $return_array;
    }
    
    var $ID;
    var $Login;
    var $Password;
    var $Email;
    var $FirstName;
    var $LastName;
    var $AccountActive;
    var $Companies;
    var $ComanyName;
    var $DeadlineReminders;
    var $Name;
    var $PersonID;
    var $InfoDisclaimer;
    var $InfoSubscription;
    var $Signature;
    var $CookieLogin;
    var $SimultaneousLogins;
    var $StoredTimeout;
    var $HasRoot;

    /// string with the member groups, used for storing permissions in cache files
    var $GroupString;
}

?>
