<?
//print $REQUEST_URI;

include_once( "classes/ezuritool.php" );

//  $url_array = explode( "/", $REQUEST_URI );
$url_array = eZURITool::split( $REQUEST_URI );

if( is_object( $user ) )
{
    $UserID = $user->id();
}

if( $UserID > 0 )
{
    $Add_User = false;
}
else
{
    $Add_User = true;
}

switch ( $url_array[2] )
{
    case "search":
    {
        switch( $url_array[3] )
        {
            case "company":
            {
                $SearchObject = "company";
                include( "ezcontact/user/companysearch.php" );
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
                    $SearchText = eZURITool::decode( $url_array[5] );
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

    case "company":
    {
        $CompanyID = $url_array[4];
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            {
                $NewCompanyCategory = $url_array[4];
                unset( $CompanyID );
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "view":
            {
                include( "ezcontact/admin/companyview.php" );
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
    break;

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

    default :
        print( "<h1>Sorry, This page isn't for you. </h1>" );
        break;
}

?>
