<?
/*
  Viser liste over prioriteringer
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugpriority.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "prioritylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "priority_page" =>  "prioritylist.tpl"
    ) );

$t->set_block( "priority_page", "priority_item_tpl", "priority_item" );

$priority = new eZBugPriority();
$priorityList = $priority->getAll();

$i=0;
foreach( $priorityList as $priorityItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "priority_id", $priorityItem->id() );
    $t->set_var( "priority_name", $priorityItem->name() );

    $i++;
    $t->parse( "priority_item", "priority_item_tpl", true );
} 

$t->pparse( "output", "priority_page" );
?>
