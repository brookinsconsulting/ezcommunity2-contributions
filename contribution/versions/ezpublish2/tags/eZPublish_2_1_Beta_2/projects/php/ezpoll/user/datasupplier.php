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
        if ( isSet( $url_array[4] ) )
             $ChoiceID = $url_array[4];
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
    case "votepage" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/user/votepage.php" );
    }
    break;

    case "userlogin" :
    {
        $VoteID = $url_array[4];
        include( "ezpoll/user/userlogin.php" );
    }    
    break;

    case "test":
    {
        include( "ezcontact/test.php" );
    }

}
?>
