<?
// 
// $Id: categorylist.php,v 1.16 2000/10/17 14:16:49 ce-cvs Exp $
//
// Definition of || class
//
// <real-name> <<mail-name>>
// Created on: <17-Oct-2000 13:50:26 ce>
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
$ini = new INIFile( "site.ini" );

$DOC_ROOT = $ini->read_var( "eZForumMain", "DocumentRoot" );
$Language = $ini->read_var( "eZForumMain", "Language" );

include_once( "classes/eztemplate.php" );
include_once( "classes/ezdb.php" );
include_once( "ezforum/classes/ezforumcategory.php" );

$t = new eZTemplate( "ezforum/" . $ini->read_var( "eZForumMain", "TemplateDir" ),
                     "ezforum/intl", $Language, "categorylist.php" );
$t->setAllStrings();

$t->set_file( Array( "categorylist_tpl" => "categorylist.tpl" ) );

$t->set_block( "categorylist_tpl", "category_tpl", "category" );

$category = new eZForumCategory();
$categories = $category->getAllCategories();
if ( !$categories )
{
    $ini = new INIFile( "ezforum/intl/" . $Language . "/categorylist.php.ini", false );
    $noitem =  $ini->read_var( "strings", "noitem" );

    $t->set_var( "category", $noitem );
}
else
{
    $i=0;
    foreach( $categories as $categoryItem )
        {
            if ( ( $i %2 ) == 0 )
                $t->set_var( "td_class", "bgdark" );
            else
                $t->set_var( "td_class", "bglight" );

            $t->set_var("id", $categoryItem->id() );
            $t->set_var("name", $categoryItem->name() );
            $t->set_var("description", $categoryItem->description() );
            $i++;
    
            $t->parse( "category", "category_tpl", true);
        }
} 

$t->pparse( "output", "categorylist_tpl" );
?>
