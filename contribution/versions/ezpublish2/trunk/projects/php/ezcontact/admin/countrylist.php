<?

include_once( "ezcontact/classes/ezcountry.php" );

$ini =& $GlobalSiteIni;
$Max = $ini->read_var( "eZContactMain", "MaxCountryList" );

include( "ezcontact/admin/typelist_pre.php" );

$language_file = "country.php";
$page_path = "/contact/country";

$item_type_array = eZCountry::getAll( true, $SearchText, $Index, $Max );
$total_types = eZCountry::getAllCount( $SearchText );
$Searchable = true;

include( "ezcontact/admin/typelist.php" );

?>
