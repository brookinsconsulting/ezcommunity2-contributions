<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );

switch ( $url_array[2] )
{
    case "polls" :
    {
        include( "ezpoll/polllist.php" );
    }
    break;

    case "vote" :
    {
        $PollID = $url_array[3];
        //  $ChoiceID = $ChoiceID;
        include( "ezpoll/vote.php" );
    }
    break;

    case "result" :
    {
        $PollID = $url_array[3];
        include( "ezpoll/result.php" );
    }
    break;

case "votebox" :
 {
     $PollID = $url_array[3];
     include( "ezpoll/votebox.php" );
 }
 break;
}
?>
