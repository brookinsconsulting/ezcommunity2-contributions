<?

/*
  suggestlink.php -> foresl�r en link
*/

include_once( "template.inc" );
require "ezlink/dbsettings.php";
include_once( "ezphputils.php" );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );

if ( $Action == "suggest" )
{
    $newlink = new eZLink();

    if ( ( $title == "" ) || ( $url == "" ) || ( $description == "" ) || ( $keywords == "" ) ) 
    {
        $terror_msg = "Legg til alle feltene..."; 

        $ttitle = $title;
        $turl = $url;
        $tkeywords = $keywords;
        $tdescription = $description;
//      printRedirect( "../index.php?page=" . $DOCUMENTROOT . "suggestlink.php" );
    }
    else
    {
        $newlink->setTitle( $title );
        $newlink->setUrl( $url );
        $newlink->setKeyWords( $keywords );
        $newlink->setDescription( $description );
        $newlink->setLinkGroup( $linkgroup );
        $newlink->setAccepted( "N" );
     
        if ( $newlink->checkUrl( $url ) == 0 )
        {
            $newlink->store();
//          printRedirect( "../index.php?page=" . $DOCUMENTROOT . "linklist.php" );
        }
        else
        {
            $terror_msg = "Linken finnes i databasen...";
            
            $ttitle = $title;
            $turl = $url;
            $tkeywords = $keywords;
            $tdescription = $description;
//          printRedirect( "../index.php?page=" . $DOCUMENTROOT . "suggestlink.php" );
        }
    }
}

$t = new Template();
$t->set_file( array(
    "suggestlink" => $DOCUMENTROOT . "templates/suggestlink.tpl",
    "suggest_group_select" => $DOCUMENTROOT . "templates/suggestgroupselect.tpl"
    ));



$groupselect = new eZLinkGroup();
$grouplink_array = $groupselect->getAll( );

// Selecter
$group_select_dict = "";
for ( $i=0; $i<count( $grouplink_array ); $i++ )
{
    $t->set_var( "grouplink_id", $grouplink_array[ $i ][ "ID" ] );
    $t->set_var( "grouplink_title", $grouplink_array[ $i ][ "Title" ] );

    if ( $grouplink_array[ $i ][ "ID" ] == $LGID )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $group_select_dict[ $grouplink_array[$i][ "ID" ] ] = $i;

    $t->parse( "group_category", "suggest_group_select", true );
}

$t->set_var( "error_msg", $terror_msg );

$t->set_var( "tjo", "001" );

$t->set_var( "title", $ttitle );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription ); 

$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "suggestlink" );


?>
