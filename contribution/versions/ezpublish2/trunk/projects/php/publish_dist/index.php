<?
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT"); 
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . "GMT"); 
header("Cache-Control: no-cache, must-revalidate"); 
header("Pragma: no-cache");

ob_start();

include_once( "classes/INIFile.php" );
$ini = new INIFile( "site.ini" );

// parse the URI
$meta_page = "";
$content_page = "";

// Remove url parameters
ereg( "([^?]+)", $REQUEST_URI, $regs) ;

$REQUEST_URI = $regs[1];

$url_array = explode( "/", $REQUEST_URI );
$meta_page = "ez" . $url_array[1] . "/metasupplier.php";

// include some html
include( "preamble.php" );

// check if there is specific meta info, ifnot include the default
if ( file_exists( $meta_page ) )
{
    include( $meta_page );
}
else
{
    // Load the default meta info
    include( "defaultmetainfo.php" );
}

// include more html
if ( $PrintableVersion == "enabled" )
{
    include( "simpleheader.php" );
}
else
{
    include( "header.php" );    
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
    $CategoryID = 0;
    include( "ezarticle/user/articlelist.php" );
}

// and the html finish
// include more html
if ( $PrintableVersion == "enabled" )
{
    include( "simplefooter.php" );
}
else
{
    include( "footer.php" );
}


                    
ob_end_flush();
?>
