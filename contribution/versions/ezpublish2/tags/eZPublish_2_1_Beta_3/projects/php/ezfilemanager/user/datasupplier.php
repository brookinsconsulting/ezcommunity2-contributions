<?

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezhttptool.php" );

switch ( $url_array[2] )
{
    case "new" :        
    {
        $Action = "New";
        include( "ezfilemanager/user/fileupload.php" );
    }
    break;

    case "insert" :        
    {
        $Action = "Insert";
        include( "ezfilemanager/user/fileupload.php" );
    }
    break;

    case "edit" :
    {
        $FileID = $url_array[3];
        $Action = "Edit";
        include( "ezfilemanager/user/fileupload.php" );
    }
    break;

    case "update" :
    {
        $FileID = $url_array[3];
        $Action = "Update";
        include( "ezfilemanager/user/fileupload.php" );
    }
    break;

    case "fileview" :
    {
        $FileID = $url_array[3];
        include( "ezfilemanager/user/fileview.php" );
    }
    break;
    case "download" :
    {
        $FileID = $url_array[3];
        include( "ezfilemanager/user/filedownload.php" );
    }
    break;
    
    case "list" :
    {
        $FolderID = $url_array[3];
        if  ( !isset( $FolderID ) || ( $FolderID == "" ) )
            $FolderID = 0;
        
        include( "ezfilemanager/user/filelist.php" );
    }
    break;

    case "folder" :
    {
        switch( $url_array[3] )
        {
           
            case "new" :
            {
                $parentID = $url_array[4];
                $Action = "New";
                include( "ezfilemanager/user/folderedit.php" );
            }
            break;
            case "delete" :
            {
                $FolderID = $url_array[4];
                $Action = "Delete";
                include( "ezfilemanager/user/folderedit.php" );
            }
            break;
            
            case "insert" :
            {
                $Action = "Insert";
                include( "ezfilemanager/user/folderedit.php" );
            }
            break;

            case "edit" :
            {
                $FolderID = $url_array[4];
                $Action = "Edit";
                include( "ezfilemanager/user/folderedit.php" );
            }
            break;

            case "update" :
            {
                $FolderID = $url_array[4];
                $Action = "Update";
                include( "ezfilemanager/user/folderedit.php" );
            }
            break;

        }
    }
    break;

    case "search":
    {
        if ( $url_array[3] == "parent" )
        {
            $SearchText = urldecode( $url_array[4] );
            $Offset = $url_array[5];
        }
        
        include( "ezfilemanager/user/search.php" );
    }
    break;
    
    
    default:
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
}

?>
