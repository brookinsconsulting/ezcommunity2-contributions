<?

include_once( "ezcontact/classes/ezprojecttype.php" );

$language_file = "projecttype.php";
$page_path = "/contact/projecttype";
$item_type_array = eZProjectType::findTypes();
$move_item = true;
$SortPage = "/contact/project/type/list";

include( "ezcontact/admin/typelist.php" );

?>
