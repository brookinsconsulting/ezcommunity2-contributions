<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

include_once( "classes/ezbenchmark.php" );

// Run benchmark test.
// $bench = new eZBenchmark();
// $bench->start();
  
$GLOBALS["DEBUG"] = true;

// Check for bots and disable cookieless Session if they show up

$UsePHPSessions = 2;
$BotIPArray = array( "193.7.255.130", "194.231.30", "199.172.149", "216.239.46", "209.202.148.12", "66.35.208.60", 
                     "198.3.103", "216.35.116", "216.35.103", "217.13.201.25", "209.73.162.191", "18.29.1.50",
                     "212.185.44.12", "216.239.46" );
		     
$checkIP3 =  explode ( ".", $GLOBALS["REMOTE_ADDR"] );
$checkIP3 =  $checkIP3[0].".".$checkIP3[1].".".$checkIP3[2];

foreach( $BotIPArray as $botIP )
{
    $checkLength =  explode ( ".", $botIP );
    
    if ( ( count ( $checkLength ) == 3 ) AND ( $UsePHPSessions != 0 ) )
    {
        $botIP    =  $checkLength[0].".".$checkLength[1].".".$checkLength[2];
        $checkIP3 == $botIP ? $UsePHPSessions = 0 : $UsePHPSessions = 1;
    }
    elseif ( ( count ( $checkLength ) > 3 ) AND ( $UsePHPSessions != 0 ) )
    {
        $GLOBALS["REMOTE_ADDR"] == $botIP ? $UsePHPSessions = 0 : $UsePHPSessions = 1;
    }
}
								    
// END
								    

ob_start();
// Turn on output buffering with gz compression
//ob_start("ob_gzhandler");
//ob_start("ob_gzhandler");

if ( $UsePHPSessions == true )
{
//    // start session handling
//    session_start();
}

// settings for sessions
// max timeout is set to 48 hours
ini_alter("session.gc_maxlifetime", "172800");
ini_alter("session.entropy_file","/dev/urandom"); 
ini_alter("session.entropy_length", "512");

include_once( "classes/INIFile.php" );
include_once( "classes/ezdb.php" );
include_once( "classes/ezhttptool.php" );
$ini =& INIFile::globalINI();
$GlobalSiteIni =& $ini;


// set character set
include_once( "classes/ezlocale.php" );
// $Language = $ini->read_var( "eZCalendarMain", "Language" );
$Locale = new eZLocale( $Language );
$iso = $Locale->languageISO();
if ( $iso != false )
    header( "Content-type: text/html;charset=$iso" );

// Design
include_once( "ezsession/classes/ezsession.php" );
include_once( "ezuser/classes/ezuser.php" );


$session =& eZSession::globalSession();

//
// the section setting code below is obsolete and will
// be removed in 2.1 final
unset( $siteDesign );
unset( $GlobalSiteDesign );
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
// Store the site design in a global variable
$GlobalSiteDesign = $siteDesign;


$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // do the statistics
    include_once( "ezstats/classes/ezpageview.php" );

    // create a global page view object for statistics
    // and store the stats
    $GlobalPageView = new eZPageView();
    $GlobalPageView->store();
}

// parse the URI
$meta_page = "";
$content_page = "";

// Check if userlogin is required
$user =& eZUser::currentUser();

$requireUserLogin =& $ini->read_var( "eZUserMain", "RequireUserLogin" );

// Cookie auto login.
if ( isSet( $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] ) and $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] != false )
{
    if ( ( !$user ) && ( $ini->read_var( "eZUserMain", "AutoCookieLogin" ) == "enabled" ) )
    {
        eZUser::autoCookieLogin( $HTTP_COOKIE_VARS["eZUser_AutoCookieLogin"] );
    }
}

$url_array = explode( "/", $REQUEST_URI );

if ( ( $requireUserLogin == "disabled" ) ||
    ( ( $requireUserLogin == "enabled" )   & ( get_class( $user ) == "ezuser" ) && ( $user->id() != 0 ) ) ) 
{

    // do url translation if needed
    $URLTranslationKeyword = $ini->read_var( "site", "URLTranslationKeyword" );

    $urlTranslatorArray = explode( ";", $URLTranslationKeyword );
    
    if ( in_array(  $url_array[1], $urlTranslatorArray ) )
    {
        include_once( "ezurltranslator/classes/ezurltranslator.php" );
        $REQUEST_URI = eZURLTranslator::translate( $REQUEST_URI );
        $url_array = explode( "/", $REQUEST_URI );
    }

    // if uri == / show default page or article list
    if ( $REQUEST_URI == "/" )
    {
        if ( $ini->read_var( "site", "DefaultPage" ) == "disabled" )
        {
            $REQUEST_URI = "/article/archive/0/";
        }
        if ( $user )
        {
            $mainGroup = $user->groupDefinition( true );
            if ( ( $mainGroup ) && $mainGroup->groupURL() )
            {
                $REQUEST_URI = $mainGroup->groupURL();
            }
        }
        $url_array = explode( "/", $REQUEST_URI );
    }
    
    // Load the main contents and store in a variable
    $content_page = "ez" . $url_array[1] . "/user/datasupplier.php";

    // site cache check
    $SiteCacheFile = "classes/cache/" . md5( $REQUEST_URI ) . ".php";
    $SiteCache = $ini->read_var( "site", "SiteCache" );

    if ( $REQUEST_METHOD == "POST" ||
         $url_array[1] == "forum" ||
         $url_array[1] == "user" ||
         $url_array[1] == "error" ||
         $url_array[1] == "poll" )
    {
        $SiteCache = "disabled";
    }

    // check to use site cache
    if ( ( $SiteCache == "enabled" ) and !eZFile::file_exists( $SiteCacheFile ) )
    {
        $StoreSiteCache = true;
    }
    else
    {
        $StoreSiteCache = false;

        if ( $SiteCache == "enabled" and eZFile::file_exists( $SiteCacheFile ) )
        {
            $timeout = $ini->read_var( "site", "SiteCacheTimeout" );
            $SiteCacheTime = eZFile::filemtime( $SiteCacheFile );
            if ( ( time() - $SiteCacheTime ) < ( $timeout*60 ) )
            {
             // print( "valid cache" );
            }
            else
            {
                $StoreSiteCache = true;

                // delete cache file
                eZFile::unlink( $SiteCacheFile );
                //  print( "time out-clearing cache" );
            }
        }        
    }
    
    if ( $StoreSiteCache || $SiteCache == "disabled" )
    {
        $buffer =& ob_get_contents();
        ob_end_clean();
        ob_start();
        
        // fetch the module printout
        if ( eZFile::file_exists( $content_page ) )
        {
            // the page with the real contents
            include( $content_page );
            // The following variables can be set from the contents page:
            // $PrintableVersion = "enabled | disabled";
            // $GlobalSectionID = integer value, reference to the selected section.
            // $SiteTitleAppend = string which will be appended to the site title
            // $SiteDescriptionOverride = string which will override the meta content information
        }
        elseif ( $ini->read_var( "site", "DefaultPage" ) != "disabled" )
        {
	    if ( $url_array[1] == "sitemap" )
	    {
	        include("sitedesign/mygold/sitemap.php");
	    }
	    elseif ( $url_array[1] == "feedback" )
	    {
	        if ( $url_array[2] == "sendmail" )
	        {
	            include("feedback/sendmail.php");
	        }
	        else
	        {
	            include("feedback/feedback.php");
	        }
	    }
	    elseif ( $url_array[1] == "callback" )
	    {
	        if ( $url_array[2] == "sendmail" )
	        {
	            include("callback/sendmail.php");
	        }
	        else
	        {
	            include("callback/callback.php");
	        }
	    }
	    elseif ( $url_array[1] == "ringgroesse" )
	    {
        	include("ringgroesse/ringgroesse.php");
	    }	    
	    else
	    {
	        include( $ini->read_var( "site", "DefaultPage" ) );
	    }
        }


																								    

        // set character set
        include_once( "classes/ezlocale.php" );
        $languageOverride = $GLOBALS["eZLanguageOverride"];
        if ( $languageOverride != "" )
        {
            $Language = $languageOverride;
            print( $Language );
        }
        else
        {
            $Language = $ini->read_var( "eZCalendarMain", "Language" );
        }
        $Locale = new eZLocale( $Language );
        $iso =& $Locale->languageISO();
        if ( $iso != false )
            header( "Content-type: text/html;charset=$iso" );
        
    
        $MainContents =& ob_get_contents();
        ob_end_clean();
    
        // fill the buffer with the old values
        ob_start();
        print( $buffer );

        // set the sitedesign from the section
        if ( $ini->read_var( "site", "Sections" ) == "enabled" )
        {
            if ( !is_numeric( $GlobalSectionID ) )
            {
                $GlobalSectionID = $ini->read_var( "site", "DefaultSection" );
            }

            include_once( "ezsitemanager/classes/ezsection.php" );

            if ( is_numeric( $SectionIDOverride ) )
            {
                $GlobalSectionID = $SectionIDOverride;
            }

            // init the section
            $sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
            $sectionObject->setOverrideVariables();

            if ( $DEBUG == true )
            {
                print( eZSection::siteDesign( $GlobalSectionID ) );
            }

        
            $siteDesign = eZSection::siteDesign( $GlobalSectionID );
            $GlobalSiteDesign = $siteDesign;
        }

        // include some html
        $Title = $ini->read_var( "site", "SiteTitle" );

        // Main contents
        // handled by the sitedesign/$design/frame.php file now..
        // print( $MainContents );

        // include framework
        if ( isSet( $PrintableVersion ) and $PrintableVersion == "enabled" )
        {
            include( "sitedesign/$siteDesign/simpleframe.php" );
        }
        else
        {
            include( "sitedesign/$siteDesign/frame.php" );
        }

        // store site cache
        if ( $StoreSiteCache == true )
        {
            $fp = fopen( $SiteCacheFile, "w+");

            $SiteContents =& ob_get_contents();
            fwrite( $fp, $SiteContents );
            fclose( $fp );
        }
    }
    else
    {
        // load site cache
        include( $SiteCacheFile );
    }

}
else
{
    // parse the URI
    $page = "";

    // send the URI to the right decoder
    $page = "ezuser/user/datasupplier.php";
    if ( eZFile::file_exists( $page ) )
    {
        include( $page );
    }

    $MainContents =& ob_get_contents();
    ob_end_clean();
    ob_start();    

    include( "sitedesign/$siteDesign/loginframe.php" );
}


// close the database connection.
$db =& eZDB::globalDatabase();
$db->close();

// $bench->stop();

// $bench->printResults();

ob_end_flush();

?>
