<?php
// 
// $Id: rfpedit.php,v 1.24.2.5 2002/04/23 15:32:42 bf Exp $
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
include_once( "classes/ezcachefile.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezrfp/classes/ezrfptool.php" );
include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfpgenerator.php" );
include_once( "ezrfp/classes/ezrfprenderer.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezuser/classes/ezauthor.php" );
include_once( "ezxml/classes/ezxml.php" );

include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

$ini =& INIFile::globalINI();

$PublishNoticeReceiver = $ini->read_var( "eZRfpMain", "PublishNoticeReceiver" );
$PublishNoticeSender = $ini->read_var( "eZRfpMain", "PublishNoticeSender" );

$session =& eZSession::globalSession();

// insert a new rfp in the database
if ( ( $Action == "Insert" ) || ( $Action == "Update" ) )
{
    $user =& eZUser::currentUser();
        
    $rfp = new eZRfp( $RfpID );
    $rfp->setName( $Name );
    
    $rfp->setAuthor( $user );

    $generator = new eZRfpGenerator();

    $contents = $generator->generateXML( $Contents );
    $rfp->setContents( $contents );

    $rfp->setPageCount( $generator->pageCount() );


    // check if author exists in the database, else create
    $author = new eZAuthor();
    if ( !$author->getByName( trim( $AuthorText ) ) )
    {
        $author = new eZAuthor( );
        $author->setName( $AuthorText );
        $author->store();
        
        $rfp->setContentsWriter( $author );
    }
    else
    {
        $rfp->setContentsWriter( $author );
    }
    
    $rfp->setLinkText( $LinkText );
    $rfp->store(); // to get ID

    // remove from category if update
    if ( $Action == "Update" )
        $rfp->removeFromCategories();
    
    // add to categories    
    $category = new eZRfpCategory( $CategoryIDSelect );
    $category->addRfp( $rfp );

    $rfp->setCategoryDefinition( $category );
    
// Which group should a user-published rfp be set to?
    eZObjectPermission::setPermission( -1, $rfp->id(), "rfp_rfp", 'w' );
    eZObjectPermission::setPermission( -1, $rfp->id(), "rfp_rfp", 'r' );

    // user-submitted rfps are never directly published


    // check if the contents is parseable
    if ( eZXML::domTree( $contents ) )
    {
        // generate keywords
        $contents = strip_tags( $contents );
        $contents = ereg_replace( "#\n#", "", $contents );
        $contents_array =& split( " ", $contents );
        $contents_array = array_unique( $contents_array );

        $keywords = "";
        foreach ( $contents_array as $word )
        {
            
            $keywords .= $word . " ";
        }

        $rfp->setKeywords( $keywords );
        
        $rfp->store();
    
        // Go to insert item..
        if ( isset( $AddItem ) )
        {
            switch( $ItemToAdd )
            {
                case "Image":
                {
                    $session->setVariable( "RfpEditID", $rfp->id() );
                    $rfpID = $rfp->id();
                    // add images
                    eZHTTPTool::header( "Location: /rfp/rfpedit/imagelist/$rfpID/" );
                    exit();
                }
                break;
                case "File":
                {
                    $session->setVariable( "RfpEditID", $rfp->id() );
                    $rfpID = $rfp->id();
                    // add files
                    eZHTTPTool::header( "Location: /rfp/rfpedit/filelist/$rfpID/" );
                    exit();
                }
                break;
            }
        }

        if ( $ini->read_var( "eZRfpMain", "CanUserPublish" ) == "enabled" )
        {
            $rfp->setIsPublished( true );

            eZRfpTool::deleteCache( $rfpID, $CategoryIDSelect, array( $CategoryIDSelect ) );
            eZRfpTool::notificationMessage( $rfp );
        }
        else
        {
            $rfp->setIsPublished( false );
        }

        $rfp->store();
        
        $session->setVariable( "RfpEditID", "" );
        eZHTTPTool::header( "Location: /rfp/archive/$CategoryIDSelect/" );
        exit();
    }
    else
    {
        $Action = "New";
        $ErrorParsing = true;
    }
}


if ( $Action == "Cancel" )
{
    $rfp = new eZRfp( $RfpID );

    $category = $rfp->categoryDefinition( );
    
    if ( $category )
    {
        $categoryID = $category->id();
    }

    eZHTTPTool::header( "Location: /rfp/archive/$categoryID/" );
    exit();
}


$Language = $ini->read_var( "eZRfpMain", "Language" );

// init the section
if ( isset ($SectionIDOverride) )
{
    include_once( "ezsitemanager/classes/ezsection.php" );
    
    $sectionObject =& eZSection::globalSectionObject( $SectionIDOverride );
    $sectionObject->setOverrideVariables();
}

$t = new eZTemplate( "ezrfp/user/" . $ini->read_var( "eZRfpMain", "TemplateDir" ),
                     "ezrfp/user/intl/", $Language, "rfpedit.php" );

$t->setAllStrings();

$t->set_file( array(
    "rfp_edit_page_tpl" => "rfpedit.tpl"
    ) );

$t->set_block( "rfp_edit_page_tpl", "value_tpl", "value" );
$t->set_block( "rfp_edit_page_tpl", "error_message_tpl", "error_message" );


if ( $ErrorParsing == true )
{
    $t->parse( "error_message", "error_message_tpl" );
}
else
{
    $t->set_var( "error_message", "" );
}

$t->set_var( "rfp_id", "" );
$t->set_var( "rfp_name", stripslashes( $Name ) );
$t->set_var( "rfp_contents_0", stripslashes( $Contents[0] ) );
$t->set_var( "rfp_contents_1", stripslashes( $Contents[1] ) );
$t->set_var( "author_text", stripslashes( $AuthorText ) );
$t->set_var( "link_text", stripslashes( $LinkText  ) );

$t->set_var( "action_value", "insert" );

if ( $Action == "New" )
{
    $user =& eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    
}

$rfpID = $session->variable( "RfpEditID" );
if ( $Action == "Edit" )
{
    $rfp = new eZRfp( $rfpID );

    $generator = new eZRfpGenerator();
    
    $contentsArray = $generator->decodeXML( $rfp->contents() );

    $catDef =& $rfp->categoryDefinition();
    $catDefID = $catDef->id();

    $user =& eZUser::currentUser();
    $t->set_var( "author_text", $user->firstName() . " " . $user->lastName());    

    $t->set_var( "rfp_name", $rfp->name() );

    $i=0;
    foreach ( $contentsArray as $content )
    {
        if ( !isset( $Contents[$i] ) )
        {
            $t->set_var( "rfp_contents_$i", htmlspecialchars( $content ) );
        }
        $i++;
    }
    $t->set_var( "rfp_keywords", $rfp->manualKeywords() );

    $t->set_var( "link_text", $rfp->linkText() );

    $t->set_var( "action_value", "update" );
    $t->set_var( "rfp_id", $rfpID );
}


// category select
$tree = new eZRfpCategory();
$treeArray = $tree->getTree();

foreach ( $treeArray as $catItem )
{
    if( eZObjectPermission::hasPermission( $catItem[0]->id(), "rfp_category", 'w', eZUser::currentUser() ) == true )
    {
        $t->set_var( "selected", "" );

        if ( $catDefID == $catItem[0]->id() )
        {
            $t->set_var( "selected", "selected" );
        }

        $t->set_var( "option_value", $catItem[0]->id() );
        $t->set_var( "option_name", $catItem[0]->name() );

        if ( $catItem[1] > 0 )
            $t->set_var( "option_level", str_repeat( "&nbsp;", $catItem[1] ) );
        else
            $t->set_var( "option_level", "" );

        $t->parse( "value", "value_tpl", true );
    }
}

if ( isset ($SectionIDOverride) ) $t->set_var( "section_id", $SectionIDOverride );

$t->pparse( "output", "rfp_edit_page_tpl" );

?>
