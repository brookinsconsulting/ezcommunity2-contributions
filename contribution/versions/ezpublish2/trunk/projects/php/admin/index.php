<?php

ob_start();

include_once( "../classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );
include_once( "../common/ezphputils.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

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

$user = eZUser::currentUser();
if ( $user )
{
//      if ( $ini->read_var( "site", "eZPublish" ) == "enabled" )
//          include( "ezpublishadmin.php" );

    if ( $ini->read_var( "site", "eZForum" ) == "enabled" )
        include( "ezforum/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZLink" ) == "enabled" )
        include( "ezlink/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZContact" ) == "enabled" )
        include( "ezcontact/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZTodo" ) == "enabled" )
        include( "eztodo/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZTrade" ) == "enabled" )
        include( "eztrade/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZPoll" ) == "enabled" )
        include( "ezpoll/admin/menubox.php" );

    if ( $ini->read_var( "site", "eZUser" ) == "enabled" )
        include( "ezuser/admin/menubox.php" );

    include( "ezuser/admin/userbox.php" );

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

    if ( file_exists( $page ) )
    {
        include( $page );
    }
//      else
//      {
//          include( "error.php" );
//      }
}
else
{
    include( "separator.php" );
    include( "ezuser/admin/login.php" );

    // parse the URI
    $page = "";
    
    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs) ;

    $REQUEST_URI = $regs[1];
    
    $url_array = explode( "/", $REQUEST_URI );
    
    // send the URI to the right decoder
    $page = "ezuser/admin/datasupplier.php";

    if ( file_exists( $page ) )
    {
        include( $page );
    }
}

// html footer
include( "footer.php" );

?>

<?php
ob_end_flush();
?>
