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

    case "visitorlist" :
    {
        $ViewMode = $url_array[3];
        $ViewLimit = $url_array[4];
        
        include( "ezstats/admin/visitorlist.php" );
    }
    break;

    case "monthrepport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        
        include( "ezstats/admin/monthrepport.php" );
    }
    break;
    
}

?>
