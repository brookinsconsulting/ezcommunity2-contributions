<?php
// 
// $Id: ratinglist.php,v 1.1 2001/10/31 12:25:19 bf Exp $
//
// Created on: <30-Oct-2001 14:27:55 bf>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "classes/ezlist.php" );

include_once( "ezarticle/classes/ezarticlerate.php" );

$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "ratinglist.php" );

$t->setAllStrings();

$t->set_file( "rating_list_page_tpl", "ratinglist.tpl" );
$t->set_block( "rating_list_page_tpl", "article_rate_list_tpl", "article_rate_list" );
$t->set_block( "article_rate_list_tpl", "article_rate_item_tpl", "article_rate_item" );


$ListLimit = 50;
$articleCount = eZArticleRate::ratedArticlesCount();
$ratedArticles = eZArticleRate::ratedArticles( $Offset, $ListLimit, $SortMode );

$db =& eZDB::globalDatabase();

$i=0;
foreach ( $ratedArticles as $rate )
{
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_class", "bgdark" );
    }

    $t->set_var( "article_name", $rate[$db->fieldName( "ArticleName" )] );
    $t->set_var( "avg_rate", $rate[$db->fieldName( "AverageRate" )] );
    $t->set_var( "max_rate", $rate[$db->fieldName( "MaxRate" )] );
    $t->set_var( "min_rate", $rate[$db->fieldName( "MinRate" )] );
    $t->set_var( "rate_count", $rate[$db->fieldName( "RateCount" )] );
    $t->parse( "article_rate_item", "article_rate_item_tpl", true );
    $i++;
}

$t->parse( "article_rate_list", "article_rate_list_tpl" );

$t->set_var( "sort_mode", $SortMode );
eZList::drawNavigator( $t, $articleCount, $ListLimit, $Offset, "rating_list_page_tpl" );

$t->pparse( "output", "rating_list_page_tpl" );

?>
