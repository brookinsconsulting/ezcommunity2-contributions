<?

switch ( $url_array[2] )
{
    case "polls" :
    {
        include( "ezpoll/user/pollist.php" );
    }
    break;

    case "vote" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/user/vote.php" );
    }
    break;

    case "result" :
    {
        $Show = $url_array[4];
        $PollID = $url_array[3];
        include( "ezpoll/user/result.php" );
    }
    break;

    case "votebox" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/user/votebox.php" );
    }
    break;
}
?>
