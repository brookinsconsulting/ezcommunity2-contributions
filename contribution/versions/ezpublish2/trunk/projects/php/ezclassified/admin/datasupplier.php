<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "test":
    {
        include( "ezclassified/admin/test.php" );
    }
    break;

    default :
        header( "Location: /error.php?type=404&reason=missingpage&hint[]=/contact/company/list/&hint[]=/contact/person/list&module=ezcontact" );
        break;
}

?>
