<?
if ( $url_array[2] == "browse" )
{
    $CategoryID = $url_array[3];
    include( "ezimagecatalogue/admin/browse.php" );
}
else
include( "ezimagecatalogue/user/datasupplier.php" );

?>
