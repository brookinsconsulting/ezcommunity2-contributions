<?php

class eZRegional
{
  var $Id;           // Id'en for dette landet
  var $Country;      // Navn på land vi jobber med
  var $Currency;     // Symbol for valuta (eks. $ )
  var $Thousand;     // Symbol for hver tusen
  var $Decimals;     // Antall desimaler
  var $Decimalsign;  // Tegn som angir desimal
  var $Date;     // Formatet datoen skal oppgis på (i php's date() format)
  var $Time;     // Formatet tiden skal oppgis i (i php's date() format

  // Konstruktør som setter land vi jobber med
  function eZRegional( $code = "no" )
    {
      $this->getValues( $code );
    }

  // Setter land vi jobber med
  function setIso( $code )
    {
      $this->getValues( $code );
    }

  // Konverterer en timestamp til det aktuelle lands tidsformat
  function convertTime( $tid )
    {
      $stamp = mktime( substr( $tid, 8, 2 ), substr( $tid, 10, 2 ), substr( $tid, 12, 2 ), substr( $tid, 4, 2 ), substr( $tid, 6, 2 ), substr( $tid, 0, 4 ) );
      return date( $this->Time, $stamp );
    }

  // Konverterer en timestamp til det aktuelle lands datoformat
  function convertDate( $dato )
    {
      $stamp = mktime( substr( $dato, 8, 2 ), substr( $dato, 10, 2 ), substr( $dato, 12, 2 ), substr( $dato, 4, 2 ), substr( $dato, 6, 2 ), substr( $dato, 0, 4 ) );
      return date( $this->Date, $stamp );
    }

  // Legger til/fjerner desimaler for å få det på riktig format
  function getCurrency( $tall )
    {
      return $this->$Currency . " " . $this->getThousands( $tall );
    }

  // Legger til tegn for hvert tredje siffer
  function getThousands( $tall )
    {
      return number_format( $tall, $this->Decimals, $this->Decimalsign, $this->Thousand );
    }

  // henter info fra databasen og legger i globale variabler
  function getValues( $code )
    {
      $result = mysql_query( "SELECT * FROM eZPublish_RegionalTable WHERE Code='$code'" ) or die( "Feil landskode!" );
      $this->Id = mysql_result( $result, 0, "Id" );
      $this->Country = mysql_result( $result, 0, "Country" ); 
      $this->Currency = mysql_result( $result, 0, "Currency" ); 
      $this->Thousand = mysql_result( $result, 0, "Thousand" ); 
      $this->Decimals = mysql_result( $result, 0, "Decimals" );
      $this->Decimalsign = mysql_result( $result, 0, "Decimalsign" ); 
      $this->Date = mysql_result( $result, 0, "Date" ); 
      $this->Time = mysql_result( $result, 0, "Time" ); 
    }
}

?>
