<?php
// 
// $Id: menurfpview.php,v 1.3 2001/09/14 08:28:59 ce Exp $
//
// Created on: <18-Oct-2000 16:34:51 bf>
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

include_once( "classes/ezhttptool.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );
include_once( "ezmail/classes/ezmail.php" );

if ( !isset( $CategoryID ) )
    $CategoryID = eZRfp::categoryDefinitionStatic( $RfpID );

$GlobalSectionID = eZRfpCategory::sectionIDStatic( $CategoryID );


$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$ForceCategoryDefinition = $ini->read_var( "eZRfpMain", "ForceCategoryDefinition" );
$CapitalizeHeadlines = $ini->read_var( "eZRfpMain", "CapitalizeHeadlines" );
$TemplateDir = $ini->read_var( "eZRfpMain", "TemplateDir" );

if ( $ForceCategoryDefinition == "enabled" )
{
    $CategoryID = eZRfp::categoryDefinitionStatic( $RfpID );
}

$t = new eZTemplate( "ezrfp/user/" . $TemplateDir,
                     "ezrfp/user/intl/", $Language, "rfpview.php" );

$t->setAllStrings();

$StaticPage = false;
if ( $url_array[2] == "static" || $url_array[2] == "rfpstatic"  )
{
    $StaticPage = true;
}


// override template for the current category
$override = "_override_$CategoryID";

if ( $StaticPage == true )
{
    if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfpstatic" . $override  . ".tpl" ) )
        $t->set_file( "rfp_view_page_tpl", "rfpstatic" . $override  . ".tpl"  );
    else
        $t->set_file( "rfp_view_page_tpl", "rfpstatic.tpl"  );
}
else
{
    if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    {
            $t->set_file( "rfp_view_page_tpl", "rfpprint.tpl"  );        
    }
    else
    {
        if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfpview" . $override  . ".tpl" ) )
            $t->set_file( "rfp_view_page_tpl", "rfpview" . $override  . ".tpl"  );
        else
            $t->set_file( "rfp_view_page_tpl", "menurfpview.tpl"  );
    }
}

// path
$t->set_block( "rfp_view_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "rfp_view_page_tpl", "rfp_url_item_tpl", "rfp_url_item" );

$t->set_block( "rfp_view_page_tpl", "rfp_header_tpl", "rfp_header" );
$t->set_block( "rfp_view_page_tpl", "rfp_intro_tpl", "rfp_intro" );

$t->set_block( "rfp_view_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_block( "rfp_view_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

$t->set_block( "rfp_view_page_tpl", "page_link_tpl", "page_link" );
$t->set_block( "rfp_view_page_tpl", "current_page_link_tpl", "current_page_link" );
$t->set_block( "rfp_view_page_tpl", "next_page_link_tpl", "next_page_link" );
$t->set_block( "rfp_view_page_tpl", "prev_page_link_tpl", "prev_page_link" );
$t->set_block( "rfp_view_page_tpl", "numbered_page_link_tpl", "numbered_page_link" );
$t->set_block( "rfp_view_page_tpl", "print_page_link_tpl", "print_page_link" );

$t->set_block( "rfp_view_page_tpl", "mail_to_tpl", "mail_to" );
$t->set_block( "rfp_view_page_tpl", "attribute_list_tpl", "attribute_list" );
$t->set_block( "attribute_list_tpl", "type_item_tpl", "type_item" );
$t->set_block( "type_item_tpl", "attribute_item_tpl", "attribute_item" );


if ( $StaticRendering == true )
{
    $t->set_var( "rfp_header", "" );
}
else
{
    $t->parse( "rfp_header", "rfp_header_tpl" );
}

$SiteURL = $ini->read_var( "site", "SiteURL" );

$t->set_var( "rfp_url", $SiteURL . $REQUEST_URI );
$t->set_var( "rfp_url_item", "" );
if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    $t->parse( "rfp_url_item", "rfp_url_item_tpl" );


$rfp = new eZRfp(  );

// check if the rfp exists
if ( $rfp->get( $RfpID ) )
{
    if ( $rfp->isPublished() )
    {
        // published rfp.
    }
    else
    {
        eZHTTPTool::header( "Location: /error/404" );
        exit();
    }

    $categories =& $rfp->categories( false );

    // path
    if ( !in_array( $CategoryID, $categories ) )
    {
        $category = $rfp->categoryDefinition();
    }
    else
    {    
        $category = new eZRfpCategory( $CategoryID );
    }

    $pathArray =& $category->path();
    
    $t->set_var( "path_item", "" );
    foreach ( $pathArray as $path )
    {
        $t->set_var( "category_id", $path[0] );
        
        if ( $CapitalizeHeadlines == "enabled" )
        {
            include_once( "classes/eztexttool.php" );
            $t->set_var( "category_name", eZTextTool::capitalize(  $path[1] ) );
        }
        else
        {
            $t->set_var( "category_name", $path[1] );
        }
        
        $t->parse( "path_item", "path_item_tpl", true );
    }
    

    // override the rfp tags:
    $template = "menu";
    $renderer = new eZRfpRenderer( $rfp, $template );

    if ( $CapitalizeHeadlines == "enabled" )
    {
        include_once( "classes/eztexttool.php" );
        $t->set_var( "rfp_name", eZTextTool::capitalize(  $rfp->name() ) );
    }
    else
    {
        $t->set_var( "rfp_name", $rfp->name() );
    }

    if ( eZMail::validate( $rfp->authorEmail() ) && $rfp->authorEmail() )
    {
        $t->set_var( "author_email", $rfp->authorEmail() );
    }
    else
    {
        $author = $rfp->author();
        $t->set_var( "author_email", $author->email() );
    }
    
    $t->set_var( "author_text", $rfp->authorText() );
    
    $t->set_var( "author_id", $rfp->contentsWriter( false ) );
    
    $categoryDef =& $rfp->categoryDefinition();

    $t->set_var( "category_definition_name", $categoryDef->name() );

    $pageCount = $rfp->pageCount();
    if ( $PageNumber > $pageCount )
        $PageNumber = $pageCount;

    if ( $PageNumber == -1 )
        $rfpContents = $renderer->renderPage( -1 );
    else
        $rfpContents = $renderer->renderPage( $PageNumber -1 );
    
    $t->set_var( "rfp_intro", $rfpContents[0] );

    if ( $PageNumber == 1 )
           $t->parse( "rfp_intro", "rfp_intro_tpl" );
    else
        $t->set_var( "rfp_intro", "" );
        
    $t->set_var( "rfp_body", $rfpContents[1] );

    $t->set_var( "link_text", $rfp->linkText() );

    $t->set_var( "rfp_id", $rfp->id() );

    $locale = new eZLocale();
    $published = $rfp->published();

    $t->set_var( "rfp_created", $locale->format( $published ) );

    // image list

    $usedImages = $renderer->usedImageList();

    $images =& $rfp->images();

    
    {
        $imageNumber = 1;
        $i=0;
        foreach ( $images as $image )
        {
            if ( is_array( $usedImages ) && !in_array(  $imageNumber, $usedImages ) )
            {
                if ( ( $i % 2 ) == 0 )
                {
                    $t->set_var( "td_class", "bglight" );
                }
                else
                {
                    $t->set_var( "td_class", "bgdark" );
                }

                if ( $image->caption() == "" )
                    $t->set_var( "image_caption", "&nbsp;" );
                else
                    $t->set_var( "image_caption", $image->caption() );

            
                $t->set_var( "image_id", $image->id() );
                $t->set_var( "rfp_id", $RfpID );

                $variation =& $image->requestImageVariation( 150, 150 );

                $t->set_var( "image_url", "/" .$variation->imagePath() );
                $t->set_var( "image_width", $variation->width() );
                $t->set_var( "image_height",$variation->height() );

                $t->parse( "image", "image_tpl", true );
                $i++;
            }
            $imageNumber++;
        }

        $t->parse( "image_list", "image_list_tpl", true );
    }
    if ( $i == 0 )
        $t->set_var( "image_list", "" );    

    

}

// set the variables in the mail_to form
if ( !isset( $SendTo ) )
    $SendTo = "";
$t->set_var( "send_to", $SendTo );
if ( !isset( $From ) )
    $From = "";
$t->set_var( "from", $From );

$types = $rfp->types();

$typeCount = count( $types );

$t->set_var( "attribute_item", "" );
$t->set_var( "type_item", "" );
$t->set_var( "attribute_list", "" );

if( $typeCount > 0 )
{
    foreach( $types as $type )
    {
        $attributes = array();
        $attributes = $type->attributes();
        $attributeCount = count( $attributes );
        
        if( $attributeCount > 0 )
        {
            $t->set_var( "type_id", $type->id() );
            $t->set_var( "type_name", $type->name() );
            $t->set_var( "attribute_item", "" );
            foreach( $attributes as $attribute )
            {
                $t->set_var( "attribute_id", $attribute->id() );
                $t->set_var( "attribute_name", $attribute->name() );
                $t->set_var( "attribute_value", nl2br( $attribute->value( $rfp ) ) );
                $t->parse( "attribute_item", "attribute_item_tpl", true );
            }
            $t->parse( "type_item", "type_item_tpl", true );
        }
    }

    $t->parse( "attribute_list", "attribute_list_tpl" );
}



// files
$files = $rfp->files();

if ( count( $files ) > 0 )
{
    $i=0;
    foreach ( $files as $file )
    {
        if ( ( $i % 2 ) == 0 )
        {
            $t->set_var( "td_class", "bglight" );
        }
        else
        {
            $t->set_var( "td_class", "bgdark" );
        }

        $t->set_var( "file_id", $file->id() );
        $t->set_var( "original_file_name", $file->originalFileName() );
        $t->set_var( "file_name", $file->name() );
        $t->set_var( "file_url", $file->name() );
        $t->set_var( "file_description", $file->description() );

        $size = $file->siFileSize();
        $t->set_var( "file_size", $size["size-string"] );
        $t->set_var( "file_unit", $size["unit"] );


        $i++;
        $t->parse( "attached_file", "attached_file_tpl", true );
    }

    $t->parse( "attached_file_list", "attached_file_list_tpl" );
}
else
{
    $t->set_var( "attached_file_list", "" );
}


$t->set_var( "current_page_link", "" );

// page links
if ( $pageCount > 1 && $PageNumber != -1 && ( $PrintableVersion != "enabled" ) )
{
    for ( $i=0; $i<$pageCount; $i++ )
    {
        $t->set_var( "rfp_id", $rfp->id() );
        $t->set_var( "page_number", $i+1 );
        $t->set_var( "category_id", $CategoryID );

        if ( ( $i + 1 )  == $PageNumber )
        {
            $t->parse( "page_link", "current_page_link_tpl", true );
        }
        else
        {
            $t->parse( "page_link", "page_link_tpl", true );            
        }
    }
}
else
{
    $t->set_var( "page_link", "" );
    
}


// non-printable version link
if ( ( $PageNumber == -1 ) && ( $PrintableVersion == "enabled" ) )
{
    $t->parse( "numbered_page_link", "numbered_page_link_tpl" );
}
else
{
    $t->set_var( "numbered_page_link", "" );
}

// printable version link
if ( ( $PrintableVersion != "enabled" ) && ( $StaticRendering != true )  )
{
    $t->parse( "print_page_link", "print_page_link_tpl" );
}
else
{
    $t->set_var( "print_page_link", "" );
}

// previous page link
if ( ( $PageNumber > 1 ) && ( $PrintableVersion != "enabled" ) )
{
    $t->set_var( "prev_page_number", $PageNumber - 1 );    
    $t->parse( "prev_page_link", "prev_page_link_tpl" );
}
else
{
    $t->set_var( "prev_page_link", "" );
}

// next page link
if ( $PageNumber < $pageCount && $PageNumber != -1 && ( $PrintableVersion != "enabled" ) )
{
    $t->set_var( "next_page_number", $PageNumber + 1 );    
    $t->parse( "next_page_link", "next_page_link_tpl" );
}
else
{
    $t->set_var( "next_page_link", "" );
}


// set variables for meta information
$SiteTitleAppend = $rfp->name();
$SiteDescriptionOverride = str_replace( "\"", "", strip_tags( $rfpContents[0] ) );

if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "?>\n";

    $output .= $t->parse( $target, "rfp_view_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "rfp_view_page_tpl" );
}



?>
