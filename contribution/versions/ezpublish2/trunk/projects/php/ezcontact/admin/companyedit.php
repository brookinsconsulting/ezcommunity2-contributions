<?
/*
  Editere firma.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );
//  include_once( "classes/ezsession.php" );
//  include_once( "classes/ezusergroup.php" );
//  include_once( "classes/ezuser.php" );

include_once( "classes/ezimagefile.php" );
include_once( "classes/ezmail.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );

//  if( !eZPermission::checkPermission( $user, "eZContact", "CompanyAdd" ) && $Action == "new" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=CompanyAdd&tried=new&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "CompanyAdd" ) && $Action == "insert" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=CompanyAdd&tried=insert&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "CompanyModify" ) )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=CompanyModify&tried=update&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "CompanyModify" ) && $Action == "edit" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=CompanyModify&tried=edit&module=ezcontact" );
//      exit();
//  }

//  if( !eZPermission::checkPermission( $user, "eZContact", "CompanyDelete" ) && $Action == "delete" )
//  {
//      header( "Location: /error.php?type=500&reason=missingpermission&permission=CompanyDelete&tried=delete&module=ezcontact" );
//      exit();
//  }

if ( isSet ( $Back ) )
{
    header( "Location: /contact/companytype/list/" );
    exit();
}

if ( isSet ( $Preview ) )
{
    header( "Location: /contact/company/view/$CompanyID" );
    exit();
}

if( $Action == "delete" )
{
    $company = new eZCompany();
    $company->get( $CompanyID );
    $company->delete();

    header( "Location: /contact/company/list/" );
}

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "companyedit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "company_edit" => "companyedit.tpl"
    ) );

$t->set_block( "company_edit", "company_item_tpl", "company_item" );

$t->set_block( "company_edit", "address_item_tpl", "address_item" );

$t->set_block( "company_edit", "phone_item_tpl", "phone_item" );
$t->set_block( "company_edit", "fax_item_tpl", "fax_item" );

$t->set_block( "company_edit", "web_item_tpl", "web_item" );
$t->set_block( "company_edit", "email_item_tpl", "email_item" );

$t->set_block( "company_edit", "logo_add_tpl", "logo_add" );
$t->set_block( "company_edit", "image_add_tpl", "image_add" );
$t->set_block( "company_edit", "logo_edit_tpl", "logo_edit" );
$t->set_block( "company_edit", "image_edit_tpl", "image_edit" );

$t->set_block( "company_edit", "company_type_select_tpl", "company_type_select" );

$t->set_block( "company_edit", "errors_tpl", "errors_item" );
$t->set_var( "errors_item", "" );
$message = "Registrer nytt kontaktfirma";

$Online[] = $OnlineWeb;
$Online[] = $OnlineEmail;
$LoginName = $Name;

$t->set_var( "name", "$Name" );
$t->set_var( "description", "$Description" );
$t->set_var( "street1", "$Street1" );
$t->set_var( "street2", "$Street2" );
$t->set_var( "zip", "$Zip" );
$t->set_var( "place", "$Place" );
$t->set_var( "telephone", $Phone[0] );
$t->set_var( "fax", $Phone[1] );
$t->set_var( "email", $Online[1] );
$t->set_var( "web", $Online[0] );
$t->set_var( "companyno", "$CompanyNo" );
$t->set_var( "company_id", "$CompanyID" );
$t->set_var( "password", "$Password" );
$t->set_var( "repeat_password", "$RepeatPassword" );
$t->set_var( "tele_phone_id", "$PhoneID" );
$t->set_var( "fax_phone_id", "$FaxID" );
$t->set_var( "email_online_id", "$MailID" );
$t->set_var( "web_online_id", "$WebID" );
$t->set_var( "address_id", "$AddressID" );
$t->set_var( "user_id", "$UserID" );

$t->set_var( "address_action_type", "hidden" );
$t->set_var( "address_list", "" );

$PHONE_TYPE_ID = 5;
$FAX_TYPE_ID = 8;
$EMAIL_TYPE_ID = 5;
$WEB_TYPE_ID = 4;
$ADDRESS_TYPE_ID = 2;

$t->set_var( "phone_type_id", "$PHONE_TYPE_ID" );
$t->set_var( "fax_type_id", "$FAX_TYPE_ID" );
$t->set_var( "email_type_id", "$EMAIL_TYPE_ID" );
$t->set_var( "web_type_id", "$WEB_TYPE_ID" );
$t->set_var( "address_type_id", "$ADDRESS_TYPE_ID" );


$error = false;
$emailCheck = true; // For future reference, this needs to read info about what we should check...
$passwordCheck = true; // For future reference, this needs to read info about what we should check...
$loginCheck = true; // For future reference, this needs to read info about what we should check...
$nameCheck = true; // For future reference, this needs to read info about what we should check...
$companyNoCheck = true; // For future reference, this needs to read info about what we should check...
$addressCheck = true; // For future reference, this needs to read info about what we should check...

if( $Action == "insert" || $Action == "update" )
{
    if( $emailCheck )
    {
        $t->set_block( "errors_tpl", "error_email_item_tpl", "error_email_item" );
        $t->set_block( "errors_tpl", "error_email_not_valid_item_tpl", "error_email_not_valid_item" );
        $t->set_var( "error_email_item", "" );
        $t->set_var( "error_email_not_valid_item", "" );
        
        if( empty( $OnlineEmail ) )
        {
            $t->parse( "error_email_item", "error_email_item_tpl" );
            $error = true;
        }
        else
        {
            if( !eZMail::validate( $OnlineEmail ) )
            {
                $t->parse( "error_email_not_valid_item", "error_email_not_valid_item_tpl" );
                $error = true;
            }
        }        
    }

    if( $passwordCheck )
    {
        $t->set_block( "errors_tpl", "error_password_item_tpl", "error_password_item" );
        $t->set_block( "errors_tpl", "error_password_too_short_item_tpl", "error_password_too_short_item" );
        $t->set_var( "error_password_item", "" );
        $t->set_var( "error_password_too_short_item", "" );
        $t->set_var( "error_passwordrepeat_item", "" );
        $t->set_var( "error_passwordmatch_item", "" );
        
        if( empty( $Password ) && empty( $UserID ) )
        {
            $t->parse( "error_password_item", "error_password_item_tpl" );
            $error = true;
        }

        if( empty( $RepeatPassword ) && !empty( $Password ) && empty( $UserID ) )
        {
            $t->parse( "error_passwordrepeat_item", "error_passwordrepeat_item_tpl" );
            $error = true;
        }

        if( $RepeatPassword != $Password &&  !empty( $Password ) && !empty( $RepeatPassword ) && empty( $UserID ) )
        {
            $t->parse( "error_passwordmatch_item", "error_passwordmatch_item_tpl" );
            $error = true;
        }
        else
        {
            if( strlen( $Password ) < 4 )
            {
                $t->parse( "error_password_too_short_item", "error_password_too_short_item_tpl" );
                $error = true;
            }
        }
        
    }
    
    if( $loginCheck )
    {
        $t->set_block( "errors_tpl", "error_loginname_item_tpl", "error_loginname_item" );
        $t->set_var( "error_loginname_item", "" );
        
        if( empty( $LoginName ) && empty( $UserID ) )
        {
            $t->parse( "error_loginname_item", "error_loginname_item_tpl" );
            $error = true;
        }
    }
    
    if( $companyNoCheck )
    {
        $t->set_block( "errors_tpl", "error_companyno_item_tpl", "error_companyno_item" );
        $t->set_var( "error_companyno_item", "" );
        
        if( empty( $CompanyNo ) )
        {
            $t->parse( "error_companyno_item", "error_companyno_item_tpl" );
            $error = true;
        }
    }
    
    if( $addressCheck )
    {
        $t->set_block( "errors_tpl", "error_address_item_tpl", "error_address_item" );
        $t->set_var( "error_address_item", "" );

        if( empty( $Street1 ) || empty( $Place ) || empty( $Zip ) )
        {
            $t->parse( "error_address_item", "error_address_item_tpl" );
            $error = true;
        }
    }
    
    if( $nameCheck )
    {
        $t->set_block( "errors_tpl", "error_name_item_tpl", "error_name_item" );
        $t->set_var( "error_name_item", "" );
        
        if( empty( $Name ) )
        {
            $t->parse( "error_name_item", "error_name_item_tpl" );
            $error = true;
        }
    }
    
    if( $error == true )
    {
        $t->parse( "errors_item", "errors_tpl" );
    }
    
}

if( $Action == "insert" && $error == false )
{
    $user = new eZUser();
    $user->setFirstName( $Name );
    $user->setLastName( $CompanyNo );
    $user->setLogin( $Name );
    $user->setEmail( $Online[1] );
    if( $Password == $RepeatPassword && !empty( $Password ) )
    {
        $user->setPassword( $Password );
        $user->store();
        $UserID = $user->id();
        $Add_User = false;
                    
        // add user to usergroup
        $AnonymousUserGroup = $ini->read_var( "eZContactMain", "CompanyUserGroup" );
        setType( $AnonymousUserGroup, "integer" );

        $group = new eZUserGroup( $AnonymousUserGroup );
        $group->addUser( $user );
    }
}

if( $Action == "insert" && $error == false )
{
    $company = new eZCompany();
    $company->setName( $Name );  
    $company->setCompanyNo( $CompanyNo );
    $company->setComment( $Description );

    $company->store();

    // adresss
    $address = new eZAddress();
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setPlace( $Place );
    $address->setAddressType( $AddressTypeID );
    $address->store();

    // Add company to categories

    if ( ( $CompanyCategoryID ) != "" )
    {
        $category = new eZCompanyType();
        
        for( $i=0; $i<count( $CompanyCategoryID ); $i++ )
        {
            $category->get( $CompanyCategoryID[$i] );
            $category->addCompany( $company );
        }
    }

    for( $i=0; $i<count( $Phone ); $i++ )
    {
        $phone = new eZPhone( );
        // telefonnummer
        $phone->setNumber( $Phone[$i] );
        $phone->setPhoneTypeID( $PhoneTypeID[$i] );
        $phone->store();
        $company->addPhone( $phone );
    }

    for( $i=0; $i<count( $Online ); $i++ )
    {
        $online = new eZOnline();
        $online->setURL( $Online[$i] );
        $online->setURLType( $URLType[$i] );
        $online->setOnlineTypeID( $OnlineTypeID[$i] );
        $online->store();
        $company->addOnline( $online );
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

    // Add to user object
    $company->addAddress( $address );
    header( "Location: /contact/company/list/" );
    exit();
}

// Oppdaterer et firma.
if ( $Action == "update" )
{
    $company = new eZCompany();
    $company->get( $CompanyID  );
    
    $company->setName( $Name );  
    $company->setComment( $Description );
    $company->setCompanyNo( $CompanyNo );

    if ( ( $CompanyCategoryID ) != "" )
    {
        $company->removeCategoryies();
        
        $category = new eZCompanyType();
        
        for( $i=0; $i<count( $CompanyCategoryID ); $i++ )
        {
            $category->get( $CompanyCategoryID[$i] );
            $category->addCompany( $company );
        }
    }


    $imageFile = new eZImageFile();
    
    if ( $imageFile->getUploadedFile( "image" ) )
    {

        $oldImage = new eZImage( $ImageID );

        $companyImage = new eZImage();

        $companyImage->setImage( $imageFile );
        
        $companyImage->store();

        $company->setCompanyImage( $companyImage );
    }
    else
    {
        $companyImage = new eZImage( $ImageID );
        $companyImage->store();
    }

    // Upload image and logo
    $logoFile = new eZImageFile();
   
    if ( $logoFile->getUploadedFile( "logo" ) )
    {

        $oldImage = new eZImage( $LogoID );

        $companyLogo = new eZImage();

        $companyLogo->setImage( $logoFile );
        
        $companyLogo->store();

        $company->setLogoImage( $companyLogo );
    }
    else
    {
        $companyLogo = new eZImage( $LogoID );
        $companyLogo->store();
    }

    if ( $DeleteImage == "on" )
    {
        $company->deleteImage( );
    }

    if ( $DeleteLogo == "on" )
    {
        $company->deleteLogo( );
    }

    
    // Update or store address
    $addressList = $company->addresses( $CompanyID );

    if ( count ( $addressList ) == 1 )
    {
        $AddressID = $addressList[0]->id();

        $address = new eZAddress( $AddressID );
        $address->setStreet1( $Street1 );
        $address->setStreet2( $Street2 );
        $address->setZip( $Zip );
        $address->setPlace( $Place );
        $address->store();
    }
    else if ( count( $addressList ) == 0 )
    {
        $address = new eZAddress();
        $address->setStreet1( $Street1 );
        $address->setStreet2( $Street2 );
        $address->setZip( $Zip );
        $address->setPlace( $Place );
        $address->store();
    }

    // Update or store phone
    $phoneList = $company->phones( $CompanyID );

    if ( ( count ( $phoneList ) == 1 ) || ( count ( $phoneList ) == 2 )  )
    {
        for( $i=0; $i<count ( $phoneList ); $i++ )
        {
            $PhoneID = $phoneList[$i]->id();
            
            $phone = new eZPhone( $PhoneID );
            $phone->setNumber( $Phone[$i] );
            $phone->store();
        }
    }

    else if ( count ( $phoneList ) == 0 )
    {
        for( $i=0; $i<count( $Phone ); $i++ )
        {
            $phone = new eZPhone( );
            $phone->setNumber( $Phone[$i] );
            $phone->setPhoneTypeID( $PhoneTypeID[$i] );
            $phone->store();
            $company->addPhone( $phone );
        }
    }

    // Update or store online
    $onlineList = $company->onlines( $CompanyID );
    if ( count ( $onlineList ) == 2 )
    {
        for( $i=0; $i<count( $onlineList ); $i++ )
        {
            $onlineID = $onlineList[$i]->id();
            
            $online = new eZOnline( $onlineID );
            $online->setURL( $Online[$i] );
            $online->store();
        }
    }
    else if ( count ( $onlineList ) == 0 )
    {
        for( $i=0; $i<count( $Online ); $i++ )
        {
            $online = new eZOnline();
            $online->setURL( $Online[$i] );
            $online->setURLType( $URLType[$i] );
            $online->setOnlineTypeID( $OnlineTypeID[$i] );
            $online->store();
            $company->addOnline( $online );
        }
    }

    $company->store();

    if ( isSet( $Update ) )
    {
        header( "Location: /contact/company/edit/$CompanyID/" );
        exit();
    }
    else
    {
        header( "Location: /contact/companytype/list" );
        exit();
    }
}

if ( $Action == "new" || $error)
{
    $t->parse( "logo_add", "logo_add_tpl" );
    $t->parse( "image_add", "image_add_tpl" );

    $t->set_var( "logo_edit", "" );
    $t->set_var( "image_edit", "" );
    $Action_value = "insert";

    $t->parse( "address_item", "address_item_tpl" );
    $t->parse( "phone_item", "phone_item_tpl" );
    $t->parse( "fax_item", "fax_item_tpl" );
    $t->parse( "web_item", "web_item_tpl" );
    $t->parse( "email_item", "email_item_tpl" );
    $t->parse( "logo_add", "logo_add_tpl" );
}

// Redigering av firma.
if ( $Action == "edit" )
{
    $company = new eZCompany();
    $company->get( $CompanyID );
    
    $t->set_var( "name", $company->name() );
    $t->set_var( "description", $company->comment() );
    $t->set_var( "companyno", $company->companyNo() );


    // View logo.
    $logoImage = $company->logoImage();

    if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $logoImage->id() != 0 ) )
    {
        $variation = $logoImage->requestImageVariation( 150, 150 );
        
        $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
        $t->set_var( "logo_name", $logoImage->name() );
        $t->set_var( "logo_id", $logoImage->id() );
        
        $t->set_var( "logo_add", "" );
        $t->parse( "logo_edit", "logo_edit_tpl" );
    }
    else
    {
        $t->set_var( "logo_edit", "" );
        $t->parse( "logo_add", "logo_add_tpl" );
    }
    

    // View company image.
    $companyImage = $company->companyImage();
    
    if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $companyImage->id() != 0 ) )
    {
        $variation = $companyImage->requestImageVariation( 150, 150 );
        
        $t->set_var( "image_src", "/" . $variation->imagePath() );
        $t->set_var( "image_name", $companyImage->name() );
        $t->set_var( "image_id", $companyImage->id() );
        
        $t->set_var( "image_add", "" );
        $t->parse( "image_edit", "image_edit_tpl" );
    }
    else
    {
        $t->set_var( "image_edit", "" );
        $t->parse( "image_add", "image_add_tpl" );
    }


    $message = "Rediger firmainformasjon";

    // Telephone list
    $phoneList = $company->phones( $company->id() );

    if ( count( $phoneList ) <= 2 )
    {
        for( $i=0; $i<count ( $phoneList ); $i++ )
        {
            if ( $phoneList[$i]->phoneTypeID() == 1 )
            {
                $t->set_var( "tele_phone_id", $phoneList[$i]->id() );
                $t->set_var( "telephone", $phoneList[$i]->number() );
            }
            if ( $phoneList[$i]->phoneTypeID() == 2 )
            {
                $t->set_var( "fax_phone_id", $phoneList[$i]->id() );
                $t->set_var( "fax", $phoneList[$i]->number() );
            }

            $t->parse( "phone_item", "phone_item_tpl" );
            $t->parse( "fax_item", "fax_item_tpl" );
        }
    }

    // Address list
    $addressList = $company->addresses( $company->id() );
    if ( count ( $addressList ) == 1 )
    {
        foreach( $addressList as $addressItem )
        {
            $t->set_var( "address_id", $addressItem->id() );
            $t->set_var( "street1", $addressItem->street1() );
            $t->set_var( "street2", $addressItem->street2() );
            $t->set_var( "zip", $addressItem->zip() );
            $t->set_var( "place", $addressItem->place() );
            
            $t->set_var( "company_id", $CompanyID );
            
            $t->set_var( "script_name", "companyedit.php" );

            $t->parse( "address_item", "address_item_tpl", true );
            
        }
    }

    // Online list
    $onlineList = $company->onlines( $company->id() );

    if ( count ( $onlineList ) <= 2 )
    {
        for( $i=0; $i<count ( $onlineList ); $i++ )
        {
            if ( $onlineList[$i]->onlineTypeID() == 1 )
            {
                $t->set_var( "web_online_id", $onlineList[$i]->id() );
                $t->set_var( "web", $onlineList[$i]->URL() );
            }
            if ( $onlineList[$i]->onlineTypeID() == 2 )
            {
                $t->set_var( "email_online_id", $onlineList[$i]->id() );
                $t->set_var( "email", $onlineList[$i]->URL() );
            }
            
        }
    }
    $t->parse( "web_item", "web_item_tpl" );
    $t->parse( "email_item", "email_item_tpl" );

    // Template variabler.
    $Action_value = "update";
    
}
    
// Company type selector
$companyType = new eZCompanyType();
$companyTypeList = $companyType->getAll();

foreach( $companyTypeList as $companyTypeItem )
{
    $t->set_var( "company_type_name", $companyTypeItem->name() );
    $t->set_var( "company_type_id", $companyTypeItem->id() );

    if ( $company )
    {
        $categoryList = $company->categories( $CompanyID );
        $found = false;

        foreach ( $categoryList as $category )
        {
            if ( $category->id() == $companyTypeItem->id() )
            {
                $found = true;
            }
        }
        if ( $found  == true )
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

// Template variabler.

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "company_edit"  );

?>
