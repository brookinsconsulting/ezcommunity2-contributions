<?
ob_end_clean();
ob_start();

include_once( "classes/INIFile.php" );
include_once( "classes/ezlocale.php" );

// eZ trade classes
include_once( "eztrade/classes/ezproductcategory.php" );
include_once( "eztrade/classes/ezproduct.php" );
include_once( "eztrade/classes/ezorder.php" );

// eZ user
include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezpermission.php" );


// include the server
include_once( "ezxmlrpc/classes/ezxmlrpcserver.php" );

// include the datatype(s) we need
include_once( "ezxmlrpc/classes/ezxmlrpcstring.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcint.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcdouble.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcarray.php" );
include_once( "ezxmlrpc/classes/ezxmlrpcresponse.php" );

// for payment information
include_once( "eztrade/classes/ezcheckout.php" );


$VersionNumber = "Pre release 1.0";

$server = new eZXMLRPCServer( );

// register functions
$server->registerFunction( "version" );
$server->registerFunction( "newOrders", array( new eZXMLRPCString(), new eZXMLRPCString() ) );

// process the server requests
$server->processRequest();

// implemented functions
function version( )
{
    $VersionNumber = $GLOBALS["VersionNumber"];
    return new eZXMLRPCString( "This is eZ trade xml rpc version: $VersionNumber" );
}

//
// Returns all the new orders and sets them to exported.
//
function &newOrders( $args )
{
    $user = new eZUser();
    $user = $user->validateUser( $args[0]->value(), $args[1]->value() );
    
    if ( ( get_class( $user ) == "ezuser" ) and eZPermission::checkPermission( $user, "eZUser", "AdminLogin" ) )
    {
        $ini =& INIFile::globalINI();

        $Language = $ini->read_var( "eZTradeMain", "Language" );
        $locale = new eZLocale( $Language );

        $orders = array();

        // fetch all new orders
        $order = new eZOrder();

        // perform search
        $orderArray =& $order->getNew( );


        foreach ( $orderArray as $orderItem )
        {
            // set the order item to be exported
            $orderItem->setIsExported( true );
            $orderItem->store();

            $datetime =& $orderItem->date();

            $date = $datetime->date(); 
            $time = $datetime->time();

            $user =& $orderItem->user();
            
            if ( $user )
            {
                $shippingAddress =& $orderItem->shippingAddress();
                $shippingCountry =& $shippingAddress->country();

                $billingAddress =& $orderItem->billingAddress();
                $billingCountry =& $billingAddress->country();

                $checkout = new eZCheckout();
                $instance =& $checkout->instance();                
                $paymentMethod = $instance->paymentName( $orderItem->paymentMethod() );

                $itemArray = array();

                $items = $orderItem->items( $OrderType );

                $shippingUser = $orderItem->shippingUser();

                foreach ( $items as $item )
                {
                    $product = $item->product();

                    $optionValues =& $item->optionValues();

                    foreach ( $optionValues as $optionValue )
                    {
                        $optionArray[] = new eZXMLRPCStruct( array( "OptionName" => new eZXMLRPCString( $optionValue->optionName() ),
                                                              "OptionValue" => new eZXMLRPCString( $optionValue->valueName() ) )
                                                              );
                    }
                    
                    
                    $itemArray[] = new eZXMLRPCStruct( array( "ProductID" => new eZXMLRPCInt( $product->id() ),
                                                              "ProductNumber" => new eZXMLRPCInt( $product->productNumber() ),
                                                              "Name" => new eZXMLRPCString( $product->name() ),
                                                              "Count" => new eZXMLRPCInt( $item->count() ),
                                                              "Price" => new eZXMLRPCDouble( ( $item->count() * $product->price() ) ),
                                                              "TotalPrice" => new eZXMLRPCDouble( ($product->price() ) ),
                                                              "Options" => new eZXMLRPCArray( $optionArray )
                                                              ) );
                }

                $orders[] = new eZXMLRPCStruct(
                    array(
                        "OrderID" => new eZXMLRPCInt( $orderItem->id() ),
                        "PaymentMethod" => new eZXMLRPCString( $paymentMethod ),
                        "Date" => new eZXMLRPCString( $locale->format( $date ) ),
                        "Time" => new eZXMLRPCString( $locale->format( $time ) ),
                        "ShippingCharge" => new eZXMLRPCDouble( $orderItem->shippingCharge() ),
                        "ShippingFirstName" => new eZXMLRPCString( $shippingUser->firstName() ),
                        "ShippingLastName" => new eZXMLRPCString( $shippingUser->lastName() ),
                        "ShippingStreet1" => new eZXMLRPCString( $shippingAddress->street1() ),
                        "ShippingStreet2" => new eZXMLRPCString( $shippingAddress->street2() ),
                        "ShippingZip" => new eZXMLRPCString( $shippingAddress->zip() ),
                        "ShippingPlace" => new eZXMLRPCString( $shippingAddress->place() ),
                        "ShippingCountry" => new eZXMLRPCString(  $shippingCountry->name() ),
                        "BillingFirstName" => new eZXMLRPCString( $user->firstName() ),
                        "BillingLastName" => new eZXMLRPCString( $user->lastName()  ),
                        "BillingStreet1" => new eZXMLRPCString( $billingAddress->street1() ),
                        "BillingStreet2" => new eZXMLRPCString( $billingAddress->street2() ),
                        "BillingZip" => new eZXMLRPCString( $billingAddress->zip() ),
                        "BillingPlace" => new eZXMLRPCString( $billingAddress->place() ),
                        "BillingCountry" => new eZXMLRPCString(  $billingCountry->name() ),
                        "OrderLines" => new eZXMLRPCArray( $itemArray )
                        ) );
            }
        }
        
        $tmp = new eZXMLRPCArray( $orders );
    }
    else
    {
        $tmp = new eZXMLRPCResponse( );
        $tmp->setError( 100, "Authorization failed." );
    }

    return $tmp;
}



ob_end_flush();
exit();
?>
