<?php

header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

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
        if ( $ini->read_var( "site", "eZArticle" ) == "enabled" )
            include( "ezarticle/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZNewsFeed" ) == "enabled" )
              include( "eznewsfeed/admin/menubox.php" );
        
        if ( $ini->read_var( "site", "eZForum" ) == "enabled" )
            include( "ezforum/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZLink" ) == "enabled" )
            include( "ezlink/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZTodo" ) == "enabled" )
            include( "eztodo/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZTrade" ) == "enabled" )
            include( "eztrade/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZPoll" ) == "enabled" )
            include( "ezpoll/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZBug" ) == "enabled" )
            include( "ezbug/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZAd" ) == "enabled" )
            include( "ezad/admin/menubox.php" );
        
        if ( $ini->read_var( "site", "eZContact" ) == "enabled" )
            include( "ezcontact/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZCV" ) == "enabled" )
            include( "ezcv/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZClassified" ) == "enabled" )
            include( "ezclassified/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZSite" ) == "enabled" )
            include( "ezsite/admin/menubox.php" );

        if ( $ini->read_var( "site", "eZUser" ) == "enabled" )
            include( "ezuser/admin/menubox.php" );
        
    // parse the URI
        $page = "";
    
        // Remove url parameters
        ereg( "([^?]+)", $REQUEST_URI, $regs) ;

        $REQUEST_URI = $regs[1];
    
        $url_array = explode( "/", $REQUEST_URI );
    
        // send the URI to the right decoder
        $page = "ez" . $url_array[1] . "/admin/datasupplier.php";


        // set the module logo
        switch ( $url_array[1] )
        {
            case "article" :
            {
                $ModuleLogo = "menu-news.gif";
            }
            break;

            case "link" :
            {
                $ModuleLogo = "menu-link.gif";
            }
            break;

            case "trade" :
            {
                $ModuleLogo = "menu-trade.gif";
            }
            break;

            case "poll" :
            {
                $ModuleLogo = "menu-poll.gif";
            }
            break;

            case "user" :
            {
                $ModuleLogo = "menu-user.gif";
            }
            break;
        
            case "forum" :
            {
                $ModuleLogo = "menu-forum.gif";
            }
            break;

            default :
            {
                $ModuleLogo = "menu-news.gif";
            }
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

ob_end_flush();
?>

