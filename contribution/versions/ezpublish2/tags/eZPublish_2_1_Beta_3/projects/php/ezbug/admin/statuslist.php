<?
/*
  Viser liste over prioriteringer
*/
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZBugMain", "Language" );
$LanguageIni = new INIFIle( "ezbug/admin/intl/" . $Language . "/statuslist.php.ini", false );


include_once( "classes/eztemplate.php" );

include_once( "ezbug/classes/ezbugstatus.php" );

$t = new eZTemplate( "ezbug/admin/" . $ini->read_var( "eZBugMain", "AdminTemplateDir" ),
                     "ezbug/admin/intl", $Language, "statuslist.php" );
$t->setAllStrings();

$t->set_file( array(
    "status_page" =>  "statuslist.tpl"
    ) );

$t->set_block( "status_page", "status_item_tpl", "status_item" );

//$t->set_var( "site_style", $SiteStyle );

if( isset( $Ok ) || isset( $AddStatus ) )
{
    $i = 0;
    if( count( $StatusID ) > 0 )
    {
        foreach( $StatusID as $itemID )
        {
            $status = new eZBugStatus( $itemID );
            $status->setName( $StatusName[$i] );
            $status->store();
            $i++;
        }
    }
}

if( isset( $AddStatus ) )
{
    $newItem = new eZBugStatus();
    $newName = $LanguageIni->read_var( "strings", "newstatus" );
    $newItem->setName($newName);
    $newItem->store();
}

if( isset( $DeleteStatus ) )
{
    if( count( $StatusArrayID ) > 0 )
    {
        foreach( $StatusArrayID as $deleteItemID )
        {
            $item = new eZBugStatus( $StatusID[ $deleteItemID ] );
            $item->delete();
        }
    }

}


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
    $t->set_var( "index_nr", $i );
    
    $t->parse( "status_item", "status_item_tpl", true );
    $i++;
} 

$t->pparse( "output", "status_page" );
?>
