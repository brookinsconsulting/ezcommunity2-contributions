<?
/*
    Edit a cv
 */
 
include_once( "classes/INIFile.php" );

$ini = new INIFile( "site.ini" );
$Language = $ini->read_var( "eZCVMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezcv/classes/ezcv.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcountry.php");
include_once( "classes/ezdate.php" );
include_once( "classes/ezlocale.php" );

if( !empty( $CertificateAdd ) || !empty( $ExperienceAdd ) || !empty( $ExtracurricularAdd ) || !empty( $EducationAdd ) )
{
    $Action = "add";
}


// Create some objects.
$person = new eZPerson();
if( is_numeric( $PersonID ) )
{
    $person->get( $PersonID );
}
else
{
    if( is_object( $user ) )
    {
        $person = $person->getByUserID( $user->id() );
    }
    else
    {
        header( "Location: /contact/person/new/" );
    }
}

$cv = new eZCV();
$country = new eZCountry();

// A new CV must be related to a person, make sure that we have a person.
// Else redirect user to register personal information.
if( ( !is_object( $person ) ) && $Action == "new" )
{
    header( "Location: /contact/person/new/" );
    exit();
}
else
{
    $PersonID = $person->id();
    $newCV = $cv->getByPerson( $PersonID );
    
    if( is_object( $newCV ) )
    {
        $CVID = $newCV->id();
    }

    if( is_numeric( $CVID ) && $Action == "new" )
    {
        header( "Location: /cv/cv/edit/$CVID" );
        exit();
    }
}

// If we''re deleting redirect to the cv list afterwards.
if( $Action == "delete" )
{
    $cv = new eZCV();
    $cv->get( $CVID );
    $cv->delete();

    header( "Location: /cv/cv/list/" );
    exit();
}

$error = false;

$t = new eZTemplate( "ezcv/user/" . $ini->read_var( "eZCVMain", "AdminTemplateDir" ),
                     "ezcv/user/intl", $Language, "cv.php" );
$intl = new INIFile( "ezcv/user/intl/" . $Language . "/cv.php.ini", false );

$t->set_file( array(                    
    "cv_edit" => "cvedit.tpl"
    ) );

$t->setAllStrings();

$t->set_block( "cv_edit", "nation_tpl", "nation_item" );
$t->set_block( "cv_edit", "sex_option_tpl", "sex_option" );
$t->set_var( "sex_option", "" );
$t->set_block( "cv_edit", "marital_option_tpl", "marital_option" );
$t->set_var( "marital_option", "" );
$t->set_block( "cv_edit", "work_option_tpl", "work_option" );
$t->set_var( "work_option", "" );
$t->set_block( "cv_edit", "army_option_tpl", "army_option" );
$t->set_var( "army_option", "" );

$t->set_block( "cv_edit", "experience_info_tpl", "experience_info" );
$t->set_var( "experience_info", "" );
$t->set_block( "experience_info_tpl", "experience_item_tpl", "experience_item" );
$t->set_var( "experience_item", "" );

$t->set_block( "cv_edit", "education_info_tpl", "education_info" );
$t->set_var( "education_info", "" );
$t->set_block( "education_info_tpl", "education_item_tpl", "education_item" );
$t->set_var( "education_item", "" );


$t->set_block( "cv_edit", "extracurricular_info_tpl", "extracurricular_info" );
$t->set_var( "extracurricular_info", "" );
$t->set_block( "extracurricular_info_tpl", "extracurricular_item_tpl", "extracurricular_item" );
$t->set_var( "extracurricular_item", "" );

$t->set_block( "cv_edit", "certificate_info_tpl", "certificate_info" );
$t->set_var( "certificate_info", "" );
$t->set_block( "certificate_info_tpl", "certificate_item_tpl", "certificate_item" );
$t->set_var( "certificate_item", "" );




// Create the objects we need from the data we _might_ have.
if( is_numeric( $CVID ) )
{
    $cv->get( $CVID );
}

if( is_numeric( $NationalityID ) )
{
    $country->get( $NationalityID );
}

$ValidFor = $ini->read_var( "eZCVMain", "ValidFor" );

if( empty( $ValidUntil ) )
{
    $time = time();
    $futureDate = $ValidFor * 60 * 60 * 24 + $time; // We are working in days here...
    $ValidUntil=gmdate( "Y-m-d", $futureDate);
}
    
if( $Action == "insert" || $Action == "update" || $Action == "add" )
{
    $cv->setNationalityID( $NationalityID );
    $cv->setPersonID( $PersonID );
    $cv->setChildren( $Children );
    $cv->setValidUntil( $ValidUntil );
    $cv->setSex( $Sex );
    $cv->setArmyStatus( $ArmyStatus );
    $cv->setWorkStatus( $WorkStatus );
    $cv->setMaritalStatus( $MaritalStatus );
    $cv->setComment( $Comment );
    $cv->store();
    
    $CVID = $cv->id();
    
    if( $Action == "insert" || $Action == "update" )
    {
        header( "Location: /cv/cv/view/$CVID" );
        exit();
    }
    
    if( $CertificateAdd )
    {
        header( "Location: /cv/certificate/new/?CVID=$CVID" );
        exit();
    }
    
    if( $ExtracurricularAdd )
    {
        header( "Location: /cv/extracurricular/new/?CVID=$CVID" );
        exit();
    }
    
    if( $EducationAdd )
    {
        header( "Location: /cv/education/new/?CVID=$CVID" );
        exit();
    }
    
    if( $ExperienceAdd )
    {
        header( "Location: /cv/experience/new/?CVID=$CVID" );
        exit();
    }
    if( $CourseAdd )
    {
        header( "Location: /cv/course/new/?CVID=$CVID" );
        exit();
    }

}



// Start setting variables to the incomming form data. Side effect of missing data is that
// the variable will be set to empty:)

$t->set_var( "person_id", "$PersonID" );
$t->set_var( "person_first_name", $person->firstName() );
$t->set_var( "person_last_name", $person->lastName() );
$t->set_var( "current_id", "$CVID" );
$t->set_var( "current_nationality_id", "$NationalityID" );
$t->set_var( "current_children", "$Children" );
$t->set_var( "current_comment", "$Comment" );
$t->set_var( "current_valid_for", $ValidFor );
$t->set_var( "current_created", $Created );
$t->set_var( "current_updated", $Updated );

$t->set_var( "current_valid_until", "$ValidUntilLocalized" );

// Okay, now we set the same variables to the data from a valid CV object. (Ie. we''ve
// received an ID for a CV object, and that ID was valid).

if( is_numeric( $CVID ) )
{
    $t->set_var( "current_id", $CVID );
    $t->set_var( "country_id", $cv->nationalityID() );
    $t->set_var( "country_name", $country->name() );
    $t->set_var( "current_children", $cv->children() );
    $t->set_var( "current_comment", $cv->comment() );
    
    
    $Created = $cv->created();
    $Updated = $cv->updated();

    $locale = new eZLocale( $Language );
    $dateTime = new eZDateTime();
    
    $dateTime->setMySQLTimeStamp( $Created );
    $CreatedLocalized = $locale->format( $dateTime );
    
    $dateTime->setMySQLTimeStamp( $Updated );
    $UpdatedLocalized = $locale->format( $dateTime );
    
    $t->set_var( "current_created", $CreatedLocalized );
    $t->set_var( "current_updated", $UpdatedLocalized );
    
    $Sex = $cv->sex();
    $ArmyStatus = $cv->armyStatus();
    $WorkStatus = $cv->workStatus();
    $MaritalStatus = $cv->maritalStatus();
    
    $experienceArray = $cv->experience();
    
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
        $startDate = new eZDate();
        $startDate->setMySQLDate( $experience->start() );
        $endDate = new eZDate();
        $endDate->setMySQLDate( $experience->end() );
        
        $t->set_var( "experience_id", $experience->id() );
        $t->set_var( "experience_employer", $experience->employer() );
        $t->set_var( "experience_position", $experience->position() );
        $t->set_var( "experience_start", $locale->format( $startDate ) );
        $t->set_var( "experience_end", $locale->format( $endDate ) );

        $t->parse( "experience_item", "experience_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "experience_info", "experience_info_tpl" );
    }



    $educationArray = $cv->education();
    
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
        $startDate = new eZDate();
        $startDate->setMySQLDate( $education->start() );
        $endDate = new eZDate();
        $endDate->setMySQLDate( $education->end() );
        
        $t->set_var( "education_id", $education->id() );
        $t->set_var( "education_institution", $education->institution() );
        $t->set_var( "education_direction", $education->direction() );
        $t->set_var( "education_start", $locale->format( $startDate ) );
        $t->set_var( "education_end", $locale->format( $endDate ) );

        $t->parse( "education_item", "education_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "education_info", "education_info_tpl" );
    }


    $certificateArray = $cv->certificate();
    
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
        $startDate = new eZDate();
        $startDate->setMySQLDate( $certificate->received() );
        $endDate = new eZDate();
        $endDate->setMySQLDate( $certificate->expires() );

        $t->set_var( "certificate_id", $certificate->id() );
        $t->set_var( "certificate_institution", $certificate->institution() );
        $t->set_var( "certificate_category", $certificate->category() );
        $t->set_var( "certificate_type", $certificate->type() );
        $t->set_var( "certificate_description", $certificate->description() );
        $t->set_var( "certificate_start", $locale->format( $startDate ) );
        $t->set_var( "certificate_end", $locale->format( $endDate ) );

        $t->parse( "certificate_item", "certificate_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "certificate_info", "certificate_info_tpl" );
    }


    $extracurricularArray = $cv->extracurricular();
    
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
        $startDate = new eZDate();
        $startDate->setMySQLDate( $extracurricular->start() );
        $endDate = new eZDate();
        $endDate->setMySQLDate( $extracurricular->end() );
        
        $t->set_var( "extracurricular_id", $extracurricular->id() );
        $t->set_var( "extracurricular_organization", $extracurricular->organization() );
        $t->set_var( "extracurricular_position", $extracurricular->position() );
        $t->set_var( "extracurricular_start", $locale->format( $startDate ) );
        $t->set_var( "extracurricular_end", $locale->format( $endDate ) );

        $t->parse( "extracurricular_item", "extracurricular_item_tpl", true );
    }
    
    if( $i != 0 )
    {
        $t->parse( "extracurricular_info", "extracurricular_info_tpl" );
    }

}


// Now we need to set some variables which use some database data and which needs
// translation. This is true both for incomming form data and data from the CV
// object
{   

    $date = new eZDate();
    $date->setMySQLDate( $ValidUntil );
    $locale = new eZLocale( $Language );
    $ValidUntilLocalized = $locale->format( $date, false );

    $t->set_var( "current_valid_until", $ValidUntilLocalized );

    $SexTypes = $cv->sexTypes();
    
    foreach( $SexTypes as $SexType )
    {
        $t->set_var( "value", $SexType );
        $t->set_var( "name", $intl->read_var( "strings", "sex_" . $SexType ) );
        $t->set_var( "selected", "" );
        if( $SexType == $Sex )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "sex_option", "sex_option_tpl", true );
    }
    
    $MaritalTypes = $cv->maritalStatusTypes();
    foreach( $MaritalTypes as $MaritalType )
    {
        $t->set_var( "value", $MaritalType );
        $t->set_var( "name", $intl->read_var( "strings", "marital_" . $MaritalType ) );
        $t->set_var( "selected", "" );
        if( $MaritalType == $MaritalStatus )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "marital_option", "marital_option_tpl", true );
    }
    
    $ArmyTypes = $cv->armyStatusTypes();
    foreach( $ArmyTypes as $ArmyType )
    {
        $t->set_var( "value", $ArmyType );
        $t->set_var( "name", $intl->read_var( "strings", "army_" . $ArmyType ) );
        $t->set_var( "selected", "" );
        
        if( $ArmyType == $ArmyStatus )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "army_option", "army_option_tpl", true );
    }

    $WorkTypes = $cv->workStatusTypes();
    foreach( $WorkTypes as $WorkType )
    {
        $t->set_var( "value", $WorkType );
        $t->set_var( "name", $intl->read_var( "strings", "work_" . $WorkType ) );
        $t->set_var( "selected", "" );
        
        if( $WorkType == $WorkStatus )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "work_option", "work_option_tpl", true );
    }
}

// The next section is data which needs even more specialized handling (loop through)
{
    $country = new eZCountry();
    $countries = $country->getAllArray();
    
    $t->set_var( "nation_item", "" );
    $DefaultCountry = $ini->read_var( "eZCVMain", "DefaultCountry" );

    foreach( $countries as $country )
    {
        $t->set_var( "country_selected", "" );

        if( is_numeric( $CVID ) )
        {
            if( $cv->nationalityID() == $country["ID"] )
            {
                $t->set_var( "country_selected", "selected" );
            }
        }
        elseif( $country["Name"] == $DefaultCountry )
        {
            $t->set_var( "country_selected", "selected" );
        }
        $t->set_var( "nation_name", $country["Name"] );
        $t->set_var( "nation_id", $country["ID"] );
        $t->parse( "nation_item", "nation_tpl", true );
    }
}

// From this point on all checks have been made, now we can just select which function we are going
// to do.

if( !empty( $CertificateAdd ) || !empty( $ExtracurricularAdd ) || !empty( $EducationAdd ) || !empty( $ExperienceAdd ) )
{
    $Action = "add";
}

if( $Action == "edit" )
{
    $ActionValue = "update";
}

if( $Action == "new" )
{
    $ActionValue = "insert";
}

$t->set_var( "action_value", $ActionValue );

$t->pparse( "output", "cv_edit"  );


?>
