<h1>Locale test bench </h1>
<?
include_once( "classes/ezdate.php" );
include_once( "classes/ezcurrency.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/eztime.php" );

$locale = new eZLocale( "no_NO" );

$date = new eZDate( 2000, 9, 2 );

$date2 = new eZDate( );
$date2->setMySQLDate( "2000-12-02" );

$time = new eZTime( 12, 2, 23 );

$currency = new eZCurrency( 4333222111.998877 );

print( "Norwegian<br>" );
print( "Locallized date: " . $locale->format( $date ) . "<br>" );
print( "Locallized date: " . $locale->format( $date, false ) . "<br>" );
print( "Locallized date: " . $locale->format( $date2 ) . "<br>" );
print( "Locallized time: " . $locale->format( $time ) . "<br>" );
print( "Locallized currency: " . $locale->format( $currency ) . "<br>" );

$time->setMySQLTime( "13:37:12" );

$locale = new eZLocale( );

print( "UK English<br>" );
print( "Locallized date: " . $locale->format( $date ) . "<br>" );
print( "Locallized date: " . $locale->format( $date, false ) . "<br>" );
print( "Locallized time: " . $locale->format( $time ) . "<br>" );
print( "Locallized currency: " . $locale->format( $currency ) . "<br>" );

?>
