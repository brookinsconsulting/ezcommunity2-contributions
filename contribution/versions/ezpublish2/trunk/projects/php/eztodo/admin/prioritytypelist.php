<?
/*
  Viser liste over prioriteringer
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
include_once( "eztodo/classes/ezpriority.php" );

 
$t = new eZTemplate( "eztodo/admin/" . $ini->read_var( "eZTodoMain", "AdminTemplateDir" ),
                     "eztodo/admin/intl", $Language, "prioritytypelist.php" );
$t->setAllStrings();

$t->set_file( array(
    "priority_type_page" =>  "prioritytypelist.tpl"
    ) );

$t->set_block( "priority_type_page", "priority_item_tpl", "priority_item" );

$t->set_var( "site_style", $SiteStyle );

$priority_type = new eZPriority();
$priority_type_array = $priority_type->getAll();

$i=0;
foreach( $priority_type_array as $priorityItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "priority_type_id", $priorityItem->id() );
    $t->set_var( "priority_type_name", $priorityItem->name() );

    $i++;
    $t->parse( "priority_item", "priority_item_tpl", true );
} 

$t->pparse( "output", "priority_type_page" );
?>
