<?
include_once( "classes/ezhttptool.php" );
include_once( "ezmail/classes/ezmailaccount.php" );
include_once( "ezuser/classes/ezuser.php" );

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

    case "mailedit" :
    {
        $MailID = $url_array[3];
        if( $MailID == "" )
            $MailID = 0;
        include( "ezmail/user/mailedit.php" );
    }
    break;

    case "fileedit" :
    {
        $MailID = $url_array[3];
        if( $MailID == "" )
            $MailID = 0;
        include( "ezmail/user/fileedit.php" );
    }
    break;
    
    case "config" :
    {
        include( "ezmail/user/configure.php" );
    }
    break;

    case "accountedit" :
    {
        $AccountID = $url_array[3];
        if( $AccountID == "" )
            $AccountID = 0;
        include( "ezmail/user/accountedit.php" );
    }
    break;

    case "check" : // check the mail for this user!
    {
        $user = eZUser::currentUser();
        $accounts = eZMailAccount::getByUser( $user->id() );

        foreach( $accounts as $account )
            $account->checkMail();
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
