<?
switch ( $url_array[2] )
{
    case "product":
    {
        switch ( $url_array[3] )
        {
            case "print":
            {
                // Only display as printable
                $PrintableVersion = "enabled";
                break;
            }
        }
    }
}
?>
