<?

include_once( "ezcontact/classes/ezaddresstype.php" );

$language_file = "addresstype.php";
$page_path = "/contact/addresstype";

$item_type = new eZAddressType();
$item_type_array = $item_type->getAll();
$move_item = true;

include( "ezcontact/admin/typelist.php" );

?>
