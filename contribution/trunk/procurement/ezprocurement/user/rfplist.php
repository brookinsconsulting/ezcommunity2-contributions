<?php
// 
// $Id: rfplist.php,v 1.81.2.7 2003/07/24 11:07:55 br Exp $
//
// Created on: <18-Oct-2000 14:41:37 bf>
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
include_once( "classes/eztexttool.php" );

include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezsitemanager/classes/ezsection.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$ImageDir = $ini->read_var( "eZRfpMain", "ImageDir" );
$CapitalizeHeadlines = $ini->read_var( "eZRfpMain", "CapitalizeHeadlines" );
$DefaultLinkText =  $ini->read_var( "eZRfpMain", "DefaultLinkText" );
$UserListLimit = $ini->read_var( "eZRfpMain", "UserListLimit" );
$GrayScaleImageList = $ini->read_var( "eZRfpMain", "GrayScaleImageList" );
$ForceCategoryDefinition = $ini->read_var( "eZRfpMain", "ForceCategoryDefinition" );
$TemplateDir = $ini->read_var( "eZRfpMain", "TemplateDir" );

$GlobalSectionID = eZRfpCategory::sectionIDStatic( $CategoryID );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$templateDirTmp = $sectionObject->templateStyle();
if ( trim( $templateDirTmp ) != "" )
{
    $TemplateDir = preg_replace( "/(.+)\/.+(\/?)/", "/\\1/$templateDirTmp\\2", $TemplateDir );
}


$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "rfplist.php" );

$t->setAllStrings();

// override template for the current category
$override = "_override_$CategoryID";
// override template for current section
// category override will be prefered
$sectionOverride = "_sectionoverride_$GlobalSectionID";


if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfplist" . $override . ".tpl" ) )
{
    $t->set_file( "rfp_list_page_tpl", "rfplist" . $override  . ".tpl"  );
}
else
{
    if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfplist" . $sectionOverride  . ".tpl" ) )
    {
        $t->set_file( "rfp_list_page_tpl", "rfplist" . $sectionOverride  . ".tpl"  );
    }
    else
    {
        $t->set_file( "rfp_list_page_tpl", "rfplist.tpl"  );
    }
}

$t->set_block( "rfp_list_page_tpl", "header_item_tpl", "header_item" );

// headline
$t->set_block( "header_item_tpl", "latest_headline_tpl", "latest_headline_item" );
$t->set_block( "header_item_tpl", "category_headline_tpl", "category_headline_item" );

// path
$t->set_block( "rfp_list_page_tpl", "path_item_tpl", "path_item" );
$t->set_block( "rfp_list_page_tpl", "rfp_path_header_tpl", "rfp_path_header" );
$t->set_block( "rfp_list_page_tpl", "rfp_path_headers_tpl", "rfp_path_headers" );
$t->set_block( "rfp_list_page_tpl", "rfp_path_headers2_tpl", "rfp_path_headers2" );


// rfp
$t->set_block( "rfp_list_page_tpl", "category_list_tpl", "category_list" );
$t->set_block( "category_list_tpl", "category_item_tpl", "category_item" );

// image
$t->set_block( "rfp_list_page_tpl", "current_image_item_tpl", "current_image_item" );

$t->set_block( "category_item_tpl", "image_item_tpl", "image_item" );
$t->set_block( "category_item_tpl", "no_image_tpl", "no_image" );

// product
$t->set_block( "rfp_list_page_tpl", "rfp_list_tpl", "rfp_list" );
$t->set_block( "rfp_list_tpl", "rfp_item_tpl", "rfp_item" );

$t->set_block( "rfp_item_tpl", "rfp_date_tpl", "rfp_date" );
$t->set_block( "rfp_item_tpl", "rfp_responce_due_date_tpl", "rfp_responce_due_date" );

$t->set_block( "rfp_item_tpl", "headline_with_link_tpl", "headline_with_link" );
$t->set_block( "rfp_item_tpl", "headline_without_link_tpl", "headline_without_link" );

$t->set_block( "rfp_item_tpl", "rfp_image_tpl", "rfp_image" );
$t->set_block( "rfp_item_tpl", "read_more_tpl", "read_more" );
$t->set_block( "rfp_item_tpl", "rfp_topic_tpl", "rfp_topic" );

// prev/next
$t->set_block( "rfp_list_page_tpl", "previous_tpl", "previous" );
$t->set_block( "rfp_list_page_tpl", "next_tpl", "next" );


// print headline
if ( $CategoryID == 0 )
{
    $t->parse( "latest_headline_item", "latest_headline_tpl" );
    $t->set_var( "category_headline_item", "" );
}
else
{
    $t->parse( "category_headline_item", "category_headline_tpl" );
    $t->set_var( "latest_headline_item", "" );
}

// read user override variables for image size
$ThumbnailImageWidth = $ini->read_var( "eZRfpMain", "ThumbnailImageWidth" );
$ThumbnailImageHeight = $ini->read_var( "eZRfpMain", "ThumbnailImageHeight" );
    
$thumbnailImageWidthOverride =& $t->get_user_variable( "rfp_list_page_tpl",  "ThumbnailImageWidth" );
if ( $thumbnailImageWidthOverride )
{
    $ThumbnailImageWidth = $thumbnailImageWidthOverride;
}

$thumbnailImageHeightOverride =& $t->get_user_variable( "rfp_list_page_tpl",  "ThumbnailImageHeight" );
if ( $thumbnailImageHeightOverride )
{
    $ThumbnailImageHeight = $thumbnailImageHeightOverride;
}

// image dir
$t->set_var( "image_dir", $ImageDir );

// makes the section ID available in rfpview template
$t->set_var( "section_id", $GlobalSectionID );

$category = new eZRfpCategory( $CategoryID );

$t->set_var( "current_category_name", $category->name() );

//EP: CategoryDescriptionXML=enabled, description go in XML -------------------
if ( $ini->read_var( "eZRfpMain", "CategoryDescriptionXML" ) == "enabled" )
{
    if ($CategoryID)
    {
	include_once( "ezrfp/classes/ezrfprenderer.php" );

        $rfp = new eZRfp ();
		$rfp->setContents ($category->description(false));

        $renderer = new eZRfpRenderer( $rfp );

	$t->set_var( "current_category_description", $renderer->renderIntro() );
    }
    else
    {
	$t->set_var( "current_category_description", "" );
    }
}
else
{
    $t->set_var( "current_category_description", eZTextTool::nl2br( $category->description() ) );
}
//EP ---------------------------------------------------------------------------

if ( isSet( $NoRfpHeader ) and $NoRfpHeader )
{
    $t->set_var( "header_item", "" );
}
else
{
    $t->parse( "header_item", "header_item_tpl" );
}

$SiteTitleAppend = "";

// path
$pathArray = $category->path();

$t->set_var( "path_item", "" );


        $t->set_var( "rfp_path_header", "" );
        $t->set_var( "rfp_path_headers", "" );
        $t->set_var( "rfp_path_headers2", "" );

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

    $SiteTitleAppend .= $path[1] . " : ";
    
    if ( $CategoryID == '10' )
    {
      // $t->parse( "path_item", "path_item_tpl", true );
    }else {
      $t->parse( "path_item", "path_item_tpl", true );
    }

    $t->set_var( "rfp_path_header", "" );
    $t->set_var( "rfp_path_headers", "" );
    $t->set_var( "rfp_path_headers2", "" );

    if ( $CategoryID == '10' )
    {
      
      $t->set_var( "rfp_path_header", "" );
      $t->set_var( "rfp_path_headers2", "" );
      
      $t->parse( "rfp_path_headers", "rfp_path_headers_tpl", true );
      // $t->parse( "rfp_path_header", "rfp_path_header_tpl", true ); 
      
    }else {
      $t->set_var( "rfp_path_header", "" );
      $t->set_var( "rfp_path_headers", "" );
        $t->set_var( "rfp_path_headers2", "" );
    }
    
    
}

$categoryList = $category->getByParent( $category );

$user =& eZUser::currentUser();

// current category image
// $image =& $category->image();

$t->set_var( "current_image_item", "" );
        
if ( ( get_class( $image ) == "ezimage" ) && ( $image->id() != 0 ) )
{
    $imageWidth =& $ini->read_var( "eZRfpMain", "CategoryImageWidth" );
    $imageHeight =& $ini->read_var( "eZRfpMain", "CategoryImageHeight" );

    $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

    $imageURL = "/" . $variation->imagePath();
    $imageWidth =& $variation->width();
    $imageHeight =& $variation->height();
    $imageCaption =& $image->caption();
    $imageDescription =& $image->description();

    $photographer =& $image->photographer();
            
    $t->set_var( "current_image_width", $imageWidth );
    $t->set_var( "current_image_height", $imageHeight );
    $t->set_var( "current_image_url", $imageURL );
    $t->set_var( "current_image_caption", $imageCaption );
    $t->set_var( "current_image_description", $imageDescription );
    $t->set_var( "current_image_photographer", $photographer->name() );
    
    $t->parse( "current_image_item", "current_image_item_tpl" );
}
else
{
    $t->set_var( "current_image_item", "" );
}

// categories
$i = 0;
$t->set_var( "category_list", "" );
foreach ( $categoryList as $categoryItem )
{
    $t->set_var( "category_id", $categoryItem->id() );
    $t->set_var( "category_name", $categoryItem->name() );

    $parent = $categoryItem->parent();
//    $image =& $categoryItem->image();

    $t->set_var( "image_item", "" );
        
    if ( ( get_class( $image ) == "ezimage" ) && ( $image->id() != 0 ) )
    {
        $imageWidth =& $ini->read_var( "eZRfpMain", "CategoryImageWidth" );
        $imageHeight =& $ini->read_var( "eZRfpMain", "CategoryImageHeight" );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth =& $variation->width();
        $imageHeight =& $variation->height();
        $imageCaption =& $image->caption();
            
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

        
    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "td_alt", "1" );
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "td_alt", "2" );
        $t->set_var( "td_class", "bgdark" );
    }

    //EP: CategoryDescriptionXML=enabled, description go in XML -------------------
    if ( $ini->read_var( "eZRfpMain", "CategoryDescriptionXML" ) == "enabled" )
    {    
	include_once( "ezrfp/classes/ezrfprenderer.php" );

        $rfp = new eZRfp ();
		$rfp->setContents ($categoryItem->description(false));

        $renderer = new eZRfpRenderer( $rfp );
    
        $t->set_var( "category_description", $renderer->renderIntro() );
    }
    else
    {
	$t->set_var( "category_description", $categoryItem->description() );
    }

    //EP ---------------------------------------------------------------------------

    $t->parse( "category_item", "category_item_tpl", true );
    $i++;
}

if ( count( $categoryList ) > 0 )
    $t->parse( "category_list", "category_list_tpl" );
else
    $t->set_var( "category_list", "" );


// set the offset/limit
if ( !isSet( $Offset ) or !is_numeric( $Offset ) )
    $Offset = 0;

if ( ( $category->listLimit() > 0 ) && $Offset == 0 )
    $Limit = $category->listLimit();
else
    $Limit = $UserListLimit;

if ( $CategoryID == 0 )
{
    // do not set offset for the main page news
    // always sort by publishing date is the merged category
    $rfp = new eZRfp();
    $rfpList =& $rfp->rfps( "time", false, $Offset, $Limit );
    $rfpCount = $rfp->rfpCount( false );
}
else
{
  // this should be important?
  $rfpList =& $category->rfps( $category->sortMode(), false, true, $Offset, $Limit );
  $rfpCount = $category->rfpCount( false, true  );
}

$t->set_var( "category_current_id", $CategoryID );

$locale = new eZLocale( $Language );
$i = 0;
$t->set_var( "rfp_list", "" );

$SiteDescriptionOverride = "";

/*
include_once("classes/ezvardump.php");
Var_Dump::display($rfpList);
*/

foreach ( $rfpList as $rfp )
{
    $categoryDef =& $rfp->categoryDefinition();

    $t->set_var( "category_id", $CategoryID );

    if ( $ForceCategoryDefinition == "enabled" )
    {
        $t->set_var( "category_id", $categoryDef->id() );
    }
    else if ( $CategoryID == 0 )
    {
        $t->set_var( "category_id", $categoryDef->id() );
    }

    $t->set_var( "category_def_name", $categoryDef->name() );
    $t->set_var( "category_def_id", $categoryDef->id() );
    
    $t->set_var( "rfp_id", $rfp->id() );
    $t->set_var( "rfp_name", $rfp->name() );
    $aProjectEstimate = number_format(  $rfp->projectEstimate() );
    $t->set_var( "rfp_project_estimate", $aProjectEstimate );

    $SiteDescriptionOverride .= $rfp->name() . " ";
        
    $t->set_var( "author_text", $rfp->authorText() );

    // preview image
    $thumbnailImage =& $rfp->thumbnailImage();
    if ( $thumbnailImage )
    {
        if ( $GrayScaleImageList == "enabled" )
            $convertToGray = true;
        else
            $convertToGray = false;

        $variation =& $thumbnailImage->requestImageVariation( $ThumbnailImageWidth, $ThumbnailImageHeight, $convertToGray );
    
        $t->set_var( "thumbnail_image_uri", "/" . $variation->imagePath() );
        $t->set_var( "thumbnail_image_width", $variation->width() );
        $t->set_var( "thumbnail_image_height", $variation->height() );
        $t->set_var( "thumbnail_image_caption", $thumbnailImage->caption() );

        $t->parse( "rfp_image", "rfp_image_tpl" );
    }
    else
    {
        $t->set_var( "rfp_image", "" );    
    }

    if ( ( $i % 2 ) == 0 )
    {
        $t->set_var( "tr_start", "<tr>" );
        $t->set_var( "tr_stop", "" );
        
        $t->set_var( "td_alt", "1" );
        $t->set_var( "td_class", "bglight" );
    }
    else
    {
        $t->set_var( "tr_start", "" );
        $t->set_var( "tr_stop", "</tr>" );


        $t->set_var( "td_alt", "2" );
        $t->set_var( "td_class", "bgdark" );
    }


    $published =& $rfp->published();
    $modified =& $rfp->modified();

    $responceDue =& $rfp->responceDueDate();
	
    $authorText = $rfp->authorText();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $modifiedDateValue =& $modified->date();
    $modifiedTimeValue =& $modified->time();

    $responceDueDateValue =& $responceDue->date();
    $responceDueTimeValue =& $responceDue->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );
    
    $t->set_var( "rfp_modified_datevalue", $locale->format( $modifiedDateValue ) );
    $t->set_var( "rfp_modified_due_timevalue", $locale->format( $modfiedTimeValue ) );

    $t->set_var( "rfp_responce_due_datevalue", $locale->format( $responceDueDateValue ) );
    $t->set_var( "rfp_responce_due_timevalue", $locale->format( $responceDueTimeValue ) );
    
    if ( $authorText == "" || $authorText[0] == "-" )
    {
      $t->set_var( "rfp_published", $locale->format( $published ) );
      $t->set_var( "rfp_date", "" );    
    }
    else
    {
      $t->set_var( "rfp_published", $locale->format( $published ) );
      $t->parse( "rfp_date", "rfp_date_tpl" );
    }
    
    if ( $authorText == "" || $authorText[0] == "-" )
    {
      $t->set_var( "rfp_modified_date", $locale->format( $modified ) );
      //        $t->set_var( "rfp_modified_date", "" );    
    }
    else
    {
      $t->set_var( "rfp_modified_date", $locale->format( $modified ) );
      //        $t->parse( "rfp_modified_date", "rfp_modified_date_tpl" );
    }
    
    if ( $authorText == "" || $authorText[0] == "-" )
    {
      $t->set_var( "rfp_responce_due_date", $locale->format( $responceDue ) );
      //        $t->set_var( "rfp_responce_due_date", "" );
    }
    else
    {
      $t->set_var( "rfp_responce_due_date", $locale->format( $responceDue ) );
      //       $t->parse( "rfp_responce_due_date", "rfp_responce_due_date_tpl" );
    }

    $renderer = new eZRfpRenderer( $rfp );
	
    // $t->set_var( "rfp_intro", $renderer->renderIntro(  ) );
    // $t->set_var( "rfp_intro", " " );
        
    if ( $rfp->linkText() != "" )
    {
        $t->set_var( "rfp_link_text", $rfp->linkText() );
    }
    else
    {
        $t->set_var( "rfp_link_text", $DefaultLinkText );
    }

    // check if the rfp contains more than intro
    $contents =& $renderer->renderPage();

    // link or no link based on rfp attributes . . . hmmm.
    if ( trim( $contents[1] ) == "" && count( $rfp->attributes( false ) ) <= 0 )
    {
        $t->set_var( "read_more", "" );
        $t->parse( "headline_without_link", "headline_without_link_tpl" );
        $t->set_var( "headline_with_link", "" );
    }
    else
    {
        $t->parse( "read_more", "read_more_tpl" );
        $t->parse( "headline_with_link", "headline_with_link_tpl" );
        $t->set_var( "headline_without_link", "" );
    }

    $t->parse( "rfp_item", "rfp_item_tpl", true );
    $i++;
}

eZList::drawNavigator( $t, $rfpCount, $UserListLimit, $Offset, "rfp_list_page_tpl" );

if ( count( $rfpList ) > 0 )    
    $t->parse( "rfp_list", "rfp_list_tpl" );
else
    $t->set_var( "rfp_list", "" );


if ( isSet( $GenerateStaticPage ) and $GenerateStaticPage == "true" and $cachedFile != "" )
{
    $fp = eZFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";    
    $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $output .= $t->parse( $target, "rfp_list_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $output );
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "rfp_list_page_tpl" );
}

?>
