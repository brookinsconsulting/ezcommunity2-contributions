<?
/*!
    $Id: ezuser.php,v 1.8 2000/07/18 14:22:13 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:06:48 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
class eZUser {
    
    /*!
        eZUser variables & constants
    */
    var $Id;
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
    
    /*! 
        initiateDBTable() : creates the eZUser table in the MySQL DB
    */
    function initiateDBTable()
    {
        openDB();
        mysql_query("CREATE TABLE UserTable(
                     id int not null auto_increment primary key,
                     group_id int not null,
                     first_name varchar(30),
                     last_name varchar(35),
                     nick_name varchar(15),
                     email varchar(50),
                     passwd varchar(16),
                     auth_hash varchar(32),
                     state ENUM('E','D') default 'E',
                     company varchar(30),
                     phone_number varchar(14),
                     mobile_number varchar(14),
                     fax_number varchar(14),
                     Address_one varchar(60),
                     Address_two varchar(60),
                     zip_code varchar(6),
                     city varchar(25),
                     country varchar(20),
                     region_info varchar(2) )
                   ")
        or die("eZUser::initiateDBTable() failed, dying...");
    }

    /*!
        newUser() : defines a new user record.
    */
    function newUser()
    {
        unset($Id);    
    }
    
    /*!
        get() : retrieves a user record from the DB
        
        $id : user ID
    */
    function get( $id )
    {
        $this->Id = $id;
        
        openDB();
        $query_id = mysql_query("SELECT group_id,first_name, last_name, nick_name, email, state,
                                 phone_number, mobile_number, Address_one, Address_two,
                                 zip_code, city, country, region_info, company, fax_number,
                                 auth_hash,passwd
                                 FROM UserTable WHERE id='$id'") 
        or die("eZUser::get($id) failed, dying...");

        $this->groupID = mysql_result( $query_id, 0, "group_id" );
        $this->firstName = mysql_result( $query_id, 0, "first_name" );
        $this->lastName = mysql_result( $query_id, 0, "last_name" );
        $this->nickName = mysql_result( $query_id, 0, "nick_name" );
        $this->email = mysql_result( $query_id, 0, "email" );
        $this->state = mysql_result( $query_id, 0, "state" );
        $this->phoneNumber = mysql_result( $query_id, 0, "phone_number" );
        $this->mobileNumber = mysql_result( $query_id, 0, "mobile_number" );
        $this->faxNumber = mysql_result( $query_id,0,"fax_number" );
        $this->AddressOne = mysql_result( $query_id,0,"Address_one" );
        $this->AddressTwo = mysql_result( $query_id,0,"Address_two" );
        $this->zipCode = mysql_result( $query_id,0,"zip_code" );
        $this->city = mysql_result( $query_id,0,"city" );
        $this->country = mysql_result( $query_id,0,"country" );
        $this->regionInfo = mysql_result( $query_id,0,"region_info" );
        $this->company = mysql_result( $query_id,0,"company" );
        $this->authHash = mysql_result( $query_id,0,"auth_hash" );
        $this->password = mysql_result( $query_id,0,"passwd" );
    }

    /*!
        store() : creates or updates a user account
    */
    function store()
    {
        openDB();

        if ($this->Id) // an allready existing user record
        {
            mysql_query("UPDATE UserTable SET
                           group_id='$this->groupID',
                           first_name='$this->firstName',
                           last_name='$this->lastName',
                           nick_name='$this->nickName',
                           email='$this->email',
                           state='$this->state',
                           phone_number='$this->phoneNumber',
                           mobile_number='$this->mobileNumber',
                           fax_number='$this->faxNumber',
                           Address_one='$this->AddressOne',
                           Address_two='$this->AddressTwo',
                           zip_code='$this->zipCode',
                           city='$this->city',
                           country='$this->country',
                           region_info='$this->regionInfo',
                           company='$this->company',
                           auth_hash='$this->authHash',
                           passwd='$this->password'
                         WHERE id='$this->id'")
            or die("eZUser::store($this->id) failed, dying...");
        }
        else // new record
       {
            $queryStr = "INSERT INTO UserTable(group_id,first_name, last_name, nick_name, email,
                                            state, phone_number, mobile_number,
                                            fax_number, Address_one, Address_two,
                                            zip_code, city, country, region_info,
                                            company, auth_hash, passwd)
                          VALUES( '$this->group_id' , '$this->firstName' , '$this->lastName',
                                 '$this->nickName' , '$this->email' , '$this->state',
                                 '$this->phoneNumber' , '$this->mobileNumber' , '$this->faxNumber' ,
                                 '$this->AddressOne' , '$this->AddressTwo' ,
                                 '$this->zipCode' , '$this->city' , '$this->country' , '$this->regionInfo' ,
                                 '$this->company' , '$this->authHash' , '$this->password')";
            $query_id = mysql_query($queryStr)
            or die("eZUser::store(new record) failed, dying...");
            $this->id = mysql_insert_id();
            return($this->id);
       }
    }
    
    /*!
        delete() : deletes a user record from the MySQL DB
        
        $id : user ID
    */
    function delete($id)
    {
        $id = addslashes($id);
        
        openDB();
        mysql_query("DELETE FROM UserTable WHERE id='$id'")
        or die("eZUser:delete($id) failed, dying...");
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
        return $this->groupID;    
    }

    /*!
        setGroupID() : sets the groupID for current user record
        
        $newGroupID : the new group ID
    */    
    function setGroupID($newGroupID)
    {
        $this->groupID = $newGroupID;
    }
    
    /*!
        firstName() : returns the first name of the current user record
    */
    function firstName()
    {
        return $this->firstName;    
    }

    /*!
        setFirstName() : sets the first name of the current user record
        
        $newFirstName : the new first name
    */    
    function setFirstName($newFirstName)
    {
        $this->firstName = $newFirstName;
    }

    /*!
        lastName() : returns the last name of the current user record
    */    
    function lastName()
    {
        return $this->lastName;
    }
    
    /*!
        setLastName() : sets the last name of the current user record
        
        $newLastName : the new first name
    */
    function setLastName($newLastName)
    {
        $this->lastName = $newLastName;    
    }
    
    /*!
        nickName() : returns the nick name of the current user record
    */
    function nickName()
    {
        return $this->nickName;    
    }
    
    /*!
        setNickName() : sets the nick name of the current user record
        
        $newNickName : new nick name
    */
    function setNickName($newNickName)
    {
        $this->nickName = $newNickName;
    }
    function searchNickName($queryNick)
    {
        openDB();
        $queryNick = addslashes( $queryNick );
        $query_id = mysql_query("SELECT Id FROM UserTable WHERE nick_name='$queryNick' ")
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
        return $this->email;    
    }
    
    /*!
        setEmail() : sets the email of the current user record
        
        $newEmail : the new emailAddress
    */
    function setEmail($newEmail)
    {
        $this->email = $newEmail;    
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
        return $this->city;    
    }
    
    /*!
        setCity() : sets the city field of the current user record
        
        $newCity: new city field
    */
    function setCity($newCity)
    {
        $this->city = $newCity;    
    }
    
    /*!
        zipCode() : returns the ZIP code field of the current user record
    */
    function zipCode()
    {
        return $this->zipCode;    
    }
    
    /*!
        setZIPCode() : sets the ZIP code field of the current user record
        
        $newZipCode : new ZIP code
    */
    function setZIPCode($newZipCode)
    {
        $this->zipCode = $newZipCode;    
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
        $this->phoneNumber = $newPhoneNumber;
    }

    /*!
        mobileNumber() : returns the mobilenumber field of the current user record
    */
    function mobileNumber()
    {
        return $this->mobileNumber;    
    }
    
    /*!
        setMobileNumber() : sets the mobilenumber field of the current user record
        
        $newMobileNumber : new mobile number
    */
    function setMobileNumber($newMobileNumber)
    {
        $this->mobileNumber = $newMobileNumber;
    }

    /*!
        faxNumber() : returns the faxnumber field of the current user record
    */
    function faxNumber()
    {
        return $this->faxNumber;    
    }
    
    /*!
        setFaxNumber() : sets the faxnumber field of the current user record
        
        $newFaxNumber : new faxnumber
    */
    function setFaxNumber($newFaxNumber)
    {
        $this->faxNumber = $newFaxNumber;
    }

    /*!
        country() : returns the country field of the current user record
    */
    function country()
    {
        return $this->country;
    }
    
    /*!
        setCountry() : sets the country field of the current user record
        
        $newCountry : new country
    */
    function setCountry($newCountry)
    {
        $this->country = $newCountry;
    }
    function company()
    {
        return $this->company;
    }
    function setCompany($newCompany)
    {
        $this->company = $newCompany;
    }

    /*!
        regionInfo() : returns the region info field of the current user record
    */
    function regionInfo()
    {
        return $this->regionInfo;
    }

    /*!
        setRegionInfo() : sets the region info field of the current user record
        
        $newRegionInfo : new regional info
    */
    function setRegionalInfo($newRegionInfo)
    {
        $this->regionInfo = $newRegionInfo;    
    }
    
    /*!
        setPassword() : sets a new password for the current user record
        
        $newPassword : new password
        
        Note: The password gets encrypted in this function
              - not stored in cleartext!
    */
    function setPassword($newPassword)
    {
        $query_id = mysql_query("SELECT PASSWORD('$newPassword') AS passwd")
        or die("eZUser::setPassword() failed, dying...");
        $this->password = mysql_result($query_id,0,"passwd");
    }
    
    /*!
        password() : returns the password from the current user record
    */
    function password()
    {
        return $this->password;
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
        return $this->authHash;
    }
    function validateUser( $userId, $Passwd )
    {
        openDB();
        $userId = addslashes( $userId );
        $Passwd = addslashes( $Passwd );
        
        $query_id = mysql_query( "SELECT Id FROM UserTable WHERE nick_name='$userId' AND passwd=PASSWORD('$Passwd')" )
             or die( );
        if ( mysql_num_rows( $query_id ) == 0)
        {
            return 0;
        }
        else
        {
            return mysql_result( $query_id, 0, "Id");
        }    
    }
    function resolveUser($Id)
    {
        $this->get($Id);
        if ($this->nickName() == "")
        {
            $returnName = $this->firstName() . $this->lastName();
        }
        else
        {
            $returnName = $this->nickName();
        }
    
        return $returnName;
    }

    function getByAuthHash( $AuthHash )
    {
        openDB();

        $query_id = mysql_query( "SELECT Id FROM UserTable WHERE auth_hash='$AuthHash'" )
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
        openDB();
        
        $query_id = mysql_query("SELECT Id FROM UserTable WHERE email='$email'")
             or die("could not look up email in UserTable, dying...");

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
	global $SERVER_NAME;

        if ( $this->getByEmail( $email ) == 0) // OK
        {
            $this->generateAuthHash();
            $this->store();

            // $this->get( $this->Id );
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
}
?>
