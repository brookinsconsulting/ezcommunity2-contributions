<?

include_once( "template.inc" );
include( "ezlink/dbsettings.php" );
include_once( "ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

$t = new Template( "." );
$t->set_file( array(
    "last_links" => $DOCUMENTROOT . "templates/linklist.tpl",
    "link_item" => $DOCUMENTROOT . "templates/lastlinkitem.tpl"
    ) );

$link = new eZLink();

$link_array = $link->getLastTenDate( 10, 0 );

if ( count( $link_array ) == 0 )
{
    $t->set_var( "link_list", "<p>Ingen linker ble funnet.</p>" );
}
else
{
    for ( $i=0; $i<count( $link_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#f0f0f0" );
        }
        else
        {
            $t->set_var( "bg_color", "#dcdcdc" );
        }  

        $t->set_var( "link_id", $link_array[ $i ][ "ID" ] );
        $t->set_var( "link_title", $link_array[ $i ][ "Title" ] );
        $t->set_var( "link_description", $link_array[ $i ][ "Description" ] );
        $t->set_var( "link_groupid", $link_array[ $i ][ "LinkGroup" ] );
        $t->set_var( "link_keywords", $link_array[ $i ][ "KeyWords" ] );
        $t->set_var( "link_created", $link_array[ $i ][ "Created" ] );
        $t->set_var( "link_modified", $link_array[ $i ][ "Modified" ] );
        $t->set_var( "link_accepted", $link_array[ $i ][ "Accepted" ] );
        $t->set_var( "link_url", $link_array[ $i ][ "Url" ] );

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $t->set_var( "document_root", $DOCUMENTROOT );

        $t->parse( "link_list", "link_item", true );
    }
}

$t->pparse( "output", "last_links" );

?>
