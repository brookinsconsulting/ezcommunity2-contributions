<?

include_once( "classes/ezhttptool.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZSiteManager", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "game":
    {
        switch ( $url_array[3] )
        {
            case "list":
            {
                if ( $url_array[4] == "parent" )
                    $Offset = $url_array[5];
                include( "ezquiz/admin/gamelist.php" );
            }
            break;
            
            case "edit":
            case "new":
            case "delete":
            case "update":
            case "insert":
            {
                if ( is_numeric( $url_array[4] ) )
                    $GameID = $url_array[4];
                include ( "ezquiz/admin/gameedit.php" );
            }
            break;

            case "questionedit":
            {
                if ( is_numeric( $url_array[4] ) )
                    $QuestionID = $url_array[4];
                include ( "ezquiz/admin/questionedit.php" );
            }
            break;
        }
        break;

    }
    break;

    default :
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }
    break;
}

?>
