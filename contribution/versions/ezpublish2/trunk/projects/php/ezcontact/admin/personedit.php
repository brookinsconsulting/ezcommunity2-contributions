<?
/*
    Edit a person
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/ezmail.php" );
include_once( "classes/eztemplate.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcountry.php" );
include_once( "ezcontact/classes/ezprojecttype.php" );

if ( isset( $Back ) )
{
    header( "Location: /contact/person/list/" );
    exit;
}

if( $Action == "delete" )
{
    $person = new eZPerson();
    $person->get( $PersonID );
    $person->delete();

    header( "Location: /contact/person/list/" );
    exit;
}

if( $Action == "new" )
{
    if( is_object( $user ) )
    {
        $person = new eZPerson();
        $person = $person->getByUserID( $user->id() );

        if( is_object( $person ) )
        {
            $PersonID = $person->id();

            header( "Location: /contact/person/view/$PersonID" );
            exit;
        }
    }
    
}

if ( isset( $OK ) )
{
    if ( $Action == "new" )
        $Action = "insert";
    else if ( $Action == "edit" )
        $Action = "update";
}

$error = false;

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "personedit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "person_edit" => "personedit.tpl"
    ) );
$t->set_block( "person_edit", "person_item_tpl", "person_item" );
$t->set_block( "person_item_tpl", "day_item_tpl", "day_item" );

$t->set_block( "person_edit", "address_item_tpl", "address_item" );
$t->set_block( "address_item_tpl", "address_table_item_tpl", "address_table_item" );
$t->set_block( "address_table_item_tpl", "address_item_select_tpl", "address_item_select" );

$t->set_block( "address_table_item_tpl", "country_item_select_tpl", "country_item_select" );

$t->set_block( "person_edit", "phone_table_item_tpl", "phone_table_item" );
$t->set_block( "phone_table_item_tpl", "phone_item_tpl", "phone_item" );
$t->set_block( "phone_item_tpl", "phone_item_select_tpl", "phone_item_select" );

$t->set_block( "person_edit", "online_table_item_tpl", "online_table_item" );
$t->set_block( "online_table_item_tpl", "online_item_tpl", "online_item" );
$t->set_block( "online_item_tpl", "online_item_select_tpl", "online_item_select" );

$t->set_block( "person_edit", "project_item_tpl", "project_item" );
$t->set_block( "project_item_tpl", "contact_group_item_select_tpl", "contact_group_item_select" );
$t->set_block( "project_item_tpl", "contact_item_select_tpl", "contact_item_select" );
$t->set_block( "project_item_tpl", "project_item_select_tpl", "project_item_select" );

$t->set_block( "person_edit", "errors_tpl", "errors_item" );

$t->set_block( "errors_tpl", "error_firstname_item_tpl", "error_firstname_item" );
$t->set_block( "errors_tpl", "error_lastname_item_tpl", "error_lastname_item" );
$t->set_block( "errors_tpl", "error_birthdate_item_tpl", "error_birthdate_item" );
$t->set_block( "errors_tpl", "error_personno_item_tpl", "error_personno_item" );
$t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );
$t->set_block( "errors_tpl", "error_phone_item_tpl", "error_phone_item" );
$t->set_block( "errors_tpl", "error_online_item_tpl", "error_online_item" );
$t->set_block( "errors_tpl", "error_contact_item_tpl", "error_contact_item" );

$t->set_var( "firstname", "" );
$t->set_var( "lastname", "" );
$t->set_var( "personno", "" );
$t->set_var( "birthdate", "" );
$t->set_var( "comment", "" );
$t->set_var( "person_id", "" );
$t->set_var( "user_id", $UserID );

$t->set_var( "contact_group_item_select", "" );
$t->set_var( "contact_item_select", "" );

/*
    Here we set some variables which are important for some pages which don't allow
    dynamic information in certain categories.
 */

$HOME_PHONE_TYPE_ID = 1;
$WORK_PHONE_TYPE_ID = 4;
$MOBILE_PHONE_TYPE_ID = 3;
$WEB_ONLINE_TYPE_ID = 2;
$EMAIL_ONLINE_TYPE_ID = 1;
$CONTACT_TYPE_ID = 3;
$ADDRESS_TYPE_ID = 1;

$t->set_var( "cv_contact_type_id", "$CONTACT_TYPE_ID" );
$t->set_var( "cv_address_type_id", "$ADDRESS_TYPE_ID" );
$t->set_var( "cv_address_id", "" );
$t->set_var( "cv_home_phone_type_id", "$HOME_PHONE_TYPE_ID" );
$t->set_var( "cv_work_phone_type_id", "$WORK_PHONE_TYPE_ID" );
$t->set_var( "cv_mobile_phone_type_id", "$MOBILE_PHONE_TYPE_ID" );
$t->set_var( "cv_web_online_type_id", "$WEB_ONLINE_TYPE_ID" );
$t->set_var( "cv_email_online_type_id", "$EMAIL_ONLINE_TYPE_ID" );
$t->set_var( "cv_home_phone_id", "" );
$t->set_var( "cv_work_phone_id", "" );
$t->set_var( "cv_mobile_phone_id", "" );
$t->set_var( "cv_web_online_id", "" );
$t->set_var( "cv_email_online_id", "" );

/* End of the pre-defined values */
if( $Action == "insert" || $Action == "update" )
{
    $t->set_var( "error_firstname_item", "" );
    $t->set_var( "error_lastname_item", "" );
    $t->set_var( "error_birthdate_item", "" );
    $t->set_var( "error_personno_item", "" );
    $t->set_var( "error_address_item", "" );
    $t->set_var( "error_phone_item", "" );
    $t->set_var( "error_online_item", "" );
    $t->set_var( "error_contact_item", "" );

//      if( empty( $Online[0] ) )
//      {
//          $t->parse( "error_email_item", "error_email_item_tpl" );
//          $error = true;
//      }
//      else
//      {
//          if( !eZMail::validate( $Online[0] ) )
//          {
//              $t->parse( "error_email_not_valid_item", "error_email_not_valid_item_tpl" );
//              $error = true;
//          }
//      }
        
    if( empty( $FirstName ) )
    {
        $t->parse( "error_firstname_item", "error_firstname_item_tpl" );
        $error = true;
    }
    
    if( empty( $LastName ) )
    {
        $t->parse( "error_lastname_item", "error_lastname_item_tpl" );
        $error = true;
    }

    $birth = new eZDate( $BirthYear, $BirthMonth, $BirthDay );
    if( !$birth->isValid() )
    {
        $t->parse( "error_birthdate_item", "error_birthdate_item_tpl" );
        $error = true;
    }

    $count = max( count( $AddressTypeID ), count( $AddressID ),
                  count( $Street1 ), count( $Street2 ),
                  count( $Zip ), count( $Place ),
                  1 );
    for ( $i = 0; $i < $count; $i++ )
    {
        if( $AddressTypeID[$i] != -1 )
        {
            if ( empty( $Street1[$i] ) || empty( $Place[$i] ) || empty( $Zip[$i] ) || empty( $Country[$i] ) )
            {
                $t->set_var( "error_address_position", $i + 1 );
                $t->parse( "error_address_item", "error_address_item_tpl", true );
                $error = true;
            }
        }
        else
        {
            if ( !empty( $Street1[$i] ) || !empty( $Street2[$i] ) || !empty( $Place[$i] ) || !empty( $Zip[$i] ) || !empty( $Country[$i] ) )
            {
                $t->set_var( "error_address_position", $i + 1 );
                $t->parse( "error_address_item", "error_address_item_tpl", true );
                $error = true;
            }
        }
    }

    $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if( $PhoneTypeID[$i] != -1 )
        {
            if ( empty( $Phone[$i] ) )
            {
                $t->set_var( "error_phone_position", $i + 1 );
                $t->parse( "error_phone_item", "error_phone_item_tpl", true );
                $error = true;
            }
        }
        else
        {
            if ( !empty( $Phone[$i] ) )
            {
                $t->set_var( "error_phone_position", $i + 1 );
                $t->parse( "error_phone_item", "error_phone_item_tpl", true );
                $error = true;
            }
        }
    }
        
    $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if( $OnlineTypeID[$i] != -1 )
        {
            if ( empty( $Online[$i] ) )
            {
                $t->set_var( "error_online_position", $i + 1 );
                $t->parse( "error_online_item", "error_online_item_tpl", true );
                $error = true;
            }
        }
        else
        {
            if ( !empty( $Online[$i] ) )
            {
                $t->set_var( "error_online_position", $i + 1 );
                $t->parse( "error_online_item", "error_online_item_tpl", true );
                $error = true;
            }
        }
    }

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

if( ( $Action == "insert" || $Action == "update" ) && $error == false )
{
    $person = new eZPerson( $PersonID, true );
    $person->setFirstName( $FirstName );
    $person->setLastName( $LastName );

    $Birth = new eZDate( $BirthYear, $BirthMonth, $BirthDay );
    $person->setBirthDay( $Birth->mySQLDate() );
    $person->setPersonNo( $PersonNo );
    $person->setComment( $Comment );
    $person->setContact( $ContactID );
    $person->setComment( $Comment );
    $person->store();

    $person->setProjectState( $ProjectID );

    // address
    $person->removeAddresses();
    $count = max( count( $AddressTypeID ), count( $AddressID ),
                  count( $Street1 ), count( $Street2 ),
                  count( $Zip ), count( $Place ) );
    for ( $i = 0; $i < $count; $i++ )
    {
        if ( !empty( $Street1[$i] ) && !empty( $Place[$i] ) &&
             !empty( $Zip[$i] ) && !empty( $Country[$i] ) && !empty( $AddressTypeID ) )
        {
            $address = new eZAddress( $AddressID[$i], true );
            $address->setStreet1( $Street1[$i] );
            $address->setStreet2( $Street2[$i] );
            $address->setZip( $Zip[$i] );
            $address->setPlace( $Place[$i] );
            $address->setAddressType( $AddressTypeID[$i] );
            $address->setCountry( $Country[$i] );
            $address->store();

            $person->addAddress( $address );
        }
    }

    $person->removePhones();
    $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
    for( $i=0; $i < $count; $i++ )
    {
        if( !empty( $PhoneTypeID[$i] ) && !empty( $Phone[$i] ) )
        {
            $phone = new eZPhone( $PhoneID[$i], true );
            $phone->setNumber( $Phone[$i] );
            $phone->setPhoneTypeID( $PhoneTypeID[$i] );
            $phone->store();

            $person->addPhone( $phone );
        }
    }

    $person->removeOnlines();
    $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
    for( $i=0; $i < $count; $i++ )
    {
        if( !empty( $OnlineTypeID[$i] ) && !empty( $Online[$i] ) )
        {
            $online = new eZOnline( $OnlineID[$i], true );
            $online->setURL( $Online[$i] );
            $online->setOnlineTypeID( $OnlineTypeID[$i] );
            $online->store();

            $person->addOnline( $online );
        }
    }

    $PersonID = $person->id();

    $t->set_var( "user_id", $UserID );
    $t->set_var( "person_id", $PersonID );

    header( "Location: /contact/person/view/$PersonID" );
}

/*
    The user wants to edit an existing person.
    
    We fetch the appropriate variables.
*/

if( $Action == "edit" )
{
    $person = new eZPerson( $PersonID, true );

    $FirstName = $person->firstName();
    $LastName = $person->lastName();
    $Birth = new eZDate();
    $Birth->setMySQLDate( $person->birthDate() );
    $BirthYear = $Birth->year();
    $BirthMonth = $Birth->month();
    $BirthDay = $Birth->day();
    $Comment = $person->comment();

    $addresses = $person->addresses();
    foreach( $addresses as $address )
    {
        $AddressTypeID[] = $address->addressTypeID();
        $AddressID[] = $address->id();
        $Street1[] = $address->street1();
        $Street2[] = $address->street2();
        $Zip[] = $address->zip();
        $Place[] = $address->place();
        $country = $address->country();
        $Country[] = $country->id();
    }

    $phones = $person->phones();
    foreach( $phones as $phone )
    {
        $PhoneTypeID[] = $phone->phoneTypeID();
        $PhoneID[] = $phone->id();
        $Phone[] = $phone->number();
    }

    $onlines = $person->onlines();
    foreach( $onlines as $online )
    {
        $OnlineTypeID[] = $online->onlineTypeID();
        $OnlineID[] = $online->id();
        $Online[] = $online->url();
    }

    $ContactID = $person->contact();
    $ProjectID = $person->projectState();
}

/*
    The user wants to create a new person.
    
    We present an empty form.
 */
if( $Action == "new" || $Action == "formdata" || $Action == "edit" )
{
    $Action_value = "new";

    $t->set_var( "person_id", $PersonID );

    $t->set_var( "user_id", $user->id() );
    if ( isset( $FirstName ) )
        $t->set_var( "firstname", $FirstName );
    else
        $t->set_var( "firstname", $user->firstName() );
    if ( isset( $LastName ) )
        $t->set_var( "lastname", $LastName );
    else
        $t->set_var( "lastname", $user->lastName() );

    for ( $i = 1; $i <= 31; $i++ )
    {
        $t->set_var( "day_id", $i );
        $t->set_var( "day_value", $i );
        $t->set_var( "selected", "" );
        if ( $BirthDay == $i )
            $t->set_var( "selected", "selected" );
        $t->parse( "day_item", "day_item_tpl", true );
    }

    $t->set_var( "select_january", "" );
    $t->set_var( "select_feburary", "" );
    $t->set_var( "select_march", "" );
    $t->set_var( "select_april", "" );
    $t->set_var( "select_may", "" );
    $t->set_var( "select_june", "" );
    $t->set_var( "select_july", "" );
    $t->set_var( "select_august", "" );
    $t->set_var( "select_september", "" );
    $t->set_var( "select_october", "" );
    $t->set_var( "select_november", "" );
    $t->set_var( "select_december", "" );

    if ( $BirthMonth == 2 )
        $t->set_var( "select_february", "selected" );
    else if ( $BirthMonth == 3 )
        $t->set_var( "select_march", "selected" );
    else if ( $BirthMonth == 4 )
        $t->set_var( "select_april", "selected" );
    else if ( $BirthMonth == 5 )
        $t->set_var( "select_may", "selected" );
    else if ( $BirthMonth == 6 )
        $t->set_var( "select_june", "selected" );
    else if ( $BirthMonth == 7 )
        $t->set_var( "select_july", "selected" );
    else if ( $BirthMonth == 8 )
        $t->set_var( "select_august", "selected" );
    else if ( $BirthMonth == 9 )
        $t->set_var( "select_september", "selected" );
    else if ( $BirthMonth == 10 )
        $t->set_var( "select_october", "selected" );
    else if ( $BirthMonth == 11 )
        $t->set_var( "select_november", "selected" );
    else if ( $BirthMonth == 12 )
        $t->set_var( "select_december", "selected" );
    else
        $t->set_var( "select_january", "selected" );

    $t->set_var( "birthyear", $BirthYear );

    $t->set_var( "comment", $Comment );

    $t->parse( "person_item", "person_item_tpl" );

    $phone_types = eZPhoneType::getAll();
    $online_types = eZOnlineType::getAll();
    $address_types = eZAddressType::getAll();
    $countries = eZCountry::getAll();

    if ( !isset( $PhoneDelete ) )
    {
        $PhoneDelete = array();
    }
    if ( !isset( $OnlineDelete ) )
    {
        $OnlineDelete = array();
    }
    if ( !isset( $AddressDelete ) )
    {
        $AddressDelete = array();
    }

    if ( isset( $NewAddress ) )
    {
        $AddressTypeID[] = "";
        $AddressID[] = "";
        $Street1[] = "";
        $Street2[] = "";
        $Zip[] = "";
        $Place[] = "";
        $Country[] = "";
    }
    $count = max( count( $AddressTypeID ), count( $AddressID ),
                  count( $Street1 ), count( $Street2 ),
                  count( $Zip ), count( $Place ), 1 );
    $item = 0;
    for ( $i = 0; $i < $count || $item < $count; $i++ )
    {
        if ( !in_array( $i, array_values( $AddressDelete ) ) )
        {
            $t->set_var( "street1", $Street1[$i] );
            $t->set_var( "street2", $Street2[$i] );
            $t->set_var( "zip", $Zip[$i] );
            $t->set_var( "place", $Place[$i] );
            $t->set_var( "address_id", $AddressID[$i] );
            $t->set_var( "address_index", $i );
            $t->set_var( "address_position", $i + 1 );

            $t->set_var( "address_item_select", "" );

            foreach( $address_types as $address_type )
            {
                $t->set_var( "type_id", $address_type->id() );
                $t->set_var( "type_name", $address_type->name() );
                $t->set_var( "selected", "" );
                if ( $address_type->id() == $AddressTypeID[$i] )
                    $t->set_var( "selected", "selected" );
                $t->parse( "address_item_select", "address_item_select_tpl", true );
            }
            foreach( $countries as $country )
            {
                $t->set_var( "type_id", $country->id() );
                $t->set_var( "type_name", $country->name() );
                $t->set_var( "selected", "" );
                if ( $country->id() == $Country[$i] )
                    $t->set_var( "selected", "selected" );
                $t->parse( "country_item_select", "country_item_select_tpl", true );
            }

            $t->parse( "address_table_item", "address_table_item_tpl", true );
            $item++;
        }
    }
    $t->parse( "address_item", "address_item_tpl" );

    if ( isset( $NewPhone ) )
    {
        $PhoneTypeID[] = "";
        $PhoneID[] = "";
        $Phone[] = "";
    }
    $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ), 3 );
    $item = 0;
    for ( $i = 0; $i < $count || $item < $count; $i++ )
    {
        if ( ( $item % 3 == 0 ) && $item > 0 )
        {
            $t->parse( "phone_table_item", "phone_table_item_tpl", true );
            $t->set_var( "phone_item" );
        }
        if ( !in_array( $i, array_values( $PhoneDelete ) ) )
        {
            $t->set_var( "phone_number", $Phone[$i] );
            $t->set_var( "phone_id", $PhoneID[$i] );
            $t->set_var( "phone_index", $i );
            $t->set_var( "phone_position", $i + 1 );

            $t->set_var( "phone_item_select", "" );

            foreach( $phone_types as $phone_type )
            {
                $t->set_var( "type_id", $phone_type->id() );
                $t->set_var( "type_name", $phone_type->name() );
                $t->set_var( "selected", "" );
                if ( $phone_type->id() == $PhoneTypeID[$i] )
                    $t->set_var( "selected", "selected" );
                $t->parse( "phone_item_select", "phone_item_select_tpl", true );
            }

            $t->parse( "phone_item", "phone_item_tpl", true );
            $item++;
        }
    }
    $t->parse( "phone_table_item", "phone_table_item_tpl", true );

    if ( isset( $NewOnline ) )
    {
        $OnlineTypeID[] = "";
        $OnlineID[] = "";
        $Online[] = "";
    }
    $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ), 2 );
    $item = 0;
    for ( $i = 0; $i < $count || $item < $count; $i++ )
    {
        if ( ( $item % 3 == 0 ) && $item > 0 )
        {
            $t->parse( "online_table_item", "online_table_item_tpl", true );
            $t->set_var( "online_item" );
        }
        if ( !in_array( $i, array_values( $OnlineDelete ) ) )
        {
            $t->set_var( "online_value", $Online[$i] );
            $t->set_var( "online_id", $OnlineID[$i] );
            $t->set_var( "online_index", $i );
            $t->set_var( "online_position", $i + 1 );

            $t->set_var( "online_item_select", "" );

            foreach( $online_types as $online_type )
            {
                $t->set_var( "type_id", $online_type->id() );
                $t->set_var( "type_name", $online_type->name() );
                $t->set_var( "selected", "" );
                if ( $online_type->id() == $OnlineTypeID[$i] )
                    $t->set_var( "selected", "selected" );
                $t->parse( "online_item_select", "online_item_select_tpl", true );
            }

            $t->parse( "online_item", "online_item_tpl", true );
            $item++;
        }
    }
    $t->parse( "online_table_item", "online_table_item_tpl", true );

    $groups = eZUserGroup::getAll();
    foreach( $groups as $group )
    {
        $t->set_var( "type_id", $group->id() );
        $t->set_var( "type_name", $group->name() );
        $t->set_var( "selected", "" );
        if ( $ContactGroupID == $group->id() )
            $t->set_var( "selected", "selected" );
        $t->parse( "contact_group_item_select", "contact_group_item_select_tpl", true );
    }

    if ( $ContactGroupID < 1 )
    {
        $users = eZUser::getAll( "name" );
    }
    else
    {
        $group = new eZUserGroup();
        $users = $group->users( $ContactGroupID, "name" );
    }

    foreach( $users as $user )
    {
        $t->set_var( "type_id", $user->id() );
        $t->set_var( "type_firstname", $user->firstName() );
        $t->set_var( "type_lastname", $user->lastName() );
        $t->set_var( "selected", "" );
        if ( $ContactID == $user->id() )
            $t->set_var( "selected", "selected" );
        $t->parse( "contact_item_select", "contact_item_select_tpl", true );
    }

    $project_types = eZProjectType::findTypes();
    foreach( $project_types as $project_type )
    {
        $t->set_var( "type_id", $project_type->id() );
        $t->set_var( "type_name", $project_type->name() );
        $t->set_var( "selected", "" );
        if ( $ProjectID == $project_type->id() )
            $t->set_var( "selected", "selected" );
        $t->parse( "project_item_select", "project_item_select_tpl", true );
    }

    $t->parse( "project_item", "project_item_tpl", true );
}



// Template variabler.

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "person_edit"  );


?>
