<?php
// 
// $Id: sitemap.php,v 1.5.2.1 2002/04/05 11:41:23 br Exp $
//
// Created on: <18-Oct-2000 15:04:39 bf>
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

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfpgenerator.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );

$t = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                     "ezrfp/admin/intl/", $Language, "sitemap.php" );

$t->setAllStrings();

$t->set_file( "rfp_sitemap_page_tpl", "sitemap.tpl" );

$t->set_block( "rfp_sitemap_page_tpl", "category_value_tpl", "category_value" );
$t->set_block( "rfp_sitemap_page_tpl", "rfp_value_tpl", "rfp_value" );
$t->set_block( "rfp_sitemap_page_tpl", "value_tpl", "value" );

$tree = new eZRfpCategory();
$treeArray =& $tree->getTree();
$user =& eZUser::currentUser();

$t->set_var( "category_value", "" );
$t->set_var( "rfp_value", "" );

foreach ( $treeArray as $catItem )
{
    if ( eZObjectPermission::hasPermission( $catItem[0]->id(), "rfp_category", 'w', $user ) == true  ||
         eZRfpCategory::isOwner( eZUser::currentUser(), $catItem[0]->id() ) )
    {
        $placement = $catItem[1] - 1;
        $option_level = str_repeat( "&nbsp;&nbsp;&nbsp;&nbsp;", $placement );

        $t->set_var( "category_id", $catItem[0]->id() );

        $t->set_var( "option_value", "archive/" . $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );
        $t->set_var( "option_level", $option_level );

        $t->parse( "value", "category_value_tpl", true );    

        $category = new eZRfpCategory( $catItem[0]->id() );

        $rfpList =& $category->rfps( "time", false, true, 0, 50 );

        foreach ( $rfpList as $rfp )
        {
            $t->set_var( "rfp_id", $rfp->id() );
            $t->set_var( "option_level", "&nbsp;&nbsp;&nbsp;&nbsp;" . $option_level );
        
            $t->set_var( "option_value", "preview/".$rfp->id() );
            $t->set_var( "option_name", $rfp->name() );
            $t->parse( "value", "rfp_value_tpl", true );    
        }
        unset ($rfpList);
        unset ($category);

    }
}


$t->pparse( "output", "rfp_sitemap_page_tpl" );

?>
