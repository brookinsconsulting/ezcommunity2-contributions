<?php
// 
// $Id: ezarticletool.php,v 1.3 2001/04/30 09:54:08 fh Exp $
//
// Definition of eZArticleTool class
//
// Jan Borsodi <jb@ez.no>
// Created on: <27-Apr-2001 14:08:05 amos>
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

//!! 
//! The class eZArticleTool has functions for article handling.
/*!

*/

include_once( "classes/ezcachefile.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/eztexttool.php" );

include_once( "ezarticle/classes/ezarticlerenderer.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezbulkmail/classes/ezbulkmail.php" );
include_once( "ezbulkmail/classes/ezbulkmailcategory.php" );

class eZArticleTool
{
    /*!
      \static
      Deletes the cache files for a given article and it's categories.
    */
    function deleteCache( $ArticleID, $CategoryID, $CategoryArray )
    {
        $user = eZUser::currentUser();

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( array( "articleprint", "articleview", "articlestatic", "static", "view", "print"  ),
                                             $ArticleID, NULL, NULL ), "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( array( "articlelist", "list" ),
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             NULL, array( "", NULL ) ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }


        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articlelinklist",
                                             array_merge( 0, $CategoryID, $CategoryArray ),
                                             $ArticleID,
                                             NULL ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }

        $files =& eZCacheFile::files( "ezarticle/cache/",
                                      array( "articleindex",
                                             NULL ),
                                      "cache", "," );
        foreach( $files as $file )
        {
            $file->delete();
        }
    }

    function notificationMessage( &$article )
    {
        include_once( "classes/eztexttool.php" );
        $ini =& INIFile::globalINI();

        $PublishNoticeReceiver = $ini->read_var( "eZArticleMain", "PublishNoticeReceiver" );
        $PublishNoticeSender = $ini->read_var( "eZArticleMain", "PublishNoticeSender" );
        $PublishNoticePadding = $ini->read_var( "eZArticleMain", "PublishNoticePadding" );
        $PublishSite = $ini->read_var( "site", "SiteTitle" );
        $SiteURL = $ini->read_var( "site", "SiteURL" );

        $mailTemplate = new eZTemplate( "ezarticle/admin/" . $ini->read_var( "eZArticleMain", "AdminTemplateDir" ),
                                        "ezarticle/admin/intl", $ini->read_var( "eZArticleMain", "Language" ), "mailtemplate.php" );
    
        $mailTemplate->set_file( "mailtemplate", "mailtemplate.tpl" );
        $mailTemplate->setAllStrings();

        $renderer = new eZArticleRenderer( $article );

        $subjectLine = $mailTemplate->Ini->read_var( "strings", "subject" );
        $subjectLine = $subjectLine . " " . $PublishSite;

        $intro = eZTextTool::linesplit(strip_tags( $renderer->renderIntro( ) ), $PublishNoticePadding, 76 );

        $mailTemplate->set_var( "body", "$intro" );
        $mailTemplate->set_var( "site", "$PublishSite" );
        $mailTemplate->set_var( "title", $article->name( false ) );
        $mailTemplate->set_var( "author", $article->authorText( false ) );
    
        $mailTemplate->set_var( "link", "http://" . $SiteURL . "/article/articleview/" . $article->id() );

        $bodyText = $mailTemplate->parse( "dummy", "mailtemplate" );
    
        // send a notice mail
        $noticeMail = new eZMail();

        $noticeMail->setFrom( $PublishNoticeSender );
        $noticeMail->setTo( $PublishNoticeReceiver );

        $noticeMail->setSubject( $subjectLine );
        $noticeMail->setBodyText( $bodyText );

        $noticeMail->send();

        // Send bulkmail also
        $category = eZBulkMailCategory::singleList();
        if( is_object( $category ) ) // send a mail to this group
        {
            $bulkmail = new eZBulkMail();
            $bulkmail->setOwner( eZUser::currentUser() );

            $bulkmail->setSender( $PublishNoticeSender  ); // from NAME
            $bulkmail->setSubject( $subjectLine );
            $bulkmail->setBodyText( $bodyText );

            $bulkmail->setIsDraft( false );
    
            $bulkmail->store();
            $category->addMail( $bulkmail );
            $bulkmail->send();
        }
    }

}

?>
