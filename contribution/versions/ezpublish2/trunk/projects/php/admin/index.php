<?php
// header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
// header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
// header("Cache-Control: no-cache, must-revalidate"); 
// header("Pragma: no-cache");

// Start the buffer cache
ob_start();

// start session handling
session_start();

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );
include_once( "classes/ezmenubox.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );

include_once( "classes/ezhttptool.php" );

$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

//  $session =& eZSession::globalSession();
//  $session->fetch();
//  print( "<pre>" . $session->hash() . "</pre>" );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

$SiteStyle =& $ini->read_var( "site", "SiteStyle" );

$GLOBALS["DEBUG"] = true;

// html header
include( "header.php" );

$user =& eZUser::currentUser();
if ( $user )
{
    require( "ezuser/admin/admincheck.php" );
    
    if ( ! ( $HelpMode == "enabled" ) )
    {
        $modules = $ini->read_array( "site", "EnabledModules" );

        foreach ( $modules as $module )
        {
            $module_dir =& strtolower( $module );
            unset( $menuItems );
            include( "$module_dir/admin/menubox.php" );
            if ( isset( $menuItems ) )
                eZMenuBox::createBox( $module, $module_dir, "admin", $SiteStyle, $menuItems );
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

        if ( $moduleName == "" )
            $moduleName = "user";
    
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
        include( "separator.php" );
        
        include( "help/datasupplier.php" );
    }
}
else
{
    if ( $moduleName == "" )
        $moduleName = "user";

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

