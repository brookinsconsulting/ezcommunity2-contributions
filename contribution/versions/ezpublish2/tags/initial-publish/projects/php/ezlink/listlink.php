<?

/*
  listlink.php viser alle kategorier
*/

include "template.inc";
require "ezlink/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

// setter template filer
$t = new Template( "." );
$t->set_file( array(
    "linkgroup_list" => $DOCUMENTROOT . "templates/linkgrouplist.tpl",
    "linkgroup_item" => $DOCUMENTROOT . "templates/linkgroupitem.tpl"
    ) );


// Lister alle kategorier
$linkgroup = new eZLinkGroup();

$linkgroup_array = $linkgroup->getByParent( 0 );

print ( "antall grupper " . count( $linkgroup_array ));

for ( $i=0; $i<count( $linkgroup_array ); $i++ )
{
    $t->set_var( "bg_color", "#ffeeff" );

    $t->set_var( "linkgroup_id", $linkgroup_array[ $i ][ "ID" ] );
    $t->set_var( "linkgroup_name", $linkgroup_array[ $i ][ "Name" ] );
    $t->set_var( "linkgroup_parent", $linkgroup_array[ $i ][ "Parent" ] );

    $t->set_var( "document_root", $DOCUMENTROOT );
    
    $t->parse( "group_list", "linkgroup_item", true );
    print ( "ug" );

}


$t->set_var( "document_root", $DOCUMENTROOT );
                       
$t->pparse( "output", "linkgroup_list" );

?>
