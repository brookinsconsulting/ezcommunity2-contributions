<?

/*
  listlink.php viser alle kategorier
*/

include_once "template.inc";
include_once "ezlink/dbsettings.php";
include_once "ezphputils.php";

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

// setter template filer
$t = new Template( "." );
$t->set_file( array(
    "search_item" => $DOCUMENTROOT . "templates/searchitemuser.tpl",
    "search_list" => $DOCUMENTROOT . "templates/searchlistuser.tpl"
    ) );

$limit = "10";
$offset = $Offset;

$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

$t->set_var( "printpath", $linkGroup->printPath( $LGID, $DOCUMENTROOT . "linklist.php" ) );


$link = new eZLink();

if ( $Action == "search" )
{
    $link_array = $link->getQueryLimit( $QueryText, $limit, $offset );
    $thit_count = count( $link->getQuery( $QueryText ) );
    $tlink_message = "Søk resultater";
}

// Lister alle linker i kategori
if ( count( $link_array ) == 0 )
{
    $t->set_var( "link_list", "Ingen linker funnet" );
}
else
{
    
    for ( $i=0; $i<count( $link_array ); $i++ )
    {
        

        $t->set_var( "bg_color", "#eeddaa" );

        $t->set_var( "link_id", $link_array[ $i ][ "ID" ] );
        $t->set_var( "link_title", $link_array[ $i ][ "Title" ] );
        $t->set_var( "link_description", $link_array[ $i ][ "Description" ] );
        $t->set_var( "link_groupid", $link_array[ $i ][ "LinkGroup" ] );
        $t->set_var( "link_keywords", $link_array[ $i ][ "KeyWords" ] );
        $t->set_var( "link_created", $link_array[ $i ][ "Created" ] );
        $t->set_var( "link_modified", $link_array[ $i ][ "Modified" ] );
        $t->set_var( "link_accepted", $link_array[ $i ][ "Accepted" ] );
        $t->set_var( "link_url", $link_array[ $i ][ "Url" ] );

        $t->set_var( "link_next_offs", $offset + $limit );
        $t->set_var( "link_prev_offs", $offset - $limit );
        $t->set_var( "query_text", $QueryText );

        $LGID =  ( $link_array[ $i ][ "LinkGroup" ] );

        $t->set_var( "printpath", $linkGroup->printPath( $LGID, $DOCUMENTROOT . "linklist.php" ) );                       

        $hit = new eZHit();
        $hits = $hit->getLinkHits( $link_array[ $i ][ "ID" ] );

        $t->set_var( "link_hits", $hits );

        $tlink_message = "Linker";

        $t->set_var( "document_root", $DOCUMENTROOT );

        $t->parse( "link_list", "search_item", true );
    }
}


if ( $Action == "search" )
{
    $link_array = $link->getQueryLimit( $QueryText, $limit, $offset );
    $tlink_message = "Søk resultater";
}


$t->set_var( "hit_count", $thit_count );
$t->set_var( "link_message", $tlink_message );
$t->set_var( "linkgroup_id", $LGID );
$t->set_var( "document_root", $DOCUMENTROOT );

$t->set_var( "printpath", $linkGroup->printPath( 0, $DOCUMENTROOT . "linklist.php" ) );                       


$t->pparse( "output", "search_list" );


?>
