<?

/*
  help.php - viser et vindu med hjelp
*/

include "template.inc";
require "ezlink/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezlinkgroup.php";
require $DOCUMENTROOT . "classes/ezlink.php";
require $DOCUMENTROOT . "classes/ezhit.php";

// Sette template filer
$t = new Template();
$t->set_file( array(
    "help_view" => $DOCUMENTROOT . "templates/help_view.tpl" ));






?>
