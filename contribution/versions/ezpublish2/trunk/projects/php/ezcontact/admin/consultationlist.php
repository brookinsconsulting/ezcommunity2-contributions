<?
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

include_once( "classes/eztemplate.php" );

if ( isset( $ConsultationList ) )
{
    $templatefile = "consultationdetaillist.tpl";
    $languagefile = "consultationdetaillist.php";
}
else
{
    $templatefile = "consultationlist.tpl";
    $languagefile = "consultationlist.php";
}

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),  "ezcontact/admin/intl", $Language, $languagefile );
$t->setAllStrings();

include_once( "ezcontact/classes/ezconsultation.php" );
include_once( "classes/ezlocale.php" );

$t->set_file( array(
    "consultation_page" => $templatefile
    ) );

if ( isset( $ConsultationList ) )
{
    $t->set_block( "consultation_page", "no_consultations_item_tpl", "no_consultations_item" );
    $t->set_block( "consultation_page", "consultation_table_item_tpl", "consultation_table_item" );
    $t->set_block( "consultation_table_item_tpl", "consultation_item_tpl", "consultation_item" );

    $t->set_block( "consultation_table_item_tpl", "new_company_consultation_item_tpl", "new_company_consultation_item" );
    $t->set_block( "consultation_table_item_tpl", "new_person_consultation_item_tpl", "new_person_consultation_item" );

    $t->set_var( "consultation_item", "" );
    $t->set_var( "no_consultations_item", "" );
    $t->set_var( "consultation_table_item", "" );
}
else
{
    $t->set_block( "consultation_page", "no_companies_item_tpl", "no_companies_item" );
    $t->set_block( "consultation_page", "company_table_item_tpl", "company_table_item" );
    $t->set_block( "company_table_item_tpl", "company_item_tpl", "company_item" );

    $t->set_block( "consultation_page", "no_persons_item_tpl", "no_persons_item" );
    $t->set_block( "consultation_page", "person_table_item_tpl", "person_table_item" );
    $t->set_block( "person_table_item_tpl", "person_item_tpl", "person_item" );

    $t->set_var( "errors", "" );

    $t->set_var( "company_item", "" );
    $t->set_var( "no_companies_item", "" );
    $t->set_var( "company_table_item", "" );

    $t->set_var( "person_item", "" );
    $t->set_var( "no_persons_item", "" );
    $t->set_var( "person_table_item", "" );
}

$user = eZUser::currentUser();

if ( !$user )
{
    header( "Location: /user/login" );
    exit();
}

if ( isset( $ConsultationList ) )
{
    // List specific consultations

    if ( !isset( $CompanyID ) && !isset( $PersonID ) )
    {
        die( "Neither CompanyID or PersonID is set" );
    }

    if ( isset( $CompanyID ) )
    {
        $consultations = eZConsultation::findConsultationsByContact( $CompanyID, $user->id(), false );
        $t->set_var( "consultation_type", "company" );
        $t->set_var( "company_id", $CompanyID  );
    }
    else if ( isset( $PersonID ) )
    {
        $consultations = eZConsultation::findConsultationsByContact( $PersonID, $user->id(), true );
        $t->set_var( "consultation_type", "person" );
        $t->set_var( "person_id", $PersonID  );
    }

    $count = count( $consultations );

    if( $i < 0 )
    {
        $t->set_block( );
    }

    $locale = new eZLocale( $Language );
    $i = 0;

    foreach ( $consultations as $consultation )
    {
        if( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "bglight" );
        }
        else
        {
            $t->set_var( "bg_color", "bgdark" );
        }

        $t->set_var( "consultation_id", $consultation->id() );
        $t->set_var( "consultation_date", $locale->format( $consultation->date() ) );
        $t->set_var( "consultation_short_description", $consultation->shortDescription() );
        $t->set_var( "consultation_status_id", $consultation->state() );
        $t->set_var( "consultation_status", eZConsultation::stateName( $consultation->state() ) );
        $t->parse( "consultation_item", "consultation_item_tpl", true );
        $i++;
    }

    if ( $count > 0 )
    {
        $t->parse( "consultation_table_item", "consultation_table_item_tpl", true );
    }
    else
    {
        $t->parse( "no_consultations_item", "no_consultations_item_tpl", true );
    }

    if ( isset( $CompanyID ) )
    {
        $t->set_var( "new_person_consultation_item", "" );
        $t->parse( "new_company_consultation_item", "new_company_consultation_item_tpl"  );
    }
    else if ( isset( $PersonID ) )
    {
        $t->parse( "new_person_consultation_item", "new_person_consultation_item_tpl"  );
        $t->set_var( "new_company_consultation_item", "" );
    }
}
else
{
    // Find companies which the user has consulted with

    $companies = eZConsultation::findConsultedCompanies( $user->id() );

    $count = count( $companies );

    if( $i < 0 )
    {
        $t->set_block( );
    }

    for( $i = 0; $i < $count; $i++ )
    {
        if( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "bglight" );
        }
        else
        {
            $t->set_var( "bg_color", "bgdark" );
        }

        $t->set_var( "company_id", $companies[$i]->id() );
        $t->set_var( "company_name", $companies[$i]->name() );
        $t->set_var( "consultation_count", eZConsultation::companyConsultationCount( $companies[$i]->id(), $user->id() ) );
        $t->parse( "company_item", "company_item_tpl", true );
    }

    if ( $count > 0 )
    {
        $t->parse( "company_table_item", "company_table_item_tpl", true );
    }
    else
    {
        $t->parse( "no_companies_item", "no_companies_item_tpl", true );
    }

    // Find persons which the user has consulted with

    $persons = eZConsultation::findConsultedPersons( $user->id() );

    $count = count( $persons );

    if( $i < 0 )
    {
        $t->set_block( );
    }

    for( $i = 0; $i < $count; $i++ )
    {
        if( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "bglight" );
        }
        else
        {
            $t->set_var( "bg_color", "bgdark" );
        }

        $t->set_var( "person_id", $persons[$i]->id() );
        $t->set_var( "person_firstname", $persons[$i]->firstName() );
        $t->set_var( "person_lastname", $persons[$i]->lastName() );
        $t->set_var( "consultation_count", eZConsultation::personConsultationCount( $persons[$i]->id(), $user->id() ) );
        $t->parse( "person_item", "person_item_tpl", true );
    }

    if ( $count > 0 )
    {
        $t->parse( "person_table_item", "person_table_item_tpl", true );
    }
    else
    {
        $t->parse( "no_persons_item", "no_persons_item_tpl", true );
    }
}

$t->pparse( "output", "consultation_page" );

?>
