<?php
// 
// $Id: sitemap.php,v 1.3 2001/07/19 12:19:21 jakobn Exp $
//
// Created on: <06-Jun-2001 17:05:38 bf>
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
include_once( "ezmail/classes/ezmail.php" );
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlegenerator.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );

$t = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZArticleMain", "TemplateDir" ),
                     "ezarticle/user/intl/", $Language, "sitemap.php" );

$t->setAllStrings();

$t->set_file( "article_sitemap_page_tpl", "sitemap.tpl" );

$t->set_block( "article_sitemap_page_tpl", "category_value_tpl", "category_value" );
$t->set_block( "article_sitemap_page_tpl", "article_value_tpl", "article_value" );
$t->set_block( "article_sitemap_page_tpl", "value_tpl", "value" );

$tree = new eZArticleCategory();
$treeArray =& $tree->getTree( $CategoryID );
$user =& eZUser::currentUser();

$t->set_var( "category_value", "" );
$t->set_var( "article_value", "" );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "article_category", 'r', $user ) == true  ||
         eZArticleCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {    
        $option_level = str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;", $catItem[1] );

        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );
        $t->set_var( "option_level", $option_level );

        $t->parse( "value", "category_value_tpl", true );    

        $category = new eZArticleCategory( $catItem[0]->id() );

        $articleList =& $category->articles( 1, false, true, 0, 50 );

        foreach ( $articleList as $article )
        {
            $t->set_var( "option_level", "&nbsp;&nbsp;&nbsp;&nbsp;" . $option_level );
        
            $t->set_var( "option_value", $article->id() );
            $t->set_var( "option_name", $article->name() );
            $t->parse( "value", "article_value_tpl", true );    
        }
        unset ($articleList);
        unset ($category);

    }
}


$t->pparse( "output", "article_sitemap_page_tpl" );

?>
