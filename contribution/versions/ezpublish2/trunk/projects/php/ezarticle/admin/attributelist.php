<?
// 
// $Id: attributelist.php,v 1.2 2001/06/06 12:24:00 pkej Exp $
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <05-Jun-2001 13:07:24 pkej>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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
include_once( "classes/ezhttptool.php" );

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticleattribute.php" );
include_once( "ezarticle/classes/ezarticletype.php" );
include_once( "ezarticle/classes/ezarticletool.php" );

if( isset( $NewType ) )
{
    eZHTTPTool::header( "Location: /article/articleedit/attributeedit/new/?ArticleID=$ArticleID" );
    exit();
}

$article = new eZArticle( $ArticleID );
$category = $article->categoryDefinition( );
$CategoryID = $category->id();

if( isset( $DeleteSelected ) )
{
    $count = count( $TypeArrayID );

    for( $i = 0; $i < $count; $i++ )
    {
        $type = new eZArticleType( $TypeArrayID[$i] );
        $article->deleteAttributesByType( $type );
    }
    eZArticleTool::deleteCache( $ArticleID, $CategoryID, $CategoryArray );
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "attribute.php" );

$t->setAllStrings();

$t->set_file( array(
    "arttribute_list_page_tpl" => "attributelist.tpl"
    ) );

$t->set_block( "arttribute_list_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "arttribute_list_page_tpl", "no_types_item_tpl", "no_types_item" );


$types = $article->types();

$typeCount = count( $types );

if( $typeCount > 0 )
{
    $i=0;
    foreach( $types as $type )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "type_id", $type->id() );
        $t->set_var( "type_name", $type->name() );
        $t->parse( "type_item", "type_item_tpl", true );
        $i++;
    }
    $t->parse( "type_list", "type_list_tpl" );
    $t->set_var( "no_types_item", "" );
}
else
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
    $t->set_var( "type_list", "" );
}

$t->set_var( "article_name", $article->name() );
$t->set_var( "article_id", $article->id() );

$t->set_var( "site_style", $SiteStyle );

$t->pparse( "output", "arttribute_list_page_tpl" );

?>
