<?

switch ( $url_array[2] )
{
    case "archive" :
    {
        include( "ezbug/admin/buglist.php" );
    }
    break;

    case "unhandled" :
    {
        include( "ezbug/admin/unhandledbugs.php" );
    }
    break;
}

?>
