<?
// 
// $Id: ezlocale.php,v 1.15 2001/01/06 16:45:40 bf Exp $
//
// Definition of eZLocale class
//
// Bård Farstad <bf@ez.no>
// Created on: <07-Sep-2000 14:33:48 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//


//!! eZCommon
//! The eZLocale class provides locale functions.
/*!
  eZLocale handles locale information and formats time, date, and currency
  information to the locale format.
<p>
  The following characters are regognized in the date/time format.
<pre>
a - "am" or "pm" 
A - "AM" or "PM" 
d - day of the month, 2 digits with leading zeros; i.e. "01" to "31" 
D - day of the week, textual, 3 letters; i.e. "Fri" 
F - month, textual, long; i.e. "January" 
g - hour, 12-hour format without leading zeros; i.e. "1" to "12" 
G - hour, 24-hour format without leading zeros; i.e. "0" to "23" 
h - hour, 12-hour format; i.e. "01" to "12" 
H - hour, 24-hour format; i.e. "00" to "23" 
i - minutes; i.e. "00" to "59" 
I (capital i) - "1" if Daylight Savings Time, "0" otherwise. 
j - day of the month without leading zeros; i.e. "1" to "31" 
l (lowercase 'L') - day of the week, textual, long; i.e. "Friday" 
L - boolean for whether it is a leap year; i.e. "0" or "1" 
m - month; i.e. "01" to "12" 
M - month, textual, 3 letters; i.e. "Jan" 
n - month without leading zeros; i.e. "1" to "12" 
s - seconds; i.e. "00" to "59" 
t - number of days in the given month; i.e. "28" to "31" 
T - Timezone setting of this machine; i.e. "MDT" 
U - seconds since the epoch 
w - day of the week, numeric, i.e. "0" (Sunday) to "6" (Saturday) 
Y - year, 4 digits; i.e. "1999" 
y - year, 2 digits; i.e. "99" 
z - day of the year; i.e. "0" to "365" 
Z - timezone offset in seconds (i.e. "-43200" to "43200")
</pre>

Example:

\code
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

\endcode

\sa eZDate eZDateTime eZTime eZCurrency eZNumber
*/

include_once( "classes/INIFile.php" );

class eZLocale
{
    /*!
      Constructs a new eZLocale object. If an ISO code is given as
      an argument the regional file for that language is used. Otherwise
      the default regional settings are used.
    */
    function eZLocale( $iso="" )
    {
        $ini =& $GLOBALS["GlobalSiteIni"];

//          $ini = new INIFile( "site.ini", false );
        
        if ( file_exists( "classes/locale/" . $iso . ".ini" ) )
        {
            $localeIni = new INIFile( "classes/locale/" . $iso . ".ini", false );
        }
        else
        {
            $localeIni = new INIFile( "classes/locale/en_GB.ini", false );
        }

        $this->CurrencySymbol =& $localeIni->read_var( "RegionalSettings", "CurrencySymbol" );
        $this->DecimalSymbol =& $localeIni->read_var( "RegionalSettings", "DecimalSymbol" );
        $this->ThousandsSymbol =& $localeIni->read_var( "RegionalSettings", "ThousandsSymbol" );
        $this->FractDigits =& $localeIni->read_var( "RegionalSettings", "FractDigits" );

        $this->PositivePrefixCurrencySymbol =& $localeIni->read_var( "RegionalSettings", "PositivePrefixCurrencySymbol" );
        $this->NegativePrefixCurrencySymbol =& $localeIni->read_var( "RegionalSettings", "NegativePrefixCurrencySymbol" );
        
        $this->TimeFormat =& $localeIni->read_var( "RegionalSettings", "TimeFormat" );
        $this->DateFormat =& $localeIni->read_var( "RegionalSettings", "DateFormat" );
        $this->ShortDateFormat =& $localeIni->read_var( "RegionalSettings", "ShortDateFormat" );

    }

    /*!
      Returns a nicely formatted string. This function automatically finds
      the appropriate format to use based on locale information and the type
      of object passed as an argument.

      If isShort is set to false then the long version of the string is used,
      if it exists.
    */
    function &format( &$obj, $isShort=true )
    {
        $returnString = "<b>Locale error</b>: object or type not supported.";

        // TODO: implement more options for the date and time format.
        switch ( get_class( $obj ) )
        {
            case "ezdatetime" :
            {
                // Date
                $date = $this->DateFormat;

                // d - day of the month, 2 digits with leading zeros; i.e. "01" to "31"
                
                $date =& str_replace( "%d", "" . $obj->day() . "", $date );
                     
                // m - month; i.e. "01" to "12" 
                $date =& str_replace( "%m", "" . $obj->month(), $date );

                // Y - year, 4 digits; i.e. "1999"
                $date =& str_replace( "%Y", "" . $obj->year(), $date );

                // Time
                $time = $this->TimeFormat;
                if ( $obj->hour()  < 10 )
                    $hour = "0" . $obj->hour();
                else
                    $hour =  $obj->hour();
                
                // H - hour, 24-hour format; i.e. "00" to "23"
                $time =& str_replace( "%H", "" . $hour . "", $time );

                if ( $obj->minute()  < 10 )
                    $minute = "0" . $obj->minute();
                else
                    $minute = $obj->minute();
                
                // i - minutes; i.e. "00" to "59"
                $time =& str_replace( "%i", "" . $minute . "", $time );

                if ( $obj->second()  < 10 )
                    $second = "0" . $obj->second();
                else
                    $second =  $obj->second();
                    
                // s - seconds; i.e. "00" to "59"
                $time = str_replace( "%s", "" . $second . "", $time );

                $returnString = $date . " " . $time;

                break;

            }
            
            case "ezdate" :
            {
                $date = $this->DateFormat;

                // d - day of the month, 2 digits with leading zeros; i.e. "01" to "31" 
                $date =& str_replace( "%d", "" . $obj->day() . "", $date );
                
                // m - month; i.e. "01" to "12" 
                $date =& str_replace( "%m", "" . $obj->month(), $date );

                // Y - year, 4 digits; i.e. "1999"
                $date =& str_replace( "%Y", "" . $obj->year(), $date );
                
                $returnString =& $date;
                break;
            }
            case "eztime" :
            {
                $time = $this->TimeFormat;
                
                // H - hour, 24-hour format; i.e. "00" to "23"
                $time =& str_replace( "%H", "" . $obj->hour() . "", $time );
                
                // i - minutes; i.e. "00" to "59"
                $time =& str_replace( "%i", "" . $obj->minute() . "", $time );

                // s - seconds; i.e. "00" to "59"
                $time =& str_replace( "%s", "" . $obj->second() . "", $time );                                

                $returnString =& $time;
                break;
            }
            case "ezcurrency" :
            {
                $value = $obj->value();

                $valueArray =& explode( ".", $value );
                $fracts = $valueArray[1] . "<br>";
                settype( $fracts, "integer" );
                $integerValue =& $valueArray[0];          

                $revInteger =& strrev( $integerValue );                
                $revInteger =& ereg_replace( "([0-9]{3})", "\\1$this->ThousandsSymbol", $revInteger );
                $integerValue =& strrev( $revInteger );

                // remove leading .
                if ( $integerValue[0] == "$this->ThousandsSymbol" )                    
                    $integerValue = ereg_replace( "^.(.*)", "\\1", $integerValue );

                if ( $fracts < 10 )
                    $fracts = "0" . $fracts;
                
                $value = $integerValue . $this->DecimalSymbol . $fracts;

                if ( $obj->isNegative )
                {
                    if ( $this->NegativePrefixCurrencySymbol == "yes" )
                    {
                        $value = "- " . $this->CurrencySymbol . " " . $value;
                    }
                    else
                    {
                        $value = "- " . $value . " " . $this->CurrencySymbol;
                    }
                }
                else
                {
                    if ( $this->PositivePrefixCurrencySymbol == "yes" )
                    {
                        $value = $this->CurrencySymbol . " " . $value;
                    }
                    else
                    {
                        $value = $value . " " . $this->CurrencySymbol;
                    }                    
                }
                
                $returnString = $value;
                break;
            }
        }
        return $returnString;
    }

    var $PositivePrefixCurrencySymbol;
    var $NegativePrefixCurrencySymbol;

    var $CurrencySymbol;
    var $DecimalSymbol;
    var $ThousandsSymbol;
    var $FractDigits;
    var $TimeFormat;
    var $DateFormat;
    var $ShortDateFormat;
}


?>
