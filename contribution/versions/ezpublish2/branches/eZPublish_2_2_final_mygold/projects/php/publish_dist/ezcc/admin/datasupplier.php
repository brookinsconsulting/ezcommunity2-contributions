<?

switch ( $url_array[2] )
{
    case "log":
    {
        if ( $url_array[3] != "" )
            $Offset = $url_array[3];
        else
            $Offset = 0;

        include( "ezcc/admin/page.php" );
    }
    break;

    case "visa":
    {
        include( "ezcc/admin/visa.php" );
    }
    break;
    
    case "mastercard":
    {
        include( "ezcc/admin/mastercard.php" );
    }
    break;

    case "elv":
    {
        include( "ezcc/admin/elv.php" );
    }
    break;

    default :
    {
        // go to default module page or show an error message
        print( "Error: your page request was not found" );
    }
}

?>
