<?php

$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "site", "DefaultSection" );

$PageCaching = $ini->read_var( "eZNewsfeedMain", "PageCaching" );

switch ( $url_array[2] )
{
    case "latest":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        if ( $PageCaching == "enabled" )
        {
            $CategoryID = $url_array[3];
            $cachedFile = "eznewsfeed/cache/latestnews," . $CategoryID . ".cache";

            if ( file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "eznewsfeed/user/newslist.php" );
            }            
        }
        else
        {
            $GenerateStaticPage = "false";
            include( "eznewsfeed/user/newslist.php" );
        }
    }
    break;

    case "allcategories" :
    {
        $GenerateStaticPage = "false";
        include( "eznewsfeed/user/allcategories.php" );
    }
    break;

    case "search":
    {
        include( "eznewsfeed/user/search.php" );
    }
    break;
}

?>
