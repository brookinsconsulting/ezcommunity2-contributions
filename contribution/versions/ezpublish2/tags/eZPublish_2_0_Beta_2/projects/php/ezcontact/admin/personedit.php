<?
/*
    Edit a person
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/ezmail.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlog.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );

include_once( "ezaddress/classes/ezcountry.php" );
include_once( "ezcontact/classes/ezprojecttype.php" );

if ( isset( $CompanyEdit ) )
{
    $item_type = "company";
    $item_id = $CompanyID;
}
else
{
    $item_type = "person";
    $item_id = $PersonID;
}

if ( isset( $ListConsultation ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/consultation/$item_type/list/$item_id" );
    exit;
}

if ( isset( $NewConsultation ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/consultation/$item_type/new/$item_id" );
    exit;
}

if ( isset( $Back ) )
{
    if ( isset( $CompanyEdit ) )
    {
        $company = new eZCompany( $CompanyID );
        $categories = $company->categories( false, false );
        $id = $categories[0];
    }
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/$item_type/list/$id" );
    exit;
}

if ( isset( $Delete ) )
{
    $Action = "delete";
}

if( $Action == "delete" )
{
    if ( isset( $Confirm ) )
    {
        if ( isset( $CompanyEdit ) )
        {
            $categories =& eZCompany::categories( $CompanyID, false, 1 );
            $id =& $categories[0];
            eZCompany::delete( $CompanyID );
        }
        else
        {
            eZPerson::delete( $PersonID );
        }

        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/$item_type/list/$id" );
        exit;
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

if ( isset( $CompanyEdit ) )
{
    $template_file = "companyedit.tpl";
    $language_file = "companyedit.php";
}
else
{
    $template_file = "personedit.tpl";
    $language_file = "personedit.php";
}

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, $language_file );
$t->setAllStrings();

$t->set_file( array(                    
    "person_edit" => $template_file
    ) );

$t->set_block( "person_edit", "edit_tpl", "edit_item" );
$t->set_block( "person_edit", "confirm_tpl", "confirm_item" );

if ( isset( $CompanyEdit ) )
{
    $t->set_block( "edit_tpl", "company_item_tpl", "company_item" );
    $t->set_block( "company_item_tpl", "company_type_select_tpl", "company_type_select" );

    $t->set_block( "edit_tpl", "logo_item_tpl", "logo_item" );
    $t->set_block( "edit_tpl", "image_item_tpl", "image_item" );
}
else
{
    $t->set_block( "edit_tpl", "person_item_tpl", "person_item" );
    $t->set_block( "person_item_tpl", "day_item_tpl", "day_item" );
}

$t->set_block( "edit_tpl", "address_table_item_tpl", "address_table_item" );
$t->set_block( "address_table_item_tpl", "address_item_tpl", "address_item" );
$t->set_block( "address_item_tpl", "address_item_select_tpl", "address_item_select" );

$t->set_block( "address_item_tpl", "country_item_select_tpl", "country_item_select" );

$t->set_block( "edit_tpl", "phone_table_item_tpl", "phone_table_item" );
$t->set_block( "phone_table_item_tpl", "phone_item_tpl", "phone_item" );
$t->set_block( "phone_item_tpl", "phone_item_select_tpl", "phone_item_select" );

$t->set_block( "edit_tpl", "online_table_item_tpl", "online_table_item" );
$t->set_block( "online_table_item_tpl", "online_item_tpl", "online_item" );
$t->set_block( "online_item_tpl", "online_item_select_tpl", "online_item_select" );

$t->set_block( "edit_tpl", "project_item_tpl", "project_item" );
$t->set_block( "project_item_tpl", "contact_group_item_select_tpl", "contact_group_item_select" );
$t->set_block( "project_item_tpl", "contact_item_select_tpl", "contact_item_select" );
$t->set_block( "project_item_tpl", "project_item_select_tpl", "project_item_select" );

$t->set_block( "person_edit", "delete_item_tpl", "delete_item" );

$t->set_block( "edit_tpl", "errors_tpl", "errors_item" );

if ( isset( $CompanyEdit ) )
{
    $t->set_block( "errors_tpl", "error_name_item_tpl", "error_name_item" );
}
else
{
    $t->set_block( "errors_tpl", "error_firstname_item_tpl", "error_firstname_item" );
    $t->set_block( "errors_tpl", "error_lastname_item_tpl", "error_lastname_item" );
    $t->set_block( "errors_tpl", "error_birthdate_item_tpl", "error_birthdate_item" );
}

$t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );
$t->set_block( "errors_tpl", "error_phone_item_tpl", "error_phone_item" );
$t->set_block( "errors_tpl", "error_online_item_tpl", "error_online_item" );
$t->set_block( "errors_tpl", "error_contact_item_tpl", "error_contact_item" );

$confirm = false;

if( $Action == "delete" )
{
    if ( !isset( $Confirm ) )
    {
        $confirm = true;

        if ( isset( $CompanyEdit ) )
        {
            $t->set_var( "company_id", $CompanyID );
            $company = new eZCompany( $CompanyID );
            $t->set_var( "name", $company->name() );
        }
        else
        {
            $t->set_var( "person_id", $PersonID );
            $person = new eZPerson( $PersonID );
            $t->set_var( "firstname", $person->firstName() );
            $t->set_var( "lastname", $person->lastName() );
        }
        $t->set_var( "edit_item", "" );
        $t->set_var( "action_value", $Action );
        $t->set_var( "delete_item", "" );
        $t->parse( "confirm_item", "confirm_tpl" );
    }
}

if ( !$confirm )
{

    $t->set_var( "confirm_item", "" );

    if ( isset( $CompanyEdit ) )
    {
        $t->set_var( "name", "" );
        $t->set_var( "companyno", "" );
    }
    else
    {
        $t->set_var( "firstname", "" );
        $t->set_var( "lastname", "" );
        $t->set_var( "birthdate", "" );
        $t->set_var( "comment", "" );
        $t->set_var( "person_id", "" );
    }

    $t->set_var( "user_id", $UserID );

    $t->set_var( "contact_group_item_select", "" );
    $t->set_var( "contact_item_select", "" );

/* End of the pre-defined values */
    if( $Action == "insert" || $Action == "update" )
    {
        if ( isset( $CompanyEdit ) )
        {
            $t->set_var( "error_name_item", "" );
        }
        else
        {
            $t->set_var( "error_firstname_item", "" );
            $t->set_var( "error_lastname_item", "" );
            $t->set_var( "error_birthdate_item", "" );
        }

        $t->set_var( "error_address_item", "" );
        $t->set_var( "error_phone_item", "" );
        $t->set_var( "error_online_item", "" );
        $t->set_var( "error_contact_item", "" );

        if ( isset( $CompanyEdit ) )
        {
            if( empty( $Name ) )
            {
                $t->parse( "error_name_item", "error_name_item_tpl" );
                $error = true;
            }
        }
        else
        {
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
        if ( isset( $CompanyEdit ) )
        {
            $company = new eZCompany( $CompanyID, true );
            $company->setName( $Name );

            $company->setCompanyNo( $CompanyNo );
            $company->setContact( $ContactID );
            $company->setComment( $Comment );
            $company->store();

            $item_id = $company->id();
            $CompanyID = $item_id;

            // Update categories
            if ( ( $CompanyCategoryID ) != "" )
            {
                $company->removeCategories();
                $category = new eZCompanyType();
                for( $i=0; $i<count( $CompanyCategoryID ); $i++ )
                {
                    $category->get( $CompanyCategoryID[$i] );
                    $category->addCompany( $company );
                }
            }

            // Upload images
            $file = new eZImageFile();
            if ( $file->getUploadedFile( "logo" ) )
            {
                $logo = new eZImage();
                $logo->setName( "Logo" );
                $logo->setImage( $file );
                $logo->store();

                $company->setLogoImage( $logo );
            }
            else
            {
                print( $file->name() . " not uploaded successfully" );
            }
  
            // Upload images
            $file = new eZImageFile();
            if ( $file->getUploadedFile( "image" ) )
            {
                $image = new eZImage( );
                $image->setName( "Image" );
                $image->setImage( $file );

                $image->store();

                $company->setCompanyImage( $image );
            }
            else
            {
                print( $file->name() . " not uploaded successfully" );
            }

            $item =& $company;
        }
        else
        {
            $person = new eZPerson( $PersonID, true );
            $person->setFirstName( $FirstName );
            $person->setLastName( $LastName );

            $Birth = new eZDate( $BirthYear, $BirthMonth, $BirthDay );
            $person->setBirthDay( $Birth->mySQLDate() );
            $person->setContact( $ContactID );
            $person->setComment( $Comment );
            $person->store();

            $item_id = $person->id();
            $PersonID = $item_id;

            $item =& $person;
        }

        $item->setProjectState( $ProjectID );

        // address
        $item->removeAddresses();
        $count = max( count( $AddressTypeID ), count( $AddressID ),
                      count( $Street1 ), count( $Street2 ),
                      count( $Zip ), count( $Place ) );
        for ( $i = 0; $i < $count; $i++ )
        {
            if ( !empty( $Street1[$i] ) && !empty( $Place[$i] ) &&
                 !empty( $Zip[$i] ) && !empty( $Country[$i] ) && !empty( $AddressTypeID ) )
            {
                $address = new eZAddress( false, true );
                $address->setStreet1( $Street1[$i] );
                $address->setStreet2( $Street2[$i] );
                $address->setZip( $Zip[$i] );
                $address->setPlace( $Place[$i] );
                $address->setAddressType( $AddressTypeID[$i] );
                $address->setCountry( $Country[$i] );
                $address->store();

                $item->addAddress( $address );
            }
        }

        $item->removePhones();
        $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ) );
        for( $i=0; $i < $count; $i++ )
        {
            if( !empty( $PhoneTypeID[$i] ) && !empty( $Phone[$i] ) )
            {
                $phone = new eZPhone( false, true );
                $phone->setNumber( $Phone[$i] );
                $phone->setPhoneTypeID( $PhoneTypeID[$i] );
                $phone->store();

                $item->addPhone( $phone );
            }
        }

        $item->removeOnlines();
        $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ) );
        for( $i=0; $i < $count; $i++ )
        {
            if( !empty( $OnlineTypeID[$i] ) && !empty( $Online[$i] ) )
            {
                $online = new eZOnline( false, true );
                $online->setURL( $Online[$i] );
                $online->setOnlineTypeID( $OnlineTypeID[$i] );
                $online->store();

                $item->addOnline( $online );
            }
        }

        if ( isset( $CompanyEdit ) )
        {
            $CompanyID = $company->id();
        }
        else
        {
            $PersonID = $person->id();
        }

        $t->set_var( "user_id", $UserID );
        $t->set_var( "person_id", $PersonID );
        $t->set_var( "company_id", $CompanyID );

        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/$item_type/view/$item_id" );
    }

/*
    The user wants to edit an existing person.
    
    We fetch the appropriate variables.
*/

    if( $Action == "edit" )
    {
        if ( isset( $CompanyEdit ) )
        {
            $company = new eZCompany( $CompanyID, true );
            $item =& $company;

            $Name = $company->name();
            $Comment = $company->comment();
            $CompanyNo = $company->companyNo();
        }
        else
        {
            $person = new eZPerson( $PersonID, true );
            $item =& $person;

            $FirstName = $person->firstName();
            $LastName = $person->lastName();
            $Birth = new eZDate();
            $Birth->setMySQLDate( $person->birthDate() );
            $BirthYear = $Birth->year();
            $BirthMonth = $Birth->month();
            $BirthDay = $Birth->day();
            $Comment = $person->comment();
        }

        $addresses = $item->addresses();
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

        $phones = $item->phones();
        foreach( $phones as $phone )
        {
            $PhoneTypeID[] = $phone->phoneTypeID();
            $PhoneID[] = $phone->id();
            $Phone[] = $phone->number();
        }

        $onlines = $item->onlines();
        foreach( $onlines as $online )
        {
            $OnlineTypeID[] = $online->onlineTypeID();
            $OnlineID[] = $online->id();
            $Online[] = $online->url();
        }

        $ContactID = $item->contact();
        $ProjectID = $item->projectState();
    }

/*
    The user wants to create a new person.
    
    We present an empty form.
 */
    if( $Action == "new" || $Action == "formdata" || $Action == "edit" )
    {
        $Action_value = "new";

        if ( isset( $CompanyEdit ) )
        {
            $t->set_var( "company_id", $CompanyID );

            if ( is_numeric( $CompanyID ) )
            {
                if ( isset( $DeleteImage ) )
                {
                    eZCompany::deleteImage( $CompanyID );
                }

                if ( isset( $DeleteLogo ) )
                {
                    eZCompany::deleteLogo( $CompanyID );
                }
            }

            $t->set_var( "user_id", $user->id() );
            $t->set_var( "name", $Name );

            $t->set_var( "comment", $Comment );
            $t->set_var( "companyno", $CompanyNo );

            // Company type selector
            $companyTypeList = eZCompanyType::getTree();

            $categoryList =& eZCompany::categories( $CompanyID, false );
            $category_values = array_values( $categoryList );
            foreach( $companyTypeList as $companyTypeItem )
            {
                $t->set_var( "company_type_name", $companyTypeItem[0]->name() );
                $t->set_var( "company_type_id", $companyTypeItem[0]->id() );

                if ( $companyTypeItem[1] > 0 )
                    $t->set_var( "company_type_level", str_repeat( "&nbsp;", $companyTypeItem[1] ) );
                else
                    $t->set_var( "company_type_level", "" );

                if ( $company )
                {
                    $found = in_array( $companyTypeItem[0]->id(), $category_values );
                    if ( $found == true )
                    {
                        $t->set_var( "is_selected", "selected" );
                    }
                    else
                    {
                        $t->set_var( "is_selected", "" );
                    }
                }
                else if ( isset( $NewCompanyCategory ) )
                {
                    if ( $NewCompanyCategory == $companyTypeItem[0]->id() )
                    {
                        $t->set_var( "is_selected", "selected" );
                    }
                    else
                    {
                        $t->set_var( "is_selected", "" );
                    }
                }
                else
                {
                    $t->set_var( "is_selected", "" );
                }


                $t->parse( "company_type_select", "company_type_select_tpl", true );
            }

            $t->parse( "company_item", "company_item_tpl" );
        }
        else
        {
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

            $birth_array = array( 1 => "select_january",
                                  2 => "select_februrary",
                                  3 => "select_march",
                                  4 => "select_april",
                                  5 => "select_may",
                                  6 => "select_june",
                                  7 => "select_july",
                                  8 => "select_august",
                                  9 => "select_september",
                                  10 => "select_october",
                                  11 => "select_november",
                                  12 => "select_december" );

            foreach( $birth_array as $month )
            {
                $t->set_var( $month, "" );
            }

            $var_name =& $birth_array[$BirthMonth];
            if ( empty( $var_name ) )
                $var_name =& $birth_array[1];

            $t->set_var( $var_name, "selected" );

            $t->set_var( "birthyear", $BirthYear );

            $t->set_var( "comment", $Comment );

            $t->parse( "person_item", "person_item_tpl" );
        }

        $phone_types =& eZPhoneType::getAll();
        $online_types =& eZOnlineType::getAll();
        $address_types =& eZAddressType::getAll();
        $countries =& eZCountry::getAllArray();

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

        $AddressMinimum = $ini->read_var( "eZContactMain", "AddressMinimum" );
        $PhoneMinimum = $ini->read_var( "eZContactMain", "PhoneMinimum" );
        $OnlineMinimum = $ini->read_var( "eZContactMain", "OnlineMinimum" );
        $AddressWidth = $ini->read_var( "eZContactMain", "AddressWidth" );
        $PhoneWidth = $ini->read_var( "eZContactMain", "PhoneWidth" );
        $OnlineWidth = $ini->read_var( "eZContactMain", "OnlineWidth" );

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
                      count( $Zip ), count( $Place ), $AddressMinimum );
        $item = 0;
        $AddressDeleteValues =& array_values( $AddressDelete );
        for ( $i = 0; $i < $count || $item < $count; $i++ )
        {
            if ( ( $item % $AddressWidth == 0 ) && $item > 0 )
            {
                $t->parse( "address_table_item", "address_table_item_tpl", true );
                $t->set_var( "address_item" );
            }
            if ( !in_array( $i, $AddressDeleteValues ) )
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
                    $t->set_var( "type_id", $country["ID"] );
                    $t->set_var( "type_name", $country["Name"] );
                    $t->set_var( "selected", "" );
                    if ( $country["ID"] == $Country[$i] )
                        $t->set_var( "selected", "selected" );
                    $t->parse( "country_item_select", "country_item_select_tpl", true );
                }

                $t->parse( "address_item", "address_item_tpl", true );
                $item++;
            }
        }
        $t->parse( "address_table_item", "address_table_item_tpl", true );

//          $t->parse( "address_item", "address_item_tpl" );

        if ( isset( $NewPhone ) )
        {
            $PhoneTypeID[] = "";
            $PhoneID[] = "";
            $Phone[] = "";
        }
        $count = max( count( $PhoneTypeID ), count( $PhoneID ), count( $Phone ), $PhoneMinimum );
        $item = 0;
        $PhoneDeleteValues =& array_values( $PhoneDelete );
        for ( $i = 0; $i < $count || $item < $count; $i++ )
        {
            if ( ( $item % $PhoneWidth == 0 ) && $item > 0 )
            {
                $t->parse( "phone_table_item", "phone_table_item_tpl", true );
                $t->set_var( "phone_item" );
            }
            if ( !in_array( $i, $PhoneDeleteValues ) )
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
        $count = max( count( $OnlineTypeID ), count( $OnlineID ), count( $Online ), $OnlineMinimum );
        $item = 0;
        $OnlineDeleteValues =& array_values( $OnlineDelete );
        for ( $i = 0; $i < $count || $item < $count; $i++ )
        {
            if ( ( $item % $OnlineWidth == 0 ) && $item > 0 )
            {
                $t->parse( "online_table_item", "online_table_item_tpl", true );
                $t->set_var( "online_item" );
            }
            if ( !in_array( $i, $OnlineDeleteValues ) )
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

        $groups =& eZUserGroup::getAll();
        foreach( $groups as $group )
        {
            $t->set_var( "type_id", $group->id() );
            $t->set_var( "type_name", $group->name() );
            $t->set_var( "selected", "" );
            if ( $ContactGroupID == $group->id() )
                $t->set_var( "selected", "selected" );
            $t->parse( "contact_group_item_select", "contact_group_item_select_tpl", true );
        }

        $t->set_var( "user_search", $UserSearch );

        if ( $ContactGroupID == -1 )
        {
            $users =& eZUser::getAll( "name", true, $UserSearch );
        }
        else if ( $ContactGroupID < 1 )
        {
            $users = array();
            if ( is_numeric( $ContactID ) )
            {
                $user = new eZUser( $ContactID );
                $users[] = $user;
            }
        }
        else
        {
            $group = new eZUserGroup();
            $users =& $group->users( $ContactGroupID, "name", $UserSearch );
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

        $project_types =& eZProjectType::findTypes();
        $t->set_var( "none_selected", "" );
        $t->set_var( "all_selected", "" );
        if ( $ContactGroupID == -1 )
        {
            $t->set_var( "all_selected", "selected" );
        }
        else if ( $ContactGroupID < 1 )
        {
            $t->set_var( "none_selected", "selected" );
        }
        $t->set_var( "project_item_select", "" );
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

        if ( isset( $CompanyEdit ) )
        {
            // View logo.
            $logoImage = eZCompany::logoImage( $CompanyID );
            if ( is_numeric( $LogoImageID ) )
            {
                $logoImage = new eZImage( $LogoImageID );
            }

            $t->set_var( "logo_item", "&nbsp;" );
            if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $logoImage->id() != 0 ) )
            {
                $variation = $logoImage->requestImageVariation( 150, 150 );
        
                $t->set_var( "logo_image_src", "/" . $variation->imagePath() );

                $t->set_var( "logo_image_width", $variation->width() );
                $t->set_var( "logo_image_height", $variation->height() );

                $t->set_var( "logo_name", $logoImage->name() );
                $t->set_var( "logo_id", $logoImage->id() );
        
                $t->parse( "logo_item", "logo_item_tpl" );
            }

            // View company image.
            $companyImage = eZCompany::companyImage( $CompanyID );
            if ( is_numeric( $CompanyImageID ) )
            {
                $companyImage = new eZImage( $CompanyImageID );
            }

            $t->set_var( "image_item", "&nbsp;" );
            if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $companyImage->id() != 0 ) )
            {
                $variation = $companyImage->requestImageVariation( 150, 150 );
        
                $t->set_var( "image_src", "/" . $variation->imagePath() );
                $t->set_var( "image_width", $variation->width() );
                $t->set_var( "image_height", $variation->height() );

                $t->set_var( "image_name", $companyImage->name() );
                $t->set_var( "image_id", $companyImage->id() );
        
                $t->parse( "image_item", "image_item_tpl" );
            }
        }
    }

// Template variabler.

    if ( is_numeric( $CompanyID ) || is_numeric( $PersonID ) )
        $t->parse( "delete_item", "delete_item_tpl" );
    else
        $t->set_var( "delete_item", "" );

    $t->set_var( "action_value", $Action_value );

    $t->parse( "edit_item", "edit_tpl" );
}

$t->pparse( "output", "person_edit"  );


?>
