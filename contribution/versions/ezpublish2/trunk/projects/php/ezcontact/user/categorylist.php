<?
/*
  Viser liste over alle kontakter
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZContactMain", "Language" );

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
    "company_page_tpl" => "categorylist.tpl" ) );

$t->set_block( "company_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );


$companyType = new eZCompanyType( );

// Categorylist
$companyTypeList = $companyType->getByParentID( 0 );

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


$t->pparse( "output", "company_page_tpl");
?>
