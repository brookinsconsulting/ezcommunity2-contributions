<?

/*
  suggestlink.php -> foreslår en link
*/

include "template.inc";
require "ezlink/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

$t = new Template();
$t->set_file( array(
    "suggestlink" => $DOCUMENTROOT . "templates/suggestlink.tpl"
    ));

if ( $Action == "suggest" )
{
    $newlink = new eZLink();

    $newlink->setTitle( $title );
    $newlink->setDescription( $description );
    $newlink->setKeyWords( $keywords );
    $newlink->setLinkGroup( $linkgroup );
    $newlink->setAccepted( "N" );
    print ( "tittel: " . $title );
    
   $newlink->store();
}

$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "suggestlink" );


?>
