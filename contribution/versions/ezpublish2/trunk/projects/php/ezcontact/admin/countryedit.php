<?

include_once( "ezcontact/classes/ezcountry.php" );

$language_file = "country.php";
$item_type = new eZCountry( $CountryID );
$page_path = "/contact/country";
$typeedit = "typeedit.tpl";
$template_array = array( "country_tpl" => "countryedit.tpl" );
$block_array = array( "extra_type_input" => "country_tpl" );

$func_call = array( "item_id" => "id",
                    "item_name" => "name",
                    "item_iso" => "iso" );

$func_call_set = array( "setName" => "ItemName",
                        "setISO" => "ItemISO" );

include( "ezcontact/admin/typeedit.php" );

?>
