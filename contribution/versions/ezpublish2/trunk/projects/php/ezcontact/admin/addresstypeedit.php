<?

include_once( "ezcontact/classes/ezaddresstype.php" );

$language_file = "addresstype.php";
$item_type = new eZAddressType( $AddressTypeID );
$page_path = "/contact/addresstype";

include( "ezcontact/admin/typeedit.php" );

?>
