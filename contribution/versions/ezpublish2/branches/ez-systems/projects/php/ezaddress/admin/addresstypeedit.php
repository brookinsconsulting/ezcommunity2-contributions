<?

include_once( "ezaddress/classes/ezaddresstype.php" );

$language_file = "addresstype.php";
$item_type = new eZAddressType( $AddressTypeID );
$page_path = "/address/addresstype";

include( "ezaddress/admin/typeedit.php" );

?>
