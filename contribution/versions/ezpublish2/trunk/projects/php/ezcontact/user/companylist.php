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
include_once( "ezcontact/classes/ezcompanytype.php" );

// include_once( "ezcontact/topmenu.php" );

$t = new eZTemplate( "ezcontact/user/" . $ini->read_var( "eZContactMain", "TemplateDir" ),
                     "ezcontact/user/intl/", $Language, "companylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "company_page_tpl" => "companylist.tpl" ) );

$t->set_block( "company_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "company_page_tpl", "company_list_tpl", "company_list" );
$t->set_block( "company_list_tpl", "company_item_tpl", "company_item" );
$t->set_block( "company_item_tpl", "image_view_tpl", "image_view" );
$t->set_block( "company_item_tpl", "no_image_tpl", "no_image" );


$t->set_block( "company_page_tpl", "path_item_tpl", "path_item" );


$companyType = new eZCompanyType( $CategoryID );

// path
$pathArray = $companyType->path();

// print( count( $pathArray ) );
$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

// Categorylist
$companyTypeList = $companyType->getByParentID( $CategoryID );

if ( count ( $companyTypeList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $i=0;
    foreach( $companyTypeList as $companyTypeItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
                
        $t->set_var( "category_id", $companyTypeItem->id() );
        $t->set_var( "category_parent_id", $companyTypeItem->parentID() );
        $t->set_var( "category_name", $companyTypeItem->name() );
        
        $i++;
        $t->parse( "category_item", "category_item_tpl", true );
    }
    $t->parse( "category_list", "category_list_tpl", true );
}

$company = new eZCompany();

$companyList = $company->getByCategory( $CategoryID );

if ( count( $companyList ) == 0 )
{
    $t->set_var( "error_msg", $errorIni->read_var( "strings", "error_msg" ) );
    $t->set_var( "company_item", "" );
    $t->set_var( "company_list", "" );
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
        if ( get_class ( $logoImage ) == "ezimage" )
        {
            $variation = $logoImage->requestImageVariation( 150, 150 );
            
            $t->set_var( "logo_image_src", "/" . $variation->imagePath() );
            $t->set_var( "logo_name", $logoImage->name() );

            $t->set_var( "no_image", "" );
            $t->parse( "image_view", "image_view_tpl" );
        }
        else
        {
            $t->set_var( "image_view", "" );
            $t->parse( "no_image", "no_image_tpl" );

        }


        // Phone list
        $phoneList = $company->phones( $companyID );
        for( $i=0; $i<count( $phoneList ); $i++ )
        {
            if ( $phoneList[$i]->phoneTypeID() == 1 )
            {
                $t->set_var( "company_telephone", $phoneList[$i]->number() );
            }
        }
        
        $color_count++;

        $t->set_var( "error", "" );
        $t->parse( "company_item", "company_item_tpl", true );
    }
    $t->parse( "company_list", "company_list_tpl", true );
}

$t->pparse( "output", "company_page_tpl");
?>
