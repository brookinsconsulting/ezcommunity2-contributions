<?

$HelpLanguage = $ini->read_var( "site", "HelpLanguage");

switch ( $url_array[2] )
{
    case "gotolink" :
    {
        $Action = $url_array[3];
        $LinkID = $url_array[4];
        $Url = $url_array[5];
        include( "ezlink/user/gotolink.php" );
    }
    break;

    default :
        print( "<h1>Sorry, Your help page could not be found. </h1>" );
        break;
}

?>
