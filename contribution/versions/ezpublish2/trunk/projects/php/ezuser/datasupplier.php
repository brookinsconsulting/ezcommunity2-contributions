<?

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "login" :
    {
        $Action = $url_array[3];
        include( "ezuser/login.php" );
    }
    break;

    case "success" :
    {

        $Action = $url_array[3];
        include( "ezuser/success.php" );
    }
    break;

    case "logout" :
    {
        $Action = $url_array[3];
        include( "ezuser/login.php" );
    }
    break;

    case "passwordchange" :
    {
        $Action = $url_array[3];
        include( "ezuser/passwordchange.php" );
    }
    break;

}
?>
