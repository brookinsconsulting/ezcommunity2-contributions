<?

include  "template.inc";
require "ezphputils.php";
require "ezcontact/dbsettings.php";

require "classes/ezuser.php";
require "classes/ezsession.php";

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

        setcookie ( "AuthenticatedSession", $hash, time() + 3600, "/", "devel.ez.no" ) or die( "Feil: kunne ikke sette cookie." );        
        
        print( $hash );

        // redirect..
        print "<html><head>";
        $url = "../index.php?page=new/successlogin.php";
        print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
        print "<link rel=\"stylesheet\" href=\"ez.css\">";
        print "</head><body bgcolor=#000000></body></html>";   
    }
    else
    {
        // redirect.. 
        print "<html><head>";
        $url = "../index.php?page=new/loginedit.php&Login=$Login";
        print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
        print "<link rel=\"stylesheet\" href=\"ez.css\">";
        print "</head><body bgcolor=#000000></body></html>";    
    }
}

else
{
    print "<html><head>";
    $url = "../index.php?page=new/loginedit.php&Login=$Login";
    print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
    print "<link rel=\"stylesheet\" href=\"ez.css\">";
    print "</head><body bgcolor=#000000></body></html>";      
}

?>
