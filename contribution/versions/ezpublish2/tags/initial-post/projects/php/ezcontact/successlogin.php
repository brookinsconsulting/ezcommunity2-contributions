<?
include  "template.inc";
require "ezphputils.php";
require "ezsession.php";
require "ezuser.php";

include( "checksession.php" );

$t = new Template( "." );
$t->set_file( array(                    
                    "login_" => "templates/successlogin.tpl"
                    ) );

$t->pparse( "output", "login_"  );

?>
