<?
/*!
    $Id: ezuser.php,v 1.2 2000/09/01 13:28:59 ce-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 13:06:48 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
class eZUser {
    

    /**
       Constructor
     */
    function eZUser( $Id = "" )
    {
        if ( $Id != "" )
            $this->get( $Id );
    }
    
    /*!
        newUser() : defines a new user record.
    */
    function newUser()
    {
        unset( $Id );    
    }
    
    /*!
        get() : retrieves a user record from the DB
        
        $id : user ID
    */
    function get( $Id )
    {
        global $PREFIX;
        $this->ID = $Id;
        $this->openDB();
        $query_id = mysql_query("SELECT group_id,first_name, last_name, nick_name, email, state,
                                 phone_number, mobile_number, Address_one, Address_two,
                                 zip_code, city, country, region_info, company, fax_number,
                                 auth_hash,passwd
                                 FROM $PREFIX"."UserTable WHERE id='$Id'") 
        or die("eZUser::get($Id) failed, dying...");

        $this->GroupId = mysql_result( $query_id, 0, "group_id" );
        $this->FirstName = mysql_result( $query_id, 0, "first_name" );
        $this->LastName = mysql_result( $query_id, 0, "last_name" );
        $this->NickName = mysql_result( $query_id, 0, "nick_name" );
        $this->Email = mysql_result( $query_id, 0, "email" );
        $this->State = mysql_result( $query_id, 0, "state" );
        $this->PhoneNumber = mysql_result( $query_id, 0, "phone_number" );
        $this->MobileNumber = mysql_result( $query_id, 0, "mobile_number" );
        $this->FaxNumber = mysql_result( $query_id,0,"fax_number" );
        $this->AddressOne = mysql_result( $query_id,0,"Address_one" );
        $this->AddressTwo = mysql_result( $query_id,0,"Address_two" );
        $this->ZipCode = mysql_result( $query_id,0,"zip_code" );
        $this->City = mysql_result( $query_id,0,"city" );
        $this->Country = mysql_result( $query_id,0,"country" );
        $this->RegionInfo = mysql_result( $query_id,0,"region_info" );
        $this->Company = mysql_result( $query_id,0,"company" );
        $this->AuthHash = mysql_result( $query_id,0,"auth_hash" );
        $this->Password = mysql_result( $query_id,0,"passwd" );
    }

    /*!
        store() : creates or updates a user account
    */
    function store()
    {
        global $PREFIX;

        echo $this->Email;
        $this->openDB();

        if ( $this->ID ) // an allready existing user record
        {
            mysql_query("UPDATE $PREFIX"."UserTable SET
                           group_id='$this->GroupId',
                           first_name='$this->FirstName',
                           last_name='$this->LastName',
                           nick_name='$this->NickName',
                           email='$this->Email',
                           state='$this->State',
                           phone_number='$this->PhoneNumber',
                           mobile_number='$this->MobileNumber',
                           fax_number='$this->FaxNumber',
                           Address_one='$this->AddressOne',
                           Address_two='$this->AddressTwo',
                           zip_code='$this->ZipCode',
                           city='$this->City',
                           country='$this->Country',
                           region_info='$this->RegionInfo',
                           company='$this->Company',
                           auth_hash='$this->AuthHash',
                           passwd='$this->Password'
                         WHERE id='$this->ID'")
            or die("eZUser::store($this->ID) failed, dying...");
        }
        else // new record
       {
            $query_id = mysql_query( "INSERT INTO $PREFIX"."UserTable(
                                            group_id,first_name, last_name, nick_name, email,
                                            state, phone_number, mobile_number,
                                            fax_number, Address_one, Address_two,
                                            zip_code, city, country, region_info,
                                            company, auth_hash, passwd)
                          VALUES( '$this->GroupId' , '$this->FirstName' , '$this->LastName',
                                 '$this->NickName' , '$this->Email' , '$this->State',
                                 '$this->PhoneNumber' , '$this->MobileNumber' , '$this->FaxNumber' ,
                                 '$this->AddressOne' , '$this->AddressTwo' ,
                                 '$this->ZipCode' , '$this->City' , '$this->Country' , '$this->RegionInfo' ,
                                 '$this->Company' , '$this->AuthHash' , '$this->Password')" )
                 or die("eZUser::store(new record) failed, dying...");
            $this->ID = mysql_insert_id();
            return( $this->ID );
       }
    }

    function update( )
    {
        global $PREFIX;
        $this->openDB();
        
        mysql_query("UPDATE $PREFIX"."UserTable SET
                           group_id='$this->GroupId',
                           first_name='$this->FirstName',
                           last_name='$this->LastName',
                           nick_name='$this->NickName',
                           email='$this->Email',
                           state='$this->State',
                           phone_number='$this->PhoneNumber',
                           mobile_number='$this->MobileNumber',
                           fax_number='$this->FaxNumber',
                           Address_one='$this->AddressOne',
                           Address_two='$this->AddressTwo',
                           zip_code='$this->ZipCode',
                           city='$this->City',
                           country='$this->Country',
                           region_info='$this->RegionInfo',
                           company='$this->Company',
                           auth_hash='$this->AuthHash',
                           passwd='$this->Password'
                         WHERE id='$this->ID'")
            or die("eZUser::store($this->ID) failed, dying...");

    }
    
    /*!
        delete() : deletes a user record from the MySQL DB
        
        $Id : user ID
    */
    function delete( )
    {
        global $PREFIX;
        
        $this->openDB();
        mysql_query("DELETE FROM $PREFIX"."UserTable WHERE id='$this->ID'")
        or die("eZUser:delete($this->ID) failed, dying...");
    }
    
    /*!
        disableUser() : disables a user account
    */
    function disableUser()
    {
        $this->state = "D";
    }
    
    /*!
        enableUser() : enables a user account
    */
    function enableUser()
    {
        $this->state = "E";
    }
    
    /*!
        groupID() : returns the group ID for the current user record
    */
    function groupID()
    {
        return $this->groupId;
    }

    function id()
    {
        return $this->ID;
    }

    /*!
        setGroupID() : sets the groupID for current user record
        
        $newGroupID : the new group ID
    */    
    function setGroupID( $newGroupID )
    {
        $this->GroupId = $newGroupID;
    }
    
    /*!
        firstName() : returns the first name of the current user record
    */
    function firstName()
    {
        return $this->FirstName;
    }

    /*!
        setFirstName() : sets the first name of the current user record
        
        $newFirstName : the new first name
    */    
    function setFirstName( $newFirstName )
    {
        $this->FirstName = $newFirstName;
    }

    /*!
        lastName() : returns the last name of the current user record
    */    
    function lastName()
    {
        return $this->LastName;
    }
    
    /*!
        setLastName() : sets the last name of the current user record
        
        $newLastName : the new first name
    */
    function setLastName( $newLastName )
    {
        $this->LastName = $newLastName;    
    }
    
    /*!
        nickName() : returns the nick name of the current user record
    */
    function nickName()
    {
        return $this->NickName;    
    }

    
    /*!
        setNickName() : sets the nick name of the current user record
        
        $newNickName : new nick name
    */
    function setNickName( $newNickName )
    {
        $this->NickName = $newNickName;
    }

    
    /*!
        setUserName() : sets the user name of the current user record
        
        $newUserName : new user name
    */
    function setUserName( $newUserName )
    {
        $this->NickName = $newUserName;
    }
    
    /*!
     */
    function searchNickName( $queryNick )
    {
        global $PREFIX;
        
        $this->openDB();
        $queryNick = addslashes( $queryNick );
        $query_id = mysql_query("SELECT Id FROM $PREFIX"."UserTable WHERE nick_name='$queryNick' ")
        or die("eZUser::searchNickName($queryNick) failed, dying...");

        if ( mysql_num_rows( $query_id ) > 0 )
            return true;
        else
            return false;
    }
    
    /*!
        email() : returns the email of the current user record
    */
    function email()
    {
        return $this->Email;    
    }
    
    /*!
        setEmail() : sets the email of the current user record
        
        $newEmail : the new emailAddress
    */
    function setEmail($newEmail)
    {
        $this->Email = $newEmail;    
    }
    
    /*!
        AddressOne() : returns the first Address field of the current user record
    */
    function AddressOne()
    {
        return $this->AddressOne;    
    }
    
    /*!
        setAddressOne() : sets the first Address field of the current user record
        
        $newAddressOne : new first Address field
    */
    function setAddressOne($newAddressOne)
    {
        $this->AddressOne = $newAddressOne;
    }

    /*!
        AddressTwo() : returns the second Address field of the current user record
    */
    function AddressTwo()
    {
        return $this->AddressTwo;    
    }
    
    /*!
        setAddressTwo() : sets the second Address field of the current user record
        
        $newAddressTwo : new second Address field
    */
    function setAddressTwo($newAddressTwo)
    {
        $this->AddressTwo = $newAddressTwo;
    }
    
    
    /*!
        city() : returns the city field of the current user record
    */
    function city()
    {
        return $this->City;    
    }
    
    /*!
        setCity() : sets the city field of the current user record
        
        $newCity: new city field
    */
    function setCity($newCity)
    {
        $this->City = $newCity;    
    }
    
    /*!
        zipCode() : returns the ZIP code field of the current user record
    */
    function zipCode()
    {
        return $this->ZipCode;    
    }
    
    /*!
        setZIPCode() : sets the ZIP code field of the current user record
        
        $newZipCode : new ZIP code
    */
    function setZIPCode( $newZipCode )
    {
        $this->ZipCode = $newZipCode;    
    }
    
    /*!
        phoneNumber() : returns the phonenumber field of the current user record
    */
    function phoneNumber()
    {
        return $this->phoneNumber;    
    }
    
    /*!
        setPhoneNumber() : sets the phonenumber field of the current user record
        
        $newPhoneNumber : new phone number.
    */
    function setPhoneNumber($newPhoneNumber)
    {
        $this->PhoneNumber = $newPhoneNumber;
    }

    /*!
        mobileNumber() : returns the mobilenumber field of the current user record
    */
    function mobileNumber()
    {
        return $this->MobileNumber;    
    }
    
    /*!
        setMobileNumber() : sets the mobilenumber field of the current user record
        
        $newMobileNumber : new mobile number
    */
    function setMobileNumber($newMobileNumber)
    {
        $this->MobileNumber = $newMobileNumber;
    }

    /*!
        faxNumber() : returns the faxnumber field of the current user record
    */
    function faxNumber()
    {
        return $this->FaxNumber;    
    }
    
    /*!
        setFaxNumber() : sets the faxnumber field of the current user record
        
        $newFaxNumber : new faxnumber
    */
    function setFaxNumber($newFaxNumber)
    {
        $this->FaxNumber = $newFaxNumber;
    }

    /*!
        country() : returns the country field of the current user record
    */
    function country()
    {
        return $this->Country;
    }
    
    /*!
        setCountry() : sets the country field of the current user record
        
        $newCountry : new country
    */
    function setCountry( $newCountry )
    {
        $this->Country = $newCountry;
    }
    function company()
    {
        return $this->Company;
    }
    function setCompany( $newCompany )
    {
        $this->company = $newCompany;
    }

    /*!
        regionInfo() : returns the region info field of the current user record
    */
    function regionInfo()
    {
        return $this->RegionInfo;
    }

    /*!
        setRegionInfo() : sets the region info field of the current user record
        
        $newRegionInfo : new regional info
    */
    function setRegionalInfo( $newRegionInfo )
    {
        $this->RegionInfo = $newRegionInfo;    
    }
    
    /*!
        setPassword() : sets a new password for the current user record
        
        $newPassword : new password
        
        Note: The password gets encrypted in this function
              - not stored in cleartext!
    */
    function setPassword( $newPassword )
    {
        $query_id = mysql_query("SELECT PASSWORD('$newPassword') AS passwd")
        or die("eZUser::setPassword() failed, dying...");
        $this->Password = mysql_result($query_id,0,"passwd");
    }
    
    /*!
        password() : returns the password from the current user record
    */
    function password()
    {
        return $this->Password;
    }
    
    /*!
      generateAuthHash() : generates a new unique authentication hash
    */
    function generateAuthHash()
    {
        //small randomization hack to get it unique, and harder to bruteforce attack
        
        srand(time());
        $rnd = rand(2389,5984398);
        $this->AuthHash = md5(time() . "secretStRiNg0000" . $rnd);
    }
    
    /*!
        authHash() : returns the current authentication hash for the current user
    */
    function authHash()
    {
        return $this->AuthHash;
    }
    function validateUser( $userId, $Passwd )
    {
        global $PREFIX;
        
        $this->openDB();
        $userId = addslashes( $userId );
        $Passwd = addslashes( $Passwd );
        
        $query_id = mysql_query( "SELECT Id FROM $PREFIX"."UserTable WHERE nick_name='$userId' AND passwd=PASSWORD('$Passwd')" )
             or die( "Feil ved henting av bruker!" );
        if ( mysql_num_rows( $query_id ) == 0)
        {
            return 0;
        }
        else
        {
            return mysql_result( $query_id, 0, "Id");
        }    
    }

    function resolveUser( $Id )
    {
        global $PREFIX;
        
        $this->openDB();

        if ( ( $Id ) && ( $Id != 0 ) )
        {
            $q = mysql_query( "SELECT nick_name, first_name, last_name FROM $PREFIX"."UserTable WHERE Id = $Id " )
                 or die("Could not resolve user name, dying...");
        }
        else
        {
            return "Anonym";
        }
        $name = mysql_fetch_array( $q );
        if ( $name["nick_name"] == "")
            return ( $name["first_name"] . " " . $name["last_name"] );
        else
            return $name["nick_name"];
    }

    function getByAuthHash( $AuthHash )
    {
        global $PREFIX;
        
        $this->openDB();

        $query_id = mysql_query( "SELECT Id FROM $PREFIX"."UserTable WHERE auth_hash='$AuthHash'" )
             or die("getByAuthHash() failed, dying...");

        if ( mysql_num_rows( $query_id ) == 1)
        {
            $this->get( mysql_result( $query_id, 0, "Id" ) );
            return 0;
        }
        else if ( mysql_num_rows( $query_id ) > 1)
        {
            die("getByAuthHash(): Found duplicates, dying...");
        }
        else // == 0 . no user found
        {
            return 1;
        }
        
    }
    
    function getByEmail( $email )
    {
        global $PREFIX;
        
        $this->openDB();
        
        $query_id = mysql_query("SELECT Id FROM $PREFIX"."UserTable WHERE email='$email'")
             or die("could not look up email in $PREFIX"."UserTable, dying...");

        if ( mysql_num_rows( $query_id ) == 1)
        {
            $this->get( mysql_result( $query_id, 0, "Id" ) );
            return 0;
        } else if (mysql_num_rows( $query_id) > 1)
        {
            // duplicate records - something is *really* wrong.
            die("Duplicate email records when trying to fetch Id, dying.");
        }
        else
        {
            // No records found.
            return 1;
        }
    }

    /*!
      Looks up a user - generates a new security hash,
      sends a mail to the user with a url to a page
      where he/she can set a new password.

      Returns 0 for success / 1 for failure (user not found)
     */
    function passwordEmail( $email )
    {
        global $PREFIX;
        global $SERVER_NAME;

        if ( $this->getByEmail( $email ) == 0) // OK
        {
            $this->generateAuthHash();
            $this->store();

            // $this->get( $this->ID );
            $msg = new eZMail();
            $msg->setTo( $email );
            $msg->setSubject( "eZ Forum @ " . $SERVER_NAME . "; New password." );
            $msg->setFrom("webmaster@" . $SERVER_NAME );
            $msg->setBody("Hei!\n\nVed hjelp av denne linken, kan du opprette et nytt passord:\n" .
                          "http://" . $SERVER_NAME .
                          "/index.php?page=$DOCROOT/recreate.php&id=$this->AuthHash\n\n" .
                          "Med Vennlig hilsen\n\n" .
                          "webmaster@" . $SERVER_NAME);

            $msg->send();

            return 0;
        }
        else // failure
        {
            echo "passwordEmail() failure";
            return 1;
        }
    }

    function getAllUsers()
    {
        global $PREFIX;
        $this->openDB();

        $q = mysql_query("SELECT * FROM $PREFIX"."UserTable")
             or die("eZUser::getAllUsers() failed, dying...");

        if ( mysql_num_rows( $q ) == 0)
            return (bool)false;
        else
        {
            for ($i = 0; $i < mysql_num_rows( $q ); $i++ )
            {
                $resultArray[$i] = mysql_fetch_array( $q );
            }

            return $resultArray;
        }
    }

    /*!
      Privat funksjon, skal kun brukes ac ezuser klassen.
      Funksjon for å åpne databasen.
    */
    function openDB( )
    {
        include_once( "classes/class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }

    /*!
        eZUser variables & constants
    */
    var $ID;
    var $GroupId;
    var $FirstName;
    var $LastName;
    var $NickName;
    var $Email;
    var $State;  
    var $PhoneNumber;
    var $MobileNumber;
    var $FaxNumber;
    var $Company;
    var $AddressOne;
    var $AddressTwo;
    var $ZipCode;
    var $City;
    var $Country;
    var $RegionInfo;
    var $Password;
    var $AuthHash;
}
?>
