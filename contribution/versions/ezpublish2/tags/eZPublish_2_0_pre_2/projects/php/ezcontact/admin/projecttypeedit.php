<?

include_once( "ezcontact/classes/ezprojecttype.php" );

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

$language_file = "projecttype.php";
$item_type = new eZProjectType( $ProjectTypeID );
$page_path = "/contact/projecttype";

include( "ezcontact/admin/typeedit.php" );

?>
