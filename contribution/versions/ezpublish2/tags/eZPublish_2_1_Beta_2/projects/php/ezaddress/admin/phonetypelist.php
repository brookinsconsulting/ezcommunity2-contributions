<?

include_once( "ezaddress/classes/ezphonetype.php" );

include( "ezaddress/admin/typelist_pre.php" );

$language_file = "phonetype.php";
$page_path = "/address/phonetype";

$item_type_array = eZPhoneType::getAll( true, $Index, $Max );
$total_types = eZPhoneType::getAllCount();
$func_call = array( "item_id" => 'id',
                    "item_name" => 'name' );
$move_item = true;

include( "ezaddress/admin/typelist.php" );

?>
