<?

include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "activate":
    {
        $ModuleName = $url_array[3];
        $Activate = true;
        include( "ezmodule/admin/activate.php" );
    }
    break;

    case "deactivate":
    {
        $ModuleName = $url_array[3];
        $Activate = false;
        include( "ezmodule/admin/activate.php" );
    }
    break;

    default :
    {
        eZHTTPTool::header( "Location: /error/403" );
        exit();
    }
    break;
}

// display a page with error msg
        

?>
