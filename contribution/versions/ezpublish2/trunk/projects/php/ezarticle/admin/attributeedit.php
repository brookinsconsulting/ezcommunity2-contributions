<?
// 
// $Id: attributeedit.php,v 1.1 2001/06/06 11:30:08 pkej Exp $
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

if( isset( $Cancel ) )
{
    eZHTTPTool::header( "Location: /article/articleedit/attributelist/$ArticleID" );
    exit();
}

$article = new eZArticle( $ArticleID );
$thisType = new eZArticleType( $TypeID );

if( isset( $OK ) )
{
    $count = count( $AttributeID );
    
    for( $i = 0; $i < $count; $i++ )
    {
        $attribute = new eZArticleAttribute( $AttributeID[$i] );
        $attribute->setValue( $article, $AttributeValue[$i] );
    }
    eZHTTPTool::header( "Location: /article/articleedit/attributelist/$ArticleID" );
    exit();
}

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );

$t = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                     "ezarticle/admin/intl/", $Language, "attribute.php" );

$t->setAllStrings();

$t->set_file( array(
    "arttribute_edit_page_tpl" => "attributeedit.tpl"
    ) );

$t->set_block( "arttribute_edit_page_tpl", "type_list_tpl", "type_list" );
$t->set_block( "type_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "arttribute_edit_page_tpl", "no_types_item_tpl", "no_types_item" );

$t->set_block( "arttribute_edit_page_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "attribute_item_tpl", "attribute_item" );
$t->set_block( "arttribute_edit_page_tpl", "no_attributes_item_tpl", "no_attributes_item" );
$t->set_block( "arttribute_edit_page_tpl", "no_selected_type_item_tpl", "no_selected_type_item" );

$ActionValue = "insert";

$types =& $thisType->getAll();

$typeCount = count( $types );

if( $thisType->id() > 0 )
{
    $attributes =& $thisType->attributes();
    
    $attributeCount = count( $attributes );
    
    if( $attributeCount > 0 )
    {
        foreach( $attributes as $attribute )
        {
            $t->set_var( "attribute_id", $attribute->id() );
            $t->set_var( "attribute_name", $attribute->name() );
            $t->set_var( "attribute_value", $attribute->value( $article ) );
            $t->parse( "attribute_item", "attribute_item_tpl", true );
        }


        $t->set_var( "no_attributes_item", "" );
        $t->set_var( "no_selected_type_item", "" );
        $t->parse( "attribute_list", "attribute_list_tpl" );
    }
    else
    {
        $t->set_var( "attribute_list", "" );
        $t->set_var( "no_selected_type_item", "" );
        $t->parse( "no_attributes_item", "no_attributes_item_tpl" );
    }
}
else
{
        $t->set_var( "attribute_list", "" );
        $t->set_var( "no_attributes_item", "" );
        $t->parse( "no_selected_type_item", "no_selected_type_item_tpl" );
}

$t->set_var( "selected", "" );
if( $typeCount > 0 )
{
    foreach( $types as $type )
    {
        $t->set_var( "type_id", $type->id() );
        $t->set_var( "type_name", $type->name() );
        
        if( $type->id() == $TypeID )
        {
            $t->set_var( "selected", "selected" );
        }
        
        $t->parse( "type_item", "type_item_tpl", true );
    }


    $t->parse( "type_list", "type_list_tpl" );
    $t->set_var( "no_types_item", "" );
}
else
{
    $t->parse( "no_types_item", "no_types_item_tpl" );
    $t->set_var( "type_list", "" );
}

if( $Action == "new" )
{
}

if( $Action == "edit" )
{
}

$t->set_var( "this_type_id", $thisType->id() );
$t->set_var( "action_value", $ActionValue );
$t->set_var( "article_name", $article->name() );
$t->set_var( "article_id", $article->id() );

$t->set_var( "site_style", $SiteStyle );

$t->pparse( "output", "arttribute_edit_page_tpl" );

?>
