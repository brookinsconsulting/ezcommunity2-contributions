<?
/*
    List cvs
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "ezcv/classes/ezcv.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezaddresstype.php" );
include_once( "ezcontact/classes/ezphonetype.php" );
include_once( "ezcontact/classes/ezonlinetype.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezdate.php" );


$t = new eZTemplate( "ezcv/admin/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/admin/intl", $Language, "cv.php" );
$intl = new INIFile( "ezcv/admin/intl/" . $Language . "/cv.php.ini", false );

$cv = new eZCV();
$person = new eZPerson();

$locale = new eZLocale( $Language );
$dateTime = new eZDateTime();
$date = new eZDate();

if( $Action == "view" )
{
    $t->set_file( array(                    
        "cv_view" => "cvview.tpl"
        ) );

    $t->set_block( "cv_view", "cv_items_tpl", "cv_items" );
    $t->set_block( "cv_view", "cv_no_items_tpl", "cv_no_items" );
    $t->set_block( "cv_items_tpl", "cv_item_tpl", "cv_item" );
    $t->set_var( "cv_items", "" );
    $t->set_var( "cv_no_items", "" );

    $t->set_block( "cv_view", "online_info_tpl", "online_info" );
    $t->set_block( "online_info_tpl", "online_item_tpl", "online_item" );
    $t->set_var( "online_info", "" );
    $t->set_var( "online_item", "" );

    $t->set_block( "cv_view", "address_info_tpl", "address_info" );
    $t->set_block( "address_info_tpl", "address_item_tpl", "address_item" );
    $t->set_var( "address_info", "" );
    $t->set_var( "address_item", "" );

    $t->set_block( "cv_view", "phone_info_tpl", "phone_info" );
    $t->set_block( "phone_info_tpl", "phone_item_tpl", "phone_item" );
    $t->set_var( "phone_info", "" );
    $t->set_var( "phone_item", "" );

    $cv->get( $CVID );
    $person->get( $cv->personID() );
    
    $BirthDate = $person->birthDate();
    $date->setMySQLDate( $BirthDate );
    $BirthDateLocalized = $locale->format( $date );

    $t->set_var( "person_last_name", $person->lastName() );
    $t->set_var( "person_first_name", $person->firstName() );
    $t->set_var( "person_birth_date", $BirthDateLocalized );
    $t->set_var( "person_no", $person->personNo() );
    $t->set_var( "person_comment", $person->comment() );

    $t->set_var( "cv_children", $cv->children() );    
    $t->set_var( "cv_sex", $intl->read_var( "strings", "sex_" . $cv->sex() ) );
    $t->set_var( "cv_marital_status", $intl->read_var( "strings", "marital_" . $cv->maritalStatus() ) );
    $t->set_var( "cv_work_status", $intl->read_var( "strings", "work_" . $cv->workStatus() ) );
    $t->set_var( "cv_army_status", $intl->read_var( "strings", "army_" . $cv->armyStatus() ) );
    
    $addressses = $person->addresses( $person->id() );
    $phones = $person->phones( $person->id() );
    $onlines = $person->onlines( $person->id() );
    
    $i = 0;
   
    foreach( $addressses as $address )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        $t->set_var( "address_street1", $address->street1() );
        $t->set_var( "address_street2", $address->street2() );
        $t->set_var( "address_place", $address->place() );
        $t->set_var( "address_zip", $address->zip() );

        $addressType = $address->addressType();
        
        $t->set_var( "address_type_id", $addressType->id() );                
        $t->set_var( "address_type", $addressType->name() );

        $country = $address->country();
        $t->set_var( "address_country_id", $country->id() );                
        $t->set_var( "address_country", $country->name() );
        
        $t->parse( "address_item", "address_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "address_info", "address_info_tpl" );
    }
    
    $i = 0;
    foreach( $phones as $phone )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        $t->set_var( "phone_number", $phone->number() );
        
        $phoneType = new eZPhoneType( $phone->phoneTypeID() );
        
        $t->set_var( "phone_type_id", $phoneType->id() );
        $t->set_var( "phone_type", $phoneType->name() );
        
        $t->parse( "phone_item", "phone_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "phone_info", "phone_info_tpl" );
    }

    $i = 0;
    foreach( $onlines as $online )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        
        $onlineType = new eZOnlineType( $online->onlineTypeID() );
        $t->set_var( "online_url", $online->url() );
        
        $t->set_var( "online_type_id", $onlineType->id() );
        $t->set_var( "online_type", $onlineType->name() );

        $t->set_var( "url_type", $online->urlType() );
        
        $t->parse( "online_item", "online_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $t->setAllStrings();
    $t->set_var( "action_value", $ActionValue );
    $t->pparse( "output", "cv_view"  ); 
}

if( $Action == "list" )
{
    $t->set_file( array(                    
        "cv_list" => "cvlist.tpl"
        ) );

    $t->set_block( "cv_list", "cv_items_tpl", "cv_items" );
    $t->set_block( "cv_list", "cv_no_items_tpl", "cv_no_items" );
    $t->set_block( "cv_items_tpl", "cv_item_tpl", "cv_item" );
    $t->set_var( "cv_items", "" );
    $t->set_var( "cv_no_items", "" );

    $t->setAllStrings();
    
    $cvs = $cv->getAllValid();
    $noItems = true;
    $i=0;
    foreach( $cvs as $cv )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "theme-type_class", "bglight" );
        }
        else
        {
            $t->set_var( "theme-type_class", "bgdark" );
        }
        $i++;
        $person->get( $cv->personID() );

        $Created = $cv->created();
        $Updated = $cv->updated();
        $ValidUntil = $cv->validUntil();

        $dateTime->setMySQLTimeStamp( $Created );
        $CreatedLocalized = $locale->format( $dateTime );

        $date->setMySQLDate( $ValidUntil );
        $ValidUntilLocalized = $locale->format( $date );

        $t->set_var( "item_id", $cv->id() );
        $t->set_var( "person_last_name", $person->lastName() );
        $t->set_var( "person_first_name", $person->firstName() );
        $t->set_var( "item_created", $CreatedLocalized );
        $t->set_var( "item_valid_until", $ValidUntilLocalized );
        $t->parse( "cv_item", "cv_item_tpl", true );
        $noItems = false;
    }
    
    if( $noItems == true )
    {
        $t->parse( "cv_no_items", "cv_no_items_tpl" );
    }
    else
    {
        $t->parse( "cv_items", "cv_items_tpl" );
    }
    $t->set_var( "action_value", $ActionValue );
    $t->pparse( "output", "cv_list"  );
}


?>
