<?

include_once( "ezcontact/classes/ezonlinetype.php" );

$language_file = "onlinetype.php";
$page_path = "/contact/onlinetype";

$item_type = new eZOnlineType();
$item_type_array = $item_type->getAll();
$move_item = true;

include( "ezcontact/admin/typelist.php" );

?>
