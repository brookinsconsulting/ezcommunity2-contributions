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
    "linkgroup_list" => $DOCUMENTROOT . "templates/linkgrouplistuser.tpl",
    "linkgroup_item" => $DOCUMENTROOT . "templates/linkgroupitemuser.tpl",
    "linkgroup_item2" => $DOCUMENTROOT . "templates/linkgroupitemuser2.tpl",
    "link_item" => $DOCUMENTROOT . "templates/linkitemuser.tpl"
    ) );

// Lister alle kategorier
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

// $linkGroup->printPath( $LGID, $DOCUMENTROOT . "linklist.php" );

$linkGroup_array = $linkGroup->getByParent( $LGID );


if ( count( $linkGroup_array ) == 0 )
{
    $t->set_var( "group_list", "Ingen grupper funnet." );

}
else
{
// print( "antall grupper " . count( $linkGroup_array )); 
    for ( $i=0; $i<count( $linkGroup_array ); $i++ )
    {
        $t->set_var( "bg_color", "#eeeedd" );
        $link_group_id = $linkGroup_array[ $i ][ "ID" ];
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $linkGroup_array[ $i ][ "Title" ] );
        $t->set_var( "linkgroup_parent", $linkGroup_array[ $i ][ "Parent" ] );

        $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
        $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
        
        $t->set_var( "document_root", $DOCUMENTROOT );
        
        if ( $i %2 == 0 )
        {
            $t->parse( "group_list", "linkgroup_item", true );
        }
        else
        {
            $t->parse( "group_list", "linkgroup_item2", true );
        }

    }
}

if ( ( $LGID == 0 ) && ( $LGID != "incoming" ) )
{
    $t->set_var( "bg_color", "#ffffdd" );

    $t->set_var( "linkgroup_id", "incoming" );
    $t->set_var( "linkgroup_title", "Innkommende linker" );
    $t->set_var( "linkgroup_parent", "" );

    
    $total_sub_links = $linkGroup->getTotalIncomingLinks();
    
    $t->set_var( "total_links", $total_sub_links );
    $t->set_var( "new_links", "X" );
     

    $t->set_var( "document_root", $DOCUMENTROOT );
    
    $t->parse( "group_list", "linkgroup_item", true );
}


// Lister alle linker i kategori
$link = new eZLink();

if ( $Action == "search" )
{
    $link_array = $link->getQuery( $QueryText );    
}
else if ( $LGID == "incoming" )
{
    $link_array = $link->getNotAccepted( $LGID );
}
else
{
    $link_array = $link->getByGroup( $LGID );
} 

if ( count( $link_array ) == 0 )
{
    $t->set_var( "link_list", "Ingen linker funnet." );
}
else
{
    for ( $i=0; $i<count( $link_array ); $i++ )
    {

        $t->set_var( "bg_color", "#eeddbb" );

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

$t->set_var( "printpath", $linkGroup->printPath( $LGID, $DOCUMENTROOT . "linklist.php" ) );

$t->set_var( "linkgroup_id", $LGID );
$t->set_var( "document_root", $DOCUMENTROOT );
                       
$t->pparse( "output", "linkgroup_list" );

?>
