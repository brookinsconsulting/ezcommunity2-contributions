<?

include_once( "ezaddress/classes/ezphonetype.php" );

$language_file = "phonetype.php";
$item_type = new eZPhoneType( $PhoneTypeID );
$page_path = "/address/phonetype";

include( "ezaddress/admin/typeedit.php" );

?>
