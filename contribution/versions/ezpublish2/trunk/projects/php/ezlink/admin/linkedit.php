<?
// $Id: linkedit.php,v 1.48 2001/06/23 12:25:32 bf Exp $
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <26-Oct-2000 14:58:57 ce>
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

/*
  linkedit.php - Redigerer en link.
*/

include_once( "classes/INIFile.php" );
include_once( "classes/ezhttptool.php" );

$ini =& $GLOBALS["GlobalSiteIni"];
$Language = $ini->read_var( "eZLinkMain", "Language" );
$error = new INIFIle( "ezuser/admin/intl/" . $Language . "/useredit.php.ini", false );

include_once( "classes/eztemplate.php" );

include( "ezlink/classes/ezlinkgroup.php" );
include( "ezlink/classes/ezlink.php" );
include( "ezlink/classes/ezhit.php" );

include_once( "ezlink/classes/ezmeta.php" );

require( "ezuser/admin/admincheck.php" );

if ( isSet ( $DeleteLinks ) )
{
    $Action = "DeleteLinks";
}

if ( isSet( $Delete ) )
{
    $Action = "delete";
}

if ( isSet( $Back ) )
{
    $link = new eZLink();
    $link->get( $LinkID );
    $LinkGroupID = $link->linkGroupID();

    eZHTTPTool::header( "Location: /link/group/$LinkGroupID" );
    exit();
}

// Get images from the image browse function.
if ( ( isSet ( $AddImages ) ) and ( is_numeric( $LinkID ) ) and ( is_numeric ( $LinkID ) ) )
{
    $image = new eZImage( $ImageID );
    $link = new eZLink( $LinkID );
    $link->setImage( $image );
    $link->update();
    $Action = "edit";
}

if ( $GetSite )
{
    if ( $Url )
    {
        if ( !preg_match( "%^([a-z]+://)%", $Url ) )
            $real_url = "http://" . $Url;
        else
            $real_url = $Url;

        $metaList = fetchURLInfo( $real_url );

        if ( $metaList == false )
        {
            // Change this to use an external message
            $terror_msg = "The site does not exists";
        }
        else if( count( $metaList ) == 0 )
        {
            $inierror = new INIFile( "ezlink/user/" . "/intl/" . $Language . "/suggestlink.php.ini", false );
            $terror_msg = $inierror->read_var( "strings", "nometa" );
        }

        if ( $metaList["description"] )
            $tdescription = $metaList["description"];
        else
            $tdescription = $description;
        if ( $metaList["keywords"] )
            $tkeywords = $metaList["keywords"];
        else
            $tkeywords = $keywords;
        if ( $metaList["title"] )
            $ttitle = $metaList["title"];
        else if ( $metaList["abstract"] )
            $ttitle = $metaList["abstract"];
        else
            $ttitle = $Title;
//          $tdescription = $metaList["description"];
//          $tkeywords = $metaList["keywords"];
//          $ttitle = $Title;
        $turl = $Url;

    }

    $action_value = "insert";
    $Action = "new";

}

// Update a link.
if ( $Action == "update" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        if ( $Title != "" &&
        $LinkGroupID != "" &&
        $Accepted != "" &&
        $Url != "" )
        {
            $link = new eZLink();
            $link->get( $LinkID );
            
            $link->setTitle( $Title );
            $link->setDescription( $Description );
            $link->setLinkGroupID( $LinkGroupID );
            $link->setKeyWords( $Keywords );
            
            if ( $Accepted == "1" )
                $link->setAccepted( true );
            else
                $link->setAccepted( false );
            
            $link->setUrl( $Url );

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "LinkImage" );
                $image->setImage( $file );

                $image->store();
                
                $link->setImage( $image );
            }

            $link->update();

            if ( $DeleteImage )
            {
                $link->deleteImage();
            }
            if ( isSet ( $Browse ) )
            {
                $linkID = $link->id();
                $session = new eZSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/linkedit/edit/$linkID/" );
                $session->setVariable( "NameInBrowse", $link->title() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }
           
            eZHTTPTool::header( "Location: /link/group/$LinkGroupID" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
        exit();
    }
}

// Delete a link.
if ( $Action == "delete" )
{
    if ( eZPermission::checkPermission( $user, "eZLink", "LinkDelete" ) )
    {
        $deletelink = new eZLink();
        $deletelink->get( $LinkID );
        $LinkGroupID = $deletelink->linkGroupID();
        $deletelink->delete();

        if ( $deletelink->accepted() == false )
        {
            eZHTTPTool::header( "Location: /link/group/incoming" );
            exit();
        }
       
        
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

if ( $Action == "DeleteLinks" )
{
    if ( count ( $LinkArrayID ) != 0 )
    {
        foreach( $LinkArrayID as $LinkID )
        {
            $deletelink = new eZLink();
            $deletelink->get( $LinkID );
            $LinkGroupID = $deletelink->linkGroupID();
            $deletelink->delete();
            
        }
        if ( $deletelink )
        {
            if ( $deletelink->accepted() == false )
            {
                eZHTTPTool::header( "Location: /link/group/incoming" );
                exit();
            }
        }
        eZHTTPTool::header( "Location: /link/group/$LinkGroupID" );
        exit();
    }
}

// Insert a link.
if ( $Action == "insert" )
{

    if ( eZPermission::checkPermission( $user, "eZLink", "LinkAdd") )
    {

        if ( $Title != "" &&
        $LinkGroupID != "" &&
        $Accepted != "" &&
        $Url != "" )
        {
            $link = new eZLink();

            $link->setTitle( $Title );
            $link->setDescription( $Description );
            $link->setLinkGroupID( $LinkGroupID );
            $link->setKeyWords( $Keywords );
            if ( $Accepted == "1" )
                $link->setAccepted( true );
            else
                $link->setAccepted( false );

            $link->setUrl( $Url );

            $ttitle = $Title;
            $turl = $Url;
            if ( !$GetSite )
            {
                $tkeywords = $Keywords;
                $tdescription = $Description;
            }

            $file = new eZImageFile();
            if ( $file->getUploadedFile( "ImageFile" ) )
            {
                $image = new eZImage( );
                $image->setName( "LinkImage" );
                $image->setImage( $file );

                $image->store();
                
                $link->setImage( $image );
            }
            
            $link->store();

            if ( isSet ( $Browse ) )
            {
                $linkID = $link->id();
                $session = new eZSession();
                $session->setVariable( "SelectImages", "single" );
                $session->setVariable( "ImageListReturnTo", "/link/linkedit/edit/$linkID/" );
                $session->setVariable( "NameInBrowse", $link->title() );
                eZHTTPTool::header( "Location: /imagecatalogue/browse/" );
                exit();
            }
           
            eZHTTPTool::header( "Location: /link/group/$LinkGroupID" );
            exit();
        }
        else
        {
            $error_msg = $error->read_var( "strings", "error_missingdata" );
        }
    }
    else
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
}

// Sette template filer.

$t = new eZTemplate( "ezlink/admin/" . $ini->read_var( "eZLinkMain", "AdminTemplateDir" ),
"ezlink/admin/" . "/intl", $Language, "linkedit.php" );
$t->setAllStrings();

$t->setAllStrings();

$t->set_file( array(
    "link_edit" => "linkedit.tpl"
    ));

$t->set_block( "link_edit", "link_group_tpl", "link_group" );

$t->set_block( "link_edit", "image_item_tpl", "image_item" );
$t->set_block( "link_edit", "no_image_item_tpl", "no_image_item" );



$languageIni = new INIFIle( "ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
$headline = $languageIni->read_var( "strings", "headline_insert" );

$linkselect = new eZLinkGroup();

$linkGroupList = $linkselect->getTree();

// Template variabler
$message = "Legg til link";
$submit = "Legg til";

$action_value = "update";

if ( $Action == "new" )
{
    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkAdd" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }

    $action_value = "insert";

    $t->set_var( "image_item", "" );
    $t->set_var( "no_image_item", "" );
}

// setter akseptert link som default.
$yes_selected = "selected";
$no_selected = "";

// editere
if ( $Action == "edit" )
{

    $languageIni = new INIFIle( "ezlink/admin/intl/" . $Language . "/linkedit.php.ini", false );
    $headline =  $languageIni->read_var( "strings", "headline_edit" );

    if ( !eZPermission::checkPermission( $user, "eZLink", "LinkModify" ) )
    {
        eZHTTPTool::header( "Location: /link/norights" );
    }
    else
    {
        $editlink = new eZLink();
        $editlink->get( $LinkID );

        $title = $editlink->Title;

        $LinkGroupID = $editlink->linkGroupID();

        $title = $editlink->title();
        $description = $editlink->description();
        $linkgroup = $editlink->linkGroupID();
        $keywords = $editlink->keyWords();
        $accepted = $editlink->accepted();
        $url = $editlink->url();

        $action_value = "update";
        $message = "Rediger link";
        $submit = "Rediger";
              
        $ttitle = $editlink->title();
        $tdescription = $editlink->description();
        $tkeywords = $editlink->keywords();
        $turl = $editlink->url();

        $image = $editlink->image();

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

            $t->set_var( "no_image_item", "" );
        }
        else
        {
            $t->parse( "no_image_item", "no_image_item_tpl" );
            $t->set_var( "image_item", "" );
        }
        
        

        if ( $editlink->accepted() == true )
        {
            $yes_selected = "selected";
            $no_selected = "";
        }
        else
        {
            $yes_selected = "";
            $no_selected = "selected";
        }
   
    }
}
    
// Selector
$link_select_dict = "";

foreach( $linkGroupList as $linkGroupItem )
{
    $t->set_var("link_group_id", $linkGroupItem[0]->id() );
    $t->set_var("link_group_title", $linkGroupItem[0]->title() );

    if ( $LinkGroupID == $linkGroupItem[0]->id() )
    {
        $t->set_var( "is_selected", "selected" );
    }
    else
    {
        $t->set_var( "is_selected", "" );
    }

    if ( $linkGroupItem[1] > 0 )
        $t->set_var( "option_level", str_repeat( "&nbsp;", $linkGroupItem[1] ) );
    else
        $t->set_var( "option_level", "" );


    $link_select_dict[ $linkGroupItem[0]->id() ] = $i;

    $t->parse( "link_group", "link_group_tpl", true );
}


$t->set_var( "yes_selected", $yes_selected );
$t->set_var( "no_selected", $no_selected );

$t->set_var( "submit_text", $submit );
$t->set_var( "action_value", $action_value );
$t->set_var( "message", $message );


$t->set_var( "title", $ttitle );
$t->set_var( "url", $turl );
$t->set_var( "keywords", $tkeywords );
$t->set_var( "description", $tdescription );
// $t->set_var( "accepted", $taccepted );

$t->set_var( "headline", $headline );

$t->set_var( "error_msg", $error_msg );

$t->set_var( "link_id", $LinkID );
$t->pparse( "output", "link_edit" );

?>
