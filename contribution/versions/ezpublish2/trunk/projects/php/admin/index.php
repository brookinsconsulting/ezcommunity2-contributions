<?php
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

// Start the buffer cache
ob_start();

// start session handling
session_start();

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


$user =& eZUser::currentUser();
if ( $user )
{
    // html header
    if ( $PrintableVersion == "enabled" )
    {        
        include( "print_header.php" );
    }
    else
    {
        include( "header.php" );
    }
              
    
    require( "ezuser/admin/admincheck.php" );
    
    if ( ! ( $HelpMode == "enabled" ) )
    {
        include_once( "ezsession/classes/ezpreferences.php" );
        $preferences = new eZPreferences();

        $modules =& $preferences->variableArray( "EnabledModules" );
        $site_modules = $ini->read_array( "site", "EnabledModules" );
        if ( $modules )
        {
            $modules = array_intersect( $modules, $site_modules );
            $extra_modules = array_diff( $site_modules, $modules );
            $modules = array_merge( $modules, $extra_modules );
            $modules = array_diff( $modules, array( "" ) );
        }
        else
        {
            $modules = array_diff( $site_modules, array( "" ) );
        }

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
                        $menuStatus =& $preferences->variable( $module_dir . "_status" );

                        if ( $menuStatus == "open" || empty( $menuStatus ) )
                        {
                            $preferences->setVariable( $module_dir . "_status", "closed" );
                        }
                        else
                        {
                            $preferences->setVariable( $module_dir . "_status", "open" );                
                        }

                        $menuStatus =& $preferences->variable( $module_dir . "_status" );
                        $uri = eZHTTPTool::removeVariable( $uri, "ToggleMenu" );
                        eZHTTPTool::header( "Location: $uri" );
                        exit;
                    }
                }
            }

            reset( $modules );
            $i = 0;
            $moved_module = false;
            while( list( $key, $module ) = each( $modules ) )
            {
                $module_low =& strtolower( $module );
                if ( !empty( $module ) )
                {
                    if ( $GLOBALS["MoveUp"] == $module_low )
                    {
                        $pos = $i;
                        if ( $i > 0 )
                        {
                            $pos_above = $i - 1;
                            $module_above = $modules[$pos_above];
                            $modules[$pos_above] = $module;
                            $modules[$pos] = $module_above;
                            $moved_module = true;
                            break;
                        }
                        else
                        {
                            $module_item = array_shift( $modules );
                            $modules = array_merge( $modules, $module_item );
                            $moved_module = true;
                            break;
                        }
                    }
                    else if ( $GLOBALS["MoveDown"] == $module_low )
                    {
                        $pos = $i;
                        if ( $i < count( $modules ) - 1 )
                        {
                            $pos_below = $i + 1;
                            $module_below = $modules[$pos_below];
                            $modules[$pos_below] = $module;
                            $modules[$pos] = $module_below;
                            $moved_module = true;
                            break;
                        }
                        else
                        {
                            $module_item = array_pop( $modules );
                            $modules = array_merge( $module_item, $modules );
                            $moved_module = true;
                            break;
                        }
                    }
                }
                $i++;
            }

            $uri = eZHTTPTool::removeVariable( $uri, "MoveUp" );
            $uri = eZHTTPTool::removeVariable( $uri, "MoveDown" );

            $preferences->setVariable( "EnabledModules", $modules );

            if ( $moved_module )
            {
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
                        "$module_dir/admin/menubox.php" );
                }
            }
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


        if ( $PrintableVersion != "enabled" )
        {
            // break the column an draw a horizontal line
            include( "separator.php" );
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
    { // show the help page
        include( "separator.php" );
        
        include( "help/datasupplier.php" );
    }

    // html footer
    if ( $PrintableVersion == "enabled" )
    {
        include( "print_footer.php" );
    }
    else
    {
        include( "footer.php" );
    }
    
    
}
else
{
    include( "loginheader.php" );
    
    if ( $moduleName == "" )
        $moduleName = "user";

    $LoginSeparator = true;

//      include( "separator.php" );
    
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

    // html footer
    include( "loginfooter.php" );

}


// close the database connection.
eZDB::close();

// flush the buffer cache
ob_end_flush();
?>

