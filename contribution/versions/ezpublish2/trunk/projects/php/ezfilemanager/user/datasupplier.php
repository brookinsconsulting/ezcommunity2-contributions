<?

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
    
}

?>
