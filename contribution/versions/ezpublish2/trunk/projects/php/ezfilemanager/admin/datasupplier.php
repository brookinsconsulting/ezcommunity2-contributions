<?

switch ( $url_array[2] )
{
    case "browse":
    {
        $FolderID = $url_array[3];
        include( "ezfilemanager/admin/browse.php" );
    }
    break;

    case "unassigned":
    {
        include( "ezfilemanager/admin/unassigned.php" );
    }
    break;

    default:
        include( "ezfilemanager/user/datasupplier.php" );
}
?>
