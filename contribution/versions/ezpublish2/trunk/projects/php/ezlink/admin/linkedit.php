<?

/*
  linkedit.php 
*/

include "template.inc";
require "ezlink/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

// Oppdatere
if ( $Action == "update" )
{
    $updatelink = new eZLink();

    $updatelink->get( $LID );

    $updatelink->setTitle( $title );
    $updatelink->setDescription( $description );
    $updatelink->setLinkGroup( $linkgroup );
    $updatelink->setKeyWords( $keywords );
    $updatelink->setAccepted( $accepted );
    $updatelink->setUrl( $url );
    
    $updatelink->update();
}

// Slette link
if ( $Action == "delete" )
{
    $deletelink = new eZLink();
    $deletelink->get( $LID );
    $deletelink->delete();

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "admin/linklist.php" );
}

// Legge til link
if ( $Action == "insert" )
{
    $newlink = new eZLink();

    $newlink->setTitle( $title );
    $newlink->setDescription( $description );
    $newlink->setLinkGroup( $linkgroup );
    $newlink->setKeyWords( $keywords );
    $newlink->setAccepted( $accepted );
    $newlink->setUrl( $url );

    $ttile = "";
    $turl = "";
    $tkeywords = "";
    $tdescription = "";
    
    $message = "Legg til ny link";
    $submit = "Legg til";
    print ( "akseptert: " . $accepted );
    $newlink->store();

    printRedirect( "../index.php?page=" . $DOCUMENTROOT . "admin/linklist.php" );
}

// Sette template filer
$t = new Template();
$t->set_file( array(
    "link_edit" => $DOCUMENTROOT . "templates/linkedit.tpl",
    "link_group_select" => $DOCUMENTROOT . "templates/linkgroupselect.tpl" ));


$linkselect = new eZLinkGroup();
$link_array = $linkselect->getAll();

// Template variabler
$message = "Legg til link";
$submit = "Legg til";
$action = "insert";


// editere

if ( $Action == "edit" )
{

    $editlink = new eZLink();
    $editlink->get( $LID );

    $title = $editlink->Title;

    $LGID = $editlink->linkGroup();

    $title = $editlink->title();
    $description = $editlink->description();
    $linkgroup = $editlink->linkGroup();
    $keywords = $editlink->keyWords();
    $accepted = $editlink->accepted();
    $url = $editlink->url();

    $action = "update";
    $message = "Rediger link";
    $submit = "Rediger";
              
    $ttile = $editlink->title();
    $tdescription = $editlink->description();
    $tkeywords = $editlink->keywords();
    $turl = $editlink->url();

    if ( $editlink->accepted() == "Y" )
    {
        $yes_selected = "selected";
        $no_selected = "";

    }
    else
    {
        $yes_selected = "";
        $no_selected = "selected";
    }

}
    
// Selector
$link_select_dict = "";

for ( $i=0; $i<count( $link_array ); $i++ )
{
    $t->set_var("link_id", $link_array[ $i ][ "ID" ] );
    $t->set_var("link_title", $link_array[ $i ][ "Title" ] );

    if ( $LGID == $link_array[ $i ][ "ID" ] )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    $link_select_dict[ $link_array[ $i ][ "ID" ] ] = $i;

    $t->parse( "group_category", "link_group_select", true );
}


$t->set_var( "yes_selected", $yes_selected );
$t->set_var( "no_selected", $no_selected );

$t->set_var( "submit_text", $submit );
$t->set_var( "action_value", $action );
$t->set_var( "message", $message );


$t->set_var( "title", $ttile );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription );
// $t->set_var( "accepted", $taccepted );


$t->set_var( "document_root", $DOCUMENTROOT );

$t->set_var( "link_id", $LID );
$t->pparse( "output", "link_edit" );

?>
