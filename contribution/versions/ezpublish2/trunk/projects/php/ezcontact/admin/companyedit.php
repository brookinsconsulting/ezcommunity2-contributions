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

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezaddress.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );
include_once( "ezcontact/classes/ezphone.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezonline.php" );

include_once( "classes/ezimagefile.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );


if ( $Action == "insert" )
{
    $company = new eZCompany();
    $company->setName( $Name );  
    $company->setContactType( $CompanyType );
    $company->setCompanyNo( $CompanyNo );
    $company->setComment( $Description );

    $company->store();

    // adresss
    $address = new eZAddress();
    $address->setStreet1( $Street1 );
    $address->setStreet2( $Street2 );
    $address->setZip( $Zip );
    $address->setPlace( $Place );
    $address->setAddressType( $AddressType );
    $address->store();

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
    print( $userfile );
    die();
    if ( $file->getUploadedFile( $userfile ) )
    {
        die();
        $logo = new eZImage();
        $logo->setName( "Logo" );
        
        $logo->setImage( $file );
        
        $logo->store();

        $company->addImage( $logo );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }
    
//      if ( isSet ( $CompanyImage ) )
//      {
//          $file = new eZImageFile();
//          if ( $file->getUploadedFile( $CompanyImage ) )
//          {
//              $image = new eZImage();
//              $image->setName( "Image" );
           
//              $image->setImage( $file );
            
//              $image->store();
//          }
//          else
//          {
//              print( "bilde funker ikke" );
//          }
//      }

    // Add to user object
    $company->addAddress( $address );
    
    if ( isSet( $image ) )
        $company->addImage( $image );
    if ( isSet( $logo ) )
        $company->addImage( $logo );

    Header( "Location: /contact/companylist/" );
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

    if ( count ( $phoneList ) == 1 )
    {
        $PhoneID = $phoneList[0]->id();

        $phone = new eZPhone( $PhoneID );
        $phone->setNumber( $Telephone );
        $phone->store();
    }
    else if ( count ( $phoneList ) == 0 )
    {
        $phone = new eZPhone( );
        $phone->setNumber( $Telephone );
        $phone->store();
    }

    // Update or store online
    $onlineList = $company->onlines( $CompanyID );
    if ( count ( $onlineList ) == 1 )
    {
        $onlineID = $onlineList[0]->id();

        $online = new eZOnline( $onlineID );
        $online->setURL( $Web );
        $online->setURLType( $URLType );
        $online->setOnlineTypeID( $OnlineTypeID );
        $online->store();
    }
    else if ( count ( $onlineList ) == 0 )
    {
        $online = new eZOnline( );
        $online->setURL( $URL );
        $online->setURLType( $URLType );
        $online->setOnlineTypeID( $OnlineTypeID );
        $online->store();
    }

    $company->store();

    Header( "Location: /contact/companylist/" );
    exit();

}

// Slette fra company list.
if ( $Action == "delete" )
{
    $deleteCompany = new eZCompany();
    $deleteCompany->get( $CompanyID );
    print( "tjo" . $CompanyID );
    $deleteCompany->delete();

    Header( "Location: index.php?page=" . $DOC_ROOT . "contactlist.php" );
}


// Setter template.
$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl", $Language, "companyedit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "company_edit" => "companyedit.tpl"
    ) );

$t->set_block( "company_edit", "company_item_tpl", "company_item" );

$t->set_block( "company_edit", "address_item_tpl", "address_item" );

$t->set_block( "company_edit", "phone_item_tpl", "phone_item" );

$t->set_block( "company_edit", "online_item_tpl", "online_item" );

$t->set_block( "company_edit", "logo_add_tpl", "logo_add" );
$t->set_block( "company_edit", "image_add_tpl", "image_add" );
$t->set_block( "company_edit", "logo_delete_tpl", "logo_delete" );
$t->set_block( "company_edit", "image_delete_tpl", "image_delete" );

if ( !isset( $Action ) )
    $Action = "insert";

$message = "Registrer nytt kontaktfirma";

$t->set_var( "name", "" );
$t->set_var( "description", "" );
$t->set_var( "street1", "" );
$t->set_var( "street2", "" );
$t->set_var( "zip", "" );
$t->set_var( "place", "" );
$t->set_var( "telephone", "" );
$t->set_var( "fax", "" );
$t->set_var( "email", "" );
$t->set_var( "web", "" );
$t->set_var( "companyno", "" );

$t->set_var( "address_action_type", "hidden" );
$t->set_var( "address_list", "" );

if ( $Action == "new" )
{
    $t->parse( "logo_add", "logo_add_tpl" );
    $t->parse( "image_add", "image_add_tpl" );

    $t->set_var( "logo_delete", "" );
    $t->set_var( "image_delete", "" );
    $Action_value = "Insert";

    $t->parse( "address_item", "address_item_tpl" );
    $t->parse( "phone_item", "phone_item_tpl" );
}

// Redigering av firma.
if ( $Action == "edit" )
{
    $company = new eZCompany();
    $company->get( $CompanyID );
    
    $t->set_var( "name", $company->name() );
    $t->set_var( "description", $company->comment() );
    $t->set_var( "companyno", $company->companyNo() );

    $message = "Rediger firmainformasjon";

    // Telephone list
    $phoneList = $company->phones( $company->id() );

    if ( count( $phoneList ) == 1 )
    {
        foreach( $phoneList as $phoneItem )
        {
            $t->set_var( "phone_id", $phoneItem->id() );
            $t->set_var( "telephone", $phoneItem->number() );
            
            $t->set_var( "script_name", "companyedit.php" );
            $t->set_var( "company_id", $CompanyID );

            $t->parse( "phone_item", "phone_item_tpl", true );
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
    if ( count ( $onlineList ) == 1 )
    {
        foreach( $onlineList as $onlineItem )
        {
            $t->set_var( "online_id", $onlineItem->id() );
            $t->set_var( "web", $onlineItem->URL() );
        }
    }

    // Image list
    $imageList = $company->images( $company->id() );
    if ( count ( $imageList ) <= 2 )
    {
        foreach( $imageList as $imageItem )
        {
            if ( $imageItem->name() == "CompanyLogo" )
            {
                $t->set_var( "logo_add", "" );
                $t->parse( "logo_delete", "logo_delete_tpl" );
            }
            else
            {
                $t->set_var( "logo_delete", "" );
                $t->parse( "logo_add", "logo_add_tpl" );
            }

            if ( $imageItem->name() == "CompanyImage" )
            {
                $t->set_var( "image_add", "" );
                $t->parse( "image_delete", "image_delete_tpl" );
            }
            else
            {
                $t->set_var( "image_delete", "" );
                $t->parse( "image_add", "image_add_tpl" );
            }
                 
        }
    }
    

    // Template variabler.
    $Action_value = "Update";

}

// Template variabler.

$t->set_var( "error", $error );

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "company_edit"  );

?>
