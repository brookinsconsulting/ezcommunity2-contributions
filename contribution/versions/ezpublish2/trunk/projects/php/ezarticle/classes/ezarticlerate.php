<?php
// 
// $Id: ezarticlerate.php,v 1.2 2001/11/01 09:09:42 bf Exp $
//
// Definition of eZArticleRate class
//
// Created on: <31-Oct-2001 09:03:03 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZArticle
//! eZArticleRate handles ratings on articles.
/*!
  \sa eZArticle
*/

include_once( "classes/ezdb.php" );

class eZArticleRate
{
    /*!
      Constructs a new eZArticleRate object.
    */
    function eZArticleRate()
    {
    }

    /*!
      \static
      Adds a rate to an article. The rate value is checked with the range
      and does not get stored if it's above the MAX level.

      It's checked on IP and cookie if a user has rated before.
      False is returned if the rating was invalid.
    */
    function addRate( $article, $rate )
    {
        $db =& eZDB::globalDatabase();

        $ip = $GLOBALS["REMOTE_ADDR"];
        $articleID = $article->id();
        
        // check if the user has rated before
        $ini =& INIFile::globalINI();

        $ratingCheck = $ini->read_var( "eZArticleMain", "ArticleRatingCheck" );

        $hasRated = false;
        switch ( $ratingCheck )
        {
            case "ip" :
            {
                $db->array_query( $ratedArticles,
                "SELECT ID  FROM eZArticle_ArticleRate WHERE IP='$ip' AND ArticleID='$articleID'" );
                if ( count( $ratedArticles ) > 0 )
                {
                    $hasRated = true;
                }

            }break;

            case "cookie" :
            {
                if ( $GLOBALS["eZArticleRate$articleID"] == "rated" )
                {
                    $hasRated = true;
                }
            }break;

            default :
            {
                $hasRated = false;
            }break;
        }

        if ( $hasRated == false )
        {
            if ( $ratingCheck == "cookie" )
            {
                setcookie ( "eZArticleRate$articleID", "rated", time() + ( 3600 * 24 * 365 ), "/",  "", 0 )
                or print( "Error: could not set cookie." );
            }

            $db->begin( );

            // lock the table
            $db->lock( "eZArticle_ArticleRate" );
            
            $nextID = $db->nextID( "eZArticle_ArticleRate", "ID" );
            
            $ret[] = $db->query( "INSERT INTO eZArticle_ArticleRate ( ID, ArticleID, Rate, IP ) VALUES
                                      ( '$nextID',
                                        '$articleID',
                                        '$rate',
                                        '$ip' )" );
            eZDB::finish( $ret, $db );
        }
        

        return $hasRated;
    }

    /*!
      \static
      Returns the number of articles which has been rated.
    */
    function ratedArticlesCount( )
    {
        $db =& eZDB::globalDatabase();
       
        $db->array_query( $articles,
        "SELECT COUNT( DISTINCT ArticleID ) AS ArticleCount from eZArticle_ArticleRate" );

        return $articles[0][$db->fieldName( "ArticleCount" )];
    }

    /*!
      \static
      Returns the rated articles.
    */
    function ratedArticles( $offs, $limit, $sortMode )
    {
        $db =& eZDB::globalDatabase();

        switch ( $sortMode )
        {
            case "avgrate" :
                $orderBySQL = " ORDER BY AverageRate DESC ";
                break;
            case "maxrate" :
                $orderBySQL = " ORDER BY MaxRate DESC ";
                break;
            case "minrate" :
                $orderBySQL = " ORDER BY MinRate ASC ";
                break;

            case "ratecount" :
                $orderBySQL = " ORDER BY RateCount DESC ";
                break;

            default:
                $orderBySQL = " ORDER BY AverageRate DESC ";
                break;                
        }

        $db->array_query( $articles,
        "SELECT
                Article.Name AS ArticleName,
                AVG( ArticleRate.Rate ) AS AverageRate,
                MAX( ArticleRate.Rate ) AS MaxRate,
                Min( ArticleRate.Rate ) AS MinRate,
                Count(*) AS RateCount
         FROM
                eZArticle_ArticleRate AS ArticleRate,
                eZArticle_Article AS Article
         WHERE
                Article.ID=ArticleRate.ArticleID
         GROUP BY ArticleID
         $orderBySQL",
        array( "Offset" => $offs, "Limit" => $limit ) );

        return $articles;
    }
}

?>
