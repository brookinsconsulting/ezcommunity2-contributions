<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "company":
    {
        $CompanyID = $url_array[4];
        $Action = $url_array[3];
        switch ( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            case "test":
            {
                include( "ezcontact/admin/test.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/companyview.php" );
                break;
            }

            case "http":
            {
                $CompanyID = $url_array[4];
                if ( isSet ( $Edit ) )
                    $Action = "edit";
                if ( isSet ( $Delete ) )
                    $Action = "delete";

                include( "ezcontact/admin/companyedit.php" );
                break;
            }

            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "companytype" :
    {
        $TypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }
    case "person":
    {
        $PersonID = $url_array[4];
        $Action = $url_array[3];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/personview.php" );
                break;
            }
            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "phonetype":
    {
        $PhoneTypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/phonetypelist.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/phonetypeview.php" );
                break;
            }
            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "addresstype":
    {
        $AddressTypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $url_array[3] )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/addresstypelist.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/addresstypeview.php" );
                break;
            }
            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }
    
    case "onlinetype":
    {
        $Action = $url_array[3];
        $OnlineTypeID = $url_array[4];
        
        switch( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/onlinetypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/onlinetypelist.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/onlinetypeview.php" );
                break;
            }
            default:
            {
                header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "error":
    {
        include( "ezcontact/admin/error.php" );
        break;
    }

    default :
        header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
        break;
}

?>
