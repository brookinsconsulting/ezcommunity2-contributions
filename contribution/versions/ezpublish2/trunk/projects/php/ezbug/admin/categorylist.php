<?
/*
  Viser liste over prioriteringer
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugcategory.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "category_page" =>  "categorylist.tpl"
    ) );

$t->set_block( "category_page", "category_item_tpl", "category_item" );

$t->set_var( "site_style", $SiteStyle );

$category = new eZBugCategory();
$categoryList = $category->getAll();

$i=0;
foreach( $categoryList as $categoryItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );

    $i++;
    $t->parse( "category_item", "category_item_tpl", true );
} 

$t->pparse( "output", "category_page" );
?>
