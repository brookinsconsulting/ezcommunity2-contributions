<?

/*
  help.php - viser et vindu med hjelp
*/

include_once( "template.inc" );
require "ezlink/dbsettings.php";
include_once( "ezphputils.php" );

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

// Sette template filer
$t = new Template();
$t->set_file( array(
    "help_view" => $DOCUMENTROOT . "templates/help_view.tpl" ));






?>
