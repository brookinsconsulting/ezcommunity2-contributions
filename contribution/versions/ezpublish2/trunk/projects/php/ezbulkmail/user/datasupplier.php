<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "subscriptionlist":
    {
        include_once( "ezbulkmail/user/subscriptionlist.php" );
    }
    break;

    case "newsubscription" :
        $New = "new";
    case "login" :
    {
        include_once( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "confirmsubscription" :
    {
        $Hash = $url_array[3];
        include_once( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "successfull" :
    {
        $mailConfirm = "";
        include_once( "ezbulkmail/user/usermessages.php" );
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
