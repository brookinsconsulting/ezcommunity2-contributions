<?

switch ( $url_array[2] )
{
    case "page":
    {
        include( "ezexample/user/page.php" );
    }
    break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
