<?
/*
  Viser liste over alle kontakter
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );
$errorIni = new INIFIle( "ezcontact/admin/intl/" . $Language . "/companylist.php.ini", false );

include_once( "classes/eztemplate.php" );

include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezpersontype.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezcontact/classes/ezcompanytype.php" );

// include_once( "ezcontact/topmenu.php" );

$t = new eZTemplate( "ezcontact/admin/" . $ini->read_var( "eZContactMain", "AdminTemplateDir" ),
                     "ezcontact/admin/intl/", $Language, "companylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "company_page_tpl" => "companylist.tpl" ) );

$t->set_block( "company_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "company_page_tpl", "company_list_tpl", "company_list" );
$t->set_block( "company_list_tpl", "company_item_tpl", "company_item" );

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
    
//    $t->parse( "path_item", "path_item_tpl", true );
}

// Categorylist
$companyTypeList = $companyType->getByParentID( $CategoryID );

if ( count ( $companyTypeList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    foreach( $companyTypeList as $companyTypeItem )
    {
        $t->set_var( "category_id", $companyTypeItem->id() );
        $t->set_var( "category_parent_id", $companyTypeItem->parentID() );
        $t->set_var( "category_name", $companyTypeItem->name() );
        
        $t->set_var( "categories", "Kategorier" );
        
        $t->parse( "category_item", "category_item_tpl", true );
    }
    $t->parse( "category_list", "category_list_tpl", true );
}

// Companylist
$company = new eZCompany();
$companyList = $company->getByCategory( $CategoryID );

if ( count( $companyList ) == 0 )
{
    $t->set_var( "company_list" );
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
        
        $color_count++;

        $t->set_var( "companys", "Firmaer" );
        $t->set_var( "error", "" );
        $t->parse( "company_item", "company_item_tpl", true );
    }
    $t->parse( "company_list", "company_list_tpl", true );
} 

$t->pparse( "output", "company_page_tpl");

?>
