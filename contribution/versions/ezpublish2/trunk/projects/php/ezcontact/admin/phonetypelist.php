<?

include_once( "ezcontact/classes/ezphonetype.php" );

include( "ezcontact/admin/typelist_pre.php" );

$language_file = "phonetype.php";
$page_path = "/contact/phonetype";

$item_type_array = eZPhoneType::getAll( true, $Index, $Max );
$total_types = eZPhoneType::getAllCount();
$move_item = true;

include( "ezcontact/admin/typelist.php" );

?>
