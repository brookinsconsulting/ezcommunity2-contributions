<?

switch ( $url_array[2] )
{
    case "monthlist" :
    {
        $Year = $url_array[3];
        
        include( "ezcalendar/user/monthlist.php" );
    }
    break;

    case "monthview" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];
        include( "ezcalendar/user/monthview.php" );
    }
    break;

    case "appointmentedit" :
    {
        include( "ezcalendar/user/appointmentedit.php" );
    }    
}

?>
