<?php
//  header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
//  header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
//  header("Cache-Control: no-cache, must-revalidate"); 
//  header("Pragma: no-cache");

// start the buffer cache
ob_start();

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

//  $session =& eZSession::globalSession();
//  $session->fetch();
//  print( $session->hash() );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

$t = new Template( "." );

$SiteStyle = $ini->read_var( "site", "SiteStyle" );
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

    require( "ezuser/admin/admincheck.php" );

    
    if ( ! ( $HelpMode == "enabled" ) )
    {
        $modules = $ini->read_array( "site", "EnabledModules" );

        foreach ( $modules as $module )
        {
            $module_dir =& strtolower( $module ); 
            include( "$module_dir/admin/menubox.php" );
        }
        
        // parse the URI
        $page = "";
    
        // Remove url parameters
        ereg( "([^?]+)", $REQUEST_URI, $regs) ;

        $REQUEST_URI = $regs[1];
    
        $url_array = explode( "/", $REQUEST_URI );
    
        // send the URI to the right decoder
        $page = "ez" . $url_array[1] . "/admin/datasupplier.php";

        // set the module logo
        $moduleName =& $url_array[1];
        
        if( file_exists( "menu-" .$moduleName . ".gif" ) )
        {
            $ModuleLogo = "menu-" .$moduleName . ".gif";
        }
        else
        {
            $ModuleLogo = "menu-news.gif";
        }
    
        // break the column an draw a horizontal line
        include( "separator.php" );

        if ( file_exists( $page ) )
        {
            include( $page );
        }
        else
        {
            include( "ezuser/admin/welcome.php" );
        }
    }
    else
    { // show the help page

        $ModuleLogo = "menu-user.gif";
        
        include( "separator.php" );

        include( "help/datasupplier.php" );
    }
}
else
{
    $ModuleLogo = "menu-user.gif";
    $LoginSeparator = true;

    include( "separator.php" );
    
    // parse the URI
    $page = "";

    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs );

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

// flush the buffer cache
ob_end_flush();
?>

