<?php
// 
// $Id: ezrfptool.php,v 1.12.2.7 2003/07/10 08:30:01 br Exp $
//
// Definition of eZRfpTool class
//
// Created on: <27-Apr-2001 14:08:05 amos>
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

//!! eZRfp
//! The class eZRfpTool has functions for rfp handling.
/*!

*/

include_once( "classes/ezcachefile.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/eztexttool.php" );

include_once( "ezrfp/classes/ezrfprenderer.php" );
include_once( "ezuser/classes/ezuser.php" );

//include_once( "ezbulkmail/classes/ezbulkmail.php" );
//include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

class eZRfpTool
{
    /*!
      \static
      Deletes the cache files for a given rfp and it's categories.
    */
    function deleteCache( $RfpID, $CategoryID, $CategoryArray )
    {
        $user =& eZUser::currentUser();

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( array( "rfpprint", "rfpview", "rfpstatic", "static", "view", "print"  ),
                                             $RfpID, NULL, NULL ), "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( array( "rfplist", "list" ),
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             NULL, array( "", NULL ) ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }


        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "rfplinklist",
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             $RfpID,
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "rfpindex",
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "menubox",
                                             NULL,
                                             NULL,
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "menubox_headlines",
                                             NULL,
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
        
        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "smallrfplist",
                                             NULL ),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezrfp/cache/",
                                      array( "rfpfrontpage",
                                             NULL,
                                             NULL),
                                      "cache", "," );
        foreach ( $files as $file )
        {
            $file->delete();
        }
    }

    function notificationMessage( &$rfp )
    {
        include_once( "classes/eztexttool.php" );
        $ini =& INIFile::globalINI();

        $PublishNoticeReceiver = $ini->read_var( "eZRfpMain", "PublishNoticeReceiver" );
        $PublishNoticeSender = $ini->read_var( "eZRfpMain", "PublishNoticeSender" );
        $PublishNoticePadding = $ini->read_var( "eZRfpMain", "PublishNoticePadding" );
        $PublishSite = $ini->read_var( "site", "SiteTitle" );
        $SiteURL = $ini->read_var( "site", "SiteURL" );

	//EP - different charsets for the MIME mail ----------------------------
	global $GlobalSectionID;
	        
	include_once("ezsitemanager/classes/ezsection.php");
	
	$category = $rfp->categoryDefinition();
	$GlobalSectionID = $category->sectionID();
	
	// init the section ???
	//$sectionObject =& eZSection::globalSectionObject( $GlobalSectionID );
	//$sectionObject->setOverrideVariables();

	//EP -------------------------------------------------------------------
					      
        $mailTemplate = new eZTemplate( "ezrfp/admin/" . $ini->read_var( "eZRfpMain", "AdminTemplateDir" ),
                                        "ezrfp/admin/intl", $ini->read_var( "eZRfpMain", "Language" ), "mailtemplate.php" );
    
        $mailTemplate->set_file( "mailtemplate", "mailtemplate.tpl" );
        $mailTemplate->setAllStrings();

        $renderer = new eZRfpRenderer( $rfp );

        $subjectLine = $mailTemplate->Ini->read_var( "strings", "subject" );
        $subjectLine = $subjectLine . " " . $PublishSite;

        $intro = eZTextTool::linesplit( strip_tags( $renderer->renderIntro() ), $PublishNoticePadding, 76 );

        $mailTemplate->set_var( "body", $intro );
        $mailTemplate->set_var( "site", $PublishSite );
        $mailTemplate->set_var( "title", $rfp->name( false ) );
        $mailTemplate->set_var( "author", $rfp->authorText( false ) );


        // Set the global nVH variables.
        $index = $ini->Index;
        $wwwDir = $ini->WWWDir;

        // the index should be index.php for the usersite. 
        if ( $index == "/index_admin.php" )
        {
           $index = "/index.php";
        }
    
        $mailTemplate->set_var( "link", "http://" . $SiteURL . $wwwDir . $index . "/rfp/rfpview/" . $rfp->id() );
        
        $bodyText = $mailTemplate->parse( "dummy", "mailtemplate" );
    
        // send a notice mail
        $noticeMail = new eZMail();

        $noticeMail->setFrom( $PublishNoticeSender );
        $noticeMail->setTo( $PublishNoticeReceiver );

        $noticeMail->setSubject( $subjectLine );
        $noticeMail->setBodyText( $bodyText );

        $noticeMail->send();

        // Send bulkmail also
        $rfpCategory = $rfp->categoryDefinition();
        $rfpCategories = $rfp->categories();
        $bulkMailCategories = array();

        $bulkMailCategory =& $rfpCategory->bulkMailCategory();
        if ( $bulkMailCategory != false )
            $bulkMailCategories[] =& $bulkMailCategory;

        foreach ( $rfpCategories as $rfpCategory )
        {
            $bulkMailCategory = $rfpCategory->bulkMailCategory();
            if ( $bulkMailCategory != false )
                $bulkMailCategories[] = $bulkMailCategory;
        }

        
        if ( count( $bulkMailCategories ) > 0 ) // send a mail to this group
        {
            $bulkmail = new eZBulkMail();
            $bulkmail->setOwner( eZUser::currentUser() );

            $bulkmail->setSender( $PublishNoticeSender ); // from NAME
            $bulkmail->setSubject( $subjectLine );
            $bulkmail->setBodyText( $bodyText );
            $bulkmail->setIsDraft( false );
            $bulkmail->store();

            $bulkmail->addToCategory( false );
            foreach ( $bulkMailCategories as $bulkMailCategory )
                $bulkmail->addToCategory( $bulkMailCategory );

            $bulkmail->send( $rfp );
        }
    }
}

?>
