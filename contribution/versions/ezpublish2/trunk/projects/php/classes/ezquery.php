<?
// 
// $Id: ezquery.php,v 1.3 2000/10/02 19:05:32 pkej-cvs Exp $
//
// Definition of eZQuery class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <02-Okt-2000 18:16:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZCommon
//! eZQuery handles query strings.
/*!
    This class handles QUERY_STRINGS and provides some useful functions
    for keeping these strings in good shape.
    
    A query string is on the form:
    
        [module]&[module]
    
    Each module must then on it's own decide how to parse the module part
    of the string.
    
    The module part of the string can be of any form, but we suggest using
    name=key+value[+key+value]+
    
    \code
    \endcode
    
 */
 
class eZQuery
{
    function eZQuery()
    {
        global $QUERY_STRING;
        global $SERVER_NAME;
        global $REQUEST_URI;
            
        $this->URLArray = explode( "/", $REQUEST_URI );
        $this->QueryArray = explode( "&", $QUERY_STRING );
        $this->count = count( $this->QueryArray );
        
        #$this->printQueries( "eZQuery pre prune" );
        
        $this->pruneQueryArray();

        #$this->printQueries( "eZQuery post prune" );        
    }


    
    /*!
        \private
        This function removes all duplicated strings in $this->QueryArray.
     */
    function pruneQueryArray()
    {
        $newQueryArray = array();
        $tempQueryArray = $this->QueryArray;
        $currentQuery;
                
        for( $i = 0; $i < $this->count; $i++ )
        {
            $currentQuery = $tempQueryArray[$i];
            
            for( $j = $i; $j < $this->count; $j++ )
            {
                $arrayItem = $tempQueryArray[$j];
                
                if( $arrayItem == $currentQuery )
                {
                    $tempQueryArray[$j] = '';
                }
            }
            
            if( $currentQuery )
            {
                $newQueryArray[] = $currentQuery;
            }
        }
        
        $this->QueryArray = $newQueryArray;
    }


    
    /*!
        \private
        This function will go through the current query string and remove all but the last
        query matching the regexp sent in. This is nice for removing double sort by queries which
        might appear in a query string due to concatenation.
        
        \in
            $regexp This is the regexp used while removing the duplicates.
        
     */
    function removeRegexpDuplicates( $regexp )
    {
        $this->printQueries( "pre removeRegexpDuplicates" );
        
        $newQueryArray = array();
        $tempQueryArray = $this->QueryArray;
        $currentQuery;
        
        for( $i = $this->count; $i >= 0; $i-- )
        { 
            $currentQuery = $tempQueryArray[$i];
            
            echo "curr: " . $currentQuery . "<br>";
            
#            for( $j = $this->count; $j > 0; $j-- )
            for( $j = 0; $j <= $this->count; $j++ )
            {
                $arrayItem = $tempQueryArray[$j];
                
                if( ereg( $regexp,  $arrayItem ) )
                {
                    $tempQueryArray[$j] = '';
                }
            }
            
            if( $currentQuery )
            {
                $newQueryArray[] = $currentQuery;
            }
        }
        
        $this->QueryArray = $newQueryArray;
        
        $this->printQueries( "post removeRegexpDuplicates" );
    }



    /*!
        This function is used to find a certain group of queries in a
        query string.
        
        \out
            \$returnArray   The array of returned queries
        \return
            Returns true if any queries are found.
     */
    function getQueries( &$returnArray, $regexp )
    {
        #$this->printQueries( "post getQueries" );
        $value = false;
        
        $returnArray = array(); 

        $i = $this->count;
        
        #echo $i . "<br>";
        
        for( $i; $i >= 0; $i-- )
        {
            $arrayItem = $this->QueryArray[$i];
            
            #echo " $i $arrayItem <br>";
            
            if( ereg( $regexp,  $arrayItem ) )
            {
                $returnArray[] = $arrayItem;
                $value = true;
            }
        }   

        #$this->printQueries( "post getQueries" );
        
        return $value;     
    }
    
    

    /*!
        This function returns a fully formed query string for use in a url.
        
        \in
            \$add Add this to the returned string.
        
        \return
            Returns the new query string.
     */
    function createQueryString( $add )
    {
        $returnString = "";
        
        $first = true;
        
        #$this->printQueries( "createQueryString" );
        
        foreach( $this->QueryArray as $query )
        {
            if( $first )
            {
                $returnString = $query;
                $first = false;
            }
            else
            {
                $returnString = $returnString . "&" . $query;
            }
        }
                
        $returnString = $returnString . $add;
        
        return $returnString;
    }
    
    
    
    /*!
        This function will print the current queries in the query array.
        
        \in
            \$prefix A string to prefixi each line with.
     */
    function printQueries( $prefix )
    {
        $this->printCount++;
        
        echo "Printing no " . $this->printCount . " called with $prefix<br><blockquote>";
        
        $i = 0;
        foreach( $this->QueryArray as $arrayItem )
        {
            echo $i . " " . $arrayItem . "<br>";
            $i++;
        }
        echo "</blockquote><br>\n";
        
    }
    
    /*!
        This function returns the $no item in the url path.
        
        \return
            Returns a path-level.
     */
    function getURLPart( $no )
    {
        #echo $no . " " . $this->URLArray[ $no ] . "<br>";
        return $this->URLArray[ $no ];
    }
    
    var $QueryArray;
    var $URLArray;
    var $NewQuery;
    var $count;
    var $printCount = 0;
};
