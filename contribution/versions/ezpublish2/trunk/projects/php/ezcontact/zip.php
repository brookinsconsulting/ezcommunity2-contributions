<?
/*
  Dette scriptet konverterer postadresser fra www.posten.no i semikolonseparert
  format og setter dette inn i MySQL database.
*/

require "ezcontact/dbsettings.php";
mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );

$fd = fopen ("zipcodes.txt", "r");
while (!feof($fd))
{
  $buffer = fgets($fd, 4096);

  $zip_list = split( "\";\"", $buffer, 3 );
  $zip_list[0] = ereg_replace(  "\"", "", $zip_list[0] );
  echo $zip_list[0] . " - " . $zip_list[1] ."<br>";

  $result = mysql_query( "SELECT * FROM Zip WHERE Code='$zip_list[0]'" ) or die( "kunne spørre etter." );

  if ( mysql_num_rows( $result ) == 0 )
  {
    mysql_query( "INSERT INTO Zip SET Code='$zip_list[0]', Place='$zip_list[1]'" ) or die( "kunne ikke sette inn postadresser." );
  }
}
fclose ($fd);

?>
