<?

switch ( $url_array[2] )
{
    case "static":
    {
        include( "ezsitemanager/staticfiles/" . $url_array[3] );
    }
    break;

}
?>
