<?
/*
  Viser liste over prioriteringer
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );

include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugstatus.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "statuslist.php" );
$t->setAllStrings();

$t->set_file( array(
    "status_page" =>  "statuslist.tpl"
    ) );

$t->set_block( "status_page", "status_item_tpl", "status_item" );

$t->set_var( "site_style", $SiteStyle );

$status = new eZBugStatus();
$statusList = $status->getAll();

$i=0;
foreach( $statusList as $statusItem )
{
    if ( ( $i %2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }
        
    $t->set_var( "status_id", $statusItem->id() );
    $t->set_var( "status_name", $statusItem->name() );

    $i++;
    $t->parse( "status_item", "status_item_tpl", true );
} 

$t->pparse( "output", "status_page" );
?>
