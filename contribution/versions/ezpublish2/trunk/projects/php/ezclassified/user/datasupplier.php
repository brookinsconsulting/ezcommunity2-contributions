<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "classifiededit":
    {
        switch( $url_array[3] )
        {
            case "new":
            {
                $Action = "new";
                include( "ezclassified/user/classifiededit.php" );
            }
            break;
            case "insert":
            {
                $Action = "insert";
                include( "ezclassified/user/classifiededit.php" );
            }
            break;

        }
    }
    break;

    case "list":
    case "classifiedlist":
    {
        switch( $url_array[3] )
        {
            case "list":
            {
                $CategoryID = $url_array[4];
                include( "ezclassified/user/classifiedlist.php" );
            }
            break;
        }
    }
    break;

    default :
        header( "Location: /error.php?type=404&reason=missingpage&hint[]=/contact/company/list/&hint[]=/contact/person/list&module=ezcontact" );
        break;
}

?>
