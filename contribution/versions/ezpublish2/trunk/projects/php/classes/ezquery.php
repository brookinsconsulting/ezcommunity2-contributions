<?
// 
// $Id: ezquery.php,v 1.1 2000/10/02 16:37:56 pkej-cvs Exp $
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
            
        $this->URLArray = explode( "/", $REQUEST_URI );
        $this->QueryArray = explode( "&", $QUERY_STRING );
        $this->count = count( $this->QueryArray );
        $this->pruneQueryArray();
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
        $newQueryArray = array();
        $tempQueryArray = $this->QueryArray;
        $currentQuery;
        
        for( $i = $this->count; $i > 0; $i-- )
        { 
            $currentQuery = $tempQueryArray[$i];
            
            for( $j = $this->count; $j > $i; $j-- )
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
        for( $i = 0; $i < $this->count; $i++ )
        {
            $arrayItem = $tempQueryArray[$i];

            if( ereg( $regexp,  $arrayItem ) )
            {
                $returnArray[] = $arrayItem;
            }
        }        
    }
    
    

    /*!
        This function returns a fully formed query string for use in a url.
        
        /return
            Returns the new query string.
     */
    function createQueryString()
    {
        $returnString;
        
        foreach( $this->QueryArray as $query )
        {
            $returnString = $returnString . "&" . $query;
        }
        
        return $returnString;
    }
    
    
    
    var $QueryArray;
    var $URLArray;
    var $NewQuery;
    var $count;
};
