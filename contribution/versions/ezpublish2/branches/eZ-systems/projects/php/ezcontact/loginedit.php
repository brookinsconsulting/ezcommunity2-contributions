<?
include  "template.inc";
require "ezphputils.php";

if ( isset( $Login ) )
{
  $message = "<h1>Kunne ikke logge på, sjekk brukernavn og passord.</h1>";
}
else
{
  $message = "<h1>Tast inn et gyldig brukernavn og passord.</h1>";
}

$t = new Template( "." );
$t->set_file( array(                    
                    "login_edit" => "templates/login.tpl"
                    ) );


$t->set_var( "login_msg", $message );

$t->set_var( "login", $Login );

$t->pparse( "output", "login_edit"  );

?>
