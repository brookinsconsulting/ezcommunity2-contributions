<?
/*
  Viser liste over alle kontakter
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezcontact/user/intl/" . $Language . "/companylist.php.ini", false );

include_once( "classes/eztemplate.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );

// include_once( "ezcontact/topmenu.php" );

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl/", $Language, "companylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "company_list" => "companylist.tpl" ) );

$t->set_block( "company_list", "company_item_tpl", "company_item" );
$t->set_block( "company_list", "error_tpl", "error" );

$company = new eZCompany();

$companyList = $company->getAll( );

if ( count( $companyList ) == 0 )
{
    $t->set_var( "error_msg", $errorIni->read_var( "strings", "error_msg" ) );
    $t->set_var( "company_item", "" );
    $t->parse( "error", "error_tpl" );
    
}
else
{
    $color_count = 0;
    foreach( $companyList as $companyItem )
    {
        if ( ( $color_count % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#F0F0F0" );
        }
        else
        {
            $t->set_var( "bg_color", "#DCDCDC" );
        }
        
        $companyID = $companyItem->id();
        $t->set_var( "company_id", $companyID );
        $t->set_var( "company_name", $companyItem->name() );

        // Image list
        $logoImage = $companyItem->logoImage();
        if ( get_class ( $logoImage ) )
        {
            $variation = $logoImage->requestImageVariation( 150, 150 );
            
            $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
            $t->set_var( "logo_name", $logoImage->name() );
        }


        // Phone list
        $phoneList = $company->phones( $companyID );
        for( $i=0; $i<count( $phoneList ); $i++ )
        {
            if ( $phoneList[$i]->phoneTypeID() == 1 )
            {
                $t->set_var( "telephone", $phoneList[$i]->number() );
            }
        }

        
        $color_count++;

        $t->set_var( "error", "" );
        $t->parse( "company_item", "company_item_tpl", true );
    }
    $t->pparse( "output", "company_list");
} 


?>
