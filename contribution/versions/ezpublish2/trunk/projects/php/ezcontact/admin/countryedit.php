<?

include_once( "ezcontact/classes/ezcountry.php" );

$language_file = "country.php";
$item_type = new eZCountry( $CountryID );
$page_path = "/contact/country";

include( "ezcontact/admin/typeedit.php" );

?>
