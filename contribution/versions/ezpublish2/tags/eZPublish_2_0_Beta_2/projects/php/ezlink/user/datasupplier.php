<?

switch ( $url_array[2] )
{
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
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
        include( "ezlink/user/success.php" );
        break;

    case "group" :
    {
        $LinkGroupID = $url_array[3];
        include( "ezlink/user/linkgrouplist.php" );
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
