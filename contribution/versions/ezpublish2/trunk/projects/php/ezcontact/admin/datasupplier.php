<?php
include_once( "classes/ezuritool.php" );
include_once( "ezuser/classes/ezpermission.php" );
include_once( "classes/ezhttptool.php" );

$user = eZUser::currentUser();
if( eZPermission::checkPermission( $user, "eZContact", "ModuleEdit" ) == false )
{
    eZHTTPTool::header( "Location: /error/403" );
    exit();
}

$url_array = eZURITool::split( $REQUEST_URI );
$ListType = $url_array[2];
switch ( $ListType )
{
    case "nopermission":
    {
        $Type = $url_array[3];
        switch( $Type )
        {
            case "company":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "category":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "person":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "login":
            case "consultation":
            {
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            case "type":
            {
                $Action = $url_array[4];
                include( "ezcontact/admin/nopermission.php" );
                break;
            }
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "setup":
    {
        include( "ezcontact/admin/setup.php" );
        break;
    }

    case "company":
    {
        $Action = $url_array[3];
        switch ( $Action )
        {
            // intentional fall through
            case "new":
            {
                if ( isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $NewCompanyCategory = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "edit":
            case "update":
            case "delete":
            case "insert":
            {
                if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $CompanyID = $url_array[4];
                include( "ezcontact/admin/companyedit.php" );
                break;
            }
            case "view":
            {
                if ( !isset( $CompanyID ) and isset( $url_array[4] ) and is_numeric( $url_array[4] ) )
                    $CompanyID = $url_array[4];
                $PersonOffset = $url_array[5];
                include( "ezcontact/admin/companyview.php" );
                break;
            }
            case "stats":
            {
                $Year = $url_array[6];
                $Month = $url_array[7];
                $Day = $url_array[8];
                $DateType = $url_array[4];
                if ( !isset( $CompanyID ) and isset( $url_array[5] ) and is_numeric( $url_array[5] ) )
                    $CompanyID = $url_array[5];
                include( "ezcontact/admin/companystats.php" );
                break;
            }
            case "list":
            {
                $TypeID = $url_array[4];
                $Offset = $url_array[5];
                $ShowStats = true;
                include( "ezcontact/admin/companytypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
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
            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
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
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
                break;
            }
        }
        break;
    }

    case "consultation":
    {
        if ( !isset( $ConsultationID ) or !is_numeric( $ConsultationID ) )
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
                if ( !isset( $CompanyID ) or !is_numeric( $CompanyID ) )
                    $CompanyID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
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
                if ( !isset( $PersonID ) )
                    $PersonID = $url_array[5];
                switch ( $Action )
                {
                    // intentional fall through
                    case "delete":
                    {
                        $ConsultationID = $url_array[5];
                    }
                    case "new":
                    case "edit":
                    case "update":
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
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
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
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/consultationtypelist.php" );
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
                include( "ezcontact/admin/consultationtypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
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
                if ( is_numeric( $url_array[4] ) )
                    $Index = $url_array[4];
                include( "ezcontact/admin/projecttypelist.php" );
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
                include( "ezcontact/admin/projecttypelist.php" );
                break;
            }

            default:
            {
                include_once( "classes/ezhttptool.php" );
                eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
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
        include_once( "classes/ezhttptool.php" );
        eZHTTPTool::header( "Location: /contact/error?Type=404&Uri=$REQUEST_URI&Query=$QUERY_STRING&BackUrl=$HTTP_REFERER" );
        break;
}

?>
