<?
/*
  Viser liste over kontakt typer.
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZTodoMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "common/ezphputils.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "eztodo/classes/eztodo.php" );
include_once( "eztodo/classes/ezcategory.php" );


$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "categorytypelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "category_type_page" =>  "categorytypelist.tpl"
    ) );

$t->set_block( "category_type_page", "category_item_tpl", "category_item" );

$category_type = new eZCategory();
$category_type_array = $category_type->getAll();

$i=0;
foreach( $category_type_array as $categoryItem )
{
    if ( ( $i %2 ) == 0 )
        $t->set_var( "td_class", "bglight" );
    else
        $t->set_var( "td_class", "bgdark" );

    $t->set_var( "category_type_id", $categoryItem->id() );
    $t->set_var( "category_type_name", $categoryItem->name() );

    $i++;;
    $t->parse( "category_item", "category_item_tpl", true );
} 

$t->pparse( "output", "category_type_page" );
    
?>
