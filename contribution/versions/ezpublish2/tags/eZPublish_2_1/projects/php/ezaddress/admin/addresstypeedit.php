<?

include_once( "ezaddress/classes/ezaddresstype.php" );

$language_file = "addresstype.php";
$item_type = new eZAddressType( $AddressTypeID );
if ( isset( $ItemArrayID ) and is_array( $ItemArrayID ) )
{
    $item_types = array();
    foreach( $ItemArrayID as $item_id )
    {
        $item_types[] = new eZAddressType( $item_id );
    }
}

$page_path = "/address/addresstype";

include( "ezaddress/admin/typeedit.php" );

?>
