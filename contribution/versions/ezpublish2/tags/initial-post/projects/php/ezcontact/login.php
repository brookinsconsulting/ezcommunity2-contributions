<?
include  "template.inc";
require "ezphputils.php";
require "ezuser.php";
require "ezsession.php";

$message = "";
$message = "<h1>Tast inn et gyldig brukernavn og passord</h1>";

if ( $TryLogin == "true" )
{
  $usr = new eZUser( $Login, $Pwd );
  if ( $usr->validate() == 1 )
  {
    $hash = md5( time() );
    $session = new eZSession();

    $session->setHash( $hash );
    $session->setUserID( $usr->id() );

    $session->store();
    
    setcookie ( "AuthenticatedSession", $hash, time()+3600 );

    // redirect.. 
    print "<html><head>";
    $url = "index.php?page=successlogin.php";
    print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
    print "<link rel=\"stylesheet\" href=\"ez.css\">";
    print "</head><body bgcolor=#000000></body></html>";     
  }
  else
  {
    // redirect.. 
    print "<html><head>";
    $url = "index.php?page=loginedit.php&Login=$Login";
    print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
    print "<link rel=\"stylesheet\" href=\"ez.css\">";
    print "</head><body bgcolor=#000000></body></html>";    
  }
}
else
{
    print "<html><head>";
    $url = "index.php?page=loginedit.php&Login=$Login";
    print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
    print "<link rel=\"stylesheet\" href=\"ez.css\">";
    print "</head><body bgcolor=#000000></body></html>";    
  
}

?>
