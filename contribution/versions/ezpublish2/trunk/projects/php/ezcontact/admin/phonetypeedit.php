<?

include_once( "ezcontact/classes/ezphonetype.php" );

$language_file = "phonetype.php";
$item_type = new eZPhoneType( $PhoneTypeID );
$page_path = "/contact/phonetype";

include( "ezcontact/admin/typeedit.php" );

?>
