<?php
// 
// $Id: rfpview.php,v 1.84.2.17 2003/07/24 08:47:34 br Exp $
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
include_once( "ezsitemanager/classes/ezsection.php" );

include_once( "ezsession/classes/ezsession.php" );

$session =& eZSession::globalSession( );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZRfpMain", "Language" );
$ForceCategoryDefinition = $ini->read_var( "eZRfpMain", "ForceCategoryDefinition" );
$TemplateDir = $ini->read_var( "eZRfpMain", "TemplateDir" );
$CapitalizeHeadlines = $ini->read_var( "eZRfpMain", "CapitalizeHeadlines" );
$ListImageWidth = $ini->read_var( "eZRfpMain", "ListImageWidth" );
$ListImageHeight = $ini->read_var( "eZRfpMain", "ListImageHeight" );

$user =& eZUser::currentUser();

// fix ezerror module, restore some 404/403 function ality to display error or kick to / 
// case : add one to ezerror for download login instead of altering existing


if ( !is_numeric( $RfpID ) )
{
    eZHTTPTool::header( "Location: /error/404" );
    exit();
}

if ( !is_numeric( $PageNumber) )
    $PageNumber = "";

if ( !is_numeric( $CategoryID ) )
    $CategoryID = eZRfp::categoryDefinitionStatic( $RfpID );


if ( $ForceCategoryDefinition == "enabled" )
{
    $CategoryID = eZRfp::categoryDefinitionStatic( $RfpID );
}

$GlobalSectionID = eZRfpCategory::sectionIDStatic( $CategoryID );

// init the section
$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
$sectionObject->setOverrideVariables();

$templateDirTmp = $sectionObject->templateStyle();
if ( trim( $templateDirTmp ) != "" )
{
    $TemplateDir = preg_replace( "/(.+)\/.+(\/?)/", "/\\1/$templateDirTmp\\2", $TemplateDir );
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
// override template for current section
// category override will be prefered
$sectionOverride = "_sectionoverride_$GlobalSectionID";

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
        // category override
        if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfpview" . $override  . ".tpl" ) )
        {
            $t->set_file( "rfp_view_page_tpl", "rfpview" . $override  . ".tpl"  );
        }
        else
        {
            // section override
            if ( eZFile::file_exists( "ezrfp/user/$TemplateDir/rfpview" . $sectionOverride  . ".tpl" ) )
            {
                $t->set_file( "rfp_view_page_tpl", "rfpview" . $sectionOverride  . ".tpl"  );
            }
            else
            {
                $t->set_file( "rfp_view_page_tpl", "rfpview.tpl"  );
            }
        }
    }
}

// path
$t->set_block( "rfp_view_page_tpl", "path_item_tpl", "path_item" );

$t->set_block( "rfp_view_page_tpl", "rfp_url_item_tpl", "rfp_url_item" );

$t->set_block( "rfp_view_page_tpl", "rfp_header_tpl", "rfp_header" );
$t->set_block( "rfp_view_page_tpl", "rfp_path_header_tpl", "rfp_path_header" );

$t->set_block( "rfp_header_tpl", "rfp_author_list_tpl", "rfp_author_list" );
$t->set_block( "rfp_author_list_tpl", "rfp_author_tpl", "rfp_author" );
$t->set_block( "rfp_author_tpl", "procurement_holder_organization_tpl", "procurement_holder_organization" );

$t->set_block( "rfp_header_tpl", "rfp_estimate_tpl", "rfp_estimate" );
$t->set_block( "rfp_header_tpl", "procurement_number_item_tpl", "procurement_number_item" );

$t->set_block( "rfp_header_tpl", "procurement_become_planholder_tpl", "procurement_become_planholder" );

/*
$t->set_block( "rfp_view_page_tpl", "rfp_topic_tpl", "rfp_topic" );
$t->set_block( "rfp_view_page_tpl", "rfp_intro_tpl", "rfp_intro" );
*/


$t->set_block( "rfp_view_page_tpl", "attached_file_list_tpl", "attached_file_list" );
$t->set_block( "attached_file_list_tpl", "attached_file_tpl", "attached_file" );

$t->set_block( "rfp_view_page_tpl", "bid_list_tpl", "bid_list" );
$t->set_block( "bid_list_tpl", "bid_tpl", "bid" );
$t->set_block( "bid_tpl", "bid_winner_tpl", "bid_winner" );
$t->set_block( "bid_tpl", "bid_rank_tpl", "bid_rank" );


$t->set_block( "rfp_view_page_tpl", "image_list_tpl", "image_list" );
$t->set_block( "image_list_tpl", "image_tpl", "image" );

// current category image
$t->set_block( "rfp_view_page_tpl", "current_category_image_item_tpl", "current_category_image_item" );

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


// read user override variables for image size
$ListImageWidth = $ini->read_var( "eZRfpMain", "ListImageWidth" );
$ListImageHeight = $ini->read_var( "eZRfpMain", "ListImageHeight" );

// Make the manual keywords available in the rfpview template
$ManualKeywords =& $rfp->manualKeywords();
$t->set_var( "rfp_keywords", $ManualKeywords );


$listImageWidthOverride =& $t->get_user_variable( "rfp_view_page_tpl",  "ListImageWidth" );
if ( $listImageWidthOverride )
{
    $ListImageWidth = $listImageWidthOverride;
}

$listImageHeightOverride =& $t->get_user_variable( "rfp_view_page_tpl",  "ListImageHeight" );
if ( $listImageHeightOverride )
{
    $ListImageHeight = $listImageHeightOverride;
}


$SiteURL = $ini->read_var( "site", "SiteURL" );


/*

//$session->setVariable( "RedirectURL", "$REQUEST_URI" );
//die($REQUEST_URI)

*/


$t->set_var( "rfp_url", $SiteURL . $REQUEST_URI );
$t->set_var( "rfp_url_item", "" );
if ( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )
    $t->parse( "rfp_url_item", "rfp_url_item_tpl" );


// makes the section ID available in rfpview template
$t->set_var( "section_id", $GlobalSectionID );

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

    // current category image
    //    $image =& $category->image();

    $t->set_var( "current_category_image_item", "" );
        
    if ( ( get_class( $image ) == "ezimage" ) && ( $image->id() != 0 ) )
    {
        $imageWidth =& $ini->read_var( "eZRfpMain", "CategoryImageWidth" );
        $imageHeight =& $ini->read_var( "eZRfpMain", "CategoryImageHeight" );

        $variation =& $image->requestImageVariation( $imageWidth, $imageHeight );

        $imageURL = "/" . $variation->imagePath();
        $imageWidth =& $variation->width();
        $imageHeight =& $variation->height();
        $imageCaption =& $image->caption();
            
        $t->set_var( "current_category_image_width", $imageWidth );
        $t->set_var( "current_category_image_height", $imageHeight );
        $t->set_var( "current_category_image_url", $imageURL );
        $t->set_var( "current_category_image_caption", $imageCaption );
        $t->parse( "current_category_image_item", "current_category_image_item_tpl" );
    }
    else
    {
        $t->set_var( "current_category_image_item", "" );
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
		
		if ( $path[0] == '10' ) {
            $t->set_var( "path_item", "" );
		}else {
	        $t->parse( "path_item", "path_item_tpl", true );
		}
        
    }
    
    
    $renderer = new eZRfpRenderer( $rfp );

    $t->set_var( "rfp_uri", $REQUEST_URI );


    if ( $CapitalizeHeadlines == "enabled" )
    {
        include_once( "classes/eztexttool.php" );
        $t->set_var( "rfp_name", eZTextTool::capitalize(  $rfp->name() ) );
    }
    else
    {
        $t->set_var( "rfp_name", $rfp->name() );
    }

	$aProjectEstimate = number_format( $rfp->projectEstimate() );
	$t->set_var( "rfp_project_estimate", $aProjectEstimate );
        $t->parse( "rfp_estimate", "rfp_estimate_tpl", true );

	$aProjectNumber = $rfp->projectNumber();
	if ( $aProjectNumber == "" || $aProjectNumber == "0") {
	  $aProjectNumber = "N/A";
	}
	$t->set_var( "procurement_number", $aProjectNumber );
	$t->parse( "procurement_number_item", "procurement_number_item_tpl", true );

	/*
	
    if ( eZMail::validate( $rfp->authorEmail() ) && $rfp->authorEmail() )
    {
        $t->set_var( "author_email", $rfp->authorEmail() );
    }
    else
    {
        $author = $rfp->author();
        // $t->set_var( "author_email", $author->email() );
	$t->set_var( "author_email", $author->emailAddress() );
    }

	*/
    
       /*
        $t->set_var( "topic_id", $topic->id() );
        $t->set_var( "topic_name", $topic->name() );
        $t->parse( "rfp_topic", "rfp_topic_tpl" );
       */

	$t->set_var("rfp_author", "");

        $theContentsWriters = $rfp->planholders();
	//	v_array($theContentsWriters[2]);

	foreach ( $theContentsWriters as $Writers )
        {

          $Writer_Name = $Writers->name();
          $Writer_ID = $Writers->personID();

	  $Writer_UserID = $Writers->id();

          if ( $user ){
             if ( $user->id() != false )
	        $currentUserID = $user->id();
	  }
	  $currentUserIsPlanholder = false;

          if ($Writer_UserID == $currentUserID) {
	    // print("$Writer_UserID == $currentUserID");
	    $currentUserIsPlanholder = true;
	  }

	  if ($currentUserIsPlanholder) {
	    $t->set_var( "procurement_become_planholder", "");
	  } else {
	    $t->parse( "procurement_become_planholder", "procurement_become_planholder_tpl" );
	  }

	  $t->set_var( "author_text", $Writer_Name );
	  $t->set_var( "author_id", $Writer_ID );

	  $Writer_Company_User = new eZPerson(  $Writer_ID  );
          $Writer_Companies = $Writer_Company_User->companies();

	  if(count($Writer_Companies) ) {
            $Writer_Companies = $Writer_Companies[0];
	    $Writer_CompanyName = $Writer_Companies->name();
	    $Writer_CompanyID = $Writer_Companies->id();

	    $t->set_var( "author_organization", $Writer_CompanyName );
	    $t->set_var( "author_organization_id", $Writer_CompanyID );
            $t->parse( "procurement_holder_organization", "procurement_holder_organization_tpl");
	  } else {
	    $Writer_CompanyName = 'No Affiliation';
            $Writer_CompanyID = '0';
            $t->set_var( "author_organization", $Writer_CompanyName );
            $t->set_var( "author_organization_id", $Writer_CompanyID );
	    // $t->parse( "procurement_holder_organization", "procurement_holder_organization_tpl"); 
	    $t->set_var( "procurement_holder_organization", "");
	  }
   	  $t->parse( "rfp_author", "rfp_author_tpl", true );
         $i++;
	}

 	$t->parse( "rfp_author_list", "rfp_author_list_tpl", true );

    /*
    // check if author is "" or starts with -
    $authorText = trim( $rfp->authorText() );
    if ( $authorText == "" ||
         $authorText[0] == "-"         
         )
    {
        $ShowHeader = "hide";        
    }
    */


    
    $categoryDef =& $rfp->categoryDefinition();

    $t->set_var( "category_definition_name", $categoryDef->name() );

    $pageCount = $rfp->pageCount();
    if ( $PageNumber > $pageCount )
        $PageNumber = $pageCount;

    if ( $PageNumber == -1 )
        $rfpContents = $renderer->renderPage( -1 );
    else
        $rfpContents = $renderer->renderPage( $PageNumber -1 );

    /*
    $t->set_var( "rfp_intro", $rfpContents[0] );
	

    if ( ( $PageNumber == 1 ) || (( isset( $PrintableVersion ) and $PrintableVersion == "enabled" )))
           $t->parse( "rfp_intro", "rfp_intro_tpl" );
    else
        $t->set_var( "rfp_intro", "" );

    // $t->set_var( "rfp_body", $rfpContents[1] );

    */

    $t->set_var( "rfp_body", $rfpContents[0] );
    $t->set_var( "link_text", $rfp->linkText() );
    $t->set_var( "rfp_id", $rfp->id() );
    $t->set_var( "procurement_id", $rfp->id() );

    $locale = new eZLocale( $Language );

	
	$publishDate = $rfp->publishDate();
//      $published =& $rfp->published();

        $modifiedDate =& $rfp->modified();
	$responceDueDate = $rfp->responceDueDate();
	
    $publishDateValue =& $publishDate->date();
    $publishTimeValue =& $publishDate->time();
	
    $modifiedDateValue =& $modifiedDate->date();
    $modifiedTimeValue =& $modifiedDate->time();

    $responceDueDateValue =& $responceDueDate->date();
    $responceDueTimeValue =& $responceDueDate->time();

    $t->set_var( "rfp_publish_datevalue", $locale->format( $publishDateValue ) );
    $t->set_var( "rfp_publish_timevalue", $locale->format( $publishDateTimeValue ) );

    $t->set_var( "rfp_published", $locale->format( $publishDate ) );
	
    $t->set_var( "rfp_modified_datevalue", $locale->format( $modifiedDateValue ) );
    $t->set_var( "rfp_modified_timevalue", $locale->format( $modifiedDateTimeValue ) );

    $t->set_var( "rfp_modified", $locale->format( $modifiedDate ) );

    $t->set_var( "rfp_responce_due_date_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_responce_due_date_timevalue", $locale->format( $publishedTimeValue ) );

    $t->set_var( "rfp_responce_due_date", $locale->format( $responceDueDate ) );
	
    $published = $rfp->published();

    $publishedDateValue =& $published->date();
    $publishedTimeValue =& $published->time();

    $t->set_var( "rfp_datevalue", $locale->format( $publishedDateValue ) );
    $t->set_var( "rfp_timevalue", $locale->format( $publishedTimeValue ) );

    $t->set_var( "rfp_created", $locale->format( $published ) );

    // image list

    $usedImages = $renderer->usedImageList();
    $images =& $rfp->images();
    
    {
        $i=0;
        foreach ( $images as $imageArray )
        {
            $image = $imageArray["Image"];
            $placement = $imageArray["Placement"];

            $showImage = true;

            if ( is_array( $usedImages ) == true )
            {
                if ( in_array( $placement, $usedImages ) )
                {
                    $showImage = false;
                }
            }
            
            if (  $showImage  )
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

                $variation =& $image->requestImageVariation( $ListImageWidth, $ListImageHeight );

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
else
{
    eZHTTPTool::header( "Location: /error/404" );
    exit();
}


if ( $StaticRendering == true  || $ShowHeader == "hide" )
{
    $t->set_var( "rfp_header", "" );
    $t->set_var( "rfp_path_header", "" );
}
else
{
    $t->parse( "rfp_header", "rfp_header_tpl" );
    $t->set_var( "rfp_path_header", "" );
    $t->parse( "rfp_path_header", "rfp_path_header_tpl" );
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


//include_once("ezprocurement/classes/fnc_viewArray2.php");
// bids
$bids = $rfp->bids();
//print "files : "; viewArray($files);
//print "<br />bids : "; viewArray($bids);

if ( count( $bids ) > 0 )
{
  $i=0;
  foreach ( $bids as $bid )
  {
      if ( ( $i % 2 ) == 0 )
      {
	$t->set_var( "td_class", "bglight" );
      }
      else
      {
	$t->set_var( "td_class", "bgdark" );
      }

      // bid information
      $t->set_var( "bid_id", $bid->id() );
      $t->set_var( "bid_amount", $bid->amount() );
      //$t->set_var( "bid_description", $bid->description() );
      //      $t->set_var( "bid_date", $bid->bidDate() );

      $bid_winner = $bid->winner();

      if ( $bid_winner ) {
	$t->set_var( "bid_iswinner", "Winner" );
	$t->parse( "bid_winner", "bid_winner_tpl" );
      } else {
	$t->set_var( "bid_iswinner", "");
        $t->set_var( "bid_winner", "");
      }

      if ($bid->rank() != ""){
	$t->set_var("rank_color", "color: grey;" );

      if ( $bid_winner )
	 $t->set_var("rank_color", "color: green;" );

         $t->set_var( "bid_rank_alpha", $bid->rank() );
	 $t->parse( "bid_rank", "bid_rank_tpl" );
      } else {
        $t->set_var( "bid_rank_alpha", "");
	$t->set_var( "bid_rank", "");
      }

      // bidder information
      $bid_company_id = $bid->companyID();
      $bid_company = new eZCompany($bid_company_id);


      $bid_person_id = $bid->personID();
      $bid_user_id = $bid->userID();

      $bid_person = new eZPerson($bid_person_id);
      $bid_user = new eZUser($bid_user_id);

      $bid_user_name = $bid_user->name();
      $bid_person_name = $bid_person->name();


      $t->set_var( "bid_company_name", $bid_company->name() );
      $t->set_var( "bid_company_id", $bid_company_id );

      $t->set_var( "bidder_id", $bid_person_id );
      $t->set_var( "bidder_name", $bid_person_name );


  $i++;
  $t->parse( "bid", "bid_tpl", true );
  }

  $t->parse( "bid_list", "bid_list_tpl" );
}
else
{
  $t->set_var( "bid", "" );
  $t->set_var( "bid_list", "" );
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

$t->set_var( "total_pages", $pageCount );
$t->set_var( "current_page", $PageNumber );

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
if ( ( !isset( $PrintableVersion ) or $PrintableVersion != "enabled" ) && ( !isset( $StaticRendering ) or $StaticRendering != true )  )
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
$SiteKeywordsOverride = str_replace( "\"", "", strip_tags( $rfp->keywords() ) );

$SiteKeywordsOverride  = str_replace( "qdom", "", $SiteKeywordsOverride );


if ( isset( $GenerateStaticPage ) && $GenerateStaticPage == "true" and $cachedFile != "" )
{    
   $fp = eZFile::fopen( $cachedFile, "w+");

    // add PHP code in the cache file to store variables
    $output = "<?php\n";
    $output .= "\$ManualKeywords=\"$ManualKeywords\";\n";
    $output .= "\$GlobalSectionID=\"$GlobalSectionID\";\n";
    $output .= "\$SiteTitleAppend=\"$SiteTitleAppend\";\n";
    $output .= "\$SiteDescriptionOverride=\"$SiteDescriptionOverride\";\n";
    $output .= "\$SiteKeywordsOverride=\"$SiteKeywordsOverride\";\n";    
    $output .= "\$eZLanguageOverride=\"$eZLanguageOverride\";\n";
    $output .= "?>\n";

    $printOut = $t->parse( $target, "rfp_view_page_tpl" );
    
    // print the output the first time while printing the cache file.
    print( $printOut );
    
    $output .= $printOut;
         
    fwrite ( $fp, $output );
    fclose( $fp );
}
else
{
    $t->pparse( "output", "rfp_view_page_tpl" );
}

?>
