<?
switch ( $url_array[2] )
{
    case "imageview" :
    {
        $ImageID = $url_array[3];

        include( "ezimagecatalogue/user/imageview.php" );
    }
    break;

}
?>
