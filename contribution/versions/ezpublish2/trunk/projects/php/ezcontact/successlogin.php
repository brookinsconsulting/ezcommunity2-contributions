<?
include  "template.inc";

require "ezcontact/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";

$inc = $DOCUMENTROOT . "checksession.php";
include( $inc  );

$t = new Template( "." );
$t->set_file( array(                    
                    "login_" => $DOCUMENTROOT . "templates/successlogin.tpl"
                    ) );
$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "login_"  );

?>
