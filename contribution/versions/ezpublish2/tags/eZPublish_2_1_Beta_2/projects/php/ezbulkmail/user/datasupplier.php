<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "subscriptionlist":
    {
        include( "ezbulkmail/user/subscriptionlist.php" );
    }
    break;

    case "newsubscription" :
        $New = "new";
    case "login" :
    {
        include( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "confirmsubscription" :
    {
        $Hash = $url_array[3];
        include( "ezbulkmail/user/subscriptionlogin.php" );
    }
    break;

    case "singlelist" :
    {
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;

    case "singlelistsubscribe" :
    {
        $Subscribe = "yes";
        $Hash = $url_array[3];
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;
    
    case "singlelistunsubscribe" :
    {
        $UnSubscribe = "yes";
        $Hash = $url_array[3];
        include( "ezbulkmail/user/singlelist.php" );
    }
    break;
    
    case "successfull" :
    {
        $mailConfirm = "";
        include( "ezbulkmail/user/usermessages.php" );
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
