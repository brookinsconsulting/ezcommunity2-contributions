<?

include_once( "ezaddress/classes/ezcountry.php" );

$ini =& INIFile::globalINI();
$Max = $ini->read_var( "eZAddressMain", "MaxCountryList" );

include( "ezaddress/admin/typelist_pre.php" );

$language_file = "country.php";
$page_path = "/address/country";
$typelist = "typelist.tpl";
$template_array = array( "country_tpl" => "countrylist.tpl" );
$variable_array = array( "country_header" => "country_header_tpl",
                         "country_item" => "country_item_tpl",
                         "extra_type_header" => "country_header",
                         "extra_type_item" => "country_item" );
$block_array = array( array( "country_tpl", "country_header_tpl", "country_header" ),
                      array( "country_tpl", "country_item_tpl", "country_item" ) );

$item_type_array = eZCountry::getAll( true, $SearchText, $Index, $Max );
$total_types = eZCountry::getAllCount( $SearchText );
$func_call = array( "item_id" => 'id',
                    "item_name" => 'name',
                    "item_iso" => 'iso' );
$Searchable = true;

include( "ezaddress/admin/typelist.php" );

?>
