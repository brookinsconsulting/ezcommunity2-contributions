<?
/*
    Edit a person
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezcontact/classes/ezperson.php" );

if( $Action == "delete" )
{
    $person = new eZPerson();
    $person->get( $CompanyID );
    $person->delete();

    header( "Location: /contact/person/list/" );
}

$error = false;

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "personedit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "person_edit" => "personedit.tpl"
    ) );
$t->set_block( "person_edit", "person_item_tpl", "person_item" );

$t->set_block( "person_edit", "address_item_tpl", "address_item" );

$t->set_block( "person_edit", "home_phone_item_tpl", "home_phone_item" );
$t->set_block( "person_edit", "work_phone_item_tpl", "work_phone_item" );

$t->set_block( "person_edit", "web_item_tpl", "web_item" );
$t->set_block( "person_edit", "email_item_tpl", "email_item" );
$t->set_block( "person_edit", "password_item_tpl", "password_item" );

$t->set_block( "person_edit", "errors_tpl", "errors_item" );

$t->set_block( "errors_tpl", "error_firstname_item_tpl", "error_firstname_item" );
$t->set_block( "errors_tpl", "error_lastname_item_tpl", "error_lastname_item" );
$t->set_block( "errors_tpl", "error_birthdate_item_tpl", "error_birthdate_item" );
$t->set_block( "errors_tpl", "error_email_item_tpl", "error_email_item" );
$t->set_block( "errors_tpl", "error_personno_item_tpl", "error_personno_item" );
$t->set_block( "errors_tpl", "error_loginname_item_tpl", "error_loginname_item" );
$t->set_block( "errors_tpl", "error_password_item_tpl", "error_password_item" );
$t->set_block( "errors_tpl", "error_passwordmatch_item_tpl", "error_passwordmatch_item" );
$t->set_block( "errors_tpl", "error_passwordrepeat_item_tpl", "error_passwordrepeat_item" );
$t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );

$t->set_var( "firstname", "" );
$t->set_var( "lastname", "" );
$t->set_var( "personno", "" );
$t->set_var( "birthday", "" );
$t->set_var( "birthmonth", "" );
$t->set_var( "birthyear", "" );
$t->set_var( "comment", "" );
$t->set_var( "person_id", "" );

$t->set_var( "user_name", "" );
$t->set_var( "old_password", "" );

$t->set_var( "street1", "" );
$t->set_var( "street2", "" );
$t->set_var( "zip", "" );
$t->set_var( "place", "" );

$t->set_var( "home_phone", "" );
$t->set_var( "work_phone", "" );

$t->set_var( "web", "" );
$t->set_var( "email", "" );


/*
    Here we set some variables which are important for some pages which don't allow
    dynamic information in certain categories.
 */

$HOME_PHONE_TYPE_ID = 1;
$WORK_PHONE_TYPE_ID = 2;
$WEB_ONLINE_TYPE_ID = 1;
$EMAIL_ONLINE_TYPE_ID = 2;
$CONTACT_TYPE_ID = 1;
$ADDRESS_TYPE_ID = 1;

$t->set_var( "cv_contact_type_id", "$CONTACT_TYPE_ID" );
$t->set_var( "cv_address_type_id", "$ADDRESS_TYPE_ID" );
$t->set_var( "cv_address_id", "" );
$t->set_var( "cv_home_phone_type_id", "$HOME_PHONE_TYPE_ID" );
$t->set_var( "cv_work_phone_type_id", "$WORK_PHONE_TYPE_ID" );
$t->set_var( "cv_web_online_type_id", "$WEB_ONLINE_TYPE_ID" );
$t->set_var( "cv_email_online_type_id", "$EMAIL_ONLINE_TYPE_ID" );
$t->set_var( "cv_home_phone_id", "" );
$t->set_var( "cv_work_phone_id", "" );
$t->set_var( "cv_web_online_id", "" );
$t->set_var( "cv_email_online_id", "" );

/* End of the pre-defined values */

if( $Action == "insert" || $Action == "update" )
{
    if( empty( $Online[0] ) )
    {
        $t->parse( "error_email_item", "error_email_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_email_item", "" );
    
    if( empty( $FirstName ) )
    {
        $t->parse( "error_firstname_item", "error_firstname_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_firstname_item", "" );
    
    if( empty( $LastName ) )
    {
        $t->parse( "error_lastname_item", "error_lastname_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_lastname_item", "" );
    
    if( empty( $BirthDay ) || empty( $BirthMonth ) || empty( $BirthYear ) )
    {
        $t->parse( "error_birthdate_item", "error_birthdate_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_birthdate_item", "" );
    
    if( empty( $PersonNo ) )
    {
        $t->parse( "error_personno_item", "error_personno_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_personno_item", "" );
    
    if( empty( $LoginName ) && empty( $UserID ) )
    {
        $t->parse( "error_loginname_item", "error_loginname_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_loginname_item", "" );
        
    if( empty( $Password ) && empty( $UserID ) )
    {
        $t->parse( "error_password_item", "error_password_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_password_item", "" );
    
    if( empty( $PasswordRepeat ) && !empty( $Password ) && empty( $UserID ) )
    {
        $t->parse( "error_passwordrepeat_item", "error_passwordrepeat_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_passwordrepeat_item", "" );

    if( $PasswordRepeat != $Password &&  !empty( $Password ) && !empty( $PasswordRepeat ) && empty( $UserID ) )
    {
        $t->parse( "error_passwordmatch_item", "error_passwordmatch_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_passwordmatch_item", "" );
    
    if( empty( $Street1 ) || empty( $Place ) || empty( $Zip ) )
    {
        $t->parse( "error_address_item", "error_address_item_tpl" );
        $error = true;
    }
    else
        $t->set_var( "error_address_item", "" );
        
    if( $error == true )
    {
        $t->parse( "errors_item", "errors_tpl" );
    }
}

if( $error == false )
{
    $t->set_var( "errors_item", "" );
}
else
{
    $Action = "formdata";
}

if( $Action == "insert" && $error == false && $Add_User == true )
{
    $user = new eZUser();
    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setLogin( $LoginName );
    $user->setEmail( $Online[0] );
    if( $Password == $PasswordRepeat && !empty( $Password ) )
    {
        $user->setPassword( $Password );
        $user->store();
        $UserID = $user->id();
        $Add_User = false;
    }
}

if( ( $Action == "insert" || $Action == "update" ) && $error == false && $Add_User == false )
{
    $user = new eZUser( $UserID, true );
    $user->setFirstName( $FirstName );
    $user->setLastName( $LastName );
    $user->setEmail( $Online[0] );
    $user->store();
    
    $person = new eZPerson( $PersonID, fetch );
    $person->setFirstName( $FirstName );
    $person->setLastName( $LastName );
    
    $BirthDate = $BirthYear . $BirthMonth . $BirthDay;
    
    $person->setBirthDay( $BirthDate );
    $person->setPersonNo( $PersonNo );
    $person->setComment( $Comment );
    $person->setContactType( $ContactType );
    $person->setCreator( $UserID );
    $person->store();
    $person->addUser( $user );

    // adresss
    $address = new eZAddress( $AddressID, true );
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setPlace( $Place );
    $address->setAddressType( $AddressTypeID );
    $address->store();
    
    $person->addAddress( $address );

    for($i=0; $i < count( $Phone ); $i++)
    {
        $phone = new eZPhone( $PhoneID[$i], true );
        $phone->setNumber( $Phone[$i] );
        $phone->setPhoneTypeID( $PhoneTypeID[$i] );
        $phone->store();
        
        $person->addPhone( $phone );
    }

    for($i=0; $i < count( $Online ); $i++)
    {
        $online = new eZOnline( $OnlineID[$i], true );
        $online->setURL( $Online[$i] );
        $online->setURLType( $URLType[$i] );
        $online->setOnlineTypeID( $OnlineTypeID[$i] );
        $online->store();
        $person->addOnline( $online );
    }
    
    $PersonID = $person->id();
    
    $t->set_var( "user_id", $UserID );
    $t->set_var( "person_id", $PersonID );
    
    header( "Location: /contact/user/view/$PersonID" );
}

/*
    The user wants to create a new person.
    
    We present an empty form.
 */
if( $Action == "new" )
{
    if( $PersonID != 0 ) // 1
    {
        header( "Location: contact/user/edit/$PersonID" );
        exit();
    }
    
    $Action_value = "insert";

    $t->set_var( "person_id", "0" );
       
    if( $Add_User == false )
    { 
        $t->set_var( "user_id", $user->id() );
        $t->set_var( "firstname", $user->firstName() );
        $t->set_var( "lastname", $user->lastName() );
        $t->set_var( "email", $user->email() );
        $t->set_var( "password_item", "" );
    }
    else
    {
        $t->parse( "password_item", "password_item_tpl" );
    }

    $t->parse( "person_item", "person_item_tpl" );
    $t->parse( "address_item", "address_item_tpl" );
    $t->parse( "home_phone_item", "home_phone_item_tpl" );
    $t->parse( "work_phone_item", "work_phone_item_tpl" );
    $t->parse( "web_item", "web_item_tpl" );
    $t->parse( "email_item", "email_item_tpl" );
}



/*
    The user wants to edit an existing person.
    
    We present a form with the info.
 */
if( $Action == "edit" )
{
    $Action_value = "update";
    $person = new eZPerson( $PersonID, true );
    
    $t->set_var( "firstname", $person->firstName() );
    $t->set_var( "lastname", $person->lastName() );
    $t->set_var( "personno", $person->personNo() );
    
    $BirthDate = $person->birthDate();
    
    $t->set_var( "birthdate", $BirthDate );
    
    include( "classes/ezdate.php" );
    
    $date = new eZDate();
    $date->setMySQLDate( $BirthDate );
    
    $t->set_var( "birthyear", $date->year() );
    $t->set_var( "birthmonth", $date->month() );
    $t->set_var( "birthday", $date->day() );
    
    $t->set_var( "comment", $person->comment() );

    // Telephone list
    $phoneList = $person->phones( $person->id() );

    $count = count( $phoneList );
    if( $count <= 2 && $count != 0 )
    {
        for( $i=0; $i < $count; $i++ )
        {
            if ( $phoneList[$i]->phoneTypeID() == $HOME_PHONE_TYPE_ID )
            {
                $t->set_var( "cv_home_phone_id", $phoneList[$i]->id() );
                $t->set_var( "home_phone", $phoneList[$i]->number() );
            }
            
            $t->parse( "home_phone_item", "home_phone_item_tpl" );
            if ( $phoneList[$i]->phoneTypeID() == $WORK_PHONE_TYPE_ID )
            {
                $t->set_var( "cv_work_phone_id", $phoneList[$i]->id() );
                $t->set_var( "work_phone", $phoneList[$i]->number() );
            }

            $t->parse( "work_phone_item", "work_phone_item_tpl" );
        }
    }
    else
    {
        $t->parse( "home_phone_item", "home_phone_item_tpl" );
        $t->parse( "work_phone_item", "work_phone_item_tpl" );
    }


    // Address list
    $addressList = $person->addresses( $person->id() );
    if( count ( $addressList ) == 1 )
    {
        foreach( $addressList as $addressItem )
        {
            $t->set_var( "cv_address_id", $addressItem->id() );
            $t->set_var( "street1", $addressItem->street1() );
            $t->set_var( "street2", $addressItem->street2() );
            $t->set_var( "zip", $addressItem->zip() );
            $t->set_var( "place", $addressItem->place() );
            
            $t->set_var( "script_name", "personedit.php" );

            $t->parse( "address_item", "address_item_tpl", true );            
        }
    }
    else
    {
        $t->parse( "address_item", "address_item_tpl", true );            
    }
    
    // Online list
    $OnlineList = $person->onlines( $person->id() );
    $count = count( $OnlineList );
    if ( $count <= 2 && $count != 0)
    {
        for( $i=0; $i<count ( $OnlineList ); $i++ )
        {
            if ( $OnlineList[$i]->onlineTypeID() == $WEB_ONLINE_TYPE_ID )
            {
                $t->set_var( "cv_web_online_id", $OnlineList[$i]->id() );
                $t->set_var( "web", $OnlineList[$i]->URL() );
            }
            
            if ( $OnlineList[$i]->onlineTypeID() == $EMAIL_ONLINE_TYPE_ID )
            {
                $t->set_var( "cv_email_online_id", $OnlineList[$i]->id() );
                $t->set_var( "email", $OnlineList[$i]->URL() );
            }
            
            $t->parse( "web_item", "web_item_tpl" );
            $t->parse( "email_item", "email_item_tpl" );
        }
    }
    else
    {
        $t->parse( "web_item", "web_item_tpl" );
        $t->parse( "email_item", "email_item_tpl" );
    }
    
    $t->set_var( "person_id", $PersonID );
            
    $t->set_var( "password_item", "" );
    
    $t->parse( "person_item", "person_item_tpl" );
}

if( $Action == "formdata" )
{
    $Action_value = "insert";
    $t->set_var( "firstname", $FirstName );
    $t->set_var( "lastname", $LastName );
    $t->set_var( "personno", $PersonNo );
    $t->set_var( "birthday", $BirthDay );
    $t->set_var( "birthmonth", $BirthMonth );
    $t->set_var( "birthyear", $BirthYear );
    $t->set_var( "comment", $Comment );

    $t->set_var( "user_name", $LoginName );
    $t->set_var( "password", $Password );
    $t->set_var( "old_password", "" );

    $t->set_var( "street1", $Street1 );
    $t->set_var( "street2", $Street2 );
    $t->set_var( "zip", $Zip );
    $t->set_var( "place", $Place );

    $t->set_var( "home_phone", $HomePhone );
    $t->set_var( "work_phone", $WorkPhone );

    $t->set_var( "web", $Online[1] );
    $t->set_var( "email", $Online[0] );
    $t->set_var( "person_id", "" );

    if( empty( $UserID ) )
    {
        $t->parse( "password_item", "password_item_tpl" );
    }
    else
    {
        $t->set_var( "password_item", "" );
    }
    $t->parse( "person_item", "person_item_tpl" );
    $t->parse( "web_item", "web_item_tpl" );
    $t->parse( "email_item", "email_item_tpl" );
    $t->parse( "address_item", "address_item_tpl", true );            
    $t->parse( "home_phone_item", "home_phone_item_tpl" );
    $t->parse( "work_phone_item", "work_phone_item_tpl" );
}
// Template variabler.

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "person_edit"  );


?>
