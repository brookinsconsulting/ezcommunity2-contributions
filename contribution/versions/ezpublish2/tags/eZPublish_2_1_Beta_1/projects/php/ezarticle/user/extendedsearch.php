<?php
// 
// $Id: extendedsearch.php,v 1.1 2001/03/29 14:09:36 jb Exp $
//
// Jan Borsodi <jb@ez.no>
// Created on: <29-Mar-2001 11:15:24 amos>
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "extendedsearch.php" );

$t->setAllStrings();

$t->set_file( "extended_search_tpl", "extendedsearch.tpl" );

$t->set_block( "extended_search_tpl", "search_item_tpl", "search_item" );
$t->set_block( "search_item_tpl", "category_item_tpl", "category_item" );

$cat = new eZArticleCategory();
$categories =& $cat->getTree();
$t->set_var( "category_item", "" );
foreach( $categories as $category )
{
    $t->set_var( "category_id", $category[0]->id() );
    $t->set_var( "category_name", $category[0]->name() );
    $t->set_var( "category_level", str_repeat( "&nbsp;", $category[1] ) );
    $t->parse( "category_item", "category_item_tpl", true );
}

$t->parse( "search_item", "search_item_tpl" );

$t->pparse( "output", "extended_search_tpl" );

?>
