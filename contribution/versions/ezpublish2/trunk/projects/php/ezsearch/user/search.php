<?php
// 
// $Id: search.php,v 1.1 2001/06/08 11:49:00 bf Exp $
//
// Bård Farstad <bf@ez.no>
// Created on: <08-Jun-2001 13:10:36 bf>
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

include_once( "classes/ezhttptool.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZSearchMain", "Language" );
$SearchModules = $ini->read_var( "eZSearchMain", "SearchModules" );

$moduleArray = explode( ";", $SearchModules );

$t = new eZTemplate( "ezsearch/user/" . $ini->read_var( "eZSearchMain", "TemplateDir" ),
                     "ezsearch/user/intl/", $Language, "search.php" );



$t->set_file( "search_tpl", "search.tpl" );

$t->set_block( "search_tpl", "search_type_tpl", "search_type" );
$t->set_block( "search_type_tpl", "search_item_tpl", "search_item" );


$t->setAllStrings();

$Limit = 10;

$t->set_var( "search_text", $SearchText );

foreach ( $moduleArray as $module )
{
    $module = strtolower( $module );

    if ( file_exists( "$module/user/searchsupplier.php" ) )
    {
        include( "$module/user/searchsupplier.php" );

        $t->set_var( "search_item", "" );
        $t->set_var( "module_name", $ModuleName );
        $i = 0;
        foreach ( $SearchResult as $res )
        {
            if ( ( $i % 2 ) == 0 )
                $t->set_var( "td_class", "bglight" );
            else
                $t->set_var( "td_class", "bgdark" );
            
            $t->set_var( "search_link", $DetailViewPath . $res->id() );
            $t->set_var( "search_name", $res->name() );
            
            $t->parse( "search_item", "search_item_tpl", true );
            $i++;
        }

        $t->set_var( "search_more_link", $DetailedSearchPath ."?" . $DetailedSearchVariable . "=". urlencode( $SearchText ) );

        $t->set_var( "search_count", $SearchCount );
        $t->set_var( "icon_src", $IconPath );

        $t->parse( "search_type", "search_type_tpl", true );
    }
}

$t->pparse( "output", "search_tpl" );


?>
