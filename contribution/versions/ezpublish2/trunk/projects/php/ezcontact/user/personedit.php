<?
/*
    Edit a person
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezcontact/classes/ezperson.php" );

if ( $Action == "insert" )
{
    
    $person = new eZPerson();
    $person->setFirstName( $FirstName );
    $person->setLastName( $LastName );
    
    $BirthDate = $BirthYear . $BirthMonth . $BirthDay;
    
    $person->setBirthDate( $BirthDate );
    $person->setPersonNo( $PersonNo );
    $person->setComment( $Comment );
    $person->setContactType( $ContactType );
    $person->setCreator( $user->id() );
    $person->setUser( $user );
    $person->store();

    // adresss
    $address = new eZAddress();
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setPlace( $Place );
    $address->setAddressType( $AddressType );
    $address->store();

    $person->addAddress( $address );

    for($i=0; $i < count( $Phone ); $i++)
    {
        $phone = new eZPhone( );
        // telefonnummer
        $phone->setNumber( $Phone[$i] );
        $phone->setPhoneTypeID( $PhoneTypeID[$i] );
        $phone->store();
        $person->addPhone( $phone );
    }

    for($i=0; $i < count( $Online ); $i++)
    {
        $online = new eZOnline();
        $online->setURL( $Online[$i] );
        $online->setURLType( $URLType[$i] );
        $online->setOnlineTypeID( $OnlineTypeID[$i] );
        $online->store();
        $person->addOnline( $online );
    }
    
    $t->set_var( "user_id", $user->id() );
}

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

$t->set_var( "firstname", "" );
$t->set_var( "lastname", "" );
$t->set_var( "personno", "" );
$t->set_var( "birthday", "" );
$t->set_var( "birthmonth", "" );
$t->set_var( "birthyear", "" );
$t->set_var( "comment", "" );

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

define( "HOME_PHONE_TYPE_ID", "1" );
define( "WORK_PHONE_TYPE_ID", "2" );
define( "WEB_ONLINE_TYPE_ID", "1" );
define( "EMAIL_ONLINE_TYPE_ID", "2" );
define( "CONTACT_TYPE_ID", "1" );
define( "ADDRESS_TYPE_ID", "1" );

$t->set_var( "cv_contact_type_id", "CONTACT_TYPE_ID" );
$t->set_var( "cv_address_type_id", "ADDRESS_TYPE_ID" );
$t->set_var( "cv_home_phone_type_id", "HOME_PHONE_TYPE_ID" );
$t->set_var( "cv_work_phone_type_id", "WORK_PHONE_TYPE_ID" );
$t->set_var( "cv_web_online_type_id", "WEB_ONLINE_TYPE_ID" );
$t->set_var( "cv_email_online_type_id", "EMAIL_ONLINE_TYPE_ID" );
$t->set_var( "cv_home_phone_id", "" );
$t->set_var( "cv_work_phone_id", "" );
$t->set_var( "cv_web_online_id", "" );
$t->set_var( "cv_email_online_id", "" );

/* End of the pre-defined values */



/*
    The user wants to create a new person.
    
    We present an empty form.
    
    1:      If a logged in user is trying to insert data.
    1.1:    Try to get a person object by the logged in user's ID
    1.2:    If the person object exists redirect to edit mode.
 */
if( $Action == "new" )
{
    if( $UserID != 0 ) // 1
    {
        $person = new eZPerson();
        $person = $person->getByUserID( $User_ID ); // 1.1
        $PersonID = $person->id();
         
        if( empty( $PersonID ) ) // 1.2
        {
            echo "logged in";
            header( "Redirect: contact/user/edit/$PersonID" );
            exit();
        }       
    }
    
    $Action_value = "insert";

    $t->set_var( "person_id", "0" );
    if( $Add_User == false )
    { 
        $t->set_var( "user_id", $user->id() );
    }
    $t->set_var( "firstname", $user->firstName() );
    $t->set_var( "lastname", $user->lastName() );
    $t->set_var( "email", $user->email() );


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
    
    echo $BirthDate . "Needs to be split:) <br />\n";
    
    $t->set_var( "birthyear", "" );
    $t->set_var( "birthmonth", "" );
    $t->set_var( "birthday", "" );
    
    $t->set_var( "comment", $person->comment() );

    // Telephone list
    $phoneList = $company->phones( $company->id() );

    if ( count( $phoneList ) <= 2 )
    {
        for( $i=0; $i<count ( $phoneList ); $i++ )
        {
            if ( $phoneList[$i]->phoneTypeID() == HOME_PHONE_TYPE_ID )
            {
                $t->set_var( "cv_home_phone_id", $phoneList[$i]->id() );
                $t->set_var( "home_phone", $phoneList[$i]->number() );
            }
            if ( $phoneList[$i]->phoneTypeID() == WORK_PHONE_TYPE_ID )
            {
                $t->set_var( "cv_work_phone_id", $phoneList[$i]->id() );
                $t->set_var( "work_phone", $phoneList[$i]->number() );
            }

            $t->parse( "home_phone_item", "home_phone_item_tpl" );
            $t->parse( "work_phone_item", "work_phone_item_tpl" );
        }
    }


    // Address list
    $addressList = $person->addresses( $person->id() );
    if ( count ( $addressList ) == 1 )
    {
        foreach( $addressList as $addressItem )
        {
            $t->set_var( "address_id", $addressItem->id() );
            $t->set_var( "street1", $addressItem->street1() );
            $t->set_var( "street2", $addressItem->street2() );
            $t->set_var( "zip", $addressItem->zip() );
            $t->set_var( "place", $addressItem->place() );
            
            $t->set_var( "person_id", $PersonID );
            
            $t->set_var( "script_name", "personedit.php" );

            $t->parse( "address_item", "address_item_tpl", true );            
        }
    }
    
    // Online list
    $onlineList = $company->onlines( $company->id() );
    if ( count ( $onlineList ) <= 2 )
    {
        for( $i=0; $i<count ( $onlineList ); $i++ )
        {
            if ( $onlineList[$i]->onlineTypeID() == WEB_ONLINE_TYPE_ID )
            {
                $t->set_var( "web_online_id", $onlineList[$i]->id() );
                $t->set_var( "web", $onlineList[$i]->URL() );
            }
            if ( $onlineList[$i]->onlineTypeID() == EMAIL_ONLINE_TYPE_ID )
            {
                $t->set_var( "email_online_id", $onlineList[$i]->id() );
                $t->set_var( "email", $onlineList[$i]->URL() );
            }
            
            $t->parse( "web_item", "web_item_tpl" );
            $t->parse( "email_item", "email_item_tpl" );
        }
    }
    
    $t->parse( "person_item", "person_item_tpl" );
}

// Template variabler.

$t->set_var( "error", $error );

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "person_edit"  );


?>
