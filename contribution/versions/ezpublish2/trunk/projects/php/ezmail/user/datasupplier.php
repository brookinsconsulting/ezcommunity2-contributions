<?
include_once( "classes/ezhttptool.php" );

switch( $url_array[2] )
{
    case "folder" :
    {
        $FolderID = $url_array[3];
//        if( $FolderID == "" )
//            $FolderID = get INBOX.
        
        include( "ezmail/user/maillist.php" );
    }
    break;

    case "view" :
    {
        $MailID = $url_array[3];
        include( "ezmail/user/mailview.php" );
    }
    break;

    case "folderedit" :
        {
            $FolderID = $url_array[3];
            if( $FolderID == "" )
                $FolderID = 0;
            include( "ezmail/user/folderedit.php" );
        }
    break;

    case "config" :
    {
        include( "ezmail/user/configure.php" );
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
    break;
}

?>
