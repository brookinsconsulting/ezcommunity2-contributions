<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "subscriptionlist":
    {
        include_once( "ezbulkmail/user/subscriptionlist.php" );
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>
