<?

include_once( "ezcontact/classes/ezphonetype.php" );

$language_file = "phonetype.php";
$page_path = "/contact/phonetype";

$item_type = new eZPhoneType();
$item_type_array = $item_type->getAll();
$move_item = true;

include( "ezcontact/admin/typelist.php" );

?>
