<?
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "categorylist":
    {
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
