<?

include_once( "ezcontact/classes/ezconsultationtype.php" );

include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezpermission.php" );

$user = eZUser::currentUser();
if ( get_class( $user ) != "ezuser" )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/login" );
    exit();
}

if ( !eZPermission::checkPermission( $user, "eZContact", "TypeAdmin" ) )
{
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: /contact/nopermission/type/admin" );
    exit();
}

$language_file = "consultationtype.php";
$item_type = new eZConsultationType( $ConsultationTypeID );
$page_path = "/contact/consultationtype";

include( "ezcontact/admin/typeedit.php" );

?>
