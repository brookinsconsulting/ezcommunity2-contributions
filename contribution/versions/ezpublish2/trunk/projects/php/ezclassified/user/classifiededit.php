<?
/*
  Editere firma.
*/

include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZClassifiedMain", "Language" );

include_once( "ezclassified/classes/ezposition.php" );
include_once( "ezclassified/classes/ezcategory.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezonline.php" );
include_once( "ezcontact/classes/ezaddress.php" );


require( "ezuser/user/usercheck.php" );


if ( !eZPermission::checkPermission( $user, "eZClassified", "ClassifiedAdd" ) && $Action = "new" )
{
    print( "insert header function here" );
    exit();
}
if ( !eZPermission::checkPermission( $user, "eZClassified", "ClassifiedAdd" ) && $Action = "insert" )
{
    
}
if ( !eZPermission::checkPermission( $user, "eZClassified", "ClassifiedAdd" ) && $Action = "edit" )
{
    
}

if ( !eZPermission::checkPermission( $user, "eZClassified", "ClassifiedAdd" ) && $Action = "delete" )
{
    
}

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

if ( $Action == "insert" )
{
    $position = new eZPosition();
    $position->setName( $Name );
    $position->setUser( $user );
    $position->setDescription( $Description );
    $position->setPrice( $Price );

    $position->setPay( $Pay );
    $position->setWorkTime( $WorkTime );
    $position->setDuration( $Duration );
    $position->setContactPerson( $ContactPerson );
    $position->setWorkPlace( $WorkPlace );
    $position->store();
}

if ( $Action == "update" )
{
    $position = new eZPosition( $PositionID );
    $position->setName( $Name );
    $position->setUser( $user );
    $position->setDescription( $Description );
    $position->setPrice( $Price );

    $position->setPay( $Pay );
    $position->setWorkTime( $WorkTime );
    $position->setDuration( $Duration );
    $position->setContactPerson( $ContactPerson );
    $position->setWorkPlace( $WorkPlace );
    $position->store();
}

if( $Action == "delete" )
{
    $position = new eZPosition( $PositionID );
    $position->delete();

    header( "Location: /classified/list/" );
}

$t = new eZTemplate( "ezclassified/user/" . $ini->read_var( "eZClassifiedMain", "TemplateDir" ),
                     "ezclassified/user/intl", $Language, "classifiededit.php" );
$t->setAllStrings();

$t->set_file( array(                    
    "classified_edit" => "classifiededit.tpl"
    ) );

$t->set_block( "classified_edit", "category_item_tpl", "category_item" );

$t->set_block( "classified_edit", "company_view_tpl", "company_view" );
$t->set_block( "company_view_tpl", "address_item_tpl", "address_item" );
$t->set_block( "company_view_tpl", "phone_item_tpl", "phone_item" );
$t->set_block( "company_view_tpl", "fax_item_tpl", "fax_item" );
$t->set_block( "company_view_tpl", "web_item_tpl", "web_item" );
$t->set_block( "company_view_tpl", "email_item_tpl", "email_item" );
$t->set_block( "company_view_tpl", "image_view_tpl", "image_view" );
$t->set_block( "company_view_tpl", "logo_view_tpl", "logo_view" );
$t->set_block( "company_view_tpl", "no_image_tpl", "no_image" );
$t->set_block( "company_view_tpl", "no_logo_tpl", "no_logo" );


if ( $Action == "new" )
{
    $t->set_var( "name", "" );
    $t->set_var( "description", "" );
    $t->set_var( "pay", "" );
    $t->set_var( "worktime", "" );
    $t->set_var( "duration", "" );
    $t->set_var( "workplace", "" );
}

// Redigering av firma.
if ( $Action == "edit" )
{

    // Template variabler.
    $Action_value = "update";
    
}

// Category selector
$category = new eZCategory();
$categoryTypeList = $category->getAll();
for( $i=0; $i < count( $categoryTypeList ); $i++ )
{
    $t->set_var( "category_name", $categoryTypeList[$i]->name() );
    $t->set_var( "category_id", $categoryTypeList[$i]->id() );

    if ( $position )
    {
        $categoryList = $position->categories( $PositionID );
        $found = false;
        foreach ( $categoryList as $category )
            {
                if ( $category->id() == $categoryTypeList[$i]->id() )
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
    $t->parse( "category_item", "category_item_tpl", true );
}

$company = new eZCompany();

$companyList = $company->getByUser( $user );

if ( count ( $companyList ) == 1 )
{
    $t->set_var( "company_name", $companyList[0]->name() );
    $t->set_var( "company_description", $companyList[0]->comment() );
    $t->set_var( "company_companyno", $companyList[0]->companyNo() );

    // View logo.
    $logoImage = $companyList[0]->logoImage();

    if ( ( get_class ( $logoImage ) == "ezimage" ) && ( $logoImage->id() != 0 ) )
    {
        $variation = $logoImage->requestImageVariation( 150, 150 );

        $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
        $t->set_var( "logo_name", $logoImage->name() );
        $t->set_var( "logo_id", $logoImage->id() );
        
        $t->set_var( "no_logo", "" );
        $t->parse( "logo_view", "logo_view_tpl" );
    }
    else
    {
        $t->set_var( "logo_view", "" );
        $t->parse( "no_logo", "no_logo_tpl" );
    }

    // Telephone list
    $phoneList = $companyList[0]->phones( $companyList[0]->id() );

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
    $addressList = $companyList[0]->addresses( $companyList[0]->id() );
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
    $onlineList = $companyList[0]->onlines( $companyList[0]->id() );

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
        $t->parse( "web_item", "web_item_tpl" );
        $t->parse( "email_item", "email_item_tpl" );

    }
    $t->parse( "company_view", "company_view_tpl" );
}
    
// Templateoun variabler.

$t->set_var( "error", $error );
$t->set_var( "errors_item", $error );

$t->set_var( "action_value", $Action_value );

$t->pparse( "output", "classified_edit"  );

?>
