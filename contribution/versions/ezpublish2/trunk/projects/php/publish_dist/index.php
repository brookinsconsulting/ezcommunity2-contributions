<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

$GLOBALS["DEBUG"] = true;


// Turn on output buffering
ob_start();
// start session handling
session_start();

include_once( "classes/INIFile.php" );
include_once( "classes/ezdb.php" );
$ini = new INIFile( "site.ini" );
$GlobalSiteIni =& $ini;

// set character set
include_once( "classes/ezlocale.php" );
$Language = $ini->read_var( "eZCalendarMain", "Language" );
$Locale = new eZLocale( $Language );
$iso = $Locale->languageISO();
if ( $iso != false )
    header( "Content-type: text/html;charset=$iso" );

// Design
include_once( "ezsession/classes/ezsession.php" );

$session =& eZSession::globalSession();

unset( $siteDesign );
if ( $session->fetch() == false )
{
    $siteDesign =& $ini->read_var( "site", "SiteDesign" );
}
else
{
    $siteDesign =& $session->variable( "SiteDesign" );

    if ( $siteDesign == "" )
    {
        $siteDesign =& $ini->read_var( "site", "SiteDesign" );
    }
}

// do the statistics
include_once( "ezstats/classes/ezpageview.php" );

// create a global page view object for statistics
$GlobalPageView = new eZPageView();
$GlobalPageView->store();


// parse the URI
$meta_page = "";
$content_page = "";

// Check if userlogin is required
$user = eZUser::currentUser();

$requireUserLogin =& $ini->read_var( "eZUserMain", "RequireUserLogin" );

if ( ( $requireUserLogin == "disabled" ) ||
    ( ( $requireUserLogin == "enabled" )   & ( get_class( $user ) == "ezuser" ) && ( $user->id() != 0 ) ) ) 
{

// Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs );

    $REQUEST_URI = $regs[1];

    $url_array = explode( "/", $REQUEST_URI );
    $meta_page = "ez" . $url_array[1] . "/metasupplier.php";

// include some html
    $Title = $ini->read_var( "site", "SiteTitle" );
    include( "sitedesign/$siteDesign/preamble.php" );

// check if there is specific meta info, if not include the default
    if ( file_exists( $meta_page ) )
    {
        include( $meta_page );
    }
    else
    {
        // Load the default meta info
        include( "sitedesign/$siteDesign/defaultmetainfo.php" );
    }

// Pre check
    {
        // send the URI to the right decoder
        $content_page_pre = "ez" . $url_array[1] . "/user/datasupplier_pre.php";
    }

    if ( file_exists( $content_page_pre ) )
    {
        // the page with the real contents
        include( $content_page_pre );
    }

// include more html
    if ( $PrintableVersion == "enabled" )
    {
        include( "sitedesign/$siteDesign/simpleheader.php" );
    }
    else
    {
        include( "sitedesign/$siteDesign/header.php" );    
    }

// Main contents
    {
        // send the URI to the right decoder
        $content_page = "ez" . $url_array[1] . "/user/datasupplier.php";
    }
    if ( file_exists( $content_page ) )
    {
        // the page with the real contents
        include( $content_page );
    }
    else
    {
        // the default page to load
        if ( $ini->read_var( "site", "DefaultPage" ) != "disabled" )
        {
            include( $ini->read_var( "site", "DefaultPage" ) );
        }
        else
        {
            $CategoryID = 0;
            include( "ezarticle/user/articlelist.php" );
        }
    }

// and the html finish
// include more html
    if ( $PrintableVersion == "enabled" )
    {
        include( "sitedesign/$siteDesign/simplefooter.php" );
    }
    else
    {
        include( "sitedesign/$siteDesign/footer.php" );
    }

}
else
{
    include( "sitedesign/$siteDesign/preamble.php" );
    include( "sitedesign/$siteDesign/loginheader.php" );

    // parse the URI
    $page = "";

    // Remove url parameters
    ereg( "([^?]+)", $REQUEST_URI, $regs );

    $REQUEST_URI = $regs[1];

    $url_array = explode( "/", $REQUEST_URI );

    // send the URI to the right decoder
    $page = "ezuser/user/datasupplier.php";
    if ( file_exists( $page ) )
    {
        include( $page );
    }
    include( "sitedesign/$siteDesign/loginfooter.php" );
}

// close the database connection.
eZDB::close();

ob_end_flush();
?>
