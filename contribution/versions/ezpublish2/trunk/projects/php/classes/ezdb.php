<?
/*!
    $Id: ezdb.php,v 1.2 2000/08/22 09:35:02 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:01:15 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/

//  /*!
//    openDB() : opens the database for queries
//  */
//  function openDB()
//  {
//      global $HOST;
//      global $USER;
//      global $PWD;
//      global $DB;
    
//      mysql_pconnect($HOST,$USER,$PWD);
//      mysql_select_db($DB);
//  }

class eZDB
{
    /*!
      Constructor.
    */
    function eZDB( $server, $db, $user, $pwd )
    {
        $this->Server = $server;
        $this->DB = $db;
        $this->User = $user;
        $this->Password = $pwd;

        mysql_pconnect( $this->Server, $this->User, $this->Password );
        
        mysql_select_db( $this->DB );
    }

    /*
      Execute a query on the global MySQL database link.  If it returns an error,
      the script is halted and the attempted SQL query and MySQL error message are printed.
    */
    function query($sql)
    {
        $r = mysql_query($sql);
  
        if ($r)
            return $r;
                            
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
        $r = query($sql);

        $offset = count( $array );
        if ( count( $r ) > 0 )
        { 
            for($i = 0; $i < mysql_num_rows($r); $i++)
                $array[$i + $offset] = mysql_fetch_array($r);
        }
    }

    // Member variables:
    
    var $Server;
    var $DB;
    var $User;
    var $Password;
}

?>
