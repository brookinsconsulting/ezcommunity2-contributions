<?

include_once( "ezcontact/classes/ezcountry.php" );

$language_file = "country.php";
$page_path = "/contact/country";
$item_type_array = eZCountry::getAll();
$SortPage = "/contact/project/type/list";

include( "ezcontact/admin/typelist.php" );

?>
