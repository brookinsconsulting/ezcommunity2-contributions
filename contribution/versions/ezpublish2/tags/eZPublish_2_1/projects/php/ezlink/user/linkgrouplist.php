<?
// 
// $Id: linkgrouplist.php,v 1.15 2001/05/09 16:41:25 ce Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 15:02:09 ce>
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

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/ezlist.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZLinkMain", "Language" );
$UserLimit = $ini->read_var( "eZLinkMain", "UserLinkLimit" );
$languageIni = new INIFile( "ezlink/user/intl/". $Language . "/linkgrouplist.php.ini", false );

include_once( "ezlink/classes/ezlinkgroup.php" );
include_once( "ezlink/classes/ezlink.php" );
include_once( "ezlink/classes/ezhit.php" );


$t = new eZTemplate( "ezlink/user/" . $ini->read_var( "eZLinkMain", "TemplateDir" ),
                     "ezlink/user/intl", $Language, "linkgrouplist.php" );

$t->setAllStrings();

$t->set_file( array(
    "link_page_tpl" => "linkgrouplist.tpl"
    ) );

$t->set_block( "link_page_tpl", "group_list_tpl", "group_list" );
$t->set_block( "group_list_tpl", "group_item_tpl", "group_item" );

$t->set_block( "group_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "group_item_tpl", "no_image_tpl", "no_image" );

$t->set_block( "link_page_tpl", "link_list_tpl", "link_list" );
$t->set_block( "link_list_tpl", "link_item_tpl", "link_item" );

$t->set_block( "link_item_tpl", "link_image_item_tpl", "link_image_item" );

$t->set_block( "link_page_tpl", "path_item_tpl", "path_item" );

if ( !$Offset )
    $Offset = 0;

// List all the categories
$linkGroup = new eZLinkGroup();
$linkGroup->get ( $LinkGroupID );

// Path
$pathArray = $linkGroup->path();

$t->set_var( "path_item", "" );
foreach ( $pathArray as $path )
{
    $t->set_var( "group_id", $path[0] );

    $t->set_var( "group_name", $path[1] );
    
    $t->parse( "path_item", "path_item_tpl", true );
}

$linkGroup_array =& $linkGroup->getByParent( $LinkGroupID );

if ( count( $linkGroup_array ) == 0 )
{
    $t->set_var( "categories", "" );
    $t->set_var( "group_list", "" );
}
else
{
    $i=0;
    foreach( $linkGroup_array as $groupItem )
    {
        $t->set_var( "td_class", ( $i % 2 ) == 0 ? "bglight" : "bgdark"  );

        $link_group_id = $groupItem->id();
        $t->set_var( "linkgroup_id", $link_group_id );
        $t->set_var( "linkgroup_title", $groupItem->title() );
        $t->set_var( "linkgroup_description", $groupItem->description() );
        $t->set_var( "linkgroup_parent", $groupItem->parent() );

        $t->set_var( "total_links", $total_sub_links );
        $t->set_var( "new_links", $new_sub_links );
 
        $image = $groupItem->image();

        $t->set_var( "image_item" , "" );
        
        if ( $image )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "CategoryImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "CategoryImageHeight" );

            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();

            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            $t->set_var( "no_image", "" );
            $t->parse( "image_item", "image_item_tpl" );
        }
        else
        {
            $t->parse( "no_image", "no_image_tpl" );
            $t->set_var( "image_item", "" );
        }

        $categories = $languageIni->read_var( "strings", "categories" );

        $t->set_var( "categories", $categories );
        
        $i++;
        $t->parse( "group_item", "group_item_tpl", true );
    }
    $t->parse( "group_list", "group_list_tpl", true );
}


// List all the links in the category
$links = $linkGroup->links( $Offset, $UserLimit );
$linkCount = $linkGroup->linkCount();

if ( count( $links ) == 0 )
{
    if ( $LinkGroupID == 0 )
    {
        $t->set_var( "link_list", "" );
    }
    else
    {
        $t->set_var( "links", "" );
        $t->set_var( "link_list", "" );
    }
    
}
else
{
    $i=0;
    foreach( $links as $linkItem )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {            
            $t->set_var( "td_class", "bgdark" );
        }
        $t->set_var( "link_id", $linkItem->id() );
        $t->set_var( "link_title", $linkItem->title() );
        $t->set_var( "link_description", $linkItem->description() );
        $t->set_var( "link_groupid",$linkItem->linkGroupID() );
        $t->set_var( "link_keywords", $linkItem->keywords() );
        $t->set_var( "link_created", $linkItem->created() );
        $t->set_var( "link_modified", $linkItem->modified() );
        $t->set_var( "link_accepted", $linkItem->accepted() );
        $t->set_var( "link_url", $linkItem->url() );

        $image =& $linkItem->image();

        $t->set_var( "link_image_item", "" );
        
        if ( $image )
        {
            $imageWidth =& $ini->read_var( "eZLinkMain", "LinkImageWidth" );
            $imageHeight =& $ini->read_var( "eZLinkMain", "LinkImageHeight" );
            
            $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );
            
            $imageURL = "/" . $variation->imagePath();
            $imageWidth = $variation->width();
            $imageHeight = $variation->height();
            $imageCaption = $image->caption();
            
            $t->set_var( "image_width", $imageWidth );
            $t->set_var( "image_height", $imageHeight );
            $t->set_var( "image_url", $imageURL );
            $t->set_var( "image_caption", $imageCaption );
            
            $t->parse( "link_image_item", "link_image_item_tpl" );
        }
        
        $hit = new eZHit();
        $hits = $hit->getLinkHits( $linkItem->id() );
        $t->set_var( "link_hits", $hits );

        $links = $languageIni->read_var( "strings", "links" );
        $t->set_var( "links", $links );
        $t->parse( "link_item", "link_item_tpl", true );
        $i++;
    }
    $t->parse( "link_list", "link_list_tpl", true );
}
eZList::drawNavigator( $t, $linkCount, $UserLimit, $Offset, "link_page_tpl" );

$t->set_var( "link_start", $Offset + 1 );
$t->set_var( "link_end", min( $Offset + $UserLimit, $linkCount ) );
$t->set_var( "link_total", $linkCount );

$t->set_var( "linkgroup_id", $LinkGroupID );
                       
$t->pparse( "output", "link_page_tpl" );    

?>
