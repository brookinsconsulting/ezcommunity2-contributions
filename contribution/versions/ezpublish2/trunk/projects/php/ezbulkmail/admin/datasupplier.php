<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "categorylist":
    {
        include_once( "ezbulkmail/admin/categorylist.php" );
    }
    break;

    case "categoryedit" :
    {
        $CategoryID = $url_array[3];
        if( !is_int( $CategoryID ) )
            $CategoryID = 0;
        include_once( "ezbulkmail/admin/categoryedit.php" );
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
