<?
// 
// $Id: eznewscommand.php,v 1.7 2000/09/28 13:30:18 th-cvs Exp $
//
// Definition of eZNewsCommand class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <20-Sep-2000 13:03:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsCommand handles commands
/*!

    Example URLs:
    \code
    http://www.site.com/news                    - main news page
    http://www.site.com/news/date               - date based pages, ie. watch the state at any given time
    http://www.site.com/news/author             - author based pages, ie. read articles by author
    http://www.site.com/news/##                 - view an article or category based on its ID number
    http://www.site.com/news/path/to/somewhere  - view a category or article based on it's path
    http://www.site.com/news/
    \endcode
 */
 
class eZNewsCommand
{
    function eZNewsCommand()
    {
        global $REQUEST_URI;
        $URLArray = explode( "/", $REQUEST_URI );

        #echo "0 " . $URLArray[0] . "<br>";
        #echo "1 " . $URLArray[1] . "<br>";
        #echo "2 " . $URLArray[2] . "<br>";
        #echo "3 " . $URLArray[3] . "<br>";
        #echo "4 " . $URLArray[4] . "</table></table></table>heheheheheh";

        $this->decodeTopLevel( $URLArray );
        $title_string = ereg_replace('/$', '', $REQUEST_URI);
        $title_string = ereg_replace('^/', '', $title_string);
        $title_string = ereg_replace('^news', '', $title_string);
        $title_string = ereg_replace('/', ' : ', $title_string);
    }
    
    function eZNewsCommand2()
    {
        global $QUERY_STRING;
        global $URLArray;
        global $REQUEST_URI;
        echo $REQUEST_URI . "<br>";
        echo $QUERY_STRING . "<br>";
        echo $URLArray[1] . "<br>";
        $title_string = ereg_replace('/$', '', $REQUEST_URI);
        $title_string = ereg_replace('^/news/', '', $title_string);
        $title_string = ereg_replace('/', ' : ', $title_string);
        echo $title_string . "<br>";
    }
    
    
    function admin()
    {
    }
    
    function decodeTopLevel( $URLArray )
    {
        switch ( $URLArray[2] )
        {
            case "date":
                $this->decodeDate( $URLArray );
                break;
            case "author":
                #$this->decode_author();
                break;
            case "admin":
                $this->admin();
                break;
            default:
                $this->decodeItem( $URLArray );
                break;          
        }
    }
    
    function decodeItem( $URLArray )
    {
        $itemInfo = $URLArray[2];
        
        if( is_numeric( $itemInfo ) )
        {
            include_once( "eznews/classes/eznewsitem.php" );
            include_once( "eznews/classes/eznewsitemtype.php" );
            
            $item = new eZNewsItem( $itemInfo );
            
            $itemType = new eZNewsItemType( $item->itemTypeID() );

            $class = $itemType->eZClass();
            if( !empty( $class ) )
            {
                $viewer = $class . "viewer";
                $path = "eznews/classes/" . strtolower( $viewer ) . ".php";
                if( include_once( $path ) )
                {
                    new $viewer( $item, $URLArray );
                }
                else
                {
                    echo "The viewer for this article doesn't work";
                }
            }            
        }
        else
        {
            include_once( "eznews/classes/eznewsitem.php" );
            include_once( "eznews/classes/eznewsitemtype.php" );
            
            $item = new eZNewsItem();
                     
            $item->getByName( $itemInfo );
            
            $itemType = new eZNewsItemType( $item->itemTypeID() );

            $class = $itemType->eZClass();
            if( !empty( $class ) )
            {
                $viewer = $class . "viewer";
                $path = "eznews/classes/" . strtolower( $viewer ) . ".php";
                if( include_once( $path ) )
                {
                    new $viewer( $item, $URLArray );
                }
                else
                {
                    echo "The viewer for this article doesn't work";
                }
            }
        }
        
    }
    
    /*
        We accept urls on the form:
        <ul>
        <li>/date/YYYY/MM/DD
        <li>/date/YYYY/MM/DD/CategoryPath
        <li>/date/YYYY/MM/DD/HH
        <li>/date/YYYY/MM/DD/HH/CategoryPath
        </ul>
        
        How this function works:
        <ol>
        <li>checking that we have enough for a full date. (Might change
        this to list everything within month, year, etc.)
        <li>now check if we have more info to parse.
        <li>if we don't, then create time limits for search.
        <li>else we need to check if we have more numeric info for the hour...
        <li>create time limits for search.
        </ol>
        
     */
    function decodeDate( $URLArray )
    {

/* 1 */ if( is_numeric( $URLArray[3] ) || is_numeric( $URLArray[4] ) || is_numeric( $URLArray[5] ) )
        {
            include_once("eznews/classes/eznewsitem.php");
            
            $ourDate = $URLArray[3] . $URLArray[4] . $URLArray[5];

/* 2 */     if( empty( $URLArray[6] ) )
            {
/* 3 */         $arguments[] = $ourDate . "000000";
                $arguments[] = $ourDate . "235959";
                $this->SearchLimits[] = sprintf( $this->OrderBy["between_timestamps"], $arguments[0], $arguments[1]);
            }
/* 4 */     else
            {
                if( is_numeric( $URLArray[6] ) )
                {
/* 5 */             $arguments[] = $ourDate . $URLArray[6] . "0000";
                    $arguments[] = $ourDate . $URLArray[6] . "5959";                    
                    $this->SearchLimits[] = sprintf( $this->OrderBy["between_timestamps"], $arguments[0], $arguments[1]);
                }
                else
                {
                    
                }
            }
        }
        else
        {
            die("error handling here: need a numeric value as the second, and consequtive parts of the path<br></table></table>");
        }
    }
    
    function decode_settings()
    {
        switch ( $arguments )
        {
            case "":
                break;
            default:
                break;
        }
    }
    
    var $SQL = array(
        "get_articles" => "SELECT * FROM eZNews_Article, eZNews_Item, eZNews_ItemCategory WHERE eZNews_Article.ItemID = %s AND eZNews_Item.ID = eZNews_Article.ItemID"
        );
    
    var $Limits = array(
        "between_timestamps" => "AND eZNews_Item.CreatedAt > '%s' AND eZNews_Item.CreatedAt < '%s'",
        "in_category" => "AND eZNews_ItemHiearchy.ParentID = %s"
        );

    var $OrderBy = array(
        "none" => "",
        "title" => "ORDER BY Title",
        "author" => "ORDER BY Author",
        "date" => "ORDER BY Date",
        "forward" => "ASC",
        "reverse" => "DESC",
        );

    var $Path = array();
    var $Arguments = array();
    var $SearchLimits = array();
    var $SearchOrder;
};

