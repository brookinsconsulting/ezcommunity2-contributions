<?
//print $REQUEST_URI;

include_once( "classes/ezuritool.php" );

//  $url_array = explode( "/", $REQUEST_URI );
$url_array = eZURITool::split( $REQUEST_URI );
switch ( $url_array[2] )
{
    case "company":
    {
        if ( !isset( $CompanyID ) )
            $CompanyID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            {
                $NewCompanyCategory = $url_array[4];
//                  unset( $CompanyID );
//                  include( "ezcontact/admin/companyedit.php" );
                $CompanyEdit = true;
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
//                  include( "ezcontact/admin/companyedit.php" );
                $CompanyEdit = true;
                include( "ezcontact/admin/personedit.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/companyview.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }

            case "http":
            {
                $CompanyID = $url_array[4];
                if ( isSet ( $Edit ) )
                    $Action = "edit";
                if ( isSet ( $Delete ) )
                    $Action = "delete";

//                  include( "ezcontact/admin/companyedit.php" );
                $CompanyEdit = true;
                include( "ezcontact/admin/personedit.php" );
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

    case "companycategory" :
    {
        $TypeID = $url_array[4];
        $Action = $url_array[3];
        switch( $Action )
        {
            // intentional fall through
            case "new":
            {
                $NewParentID = $url_array[4];
                unset( $TypeID );
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/companytypeedit.php" );
                break;
            }
//              case "list":
//              {
//                  include( "ezcontact/admin/companytypelist.php" );
//                  break;
//              }
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
        switch( $Action )
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
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/personlist.php" );
                break;
            }
            case "search":
            {
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                if ( count( $url_array ) >= 5 && !isset( $SearchText ) )
                {
                    $SearchText = $url_array[5];
                    $SearchText = eZURITool::decode( $SearchText );
                }
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
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/phonetypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/phonetypelist.php" );
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
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/addresstypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/addresstypelist.php" );
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
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/onlinetypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/onlinetypelist.php" );
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

    case "consultation":
    {
        $ConsultationID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/consultationedit.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/consultationview.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/consultationlist.php" );
                break;
            }
            case "company":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                $CompanyID = $url_array[5];
//                  $ConsultationID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "new":
                    case "edit":
                    case "update":
                    case "delete":
                    case "insert":
                    {
                        include( "ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
                break;
            }
            case "person":
            {
                $SubAction = $url_array[3];
                $Action = $url_array[4];
                $PersonID = $url_array[5];
//                  $ConsultationID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "new":
                    case "edit":
                    case "update":
                    case "delete":
                    case "insert":
                    {
                        include( "ezcontact/admin/consultationedit.php" );
                        break;
                    }
                    case "list":
                    {
                        $ConsultationList = true;
                        include( "ezcontact/admin/consultationlist.php" );
                        break;
                    }
                    case "view":
                    {
                        include( "ezcontact/admin/consultationview.php" );
                        break;
                    }
                }
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

    case "consultationtype":
    {
        $ConsultationTypeID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/consultationtypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/consultationtypelist.php" );
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

    case "projecttype":
    {
        $ProjectTypeID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/projecttypeedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/projecttypelist.php" );
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

    case "country":
    {
        $CountryID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            case "edit":
            case "update":
            case "delete":
            case "confirm":
            case "insert":
            case "up":
            case "down":
            {
                include( "ezcontact/admin/countryedit.php" );
                break;
            }
            case "list":
            {
                include( "ezcontact/admin/countrylist.php" );
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
