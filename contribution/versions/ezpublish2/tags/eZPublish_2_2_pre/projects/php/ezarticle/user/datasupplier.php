<?php
//
// $Id: datasupplier.php,v 1.93 2001/10/11 07:02:04 th Exp $
//
// Created on: <23-Oct-2000 17:53:46 bf>
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

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlecategory.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezuser/classes/ezuser.php" );

$PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );
$UserComments = $ini->read_var( "eZArticleMain", "UserComments" );

$GlobalSectionID = $ini->read_var( "eZArticleMain", "DefaultSection" );

switch ( $url_array[2] )
{
    case "mailtofriend":
    {
        $ArticleID = $url_array[3];
        include( "ezarticle/user/mailtofriend.php" );
    }
    break;

    case "topiclist":
    {
        $TopicID = $url_array[3];
        include( "ezarticle/user/topiclist.php" );
    }
    break;
    
    case "sitemap":
    {
        if ( isset( $url_array[3] ) )
            $CategoryID = $url_array[3];
        else
            $CategoryID = "";
        include( "ezarticle/user/sitemap.php" );        
    }
    break;

    case "frontpage":
    {
        if ( isset( $url_array[3] ) )
            $GlobalSectionID = $url_array[3];

        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray =& $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        else
            $user = 0;

$PageCaching = false;
        if ( $PageCaching == "enabled" )
        {
            include_once( "classes/ezcachefile.php" );
            $file = new eZCacheFile( "ezarticle/cache/", array( "articlefrontpage", $GlobalSectionID, $groupstr ),
                                     "cache", "," );
        
            $cachedFile = $file->filename( true );
        
            if ( $file->exists() )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";                
                include( "ezarticle/user/frontpage.php" );
            }
        }
        else 
        {
            include( "ezarticle/user/frontpage.php" );
        }

    }
    break;

    case "newsgroup":
    {
        if ( isset( $url_array[3] ) )
            $CategoryID = $url_array[3];
        else
            $CategoryID = "";

        include( "ezarticle/user/newsgroup.php" );        
    }
    break;

    case "author":
    {
        $Action = $url_array[3];
        switch( $Action )
        {
            case "list":
            {
                if ( isset( $url_array[4] ) )
                    $SortOrder = $url_array[4];
                else
                    $SortOrder = "Name";
                include( "ezarticle/user/authorlist.php" );
                break;
            }
            case "view":
            {
                $AuthorID = $url_array[4];
                $SortOrder = $url_array[5];
                $Offset = $url_array[6];
                include( "ezarticle/user/authorview.php" );
                break;
            }
        }
        break;
    }

    case "archive":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $Offset = $url_array[4];
        if  ( !is_numeric( $Offset ) )
            $Offset = 0;


        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray =& $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        else
            $user = 0;
//        print( "Checking category: $CategoryID <br>" );

        if ( $PageCaching == "enabled" )
        {
            //$CategoryID = $url_array[3];

            include_once( "classes/ezcachefile.php" );
            $file = new eZCacheFile( "ezarticle/cache/", array( "articlelist", $CategoryID, $Offset, $groupstr ),
                                     "cache", "," );
            
            $cachedFile = $file->filename( true );
//            print( "Cache file name: $cachedFile" );
            if ( $file->exists() )
            {
                include( $cachedFile );
            }
            else if( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "article_category", 'r' ) ||
            eZArticleCategory::isOwner( $user, $CategoryID) )
                // check if user really has permissions to browse this category
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articlelist.php" );
            }
        }
        else if( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "article_category", 'r' ) ||
        eZArticleCategory::isOwner( $user, $CategoryID) )
        {
            include( "ezarticle/user/articlelist.php" );
        }
        
    }
    break;


    case "search":
    {
        if( $url_array[3] == "advanced" )
        {
            include( "ezarticle/user/searchform.php" );
        }
        else
        {
            $Offset = 0;
            if( $url_array[3] == "parent" )
            {
                $SearchText = urldecode( $url_array[4] );
                if( $url_array[5] != urlencode( "+" ) )
                    $StartStamp = urldecode( $url_array[5] );
                if( $url_array[6] != urlencode( "+" ) )
                    $StopStamp = urldecode( $url_array[6] );
                if( $url_array[7] != urlencode( "+" ) )
                    $CategoryArray = explode( "-", urldecode( $url_array[7] ) );
                if( $url_array[8] != urlencode( "+" ) )
                    $ContentsWriterID = urldecode( $url_array[8] );
                if( $url_array[9] != urlencode( "+" ) )
                    $PhotographerID = urldecode( $url_array[9] );
                
                $Offset = $url_array[10];
            }
            include( "ezarticle/user/search.php" );
        }
    }
    break;

    case "index":
    {
        $CurrentIndex = urldecode( $url_array[3] );

        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        include_once( "classes/ezcachefile.php" );
        $file = new eZCacheFile( "ezarticle/cache/", array( "articleindex", $groupstr, $CurrentIndex ),
                                 "cache", "," );
            
        $cachedFile = $file->filename( true );
        if ( $file->exists() )
        {
            include( $cachedFile );
        }
        else
        {
            $GenerateStaticPage = "true";
            include( "ezarticle/user/index.php" );
        }
    }
    break;

    case "extendedsearch":
    {
        if ( !isset( $Category ) and count( $url_array ) > 5 )
        {
            $Category = trim( urldecode( $url_array[4] ) );
        }
        if ( !isset( $SearchText ) and count( $url_array ) > 5 )
        {
            $SearchText = trim( urldecode( $url_array[3] ) );
        }
        if ( count( $url_array ) > 5 )
            $Offset = $url_array[5];
        if ( count( $url_array ) > 5 )
            $Search = true;
        include( "ezarticle/user/extendedsearch.php" );
    }
    break;

    case "articleheaderlist":
    {
        $CategoryID = $url_array[3];
        if  ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezarticle/user/articleheaderlist.php" );
    }
    break;
    
    case "view":
    case "articleview":
    {
        $StaticRendering = false;        
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];
        $CategoryID = $url_array[5];
        if ( $PageNumber != -1 )
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ) )
                $PageNumber= 1;
        
        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray =& $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        else
            $user = 0;

        $article = new eZArticle( $ArticleID );
        $definition = $article->categoryDefinition( true );
        $definition = $definition->id();

        if ( $PageCaching == "enabled" )
        {
            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber . "," . $CategoryID . "," . $groupstr  .".cache";
            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else if ( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                     || eZArticle::isAuthor( $user, $ArticleID ) )
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }
        }
        else if ( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                  || eZArticle::isAuthor( $user, $ArticleID ) )
        {
            include( "ezarticle/user/articleview.php" );
        }
        
        /* Should there be permissions here? */
        if  ( ( $PrintableVersion != "enabled" ) && ( $UserComments == "enabled" ) )
        {
            $RedirectURL = "/article/view/$ArticleID/$PageNumber/";
            $article = new eZArticle( $ArticleID );
            if ( ( $article->id() >= 1 ) && $article->discuss() )
            {
                for ( $i=0; $i < count ( $url_array ); $i++ )
                {
                    if ( ( $url_array[$i] ) == "parent" )
                    {
                        $next = $i+1;
                        $Offset = $url_array[$next];
                    }
                }
                $forum = $article->forum();
                $ForumID = $forum->id();
                include( "ezforum/user/messagesimplelist.php" );
            }
        }        
    }
    break;

    case "articleuncached":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];

        
        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ) )
            $PageNumber= 1;
        
        include( "ezarticle/user/articleview.php" );
    }
    break;

    case "print":
    case "articleprint":
    {
        $PrintableVersion = "enabled";
        
        $StaticRendering = false;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];

        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        else
            $user = 0;

        
        if ( $PageNumber != -1 )
        {
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) )
                $PageNumber = -1;
            else if ( $PageNumber < 1 )
                $PageNumber = 1;
        }

        $article = new eZArticle( $ArticleID );
        $definition = $article->categoryDefinition( true );
        $definition = $definition->id();
        
        if ( $PageCaching == "enabled" )
        {
            $cachedFile = "ezarticle/cache/articleprint," . $ArticleID . ",". $PageNumber . "," . $groupstr . ".cache";
            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else if( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                     || eZArticle::isAuthor( $user, $ArticleID ) )
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }
        }
        else if( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                 || eZArticle::isAuthor( $user, $ArticleID ) )
        {
            include( "ezarticle/user/articleview.php" );
        }
    }
    break;

    case "static":
    case "articlestatic":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $ArticleID = $url_array[3];
        $PageNumber= $url_array[4];

        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach( $groupIDArray as $groupID )
                {
                    $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                    $first = false;
                }
        }
        else
            $user = 0;
        
        if ( !isset( $CategoryID ) )
            $CategoryID = eZArticle::categoryDefinitionStatic( $ArticleID );
        
        $GlobalSectionID = eZArticleCategory::sectionIDStatic( $CategoryID );

        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) ||  ( $PageNumber < 1 ) )
            $PageNumber= 1;

        $article = new eZArticle( $ArticleID );
        $definition = $article->categoryDefinition( true );
        $definition = $definition->id();
        
        if ( $PageCaching == "enabled" )
        {
            $cachedFile = "ezarticle/cache/articleview," . $ArticleID . ",". $PageNumber . "," . $groupstr .".cache";
            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else if( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                     || eZArticle::isAuthor( $user, $ArticleID ) )
            {
                $GenerateStaticPage = "true";
                
                include( "ezarticle/user/articleview.php" );
            }
        }
        else if( eZObjectPermission::hasPermissionWithDefinition( $ArticleID, "article_article", 'r', false, $definition )
                 || eZArticle::isAuthor( $user, $ArticleID ) )
        {
            include( "ezarticle/user/articleview.php" );
        }
    }
    break;

    case "rssheadlines":
    {
        include( "ezarticle/user/articlelistrss.php" );
    }
    break;

    case "articleedit":
    {
        if ( eZUser::currentUser() != false &&
             $ini->read_var( "eZArticleMain", "UserSubmitArticles" ) == "enabled" )
        {
            switch ( $url_array[3] )
            {
                case "new":
                {
                    $Action = "New";
                    include( "ezarticle/user/articleedit.php" );
                    break;
                }
                case "edit":
                {
                    $Action = "Edit";
                    include( "ezarticle/user/articleedit.php" );
                    break;
                }

                case "insert":
                {
                    $Action = "Insert";
                    $ArticleID = $url_array[4];
                    include( "ezarticle/user/articleedit.php" );
                    break;
                }
                case "update":
                {
                    $Action = "Update";
                    $ArticleID = $url_array[4];
                    include( "ezarticle/user/articleedit.php" );
                    break;
                }

                case "cancel":
                {
                    $Action = "Cancel";
                    $ArticleID = $url_array[4];
                    include( "ezarticle/user/articleedit.php" );
                    break;
                }
                case "imagelist" :
                {
                    $ArticleID = $url_array[4];
                    if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                        || eZArticle::isAuthor( $user, $ArticleID ) )
                        include( "ezarticle/user/imagelist.php" );
                    break;
                }

                case "filelist" :
                {
                    $ArticleID = $url_array[4];
                    if( eZObjectPermission::hasPermission(  $ArticleID, "article_article", 'w' )
                        || eZArticle::isAuthor( $user, $ArticleID ) )
                        include( "ezarticle/user/filelist.php" );
                }
                break;


                case "imageedit" :
                {
                    switch ( $url_array[4] )
                    {
                        case "new" :
                        {
                            $Action = "New";
                            $ArticleID = $url_array[5];
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/imageedit.php" );
                        }
                        break;

                        case "edit" :
                        {
                            $Action = "Edit";
                            $ArticleID = $url_array[6];
                            $ImageID = $url_array[5];
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/imageedit.php" );
                        }
                        break;

                        case "storedef" :
                        {
                            $Action = "StoreDef";
                            if ( isset( $DeleteSelected ) )
                                $Action = "Delete";
                            $ArticleID = $url_array[5];
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/imageedit.php" );
                        }
                        break;

                        default :
                        {
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/imageedit.php" );
                        }
                    }
                }
                break;

                case "fileedit" :
                {
                    switch ( $url_array[4] )
                    {
                        case "new" :
                        {
                            $Action = "New";
                            $ArticleID = $url_array[5];
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/fileedit.php" );
                        }
                        break;
                        
                        case "delete" :
                        {
                            $Action = "Delete";
                            $ArticleID = $url_array[6];
                            $FileID = $url_array[5];
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/fileedit.php" );
                        }
                        break;
                        
                        default :
                        {
                            if( eZObjectPermission::hasPermission( $ArticleID, "article_article", 'w' )
                                || eZArticle::isAuthor( $user, $ArticleID ) )
                                include( "ezarticle/user/fileedit.php" );
                        }
                    }
                }
            }
        }
        else
        {
            include_once( "classes/ezhttptool.php" );
            eZHTTPTool::header( "Location: /article/archive/" );
            exit();
        }
    }
    break;



    
    // XML rpc interface
    case "xmlrpc" :
    {
        include( "ezarticle/xmlrpc/xmlrpcserver.php" );
    }
    break;
}

?>
