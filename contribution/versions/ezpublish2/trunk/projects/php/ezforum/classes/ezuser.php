<?
/*!
    $Id: ezuser.php,v 1.2 2000/07/14 13:01:56 lw-cvs Exp $

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
        unset($id);    
    }
    
    /*!
        get() : retrieves a user record from the DB
        
        $id : user ID
    */
    function get($id)
    {
        //$id = addslashes($id);
        //$this->id = $id;
        
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
        /*$this->groupID = addslashes($this->groupID);
        $this->firstName = addslashes($this->firstName);
        $this->lastName = addslashes($this->lastName);
        $this->nickName = addslashes($this->nickName);
        $this->email = addslashes($this->email);
        $this->state = addslashes($this->state);
        $this->phoneNumber = addslashes($this->phoneNumber);
        $this->mobileNumber = addslashes($this->mobileNumber);
        $this->faxNumber = addslashes($this->faxNumber);
        $this->AddressOne = addslashes($this->AddressOne);
        $t