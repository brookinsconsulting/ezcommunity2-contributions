<?

include_once( "eztrade/classes/ezvoucher.php" );
include_once( "eztrade/classes/ezvoucherinformation.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "ezuser/classes/ezuser.php" );

$Language = $ini->read_var( "eZTradeMain", "Language" );

$t = new eZTemplate( "eztrade/admin/" . $ini->read_var( "eZTradeMain", "AdminTemplateDir" ),
                     "eztrade/admin/intl/", $Language, "vouchercreate.php" );

$t->set_file( "vouchercreate_tpl", "vouchercreate.tpl" );

$t->setAllStrings();

$t->set_block( "vouchercreate_tpl", "error_tpl", "error" );
$t->set_block( "vouchercreate_tpl", "form_tpl", "form" );
$t->set_block( "vouchercreate_tpl", "keys_tpl", "keys" );
$t->set_block( "vouchercreate_tpl", "success_tpl", "success" );

$t->set_block( "error_tpl", "qty_too_low_tpl", "qty_too_low" );
$t->set_block( "error_tpl", "price_too_low_tpl", "price_too_low" );
$t->set_block( "error_tpl", "valid_until_invalid_tpl", "valid_until_invalid" );
$t->set_block( "error_tpl", "from_name_empty_tpl", "from_name_empty" );
$t->set_block( "error_tpl", "from_email_wrong_tpl", "from_email_wrong" );
$t->set_block( "error_tpl", "to_name_empty_tpl", "to_name_empty" );
$t->set_block( "error_tpl", "to_email_wrong_tpl", "to_email_wrong" );
$t->set_block( "error_tpl", "description_empty_tpl", "description_empty" );

$t->set_var( "qty_too_low", "" );
$t->set_var( "price_too_low", "" );
$t->set_var( "valid_until_invalid", "" );
$t->set_var( "description_empty", "" );
$t->set_var( "from_name_empty", "" );
$t->set_var( "from_email_wrong", "" );
$t->set_var( "to_name_empty", "" );
$t->set_var( "to_email_wrong", "" );
$t->set_var( "error", "" );
$t->set_var( "success", "" );
$t->set_var( "form", "" );
$t->set_var( "keys", "" );
$t->set_var( "qty", $Qty );
$t->set_var( "description", $Description );
$t->set_var( "price", $Price );
$t->set_var( "from_name", $FromName );
$t->set_var( "from_email", $FromEmail );
$t->set_var( "to_name", $ToName );
$t->set_var( "to_email", $ToEmail );
$t->set_var( "checked", "" );
$t->set_var( "valid_until", "" );

$error = false;
$keys_succes = false;

$now = time();

if ( !$Valid )
{
    $then = $now + $ValidUntil * (24*60*60);
    
    $days = GetDayDiff( $then, $now );
    if ( $days == 0 )
	$days = "";
    
    $t->set_var( "valid_until", $days );
}
elseif ( $Valid == "unlimited" )
{
    $then = 0;
    $t->set_var( "checked", "checked" );
}




if ( $Action == "Create" )
{
    if ( $Qty < 1 OR $Qty == "" )
    {
        $t->parse( "qty_too_low", "qty_too_low_tpl" );
        $error = true;
    }
    
    if ( $Price < 10 OR $Price == "" )
    {
        $t->parse( "price_too_low", "price_too_low_tpl" );
        $error = true;
    }
    
    if ( !is_integer( $ValidUntil*1 ) OR ( $ValidUntil == "" AND $Valid != "unlimited" ) )
    {
        $t->parse( "valid_until_invalid", "valid_until_invalid_tpl" );
        $error = true;
    }
    
    if ( $Description == "" )
    {
        $t->parse( "description_empty", "description_empty_tpl" );
        $error = true;
    }

    if ( $FromName == "" )
    {
        $t->parse( "from_name_empty", "from_name_empty_tpl" );
        $error = true;
    }

    if ( $ToName == "" )
    {
        $t->parse( "to_name_empty", "to_name_empty_tpl" );
        $error = true;
    }

    if ( validate( $FromEmail ) != 1 )
    {
        $t->parse( "from_email_wrong", "from_email_wrong_tpl" );
        $error = true;
    }

    if ( validate( $ToEmail ) != 1 )
    {
        $t->parse( "to_email_wrong", "to_email_wrong_tpl" );
        $error = true;
    }


    if ( $error == false )
    {
        $user =& eZUser::currentUser();
        $product = new eZProduct( 4652 );  // must become flexible no idea how to fetch this value yet...

        for ( $i = 0; $i < $Qty; $i++ )
        {
            $voucher = new eZVoucher();
            $voucherInfo = new eZVoucherInformation();

            $voucher->setPrice( $Price );
            $voucher->generateKey();
            $voucher->setAvailable( true );
            $voucher->setUser( $user );
            $voucher->setProduct( $product ); 
            $voucher->setValidUntil( $then );
            $voucher->setTotalValue( $Price );	    
            $voucher->store();

            $voucher_keys[] = $voucher->keyNumber(); // save all created Keys in an array

            $online = new eZOnline();
            $online->setUrl( $ToEmail );
            $online->store();
            $voucherInfo->setToOnline( $online );

            $online = new eZOnline();
            $online->setUrl( $FromEmail );
            $online->store();
            $voucherInfo->setFromOnline( $online );

            $voucherInfo->setPrice( $Price );

          
            $voucherInfo->setFromName( $FromName );
            $voucherInfo->setToName( $ToName );
            $voucherInfo->setDescription( $Description );
            $voucherInfo->setMailMethod( 1 );
            $voucherInfo->setVoucher( $voucher );
            $voucherInfo->store();
        }
        $keys_succes = true;
    }
}

if ( $error )
{
   $t->parse( "error", "error_tpl" );
   $t->parse( "form", "form_tpl" );
}
elseif ( $keys_succes )
{
    foreach( $voucher_keys as $value )
    {
        $t->set_var( "key_values", $value );
        $t->parse( "keys", "keys_tpl", true );
    }
    $t->parse( "success", "success_tpl" );
}
else
   $t->parse( "form", "form_tpl" );
   
$t->pparse( "output", "vouchercreate_tpl");


function GetDayDiff($ts_1, $ts_2)
{
   if ($ts_1 > $ts_2)
   {
       $var_days = ($ts_1 - $ts_2) / 86400; // 24 * 60 *60
   }
   elseif ($ts_1 < $ts_2)
   {
       $var_days = ($ts_2 - $ts_1) / 86400; // 24 * 60 *60
   }
   else
   {
       $var_days = 0;
   }
   return($var_days);
}

function validate( $address )
{
    $pos = ( ereg('^[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+'.'@'.'[-!#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.'[-!#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$', $address) );
    return $pos;
}
	

?>
