<?

include_once( "ezaddress/classes/ezaddresstype.php" );

$language_file = "addresstype.php";
$page_path = "/address/addresstype";

$item_type = new eZAddressType();
$item_type_array = $item_type->getAll();
$move_item = true;

include( "ezaddress/admin/typelist.php" );

?>
