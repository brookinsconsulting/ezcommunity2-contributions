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
$page_path = "/contact/consultationtype";
$item_type_array = eZConsultationType::findTypes();
$move_item = true;
$SortPage = "/contact/consultation/type/list";

include( "ezcontact/admin/typelist.php" );

?>
