<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

// Start the buffer cache
ob_start();

$UsePHPSessions = false;

if ( $UsePHPSessions == true )
{
    // start session handling
    session_start();
}

// settings for sessions
// max timeout is set to 48 hours
ini_alter("session.gc_maxlifetime", "172800");
ini_alter("session.entropy_file","/dev/urandom"); 
ini_alter("session.entropy_length", "512");  

ini_alter("session.cache_expire", "172800");

include_once( "classes/ezdb.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/template.inc" );
include_once( "classes/ezmenubox.php" );

include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezmodule.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "ezmodule/classes/ezmodulehandler.php" );

include_once( "classes/ezhttptool.php" );

$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;

//  $session =& eZSession::globalSession();
//  $session->fetch();
//  print( "<pre>" . $session->hash() . "</pre>" );

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

$SiteStyle =& $ini->read_var( "site", "SiteStyle" );

$GLOBALS["DEBUG"] = true;

// Remove url parameters
ereg( "([^?]+)", $REQUEST_URI, $regs ) ;

$REQUEST_URI = $regs[1];

$url_array =& explode( "/", $REQUEST_URI );


$user =& eZUser::currentUser();
if ( $user )
{
    if ( $url_array[1] == "help" )
    {
        $HelpMode  = "enabled";
        
        include( "admin/help_header.php" );
    }
    else
    {
        // html header
        if ( $PrintableVersion == "enabled" )
        {        
            include( "admin/print_header.php" );
        }
        else
        {
            include( "admin/header.php" );
        }
    }
              
    
    require( "ezuser/admin/admincheck.php" );
    
    if ( !( $HelpMode == "enabled" ) )
    {
        include_once( "ezsession/classes/ezpreferences.php" );
        $preferences = new eZPreferences();

        $site_modules = $ini->read_array( "site", "EnabledModules" );
        $modules =& eZModuleHandler::active();

        $uri =& $GLOBALS["REQUEST_URI"];

        if ( $PrintableVersion != "enabled" )
        {
            if ( !empty( $GLOBALS["ToggleMenu"] ) )
            {
                foreach( $modules as $module )
                {
                    $module_dir = strtolower( $module );
                    if ( $GLOBALS["ToggleMenu"] == $module_dir )
                    {
                        eZModuleHandler::toggle( $module_dir );
                        $uri = eZHTTPTool::removeVariable( $uri, "ToggleMenu" );
                        eZHTTPTool::header( "Location: $uri" );
                        exit;
                    }
                }
            }

            $moved_module = false;
            eZModuleHandler::moveUp( $modules, $GLOBALS["MoveUp"], $moved_module );
            if ( !$moved_module )
            {
                eZModuleHandler::moveDown( $modules, $GLOBALS["MoveDown"], $moved_module );
            }

            $uri = eZHTTPTool::removeVariable( $uri, "MoveUp" );
            $uri = eZHTTPTool::removeVariable( $uri, "MoveDown" );

            if ( $moved_module )
            {
                $preferences->setVariable( "EnabledModules", $modules );
                eZHTTPTool::header( "Location: $uri" );
                exit;
            }

            // draw modules
            foreach ( $modules as $module )
            {
                if ( !empty( $module ) )
                {
                    $module_dir =& strtolower( $module );
                    unset( $menuItems );
                    include( "$module_dir/admin/menubox.php" );
                    if ( isset( $menuItems ) )
                        eZMenuBox::createBox( $module, $module_dir, "admin",
                        $SiteStyle, $menuItems, true, false,
                        "$module_dir/admin/menubox.php", false, true );
                }
            }
        }

        // parse the URI
        $page = "";
    
    
        // send the URI to the right decoder
        $page = "ez" . $url_array[1] . "/admin/datasupplier.php";

        // set the module logo
        $moduleName =& $url_array[1];

        if ( $moduleName == "" )
            $moduleName = "user";


        if ( $PrintableVersion != "enabled" )
        {
            // break the column an draw a horizontal line
            include( "admin/separator.php" );
        }

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
    {
        // show the help page

        $helpFile = "ez" . $url_array[2] . "/admin/help/". $Language . "/" . $url_array[3] . "_" . $url_array[4] . ".hlp";

        if ( file_exists( $helpFile ) )
        {
            include( $helpFile );
        }
        else
        {
            print( "help file not found" );

        }
    }

    if ( $HelpMode == "enabled" )
    {
        include( "admin/help_footer.php" );
    }
    else
    {
        // html footer
        if ( $PrintableVersion == "enabled" )
        {
            include( "admin/print_footer.php" );
        }
        else
        {
            include( "admin/footer.php" );
        }
    }    
}
else
{
    include( "admin/loginheader.php" );
    
    if ( $moduleName == "" )
        $moduleName = "user";

    $LoginSeparator = true;

    if ( $REQUEST_URI == "/" )
    {
        $REQUEST_URI = "/user/login";
        $url_array =& explode( "/", $REQUEST_URI );
    }

    // parse the URI
    $page = "";

    // send the URI to the right decoder
    $page = "ezuser/admin/datasupplier.php";

    if ( file_exists( $page ) )
    {
        include( $page );
    }

    // html footer
    include( "admin/loginfooter.php" );
}


// close the database connection.
eZDB::close();

// flush the buffer cache
ob_end_flush();
?>

