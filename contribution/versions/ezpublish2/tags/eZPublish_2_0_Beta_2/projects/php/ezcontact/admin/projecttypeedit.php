<?

include_once( "ezcontact/classes/ezprojecttype.php" );

$language_file = "projecttype.php";
$item_type = new eZProjectType( $ProjectTypeID );
$page_path = "/contact/projecttype";

include( "ezcontact/admin/typeedit.php" );

?>
