<?
include  "template.inc";
require "ezphputils.php";
require "ezcontact_ce/dbsettings.php";

require "classes/ezuser.php";
require "classes/ezsession.php";

$message = "<h1>Tast inn et gyldig brukernavn og passord</h1>";

if ( $TryLogin == "true" )
{
    $usr = new eZUser(  );
    $usr->setLogin( $Login );
    $usr->setPassword( $Pwd );
    if ( $usr->validate() == 1 )
    {
        $session = new eZSession();
        $session->setUserID( $usr->id() );
        
        $session->store();

//        setcookie ( "AuthenticatedSession", $hash, time() + 3600, "/",  $DOMAIN, 0 ) or die( "Feil: kunne ikke sette cookie." );        
        
        // redirect..
        print "<html><head>";
//        $url = "login.php";
        $url = "/index.php?page=" . $DOCUMENTROOT . "contactlist.php";
        print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
        print "<link rel=\"stylesheet\" href=\"ez.css\">";
        print "</head><body bgcolor=#000000></body></html>";   
    }
    else
    {
        // redirect.. 
        print "<html><head>";
        $url = "/index.php?page=" . $DOCUMENTROOT . "loginedit.php&Login=$Login";
        print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
        print "<link rel=\"stylesheet\" href=\"ez.css\">";
        print "</head><body bgcolor=#000000></body></html>";    
    }
}

else
{
    print "<html><head>";
    $url = "/index.php?page=" . $DOCUMENTROOT . "loginedit.php&Login=$Login";
    print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
    print "<link rel=\"stylesheet\" href=\"ez.css\">";
    print "</head><body bgcolor=#000000></body></html>";      
}

?>
