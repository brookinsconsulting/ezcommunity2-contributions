<?
switch( $url_array[2] )
{
    case "403" :
    {
        include( "ezerror/admin/403.php" );
    }
    break;
    
    case "404" :
    {
        include( "ezerror/admin/404.php" );
    }
    break;
}

?>
