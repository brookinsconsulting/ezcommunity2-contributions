<?

switch ( $url_array[2] )
{
    case "upload" :        
    {
        include( "ezfilemanager/user/fileupload.php" );
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
        include( "ezfilemanager/user/filelist.php" );
    }
    break;
    
}

?>
