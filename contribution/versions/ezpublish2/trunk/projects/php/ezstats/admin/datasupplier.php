<?

switch ( $url_array[2] )
{
    case "overview" :
    {
        include( "ezstats/admin/overview.php" );
    }
    break;

    case "pageviewlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        
        include( "ezstats/admin/pageviewlist.php" );
    }
    break;
    
}

?>
