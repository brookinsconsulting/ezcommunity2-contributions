<?
if ( isset( $BF ) )
{
    print( "blablalbalblala" .  $BF );
}
include  "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";

require $DOCUMENTROOT . "classes/ezsession.php";
require $DOCUMENTROOT . "classes/ezuser.php";

include( $DOCUMENTROOT . "checksession.php" );

$t = new Template( "." );
$t->set_file( array(                    
                    "login_" => $DOCUMENTROOT . "templates/successlogin.tpl"
                    ) );
$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "login_"  );

?>
