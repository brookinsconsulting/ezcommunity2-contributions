<?
//print $REQUEST_URI;

$url_array = explode( "/", $REQUEST_URI );
switch ( $url_array[2] )
{
    case "company":
    {
        switch ( $url_array[3] )
        {
            case "list":
            {
                $TypeID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            case "test":
            {
                include( "ezcontact/admin/test.php" );
                break;
            }

            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "insert":
            {
                $Action = "insert";
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "edit":
            {
                $Action = "edit";
                $CompanyID = $url_array[4];

                include( "ezcontact/admin/companyedit.php" );
                break;
            }

            case "update":
            {
                $Action = "update";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            
            case "delete":
            {
                $Action = "delete";
                $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "view":
            {
                $CompanyID = $url_array[4];
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
        switch( $url_array[3] )
        {
            case "view":
            {
                $TypeID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }

            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "insert":
            {
                $TypeID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "edit":
            {
                $TypeID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "update":
            {
                $TypeID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "delete":
            {
                $TypeID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/companytypeedit.php" );
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
        switch( $url_array[3] )
        {
            case "list":
            {
                $PersonID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "insert":
            {
                $PersonID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "view":
            {
                $PersonID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/personview.php" );
                break;
            }
            case "edit":
            {
                $PersonID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "update":
            {
                $PersonID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "delete":
            {
                $PersonID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/persondelete.php" );
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
        switch( $url_array[3] )
        {
            case "list":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/phonetypelist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "insert":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "view":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/phonetypeview.php" );
                break;
            }
            case "edit":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "update":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "delete":
            {
                $PhoneTypeID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/phonetypeedit.php" );
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
        switch( $url_array[3] )
        {
            case "list":
            {
                $AddressTypeID = $url_array[4];
                $Action = "list";
                include( "ezcontact/admin/addresstypelist.php" );
                break;
            }
            case "new":
            {
                $Action = "new";
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "insert":
            {
                $AddressTypeID = $url_array[4];
                $Action = "insert";
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "view":
            {
                $AddressTypeID = $url_array[4];
                $Action = "view";
                include( "ezcontact/admin/addresstypeview.php" );
                break;
            }
            case "edit":
            {
                $AddressTypeID = $url_array[4];
                $Action = "edit";
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "update":
            {
                $AddressTypeID = $url_array[4];
                $Action = "update";
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "delete":
            {
                $AddressTypeID = $url_array[4];
                $Action = "delete";
                include( "ezcontact/admin/addresstypeedit.php" );
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
