<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "categorylist":
    {
        $CategoryID = $url_array[3];
        $Offset = $url_array[4];
        if( $Offset == "" )
            $Offset = 0;
        include_once( "ezbulkmail/admin/categorylist.php" );
    }
    break;

    case "categoryedit" :
    {
        $CategoryID = $url_array[3];
        if( !is_numeric( $CategoryID ) )
            $CategoryID = 0;
        include_once( "ezbulkmail/admin/categoryedit.php" );
    }
    break;

    case "templatelist" :
    {
        include_once( "ezbulkmail/admin/templatelist.php" );
    }
    break;

    case "templateedit" :
    {
        $TemplateID = $url_array[3];
        if( !is_numeric( $TemplateID ) )
            $TemplateID = 0;
        include_once( "ezbulkmail/admin/templateedit.php" );
    }
    break;

    case "mailedit" :
    {
        $MailID = $url_array[3];
        if( !is_numeric( $MailID ) )
            $MailID = 0;
        include_once( "ezbulkmail/admin/mailedit.php" );
    }
    break;

    case "drafts" :
    {
        include_once( "ezbulkmail/admin/maillist.php" );
    }
    break;

    case "send" :
        $SendButton = true;
    case "preview" :
        $EditButton = true;
    case "view" :
    {
        $MailID = $url_array[3];
        if( !is_numeric( $MailID ) )
        {
            eZHTTPTool::header( "Location: /error/404" );
            exit();
        }
        include_once( "ezbulkmail/admin/mailview.php" );
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
