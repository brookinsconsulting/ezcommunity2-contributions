<?
/*
  Viser liste over prioriteringer
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugmodule.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "modulelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "module_page" =>  "modulelist.tpl"
    ) );

$t->set_block( "module_page", "module_item_tpl", "module_item" );
$t->set_block( "module_page", "path_item_tpl", "path_item" );

$t->set_var( "site_style", $SiteStyle );

$module = new eZBugModule( $ParentID );
$t->set_var( "this_id", $ParentID );


if( isset( $DeleteModules ) ) // delete selected modules
{
    if( count( $ModuleArrayID ) > 0 )
    {
        foreach( $ModuleArrayID as $itemID )
        {
            $delModule = new eZBugModule( $itemID );
            $delModule->delete();
        }
    }
}

// path
$pathArray = $module->path();

// print( count( $pathArray ) );
$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "module_id", $path[0] );

    $t->set_var( "module_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$moduleList = $module->getByParent( $module );

if ( count( $moduleList ) == 0 )
{
    $t->set_var( "module_item", "ingen moduler funnet" );
}
else
{
    $i=0;
    foreach( $moduleList as $moduleItem )
    {
        if ( ( $i %2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        
        $t->set_var( "module_id", $moduleItem->id() );
        $t->set_var( "module_name", $moduleItem->name() );
        
        $i++;
        $t->parse( "module_item", "module_item_tpl", true );
    }
} 

$t->pparse( "output", "module_page" );
?>
