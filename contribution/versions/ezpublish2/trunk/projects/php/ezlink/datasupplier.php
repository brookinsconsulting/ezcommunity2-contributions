<?
$PageCaching = $ini->read_var( "eZLinkMain", "PageCaching");

switch ( $url_array[2] )
{
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LID = $url_array[4];
        $Url = $url_array[5];
        include( "ezlink/gotolink.php" );
    }
    break;

    case "search" :
        include( "ezlink/search.php" );
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
                include( "ezlink/linklist.php" );                
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
        $LGID = $url_array[3];
        include( "ezlink/suggestlink.php" );
    }
    break;

    default :
        print( "<h1>Sorry, Your link page could not be found. </h1>" );
        break;
}

?>
