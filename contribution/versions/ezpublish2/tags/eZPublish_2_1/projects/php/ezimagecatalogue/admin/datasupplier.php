<?

switch ( $url_array[2] )
{
    case "browse":
    {
        $CategoryID = $url_array[3];
        include( "ezimagecatalogue/admin/browse.php" );
    }
    break;

    case "unassigned":
    {
        include( "ezimagecatalogue/admin/unassigned.php" );
    }

    default:
        include( "ezimagecatalogue/user/datasupplier.php" );
    
}

?>
