<?

switch ( $url_array[2] )
{
    case "archive" :        
    {
        $ModuleID = $url_array[3];
        
        include( "ezbug/user/buglist.php" );
    }
    break;

    case "search" :        
    {
        include( "ezbug/user/search.php" );
    }
    break;

    case "view" :        
    case "bugview" :        
    {
        $BugID = $url_array[3];
        
        include( "ezbug/user/bugview.php" );
    }
    break;
    
    
    case "report" :
    {
        include( "ezbug/user/bugreport.php" );
    }
    break;

    case "reportsuccess" :
    {
        include( "ezbug/user/reportsuccess.php" );
    }
    break;

    default :
    {
        print( "Error: Bug file not found" );
    }
    break;
    
}

?>
