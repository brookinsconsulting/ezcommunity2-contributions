<?php

ob_start();

include_once( "../classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );
include_once( "../classes/ezuser.php" );

include_once( "../classes/ezsession.php" );
include_once( "../common/ezphputils.php" );

$session = new eZSession();

$ini = new INIFile( "../site.ini" );
$t = new Template( "." );

$SiteStyle = $ini->read_var( "site", "SiteStyle");
switch ( $SiteStyle )
{
    case "eztrade" :
        $SiteBackground = "#000064";    
        break;
    case "ezpublish" :
        $SiteBackground = "#640000";
        break;            
    case "ezintranet" :
        $SiteBackground = "#004b00";
        break;        
}

// html header
include( "header.php" );

if ( $session->get( $AuthenticatedSession ) == 0 )
{

    if ( $ini->read_var( "site", "eZPublish" ) == "enabled" )
        include( "ezpublishadmin.php" );

    if ( $ini->read_var( "site", "eZForum" ) == "enabled" )
        include( "ezforum/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZLink" ) == "enabled" )
        include( "ezlink/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZContact" ) == "enabled" )
        include( "ezcontactadmin.php" );

    if ( $ini->read_var( "site", "eZTodo" ) == "enabled" )
        include( "eztodoadmin.php" );

    if ( $ini->read_var( "site", "eZTrade" ) == "enabled" )
        include( "eztrade/admin/menubox.php" );

    include( "useradmin.php" );


    // break the column an draw a horizontal line
    include( "separator.php" );
    
    
    // parse the URI
    $page = "";
    
    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs) ;

    $REQUEST_URI = $regs[1];
    
    $url_array = explode( "/", $REQUEST_URI );

    
    // send the URI to the right decoder
    $page = "ez" . $url_array[1] . "/admin/datasupplier.php";

    // handle users
    if ( $url_array[1] == "user" )
    {
        if ( $url_array[2] == "logout" )
        {
            $page = "logout.php";
        }
        else
        {
            $page = "userlist.php";
        }
    }

    if ( file_exists( $page ) )
    {
        include( $page );
    }
    else
    {
        // Load the default page
        include( "main.php" );
    }        
}
else
{
    include( "separator.php" );
    
    $t->set_file( "login", "./templates/login.tpl" );
    
    if( !isset( $message ) )
    {
        $message = "Skriv inn brukernavn og passord!";
    }
    
    $t->set_var( "text", $message );
    $t->pparse( "output", "login" );
}

// html footer
include( "footer.php" );


?>

<?php

ob_end_flush();


?>
