<?
/*
  Viser liste over alle kontakter
*/
include_once( "classes/INIFile.php" );

$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZClassifiedMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezclassified/classes/ezcategory.php" );
include_once( "ezclassified/classes/ezclassified.php" );
include_once( "classes/ezlocale.php" );

$locale = new eZLocale( $Language );

$t = new eZTemplate( "ezclassified/admin/" . $ini->read_var( "eZClassifiedMain", "AdminTemplateDir" ),
                     "ezclassified/admin/intl/", $Language, "classifiedlist.php" );
$t->setAllStrings();

$t->set_file( array(
    "classified_page_tpl" => "classifiedlist.tpl" ) );

$t->set_block( "classified_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

$t->set_block( "classified_page_tpl", "classified_list_tpl", "classified_list" );
$t->set_block( "classified_list_tpl", "classified_item_tpl", "classified_item" );

$t->set_block( "classified_page_tpl", "path_item_tpl", "path_item" );


$category = new eZCategory( $CategoryID );

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "category_id", $path[0] );

    $t->set_var( "category_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

// Categorylist
$categoryList = $category->getByParentID( $CategoryID );

if ( count ( $categoryList ) == 0 )
{
    $t->set_var( "category_list", "" );
}
else
{
    $i=0;
    foreach( $categoryList as $categoryItem )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );
                
        $t->set_var( "category_id", $categoryItem->id() );
        $t->set_var( "category_parent_id", $categoryItem->parentID() );
        $t->set_var( "category_name", $categoryItem->name() );
        
        $i++;
        $t->parse( "category_item", "category_item_tpl", true );
    }
    $t->parse( "category_list", "category_list_tpl", true );
}

$position = new eZClassified();

$positionList = $position->getByCategory( $CategoryID );

if ( count ( $positionList ) == 0 )
{
    $t->set_var( "classified_list", "" );
//    print( "Gjør noe slemt" );
}
else
{
    for( $i=0;$i<count( $positionList ); $i++ )
    {
        if ( ( $i %2 ) == 0 )
            $t->set_var( "td_class", "bglight" );
        else
            $t->set_var( "td_class", "bgdark" );

        $t->set_var( "classified_title", $positionList[$i]->title() );
        $t->set_var( "classified_id", $positionList[$i]->id() );
        $validUntil = $positionList[$i]->validUntil();

        $date = $locale->format( $validUntil );

        $t->set_var( "valid_until", $date );

        $company = $positionList[$i]->company();

        $t->set_var( "company_name", $company->name() );
        
        $t->parse( "classified_item", "classified_item_tpl", true );
    }
    $t->parse( "classified_list", "classified_list_tpl", true );
}
$t->pparse( "output", "classified_page_tpl");
?>
         
