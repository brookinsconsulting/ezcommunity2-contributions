<?
// 
// $Id: ezperson.php,v 1.52 2001/05/04 16:37:24 descala Exp $
//
// Definition of eZPerson class
//
// <real-name><<email-name>>
// Created on: <09-Nov-2000 14:52:40 ce>
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

//!! eZContact
//! eZPerson handles a persons belonging in contacts information.
/*!
 This class handles persons in the eZ contact database.
*/

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezdb.php" );
include_once( "classes/ezquery.php" );
include_once( "ezaddress/classes/ezaddress.php" );
include_once( "ezaddress/classes/ezphone.php" );
include_once( "ezaddress/classes/ezonline.php" );

class eZPerson
{
    /*!
      Constructs a new eZPerson object.
      
      If $id is set, the object's values are fetched from the
      database.
    */
    function eZPerson( $id="", $fetch=true  )
    {
        if( !empty( $id ) )
        {
            $this->ID = $id;
            if( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }
  
    /*!
      Stores a person to the database. 
    */
    function store()
    {
        $db = eZDB::globalDatabase();
        $birth = "NULL";
        if ( isset( $this->BirthDate ) and $this->BirthDate != "" )
            $birth = "'$this->BirthDate'";
        $firstname = addslashes( $this->FirstName );
        $lastname = addslashes( $this->LastName );
        $comment = addslashes( $this->Comment );
        if( !isSet( $this->ID ) )
        {
            $db->query( "INSERT INTO eZContact_Person set
                                                    FirstName='$firstname',
                                                    LastName='$lastname',
	                                                Comment='$comment',
	                                                BirthDate=$birth,
                                                    ContactTypeID='$this->ContactType'" );
			$this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZContact_Person set
                                                    FirstName='$firstname',
                                                    LastName='$lastname',
	                                                Comment='$comment',
	                                                BirthDate=$birth,
                                                    ContactTypeID='$this->ContactType'
                                                    WHERE ID='$this->ID'" );
        }
    }


    /*!
      Deletes an eZPerson from the database.
    */
    function delete( $id = false )
    {
        $db = eZDB::globalDatabase();

        if ( !$id )
            $id = $this->ID;

        if( isset( $id ) && is_numeric( $id ) )
        {
            // Delete project state
            eZPerson::setProjectState( false, $id );

            // Delete real world addresses

            $db->array_query( $address_array, "SELECT eZContact_PersonAddressDict.AddressID AS 'DID'
                                               FROM eZAddress_Address, eZContact_PersonAddressDict
                                               WHERE eZAddress_Address.ID=eZContact_PersonAddressDict.AddressID
                                                     AND eZContact_PersonAddressDict.PersonID='$id' " );

            foreach( $address_array as $addressItem )
            {
                $addressDictID = $addressItem["DID"];
                $db->query( "DELETE FROM eZAddress_Address WHERE ID='$addressDictID'" );
            }
            $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$id'" );
           
            // Delete phone numbers.

            $db->array_query( $phone_array, "SELECT eZContact_PersonPhoneDict.PhoneID AS 'DID'
                                     FROM eZAddress_Phone, eZContact_PersonPhoneDict
                                     WHERE eZAddress_Phone.ID=eZContact_PersonPhoneDict.PhoneID
                                       AND eZContact_PersonPhoneDict.PersonID='$id' " );

            foreach( $phone_array as $phoneItem )
            {
                $phoneDictID = $phoneItem["DID"];
                $db->query( "DELETE FROM eZAddress_Phone WHERE ID='$phoneDictID'" );
            }
            $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$id'" );

            // Delete online address.

            $db->array_query( $online_array, "SELECT eZContact_PersonOnlineDict.OnlineID AS 'DID'
                                     FROM eZAddress_Online, eZContact_PersonOnlineDict
                                     WHERE eZAddress_Online.ID=eZContact_PersonOnlineDict.OnlineID
                                       AND eZContact_PersonOnlineDict.PersonID='$id' " );

            foreach( $online_array as $onlineItem )
            {
                $onlineDictID = $onlineItem["DID"];
                $db->query( "DELETE FROM eZAddress_Online WHERE ID='$onlineDictID'" );
            }
            $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$id'" );

            $db->query( "DELETE FROM eZContact_CompanyPersonDict WHERE PersonID='$id'" );

            $db->query( "DELETE FROM eZContact_Person WHERE ID='$id'" );
        }
        return true;
    }


    /*
      Henter ut person med ID == $id
    */
    function get( $id )
    {
        $db = eZDB::globalDatabase();
        if( $id != "" )
        {
            $db->array_query( $person_array, "SELECT * FROM eZContact_Person WHERE ID='$id'" );
            if( count( $person_array ) > 1 )
            {
                die( "Feil: Flere personer med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if( count( $person_array ) == 1 )
            {
                $this->ID = $person_array[ 0 ][ "ID" ];
                $this->FirstName = $person_array[ 0 ][ "FirstName" ];
                $this->LastName = $person_array[ 0 ][ "LastName" ];
                $this->ContactType = $person_array[ 0 ][ "ContactTypeID" ];
                $this->BirthDate = $person_array[ 0 ][ "BirthDate" ];
                $this->Comment = $person_array[ 0 ][ "Comment" ];
            }
            if ( $this->BirthDate == "NULL" )
                unset( $this->BirthDate );
        }
    }

    /*
        Fetches the person with the USER ID == $id
     */
    function getByUserID( $id )
    {
        $db = eZDB::globalDatabase();
        
        $query = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID='$id'";

        $return_item = 0;

        $db->array_query( $person_array, $query );
        foreach( $person_array as $personItem )
        {
            $return_item = new eZPerson( $personItem["PersonID"], false );
        }
        
        return $return_item;
    }
    
    function getAllCount( $search_types = "", $cond = "all" )
    {
        $db = eZDB::globalDatabase();

        if ( empty( $search_types ) )
        {
            switch( $cond )
            {
                case "standalone":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NULL
                            GROUP BY Person.ID";
                    $db->array_query( $person_array, $qry );
                    return count( $person_array );
                    break;
                }
                case "connected":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NOT NULL
                            GROUP BY Person.ID";
                    $db->array_query( $person_array, $qry );
                    return count( $person_array );
                    break;
                }
                case "all":
                default:
                {
                    $qry = "SELECT count( ID ) AS Count FROM eZContact_Person
                            ORDER BY LastName, FirstName";
                    $db->query_single( $persons, $qry );
                    return $persons["Count"];
                    break;
                }
            }
        }
        else
        {
            switch( $cond )
            {
                case "standalone":
                {
                    $cond_text = "Dict.CompanyID IS NULL AND";
                    break;
                }
                case "connected":
                {
                    $cond_text = "Dict.CompanyID IS NOT NULL AND";
                    break;
                }
                case "all":
                default:
                {
                    $cond_text = "";
                }
            }
            $query = new eZQuery( array( "A.FirstName", "A.LastName",
                                         "C.Number",
                                         "E.Street1", "E.Street2", "E.Place", "E.Zip",
                                         "Online.Url" ), $search_types );
            $qry = "SELECT A.ID AS ID FROM
                    eZContact_Person AS A LEFT JOIN eZContact_PersonPhoneDict as B
                    ON A.ID=B.PersonID    LEFT JOIN eZAddress_Phone as C
		            ON B.PhoneID=C.ID     LEFT JOIN eZContact_PersonAddressDict AS D
		            ON A.ID=D.PersonID    LEFT JOIN eZAddress_Address AS E
		            ON D.AddressID=E.ID   LEFT JOIN eZContact_PersonOnlineDict AS F
		            ON A.ID=F.PersonID    LEFT JOIN eZAddress_Online AS Online
		            ON F.OnlineID=Online.ID LEFT JOIN eZContact_CompanyPersonDict AS Dict
                    ON A.ID=Dict.PersonID
                    WHERE $cond_text (" .
                    $query->buildQuery() .
                    ") 
                    GROUP BY A.ID
                    ORDER BY A.LastName, A.FirstName";
            $db->array_query( $persons, $qry );
            return count( $persons );
        }
    }

    /*!
      Fetches all persons in the database.
    */
    function getAll( $search_types = "", $limit_index = 0, $limit = 1, $cond = "all")
    {
        $db = eZDB::globalDatabase();
        $person_array = 0;

        if ( $limit >= 0 )
        {
            $limit_text = "LIMIT $limit_index, $limit";
        }

        if ( empty( $search_types ) )
        {
            switch( $cond )
            {
                case "standalone":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NULL
                            GROUP BY Person.ID $limit_text";
                    break;
                }
                case "connected":
                {
                    $qry = "SELECT Person.ID
                            FROM eZContact_Person AS Person LEFT JOIN eZContact_CompanyPersonDict AS Dict
                            ON Person.ID=Dict.PersonID WHERE Dict.CompanyID IS NOT NULL
                            GROUP BY Person.ID $limit_text";
                    break;
                }
                case "all":
                default:
                {
                    $qry = "SELECT ID FROM eZContact_Person ORDER BY LastName, FirstName
                            $limit_text";
                    break;
                }
            }
            $db->array_query( $person_array, $qry );
        }
        else
        {
            switch( $cond )
            {
                case "standalone":
                {
                    $cond_text = "Dict.CompanyID IS NULL AND";
                    break;
                }
                case "connected":
                {
                    $cond_text = "Dict.CompanyID IS NOT NULL AND";
                    break;
                }
                case "all":
                default:
                {
                    $cond_text = "";
                }
            }
            $query = new eZQuery( array( "A.FirstName", "A.LastName",
                                         "C.Number",
                                         "E.Street1", "E.Street2", "E.Place", "E.Zip",
                                         "Online.Url" ), $search_types );
            $qry = "SELECT A.ID AS ID FROM
                    eZContact_Person AS A LEFT JOIN eZContact_PersonPhoneDict as B
                    ON A.ID=B.PersonID    LEFT JOIN eZAddress_Phone as C
		            ON B.PhoneID=C.ID     LEFT JOIN eZContact_PersonAddressDict AS D
		            ON A.ID=D.PersonID    LEFT JOIN eZAddress_Address AS E
		            ON D.AddressID=E.ID   LEFT JOIN eZContact_PersonOnlineDict AS F
		            ON A.ID=F.PersonID    LEFT JOIN eZAddress_Online AS Online
		            ON F.OnlineID=Online.ID LEFT JOIN eZContact_CompanyPersonDict AS Dict
                    ON A.ID=Dict.PersonID
                    WHERE $cond_text (" .
                    $query->buildQuery() .
                    ") 
                    GROUP BY A.ID
                    ORDER BY A.LastName, A.FirstName
                    $limit_text";
            $db->array_query( $person_array, $qry );
        }

        foreach( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem["ID"] );
        }
        return $return_array;
    }

    /*!
      Fetches all persons whith first name or last name equal to the query string.
    */
    function search( $query )
    {
        $db = eZDB::globalDatabase();
        $person_array = 0;
    
        $db->array_query( $person_array, "SELECT * FROM eZContact_Person
                                          WHERE FirstName LIKE '%$query%' OR
                                                LastName LIKE '%$query%' ORDER BY LastName" );
    
        foreach( $person_array as $personItem )
        {
            $return_array[] = new eZPerson( $personItem["ID"] );
        }
        return $return_array;
    }
    
    /*!
      Returns the address that belong to this eZPerson object.
    */
    function addresses()
    {
        $return_array = array();
        $db = eZDB::globalDatabase();

        $PersonID = $this->ID;


        $db->array_query( $address_array, "SELECT PAD.AddressID
                                           FROM eZContact_PersonAddressDict AS PAD, eZAddress_Address AS A,
                                                eZAddress_AddressType AS AT
                                           WHERE PAD.AddressID = A.ID AND A.AddressTypeID = AT.ID
                                                 AND PAD.PersonID='$PersonID' AND AT.Removed=0" );

        foreach( $address_array as $addressItem )
        {
            $return_array[] = new eZAddress( $addressItem["AddressID"] );
        }

        return $return_array;
    }

    /*!
      Adds an address to the current Person.
    */
    function addAddress( $address )
    {
        $ret = false;
       
        $db = eZDB::globalDatabase();
        if( get_class( $address ) == "ezaddress" )
        {
            $addressID = $address->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonAddressDict WHERE AddressID='$addressID'";

            $db->array_query( $address_array, $checkQuery );

            $count = count( $address_array );

            if( $count == 0 )
            {
                $db->query( "INSERT INTO eZContact_PersonAddressDict
                                SET PersonID='$this->ID', AddressID='$addressID'" );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
      Remove all addresses to the current Person.
    */
    function removeAddresses()
    {
        $db = eZDB::globalDatabase();
        $db->array_query( $address_array, "SELECT AddressID FROM eZContact_PersonAddressDict
                                           WHERE PersonID='$this->ID'" );
        foreach( $address_array as $address )
        {
            $id = $address["AddressID"];
            eZAddress::delete( $id );
        }
        $db->query( "DELETE FROM eZContact_PersonAddressDict WHERE PersonID='$this->ID'" );
    }

    /*!
      Returns the phones that belong to this eZPerson object.
    */
    function phones( $personID = false )
    {
        if ( !is_numeric( $personID ) )
            $personID = $this->ID;

        $return_array = array();
        $db = eZDB::globalDatabase();

        $PersonID = $this->ID;

        $db->array_query( $phone_array, "SELECT PPD.PhoneID
                                         FROM eZContact_PersonPhoneDict AS PPD, eZAddress_Phone AS P,
                                              eZAddress_PhoneType AS PT
                                         WHERE PPD.PhoneID = P.ID AND P.PhoneTypeID = PT.ID
                                               AND PersonID='$PersonID' AND PT.Removed=0" );

        foreach( $phone_array as $phoneItem )
        {
            $return_array[] = new eZPhone( $phoneItem["PhoneID"] );
        }

        return $return_array;
    }

    /*!
      Adds an phone to the current Person.
    */
    function addPhone( $phone )
    {
        $ret = false;
       
        $db = eZDB::globalDatabase();
        if( get_class( $phone ) == "ezphone" )
        {
            $phoneID = $phone->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonPhoneDict WHERE PhoneID='$phoneID'";

            $db->array_query( $phone_array, $checkQuery );

            $count = count( $phone_array );
            if( $count == 0 )
            {
                $db->query( "INSERT INTO eZContact_PersonPhoneDict
                                SET PersonID='$this->ID', PhoneID='$phoneID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Remove all phones to the current Person.
    */
    function removePhones()
    {
        $db = eZDB::globalDatabase();
        $db->array_query( $phone_array, "SELECT PhoneID FROM
                                         eZContact_PersonPhoneDict WHERE PersonID='$this->ID'" );
        foreach( $phone_array as $phone )
        {
            $id = $phone["PhoneID"];
            eZPhone::delete( $id );
        }
        $db->query( "DELETE FROM eZContact_PersonPhoneDict WHERE PersonID='$this->ID'" );
    }

    /*!
      Returns the onlines that belong to this eZPerson object.
    */
    function onlines()
    {
        $return_array = array();
        $db = eZDB::globalDatabase();

        $PersonID = $this->ID;

        $db->array_query( $online_array, "SELECT POD.OnlineID
                                          FROM eZContact_PersonOnlineDict AS POD, eZAddress_Online AS O,
                                               eZAddress_OnlineType AS OT
                                          WHERE POD.OnlineID = O.ID AND O.OnlineTypeID = OT.ID
                                                AND PersonID='$PersonID' AND OT.Removed=0" );

        foreach( $online_array as $onlineItem )
        {
            $return_array[] = new eZOnline( $onlineItem["OnlineID"] );
        }

        return $return_array;
    }

    /*!
      Remove all onlines to the current Person.
    */
    function removeOnlines()
    {
        $db = eZDB::globalDatabase();
        $db->array_query( $online_array, "SELECT OnlineID FROM eZContact_PersonOnlineDict
                                          WHERE PersonID='$this->ID'" );
        foreach( $online_array as $online )
        {
            $id = $online["OnlineID"];
            eZOnline::delete( $id );
        }
        $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$this->ID'" );
    }

    /*!
      Returns the email address of the person, returns false if none exists.
    */
    function emailAddress()
    {
        $onlines = $this->onlines();
        if ( count( $onlines ) >= 1 )
        {
            $found_mail = false;
            foreach ( $onlines as $online )
                {
                    if ( $online->urlType() == "mailto" )
                    {
                        return $online->url();
                    }
                }
        }
        return false;
    }

    /*!
      Returns the work phone of the person, returns false if none exists.
    */
    function workPhone()
    {
        $phones = $this->phones( 0 );
        if ( count( $phones ) >= 1 )
        {
            foreach ( $phones as $phone )
                {
                    /* FIXME please, don't use contants!!! */
                    if ( $phone->phoneTypeID() == 4 ) // 4 = Work phone
                    {
                        return $phone->number();
                    }
                }
        }
        return false;
    }

    /*!
      Returns the fax phone of the person, returns false if none exists.
    */
    function faxPhone()
    {
        $phones = $this->phones( 0 );
        if ( count( $phones ) >= 1 )
        {
            foreach ( $phones as $phone )
                {
                    /* FIXME please, don't use contants!!! */
                    if ( $phone->phoneTypeID() == 8 ) // 4 = Fax
                    {
                        return $phone->number();
                    }
                }
        }
        return false;
    }

    /*!
      Returns the title of the user related to a given company ($companyID)
      \obsolete
    */
    function hasTitle( $companyID )
    {
        $db = eZDB::globalDatabase();
        $checkQuery = "SELECT Title FROM eZContact_CompanyPersonDict
                                    WHERE CompanyID='$companyID' and PersonID='$this->ID'";

        $title_array = array();

        $db->array_query( $title_array, $checkQuery );

        return count( $title_array ) > 0;
    }

    /*!
      Returns the title of the user related to a given company ($companyID)
      \obsolete
    */
    function title( $companyID )
    {
        $ret = false;

        $db = eZDB::globalDatabase();
        $checkQuery = "SELECT Title FROM eZContact_CompanyPersonDict
                                    WHERE CompanyID='$companyID' and PersonID='$this->ID'";

        $title_array = array();

        $db->array_query( $title_array, $checkQuery );

        $title = false;

        if ( count( $title_array ) == 1 )
        {
            $title = $title_array[0]["Title"];
        }
        else
        {
            die( "eZPerson::title(): Found " . count( $title_array ) . " titles, expected 1" );
        }
        return $title;
    }

    /*!
      Adds an online to the current Person.
    */
    function addOnline( $online )
    {
        $ret = false;
       
        $db = eZDB::globalDatabase();

        if( get_class( $online ) == "ezonline" )
        {
            $onlineID = $online->id();

            $checkQuery = "SELECT PersonID FROM eZContact_PersonOnlineDict WHERE OnlineID='$onlineID'";
            
            $db->array_query( $online_array, $checkQuery );

            $count = count( $online_array );

            if( $count == 0 )
            {
                $db->query( "INSERT INTO eZContact_PersonOnlineDict
                                SET PersonID='$this->ID', OnlineID='$onlineID'" );
            }

            $ret = true;
        }
        return $ret;
    }

    /*!
      Returns the user that belong to this eZPerson object.
    */
    function user()
    {
        $return_array = array();
        $db = eZDB::globalDatabase();

        $db->array_query( $user_array, "SELECT UserID
                                                 FROM eZContact_UserPersonDict
                                                 WHERE PersonID='$this->ID'" );

        foreach( $user_array as $userItem )
        {
            $return_array[] = new eZUser( $userItem["UserID"] );
        }

        return $return_array;
    }

    /*!
      Adds a user to the current Person.
    */
    function addUser( $user )
    {
        $ret = false;
        
        $db = eZDB::globalDatabase();

        if( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();
            
            $checkQuery = "SELECT PersonID FROM eZContact_UserPersonDict WHERE UserID=$userID";
            $db->array_query( $user_array, $checkQuery );
            
            $count = count( $user_array );
            
            if( $count == 0 )
            {
                $db->query( "INSERT INTO eZContact_UserPersonDict
                                SET PersonID='$this->ID', UserID='$userID'" );
            }
            $ret = true;
        }
        return $ret;
    }

    /*!
        Set the first name of this object to $value.
     */
    function setFirstName( $value )
    {
        $this->FirstName = $value;
    }

    /*!
        Set the last name of this object to $value.
     */
    function setLastName( $value )
    {
        $this->LastName = $value;
    }

    /*!
        Set the comment of this object to $value.
     */
    function setComment( $value )
    {
        $this->Comment = $value;
    }

    /*!
        Set the contact for this object to $value.
     */
    function setContact( $value )
    {
        $this->ContactType = $value;
    }

    /*!
        Set the birth day of this object to $value.
    */
    function setBirthDay( $value )
    {
        $this->BirthDate = $value;
    }

    /*!
        Sets the person to have no birthday
    */
    function setNoBirthDay()
    {
        unset( $this->BirthDate );
    }
  
    /*!
      Returns the ID of the person.
    */
    function id()
    {
        return $this->ID;
    }
  
    /*!
      Returns the first name and the last name of the person.
    */
    function name()
    {
        return $this->firstName() . " " . $this->lastName();
    }

    /*!
      Returns the first name of the person.
    */
    function firstName()
    {
        return $this->FirstName;
    }

    /*!
      Returns the last name of the person.
    */
    function lastName()
    {    
        return $this->LastName;
    }

    /*!
      Returns the first name and last name of the person concated together.
    */
    function fullName()
    {
        return $this->FirstName . " " . $this->LastName;
    }

    /*!
      Returns the comment for this person.
    */
    function comment( )
    {
        return $this->Comment;
    }

    /*!
      Returns the contact for this person.
    */
    function contact( )
    {
        return $this->ContactType;
    }

    /*!
      Returns the project state of this person.
    */
    function projectState()
    {
        $ret = "";

        $db = eZDB::globalDatabase();

        $checkQuery = "SELECT ProjectID FROM eZContact_PersonProjectDict WHERE PersonID='$this->ID'";
        $db->array_query( $array, $checkQuery, 0, 1 );

        if( count( $array ) == 1 )
        {
            $ret = $array[0]["ProjectID"];
        }

        return $ret;
    }

    /*!
      Returns the project state of this person.
    */
    function setProjectState( $value, $id = false )
    {
        if ( !$id )
            $id = $this->ID;
        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_PersonProjectDict WHERE PersonID='$id'" );

        if ( is_numeric( $value )  )
        {
            if ( $value > 0 )
            {
                $checkQuery = "INSERT INTO eZContact_PersonProjectDict
                               SET PersonID='$id', ProjectID='$value'";
                $db->query( $checkQuery );
            }
        }
    }

    /*!
      Returns the birthday of this person.
    */
    function birthDate( )
    {
        return $this->BirthDate;
    }

    /*!
      Returns true if a birthday is present.
    */
    function hasBirthDate()
    {
        return isset( $this->BirthDate );
    }

    function removeCompanies( $id = false )
    {
        if ( !$id )
            $id = $this->ID;

        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_CompanyPersonDict
                     WHERE PersonID='$id'" );
    }

    /*!
      Returns an array of companies this person is related to.
    */
    function companies( $id = false, $as_object = true )
    {
        if ( !$id )
            $id = $this->ID;

        $db = eZDB::globalDatabase();
        $db->array_query( $arr,
                          "SELECT CPD.CompanyID
                           FROM eZContact_CompanyPersonDict AS CPD, eZContact_Company AS C
                           WHERE CPD.PersonID='$id' AND CPD.CompanyID=C.ID
                           ORDER BY C.Name" );
        $ret = array();
        if ( $as_object )
        {
            foreach( $arr as $row )
            {
                $ret[] = new eZCompany( $row["CompanyID"] );
            }
        }
        else
        {
            foreach( $arr as $row )
            {
                $ret[] = $row["CompanyID"];
            }
        }
        return $ret;
    }

    var $ID;
    var $FirstName;
    var $LastName;
    var $BirthDate;  
    var $ContactType;
    var $Comment;
};

?>
 