<?
include_once( "classes/ezdatetime.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezcurrency.php" );

$items =& $cart->items();

foreach ( $items as $item )
{
    $product =& $item->product();
    $mainCategory =& $product->categoryDefinition();

    $options =& $item->optionValues();

    if ( count ( $options ) > 0 )
    {
        $optionValue = $options[0]->optionValue();
        $remoteID = $optionValue->remoteID();
        $price = $optionValue->price();

        if ( !$remoteID )
        {
            $remoteID = $product->remoteID();
            $price = $product->price();
        }
    }
    else
    {
        $remoteID = $product->remoteID();
        $price = $product->price();
    }

    $numberArray = explode( "-", $remoteID );

    if ( strlen( $numberArray[1] ) == 1 )
        $numberArray[1] = "000" . $numberArray[1];

    if ( strlen( $numberArray[1] ) == 2 )
        $numberArray[1] = "00" . $numberArray[1];

    if ( strlen( $numberArray[1] ) == 3 )
        $numberArray[1] = "0" . $numberArray[1];
        
    $date = new eZDateTime();

    $day = $date->addZero( $date->day() );
    $mon = $date->addZero( $date->month() );

    $year = substr( $date->year(), 2, 2 );

    $priceExVAT = number_format( $product->priceExVAT( $price ), 2 );
    $insert = $mainCategory->remoteID() . $numberArray[1] . $day . "." . $mon . "." . $year . "B    " . $price . "    " . $priceExVAT . "WS\n";

    $file = fopen( "export", "a" );
    fwrite( $file, $insert );
    fclose( $file );
}
?>
