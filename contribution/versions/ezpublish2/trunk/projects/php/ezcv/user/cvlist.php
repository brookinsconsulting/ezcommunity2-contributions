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


$t = new eZTemplate( "ezcv/user/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/user/intl", $Language, "cv.php" );
$intl = new INIFile( "ezcv/user/intl/" . $Language . "/cv.php.ini", false );

$cv = new eZCV();
$person = new eZPerson();

$locale = new eZLocale( $Language );
$dateTime = new eZDateTime();
$date = new eZDate();

if( is_object( $user ) )
{
    $checkObject =& $user;
    $UserID = $user->id();
}
elseif( is_object( $group ) )
{
    $checkObject =& $group;
}

if( is_object( $checkObject ) )
{
    $listPermission = eZPermission::checkPermission( $checkObject, "eZCV", "CVList" );
    $viewPermission = eZPermission::checkPermission( $checkObject, "eZCV", "CVView" );

    if( $Action != "list" && !$listPermission )
    {
        header( "Location: /cv/error" );
    }
}
else
{
    if( $Action != "list" )
    {
        header( "Location: /cv/cv/new" );
    }
}

$isOwner = false;

if( is_numeric( $CVID ) )
{
        $cv->get( $CVID );
        
        if( $cv->id() > 0 )
        {
            $person->get( $cv->personID() );

            $cvOwner = $person->user();

            if( is_object( $cvOwner[0] ) )
            {
                $cvOwner = $cvOwner[0];
                $CVID = $cv->id();
                $PersonID = $cv->personID();
            }

            if( $cvOwner == $UserID )
            {
                $isOwner = true;
            }
        }
        else
        {
            if( $Action == "view" )
            {
                header( "Location: /cv/cv/list" );
                exit();
            }
        }
}
else
{
    if( $UserID > 0 )
    {
        $person = $person->getByUserID( $UserID );
        if( is_object( $person ) )
        {
            $cv = $cv->getByPerson( $person );
            if( $cv->id() > 0 )
            {
                $isOwner = true;
                $CVID = $cv->id();
            }
            $PersonID = $person->id();
        }
        else
        {
            if( $Action == "view" )
            header( "Location: /contact/person/edit" );
        }
    }
}

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

    $t->set_block( "cv_view", "edit_items_tpl", "edit_items" );
    $t->set_var( "edit_items", "" );

    $t->set_block( "cv_view", "education_items_tpl", "education_items");
    $t->set_block( "cv_view", "no_education_items_tpl", "no_education_items");
    $t->set_block( "education_items_tpl", "education_item_tpl", "education_item" );
    $t->set_var( "education_items", "");
    $t->set_var( "no_education_items", "");
    $t->set_var( "education_item", "" );

    $t->set_block( "cv_view", "experience_items_tpl", "experience_items");
    $t->set_block( "cv_view", "no_experience_items_tpl", "no_experience_items");
    $t->set_block( "experience_items_tpl", "experience_item_tpl", "experience_item" );
    $t->set_var( "experience_items", "");
    $t->set_var( "no_experience_items", "");
    $t->set_var( "experience_item", "" );
    
    $t->set_block( "cv_view", "course_items_tpl", "course_items");
    $t->set_block( "cv_view", "no_course_items_tpl", "no_course_items");
    $t->set_block( "course_items_tpl", "course_item_tpl", "course_item" );
    $t->set_var( "course_items", "");
    $t->set_var( "no_course_items", "");
    $t->set_var( "course_item", "" );

    $t->set_block( "cv_view", "extracurricular_items_tpl", "extracurricular_items");
    $t->set_block( "cv_view", "no_extracurricular_items_tpl", "no_extracurricular_items");
    $t->set_block( "extracurricular_items_tpl", "extracurricular_item_tpl", "extracurricular_item" );
    $t->set_var( "extracurricular_items", "");
    $t->set_var( "no_extracurricular_items", "");
    $t->set_var( "extracurricular_item", "" );

    $t->set_block( "cv_view", "certificate_items_tpl", "certificate_items");
    $t->set_block( "cv_view", "no_certificate_items_tpl", "no_certificate_items");
    $t->set_block( "certificate_items_tpl", "certificate_item_tpl", "certificate_item" );
    $t->set_var( "certificate_items", "");
    $t->set_var( "no_certificate_items", "");
    $t->set_var( "certificate_item", "" );
    
    if( $isOwner )
    {
        $t->set_var( "user_id", $UserID );
        $t->set_var( "person_id", $PersonID );
        $t->set_var( "cv_id", $CVID );
        $t->parse( "edit_items", "edit_items_tpl" );
    }
    
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
}

if( $Action == "view" && $viewPermission )
{
    // Experience list
    $experienceArray = $cv->experience();
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $i = 0;
    foreach( $experienceArray as $experience )
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
        
        $t->set_var( "item_end_period", $experience->end() );
        $t->set_var( "item_start_period", $experience->start() );
        $t->set_var( "item_where", $experience->employer() );
        $t->set_var( "item_what", $experience->position() );
        $t->set_var( "item_id", $experience->id() );
        $t->parse( "experience_item", "experience_item_tpl", true );

    }
    
    if( $i != 0 )
    {
        $t->parse( "experience_items", "experience_items_tpl");
    }
    else
    {
        $t->parse( "no_experience_items", "no_experience_items_tpl");
    }

    // Extracurricular list
    $extracurricularArray = $cv->extracurricular();
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $i = 0;
    foreach( $extracurricularArray as $extracurricular )
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
        
        $t->set_var( "item_end_period", $extracurricular->end() );
        $t->set_var( "item_start_period", $extracurricular->start() );
        $t->set_var( "item_where", $extracurricular->organization() );
        $t->set_var( "item_what", $extracurricular->position() );
        $t->set_var( "item_id", $extracurricular->id() );
        $t->parse( "extracurricular_item", "extracurricular_item_tpl", true );

    }
    
    if( $i != 0 )
    {
        $t->parse( "extracurricular_items", "extracurricular_items_tpl");
    }
    else
    {
        $t->parse( "no_extracurricular_items", "no_extracurricular_items_tpl");
    }


    // Education list
    $educationArray = $cv->education();
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $i = 0;
    foreach( $educationArray as $education )
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
        
        $t->set_var( "item_end_period", $education->end() );
        $t->set_var( "item_start_period", $education->start() );
        $t->set_var( "item_where", $education->institution() );
        $t->set_var( "item_what", $education->direction() );
        $t->set_var( "item_id", $education->id() );
        $t->parse( "education_item", "education_item_tpl", true );

    }
    
    if( $i != 0 )
    {
        $t->parse( "education_items", "education_items_tpl");
    }
    else
    {
        $t->parse( "no_education_items", "no_education_items_tpl");
    }

    // Course list
    $courseArray = $cv->course();
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $i = 0;
    foreach( $courseArray as $course )
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
        
        $t->set_var( "item_end_period", $course->courseEnd() );
        $t->set_var( "item_start_period", $course->courseStart() );
        $t->set_var( "item_place", $course->coursePlace() );
        $t->set_var( "item_name", $course->courseName() );
        $t->set_var( "item_id", $course->id() );
        $t->parse( "course_item", "course_item_tpl", true );

    }
    
    if( $i != 0 )
    {
        $t->parse( "course_items", "course_items_tpl");
    }
    else
    {
        $t->parse( "no_course_items", "no_course_items_tpl");
    }


    // Certificate list
    $certificateArray = $cv->certificate();
    
    if( $i != 0 )
    {
        $t->parse( "online_info", "online_info_tpl" );
    }

    $i = 0;
    foreach( $certificateArray as $certificate )
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
        
        $certificateType = $certificate->certificateType();
        
        $t->set_var( "certificate_id", $certificate->id() );
        $t->set_var( "certificate_institution", $certificate->institution() );
        $t->set_var( "certificate_category", $certificate->category() );
        $t->set_var( "certificate_type", $certificate->type() );
        $t->set_var( "certificate_description", $certificate->description() );
        $t->set_var( "certificate_start", $certificate->received() );
        $t->set_var( "certificate_end", $certificate->expires() );
        $t->parse( "certificate_item", "certificate_item_tpl", true );

    }
    
    if( $i != 0 )
    {
        $t->parse( "certificate_items", "certificate_items_tpl");
    }
    else
    {
        $t->parse( "no_certificate_items", "no_certificate_items_tpl");
    }
}

if( $Action == "view" )
{
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

    $t->set_block( "cv_item_tpl", "cv_item_permissible_tpl", "cv_item_permissible" );
    $t->set_block( "cv_item_tpl", "cv_item_not_permissible_tpl", "cv_item_not_permissible" );
    $t->set_var( "cv_item_permissible", "" );
    $t->set_var( "cv_item_not_permissible", "" );

    $t->setAllStrings();
    
    $cvs = $cv->getAllValid();
    $noItems = true;
    // Has the scope changed? On line 29 I'm doing exactly what I'm doing on the next line....
    $person = new eZPerson();
    
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
        
        if( $viewPermission )
        {
            $t->parse( "cv_item_permissible", "cv_item_permissible_tpl" );
        }
        else
        {
            $t->parse( "cv_item_not_permissible", "cv_item_not_permissible_tpl" );
        }
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
