<?

switch ( $url_array[2] )
{
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
