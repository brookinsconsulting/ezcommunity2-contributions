<?
/*!
    $Id: ezdb.php,v 1.5 2000/09/12 11:41:22 bf-cvs Exp $

    Author: Bård Farstad <bf@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:01:15 bf>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

//!! eZCommon
//! The eZDB class provides database functions.
/*!
  The eZDB class hanles database connections and is a wrapper
  to query functions.

  
*/


class eZDB
{
    /*!
      Constructs a new eZDB object, connects to the database and
      selects the desired table.

      The eZDB constructor takes a .ini file as an argumen.
      The second argument defines under what category in the .ini
      file the database information is located.
    */
    function eZDB( $iniFile, $category )
    {
        include_once( "classes/INIFile.php" );
        
        $ini = new INIFile( "site.ini" );
        
        $this->Server = $ini->read_var( "eZTradeMain", "Server" );
        $this->DB = $ini->read_var( "eZTradeMain", "Database" );
        $this->User = $ini->read_var( "eZTradeMain", "User" );
        $this->Password = $ini->read_var( "eZTradeMain", "Password" );
        
        
        mysql_pconnect( $this->Server, $this->User, $this->Password )
            or warn( "Error: could not connect to the database." );
        
        mysql_select_db( $this->DB )
            or warn( "Error: could not connect to the database." );;
    }

    /*
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function query($sql)
    {
        $result = mysql_query($sql);
  
        if ( $result )
            return $result;
                            
        echo "<code>" . htmlentities($sql) . "</code><br>\n<b>" . htmlentities(mysql_error()) . "</b>\n" ;
        exit()					;
    }

    /*
      Executes a SELECT query that returns multiple rows and puts the results into the passed
      array as an indexed associative array.  The array is cleared first.  The results start with
      the array start at 0, and the number of results can be found with the count() function.
    */
    function array_query(&$array, $sql)
    {
        $array = array();
        $r = query($sql);

        if ( count( $r ) > 0 )
        { 
            for($i = 0; $i < mysql_num_rows($r); $i++)
                $array[$i] = mysql_fetch_array($r);
        }
    }

    /*!
      Differs from the above function only by not creating av empty array,
      but simply appends to the array passed as an argument.
     */    
    function array_query_append(&$array, $sql)
    {
        $result = query($sql);

        $offset = count( $array );
        if ( count( $result ) > 0 )
        { 
            for($i = 0; $i < mysql_num_rows($result); $i++)
                $array[$i + $offset] = mysql_fetch_array($result);
        }
    }

    // Member variables:
    
    var $Server;
    var $DB;
    var $User;
    var $Password;
}

?>
