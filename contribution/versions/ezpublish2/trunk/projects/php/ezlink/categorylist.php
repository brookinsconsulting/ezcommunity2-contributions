<?

/*
  listlink.php viser alle kategorier
*/

include_once( "template.inc" );
require "ezlink/dbsettings.php";
include_once( "ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

// setter template filer
$t = new Template( "." );
$t->set_file( array(
    "linkgroup_list" => $DOCUMENTROOT . "templates/linkgrouplistshort.tpl",
    "linkgroup_item" => $DOCUMENTROOT . "templates/linkgroupitemshort.tpl",
    ) );

// Lister alle kategorier
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LGID );

// $linkGroup->printPath( $LGID, $DOCUMENTROOT . "linklist.php" );

$linkGroup_array = $linkGroup->getByParent( 0 );


if ( count( $linkGroup_array ) == 0 )
{
    $t->set_var( "group_list", "" );

}
else
{
// print( "antall grupper " . count( $linkGroup_array )); 
    for ( $i=0; $i<count( $linkGroup_array ); $i++ )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "bg_color", "#FFFFFF" );
        }
        else
        {
            $t->set_var( "bg_color", "#FFFFFF" );
        }  

        $link_group_id = $linkGroup_array[ $i ][ "ID" ];
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $linkGroup_array[ $i ][ "Title" ] );
        $t->set_var( "linkgroup_parent", $linkGroup_array[ $i ][ "Parent" ] );

        $total_sub_links = $linkGroup->getTotalSubLinks( $link_group_id, $link_group_id );
        $new_sub_links = $linkGroup->getNewSubLinks( $link_group_id, $link_group_id, 1 );
        
        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
        
        $t->set_var( "document_root", $DOCUMENTROOT );
        $t->parse( "group_list", "linkgroup_item", true );

    }
}



$t->set_var( "linkgroup_id", $LGID );
$t->set_var( "document_root", $DOCUMENTROOT );
                       
$t->pparse( "output", "linkgroup_list" );

?>
