<?
/*
  Shows a list of priorities, and lets the user edit and add new priorities.
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );
$LanguageIni = new INIFIle( "ezbug/admin/intl/" . $Language . "/prioritylist.php.ini", false );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugpriority.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "prioritylist.php" );
$t->setAllStrings();

$t->set_file( array(
    "priority_page" =>  "prioritylist.tpl"
    ) );

$t->set_block( "priority_page", "priority_item_tpl", "priority_item" );

$t->set_var( "site_style", $SiteStyle );


if( isset( $DeletePriorities ) )
{
    if( count( $PriorityArrayID ) > 0 )
    {
        foreach( $PriorityArrayID as $deleteItemID )
        {
            $item = new eZBugPriority( $PriorityID[ $deleteItemID ] );
            $item->delete();
        }
    }
}


if( isset( $Ok ) || isset( $AddPriority ) )
{
    $i = 0;
    if( count( $PriorityID ) > 0 )
    {
        foreach( $PriorityID as $itemID )
        {
            $priority = new eZBugPriority( $itemID );
            $priority->setName( $PriorityName[$i] );
            $priority->store();
            $i++;
        }
    }
    
//    $priority = new eZBugPriority( $PriorityID );
//    $priority->setName( $Name );
//    $priority->store();
}

if( isset( $AddPriority ) )
{
    $newItem = new eZBugPriority();
    $newName = $LanguageIni->read_var( "strings", "new_priority" );
    $newItem->setName($newName);
    $newItem->store();
}


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
    $t->set_var( "index_nr", $i );
    
    $t->parse( "priority_item", "priority_item_tpl", true );
    $i++;
} 

$t->pparse( "output", "priority_page" );
?>
