<?
include_once( "classes/ezhttptool.php" );

$Operation = $url_array[2];
$Action = $url_array[3];

switch( $Operation )
{
    case "form":
    {
        $FormID = $url_array[4];
        
        switch( $Action )
        {
            case "edit":
            case "insert":
            case "update":
            case "delete":
            case "up":
            case "down":
            case "new":
            {
                include( "ezform/admin/formedit.php" );
            }
            break;
            
            case "list":
            {
                $Offset = $url_array[5];
                include( "ezform/admin/formlist.php" );
            }
            break;
            
            case "view":
            case "process":
            {
                include( "ezform/admin/formview.php" );
            }
            break;
            
            case "preview":
            {
                include( "ezform/admin/formpreview.php" );
            }
            break;
            
            default:
            {
                eZHTTPTool::header( "Location: /error/404" );
            }
            break;
        }
    }
    break;
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404" );
    }
    break;
}

?>
