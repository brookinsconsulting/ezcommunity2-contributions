<?
// 
// $Id: ezquery.php,v 1.9 2001/01/25 13:49:29 jb Exp $
//
// Definition of eZQuery class
//
// Bård Farstad <bf@ez.no>
// Created on: <15-Sep-2000 14:40:06 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZLink
//! The eZQuery class builds SQL queries.
/*!
  En klasse som håndterer SQL queries. Lager query setninger fra
  tekststrenger.
  
  Example code
  \code
  // create a new query and search in the columns Topic and Body
  $query = new eZQuery( array( "Topic", "Body" ), $query );

  // create a select 
  $query_str = "SELECT ID FROM MyTable WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY SomeColumn LIMIT $offset, $limit";

  // do the query
  $this->Database->array_query( $message_array, $query_str );
  \endcode

  \sa eZDB
  
*/

class eZQuery
{
    /*!
      
    */
    function eZQuery( $fields, $queryText )
    {
        $this->Fields = $fields;
        $this->QueryText = $queryText;        
    }

    /*!
      Returns a SQL Where clause.
    */
    function buildQuery( )
    {
        $field = "KeyWords";

        $QueryText = $this->QueryText;
        
        $QueryText = trim( $QueryText );
//          $QueryText = ereg_replace( "[ ]+", " ", $QueryText );
//        $queryArray = explode( " ", $QueryText );
        $QueryText = ereg_replace( '\\\\"', '"', $QueryText );
        preg_match_all( "/((\"[^\"]+\")|([^ ]+))/", $QueryText, $m );
        $queryArray = array();
        foreach( $m[0] as $match )
        {
            $queryArray[] = $match;
        }
        if ( count( $queryArray ) == 0 )
            $queryArray[] = "";

        $normalArray = array();
        $addArray = array();
        $subArray = array();

        foreach( $queryArray as $queryItem )
        {
            switch ( $queryItem[0] )
            {
                case '-':
                {
                    $subArray[] = substr( $queryItem, 1 );
                    break;
                }
                case '+':
                {
                    $addArray[] = substr( $queryItem, 1 );
                    break;
                }
                default:
                {
                    $normalArray[] = $queryItem;
                }
            }
        }

        $arrs = array( array( "array" => "normalArray", "item_delim" => "OR", "delim" => "OR", "compare" => "LIKE" ),
                       array( "array" => "addArray", "item_delim" => "OR", "delim" => "AND", "compare" => "LIKE" ),
                       array( "array" => "subArray", "item_delim" => "AND", "delim" => "AND", "compare" => "NOT LIKE" ) );

        $total_query = "";
        foreach( $arrs as $arr )
        {
            $queryArray = ${$arr["array"]};
            $item_delim = $arr["item_delim"];
            $delim = $arr["delim"];
            $compare = $arr["compare"];
            $query = "";
            for ( $i=0; $i<count($queryArray); $i++ )
            {
                $queryVal = $queryArray[$i];
                if ( strlen( $queryVal ) > 1 and $queryVal[0] == '"' and $queryVal[strlen($queryVal)-1] == '"' )
                {
                    $queryVal = substr( $queryVal, 1, strlen( $queryVal ) - 2 );
                }

                $subquery = "";
                for ( $j=0; $j<count($this->Fields); $j++ )
                {
                    $queryItem = $queryVal;

                    $queryItem = $this->Fields[$j] ." $compare '%" . $queryItem . "%' ";

                    if ( $j > 0 )
                        $queryItem = $item_delim . " " . $queryItem . " ";

                    $subquery .= $queryItem;
                }
                $query .= "( $subquery )";

                if ( count( $queryArray) != ($i+1) )
                    $query .= " $delim ";
            }
            if ( count( $queryArray ) )
            {
                if ( !empty( $total_query ) )
                {
                    $total_query = "$total_query $delim ( $query )";
                }
                else
                {
                    $total_query = "( $query )";
                }
            }
        }
        return $total_query;
    }

//      /*!
      
//      */
//      function buildQuery( )
//      {
//          $field = "KeyWords";

//          $QueryText = $this->QueryText;
        
//          $QueryText = trim( $QueryText );
//          $QueryText = ereg_replace( "[ ]+", " ", $QueryText );
//          $queryArray = explode( " ", $QueryText );

//          $query = "";
//          for ( $i=0; $i<count($queryArray); $i++ )            
//          {
//              for ( $j=0; $j<count($this->Fields); $j++ )
//              {
//                  $queryItem = $queryArray[$i];
//                  if ( $queryItem[0] == "-" )
//                  {
//                      $queryItem = ereg_replace( "^-", "", $queryItem );
//                      $not = "NOT";
//                  }
//                  else
//                      $not = "";
                
//                  $queryItem = $this->Fields[$j] ." " . $not . " LIKE '%" . $queryItem . "%' ";

//                  if ( $j > 0 )                    
//                      $queryItem = "OR " . $queryItem . " ";
                    
            
//                  $query .= $queryItem;
//              }

//              if (  count( $queryArray) != ($i+1) )
//                  $query = " (" . $query . ") AND ";
//              else
//                  $query = " (" . $query . ") ";
            
//          }
//          return $query;
//      }
    
    var $Fields;
    var $QueryText;    
}
?>
