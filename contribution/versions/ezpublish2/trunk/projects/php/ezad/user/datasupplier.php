<?

switch ( $url_array[2] )
{
    case "goto" :
    {
        $AdID = $url_array[3];

        include( "ezad/user/gotoad.php" );
    }
    break;

    case "show" :
    {
        include( "ezad/user/showbanner.php" );
    }
    break;
}

?>
