<?
include "template.inc";
require "ezcontact/dbsettings.php";
require "ezphputils.php";

if ( isset( $Login ) )
{
  $message = "Kunne ikke logge på, sjekk brukernavn og passord!";
}
else
{
  $message = "Tast inn et gyldig brukernavn og passord!";
}

$t = new Template( "." );
$t->set_file( array(                    
                    "login_edit" => $DOCUMENTROOT . "templates/login.tpl"
                    ) );


$t->set_var( "login_msg", $message );

$t->set_var( "login", $Login );

$t->set_var( "document_root", $DOCUMENTROOT );

$t->pparse( "output", "login_edit"  );

?>
