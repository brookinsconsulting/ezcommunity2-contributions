<?

switch ( $url_array[2] )
{
    case "log":
    {
        include( "ezcc/admin/page.php" );
    }
    break;

    case "cutover":
    {
        include( "ezcc/admin/cutover.php" );
    }
    break;
    
    case "cancel":
    {
        include( "ezcc/admin/reveresal.php" );
    }
    break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
