<?

$PageCaching = $ini->read_var( "eZLinkMain", "PageCaching");

switch ( $url_array[2] )
{
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
        $Url = $url_array[5];
        include( "ezlink/user/gotolink.php" );
    }
    break;

    case "latest":
    {
        include( "ezlink/user/latest.php" );
    }

    case "search" :
        include( "ezlink/user/search.php" );
        break;

    case "success" :
        include( "ezlink/success.php" );
        break;

    case "group" :
    {
        if ( $PageCaching == "enabled" )
        {
            print( "cached version<br>" );
            
            $LGID = $url_array[3];

            $cachedFile = "ezlink/cache/linklist," .$LGID .".cache";
            
            if ( file_exists( $cachedFile ) )
            {
                print( "pure static" );
                include( $cachedFile );
            }
            else
            {
                print( "first time generated" );                
                $GenerateStaticPage = "true";
                include( "ezlink/user//linklist.php" );                
            }            
        }
        else
        {
            print( "uncached version" );

            $LGID = $url_array[3];
            include( "ezlink/user/linklist.php" );
        }
        
    }
    break;

    case "suggestlink" :
    {
        if ( $url_array[3] == "insert" )
        {
            $Action = "insert";
            include( "ezlink/user/suggestlink.php" );
        }
        else
        {
            $LinkGroupID = $url_array[3];
            include( "ezlink/user/suggestlink.php" );
        }
    }
    break;

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
