<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{

    case "" :
        include( "ezlink/linklist.php" );        
        break;

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

    case "group" :
    {
        $LGID = $url_array[3];
        include( "ezlink/linklist.php" );
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
