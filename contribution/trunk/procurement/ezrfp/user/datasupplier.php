<?php
//
// $Id: datasupplier.php,v 1.95.2.12 2003/03/24 11:31:25 br Exp $
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

include_once( "ezrfp/classes/ezrfp.php" );
include_once( "ezrfp/classes/ezrfpcategory.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezuser/classes/ezobjectpermission.php" );
include_once( "ezuser/classes/ezuser.php" );

include_once( "ezsession/classes/ezsession.php" );

$session =& eZSession::globalSession( );


$PageCaching = $ini->read_var( "eZRfpMain", "PageCaching" );
$UserComments = $ini->read_var( "eZRfpMain", "UserComments" );

$GlobalSectionID = $ini->read_var( "eZRfpMain", "DefaultSection" );


$session->setVariable( "RedirectURL", "$REQUEST_URI" );
//die($REQUEST_URI);

switch ( $url_array[2] )
{

    case "report" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezrfp/admin/rfpreport.php" );
    }
    break;

    case "stats" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezrfp/admin/rfpreport.php" );
    }
    break;


/*
    case "stats" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezstats/admin/rfpreport.php" );
    }
    break;

    case "rfpreport" :
    {
        $Year = $url_array[3];
        $Month = $url_array[4];

        include( "ezstats/admin/rfpreport.php" );
    }
    break;
*/

    case "download":
    {
        $RfpID = $url_array[3];
        $PageNumber= $url_array[4];
        $CategoryID = $url_array[5];

//        include( "ezrfp/user/mailtofriend.php" );
//	location: filemanager/download/$urlarray[3];/$urlarray[4]
print( "Insert rfpdownload.php function <br /> Check for Authenticated Session, Insert Client Stats Capture & Redirect to File Download");
    exit();
    }
    break;

    case "emailreminders":
    {
	//        $RfpID = $url_array[3];
	$RfpJunket = 0;
        include( "ezrfp/user/rfpdeadlinereminders.php" );
    }
    break;

    case "mailtofriend":
    {
        $RfpID = $url_array[3];
        include( "ezrfp/user/mailtofriend.php" );
    }
    break;

    case "topiclist":
    {
        $TopicID = $url_array[3];
        include( "ezrfp/user/topiclist.php" );
    }
    break;

    case "map":
    case "sitemap":
    {
        if ( isset( $url_array[3] ) )
            $CategoryID = $url_array[3];
        else
            $CategoryID = "";
        include( "ezrfp/user/sitemap.php" );
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
        if ( get_class( $user ) == "ezuser" )
        {
            $groupIDArray =& $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                $first = false;
            }
        }
        else
            $user = 0;

        if ( $PageCaching == "enabled" )
        {
            include_once( "classes/ezcachefile.php" );
            $file = new eZCacheFile( "ezrfp/cache/", array( "rfpfrontpage", $GlobalSectionID, $groupstr ),
                                     "cache", "," );

            $cachedFile = $file->filename( true );

            if ( $file->exists() )
            {
                include( $cachedFile );
            }
            else
            {
                $GenerateStaticPage = "true";
                include( "ezrfp/user/frontpage.php" );
            }
        }
        else
        {
            include( "ezrfp/user/frontpage.php" );
        }

    }
    break;

    case "newsgroup":
    {
        if ( isset( $url_array[3] ) )
            $CategoryID = $url_array[3];
        else
            $CategoryID = "";

        include( "ezrfp/user/newsgroup.php" );
    }
    break;
	
	// so, what's the difference between . . . 

    case "author":
    {
        $Action = $url_array[3];
        switch ( $Action )
        {
            case "list":
            {
                if ( isset( $url_array[4] ) )
                    $SortOrder = $url_array[4];
                else
                    $SortOrder = "Name";
                include( "ezrfp/user/authorlist.php" );
                break;
            }
            case "view":
            {
                $AuthorID = $url_array[4];
                $SortOrder = $url_array[5];
                $Offset = $url_array[6];
                include( "ezrfp/user/authorview.php" );
                break;
            }
        }
        break;
    }

    case "archive":
    {
        $CategoryID = $url_array[3];
        if ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        $Offset = $url_array[4];
        if ( !is_numeric( $Offset ) )
            $Offset = 0;


        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if ( get_class( $user ) == "ezuser" )
        {
            $groupstr = $user->groupString();
        }
        else
            $user = 0;

//        print( "Checking category: $CategoryID <br>" );

        if ( $PageCaching == "enabled" )
        {
            //$CategoryID = $url_array[3];

            include_once( "classes/ezcachefile.php" );
            $file = new eZCacheFile( "ezrfp/cache/", array( "rfplist", $CategoryID, $Offset, $groupstr ),
                                     "cache", "," );

            $cachedFile = $file->filename( true );
//            print( "Cache file name: $cachedFile" );

            if ( $file->exists() )
            {
                include( $cachedFile );
            }
            else if ( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "rfp_category", 'r' ) ||
            eZRfpCategory::isOwner( $user, $CategoryID) )
                // check if user really has permissions to browse this category
            {
                $GenerateStaticPage = "true";

                include( "ezrfp/user/rfplist.php" );
            }
            else
            {
                eZHTTPTool::header( "Location: /error/403" );
                exit();

            }
        }
        else if ( $CategoryID == 0 || eZObjectPermission::hasPermission( $CategoryID, "rfp_category", 'r' )
        || eZRfpCategory::isOwner( $user, $CategoryID ) )
        {
            include( "ezrfp/user/rfplist.php" );
        }
        else
        {
            eZHTTPTool::header( "Location: /error/403" );
            exit();
        }
    }
    break;


    case "search":
    {
        if ( $url_array[3] == "advanced" )
        {
            include( "ezrfp/user/searchform.php" );
        }
        else
        {
            $Offset = 0;
            if ( $url_array[3] == "parent" )
            {
                $SearchText = urldecode( $url_array[4] );
                if ( $url_array[5] != urlencode( "+" ) )
                    $StartStamp = urldecode( $url_array[5] );
                if ( $url_array[6] != urlencode( "+" ) )
                    $StopStamp = urldecode( $url_array[6] );
                if ( $url_array[7] != urlencode( "+" ) )
                    $CategoryArray = explode( "-", urldecode( $url_array[7] ) );
                if ( $url_array[8] != urlencode( "+" ) )
                    $ContentsWriterID = urldecode( $url_array[8] );
                if ( $url_array[9] != urlencode( "+" ) )
                    $PhotographerID = urldecode( $url_array[9] );
                
                $Offset = $url_array[10];
            }
            include( "ezrfp/user/search.php" );
        }
    }
    break;

    case "index":
    {
        $CurrentIndex = urldecode( $url_array[3] );

        $user =& eZUser::currentUser();
        $groupstr = "";
        if ( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupstr .= $groupID : $groupstr .= "-$groupID";
                $first = false;
            }
        }
        include_once( "classes/ezcachefile.php" );
        $file = new eZCacheFile( "ezrfp/cache/", array( "rfpindex", $groupstr, $CurrentIndex ),
                                 "cache", "," );
            
        $cachedFile = $file->filename( true );
        if ( $file->exists() )
        {
            include( $cachedFile );
        }
        else
        {
            $GenerateStaticPage = "true";
            include( "ezrfp/user/index.php" );
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
        include( "ezrfp/user/extendedsearch.php" );
    }
    break;

    case "rfpheaderlist":
    {
        $CategoryID = $url_array[3];
        if ( !isset( $CategoryID ) || ( $CategoryID == "" ) )
            $CategoryID = 0;

        include( "ezrfp/user/rfpheaderlist.php" );
    }
    break;
    
    case "view":
    case "rfpview":
    {
        $StaticRendering = false;
        $RfpID = $url_array[3];
        $PageNumber= $url_array[4];
        $CategoryID = $url_array[5];
        if ( $PageNumber != -1 )
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) || ( $PageNumber < 1 ) )
                $PageNumber= 1;


        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if ( get_class( $user ) == "ezuser" )
        {
            $groupIDArray =& $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                $first = false;
            }
        }
        else
            $user = 0;

        $rfp = new eZRfp( $RfpID );
        $definition = $rfp->categoryDefinition( false );

        $showComments = false;

        if ( $PageCaching == "enabled" )
        {
            $cachedFile = "ezrfp/cache/rfpview," . $RfpID . ",". $PageNumber . "," . $CategoryID . "," . ( $PrintableVersion == "enabled" )  . "," . $groupstr  .".cache";

            if ( eZFile::file_exists( $cachedFile ) )
            {
//		print( " Include Cached File <br>" );
                include( $cachedFile );
                $showComments = true;
            }
            else if ( $PageCaching == "enabled" )
            {
		// $user == false
		// else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition ) || eZRfp::isAuthor( $user, $RfpID ) )

                $GenerateStaticPage = "true";

                include( "ezrfp/user/rfpview.php" );
                $showComments = true;
            }
            else
            {
		print( " Empty Else <br>" );

            }
        }
        else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                  || eZRfp::isAuthor( $user, $RfpID ) )
        {

	// here we go
            include( "ezrfp/user/rfpview.php" );
            $showComments = true;
        }
        else
        {
	// user bug, user permissions don't match author article permissions
	// here we go
            include( "ezrfp/user/rfpview.php" );
            $showComments = true;
        }

        /* Should there be permissions here? */
        if ( $showComments == true )
        {
            if  ( ( $PrintableVersion != "enabled" ) && ( $UserComments == "enabled" ) )
            {
                $RedirectURL = "/rfp/view/$RfpID/$PageNumber/";
                $rfp = new eZRfp( $RfpID );
                if ( ( $rfp->id() >= 1 ) && $rfp->discuss() )
                {
                    for ( $i = 0; $i < count( $url_array ); $i++ )
                    {
                        if ( ( $url_array[$i] ) == "parent" )
                        {
                            $next = $i + 1;
                            $Offset = $url_array[$next];
                        }
                    }
                    $forum = $rfp->forum();
                    $ForumID = $forum->id();
                    include( "ezforum/user/messagesimplelist.php" );
                }
            }
        }
    }
    break;

    case "rfpuncached":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $RfpID = $url_array[3];
        $PageNumber= $url_array[4];
        $CategoryID = $url_array[5];

        $user =& eZUser::currentUser();

        $rfp = new eZRfp( $RfpID );
        $definition = $rfp->categoryDefinition( false );

        if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                  || eZRfp::isAuthor( $user, $RfpID ) )
        {
            if ( !isset( $PageNumber ) || ( $PageNumber == "" ) || ( $PageNumber < 1 ) )
                $PageNumber= 1;

            include( "ezrfp/user/rfpview.php" );
        }
    }
    break;

    case "print":
    case "rfpprint":
    {
        $PrintableVersion = "enabled";

        $StaticRendering = false;
        $RfpID = $url_array[3];
        $PageNumber= $url_array[4];
        $CategoryID = $url_array[5];

        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if ( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
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

        $rfp = new eZRfp( $RfpID );
        $definition = $rfp->categoryDefinition( true );
        $definition = $definition->id();
        
        if ( $PageCaching == "enabled" )
        {
             $cachedFile = "ezrfp/cache/rfpprint," . $RfpID . ",". $PageNumber . "," . $CategoryID . "," . $groupstr  .".cache";
            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                      || eZRfp::isAuthor( $user, $RfpID ) )
            {
                $GenerateStaticPage = "true";
                
                include( "ezrfp/user/rfpview.php" );
            }
        }
        else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                  || eZRfp::isAuthor( $user, $RfpID ) )
        {
            include( "ezrfp/user/rfpview.php" );
        }
    }
    break;

    case "static":
    case "rfpstatic":
    {
        $ViewMode = "static";

        $StaticRendering = true;
        $RfpID = $url_array[3];
		if ( isset( $url_array[4] ) )
	        $PageNumber = $url_array[4];
		else
			$PageNumber = "";

        // if file exists... evrything is ok..
        // if not.. check permission, then run page if ok
        $user =& eZUser::currentUser();
        $groupstr = "";
        if ( get_class( $user ) == "ezuser" )
        {
            $groupIDArray = $user->groups( false );
            sort( $groupIDArray );
            $first = true;
            foreach ( $groupIDArray as $groupID )
            {
                $first ? $groupstr .= "$groupID" : $groupstr .= "-$groupID";
                $first = false;
            }
        }
        else
            $user = 0;
        
        if ( !isset( $CategoryID ) )
            $CategoryID = eZRfp::categoryDefinitionStatic( $RfpID );
        
        $GlobalSectionID = eZRfpCategory::sectionIDStatic( $CategoryID );

        if ( !isset( $PageNumber ) || ( $PageNumber == "" ) || ( $PageNumber < 1 ) )
            $PageNumber= 1;

        $rfp = new eZRfp( $RfpID );
        $definition = $rfp->categoryDefinition( true );
        $definition = $definition->id();

        if ( $PageCaching == "enabled" )
        {
            $cachedFile = "ezrfp/cache/rfpview," . $RfpID . ",". $PageNumber . "," . $CategoryID . "," . $groupstr  .".cache";
            if ( eZFile::file_exists( $cachedFile ) )
            {
                include( $cachedFile );
            }
            else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                      || eZRfp::isAuthor( $user, $RfpID ) )
            {
                $GenerateStaticPage = "true";
                
                include( "ezrfp/user/rfpview.php" );
            }
        }
        else if ( eZObjectPermission::hasPermissionWithDefinition( $RfpID, "rfp_rfp", 'r', false, $definition )
                  || eZRfp::isAuthor( $user, $RfpID ) )
        {

            include( "ezrfp/user/rfpview.php" );
        }
    }
    break;

    case "rssheadlines":
    {
        include( "ezrfp/user/rfplistrss.php" );
    }
    break;

    case "rfpedit":
    {
        if ( eZUser::currentUser() != false &&
             $ini->read_var( "eZRfpMain", "UserSubmitRfps" ) == "enabled" )
        {
            switch ( $url_array[3] )
            {
                case "new":
                {
                    $Action = "New";
                    include( "ezrfp/user/rfpedit.php" );
                    break;
                }
                case "edit":
                {
                    $Action = "Edit";
                    include( "ezrfp/user/rfpedit.php" );
                    break;
                }
                case "insert":
                {
                    $Action = "Insert";
                    $RfpID = $url_array[4];
                    include( "ezrfp/user/rfpedit.php" );
                    break;
                }
                case "update":
                {
                    $Action = "Update";
                    $RfpID = $url_array[4];
                    include( "ezrfp/user/rfpedit.php" );
                    break;
                }
                case "cancel":
                {
                    $Action = "Cancel";
                    $RfpID = $url_array[4];
                    include( "ezrfp/user/rfpedit.php" );
                    break;
                }
                case "imagelist" :
                {
                    $RfpID = $url_array[4];
                    if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                         || eZRfp::isAuthor( $user, $RfpID ) )
                        include( "ezrfp/user/imagelist.php" );
                    break;
                }
                case "filelist" :
                {
                    $RfpID = $url_array[4];
                    if ( eZObjectPermission::hasPermission(  $RfpID, "rfp_rfp", 'w' )
                         || eZRfp::isAuthor( $user, $RfpID ) )
                        include( "ezrfp/user/filelist.php" );
                    break;
                }
                case "imageedit" :
                {
                    switch ( $url_array[4] )
                    {
                        case "new" :
                        {
                            $Action = "New";
                            $RfpID = $url_array[5];
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/imageedit.php" );
                        }
                        break;

                        case "edit" :
                        {
                            $Action = "Edit";
                            $RfpID = $url_array[6];
                            $ImageID = $url_array[5];
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/imageedit.php" );
                        }
                        break;

                        case "storedef" :
                        {
                            $Action = "StoreDef";
                            if ( isset( $DeleteSelected ) )
                                $Action = "Delete";
                            $RfpID = $url_array[5];
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/imageedit.php" );
                        }
                        break;

                        default :
                        {
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/imageedit.php" );
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
                            $RfpID = $url_array[5];
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/fileedit.php" );
                        }
                        break;
                        
                        case "delete" :
                        {
                            $Action = "Delete";
                            $RfpID = $url_array[6];
                            $FileID = $url_array[5];
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/fileedit.php" );
                        }
                        break;
                        
                        default :
                        {
                            if ( eZObjectPermission::hasPermission( $RfpID, "rfp_rfp", 'w' )
                                 || eZRfp::isAuthor( $user, $RfpID ) )
                                include( "ezrfp/user/fileedit.php" );
                        }
                    }
                }
            }
        }
        else
        {
            include_once( "classes/ezhttptool.php" );
	    // eZHTTPTool::header( "Location: http://ladivaloca.org/index.php/rfp/author/list" );
            eZHTTPTool::header( "Location: /rfp/archive/" );
            exit();
        }
    }
    break;
    
    // XML rpc interface
    case "xmlrpc" :
    {
        include( "ezrfp/xmlrpc/xmlrpcserver.php" );
    }
    break;
}

?>
