<?php
// 
// $Id: eznewsitemviewer.php,v 1.18 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsItemViewer class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <20-Sep-2000 13:03:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//
//!! eZNews
//! eZNewsItemViewer handles the kind of info the user sees.
/*!
    This class will be called first when getting into the eZNews system.
    This class will based on the url query decide which other creators/viewers
    are called. It will also do some editing stuff, but only if we're on the
    edit site.

    Example URLs:
    \code
    http://www.site.com/news                    - main news page
    http://www.site.com/news/date               - date based pages, ie. watch the state at any given time
    http://www.site.com/news/author             - author based pages, ie. read articles by author
    http://www.site.com/news/##                 - view an article or category based on its ID number
    http://www.site.com/news/path/to/somewhere  - view a category or article based on it's path
    http://www.site.com/news/
    \endcode
    
    This class adds the following directives to the ini files [eZNewsAdmin] group:
    
    <dl>
        <dt>Adminsite
        <dd>A regexp to determine if we're doing admin work. The two normal set ups are:
        <ul>
        <li>^admin for site admin through "admin.site.com".
        <li>^/admin/ for site admin through "site.com/admin/".
        </ul>
        <dt>OrphansMainPage
        <dd>The number of orphaned items in the site displayed on the main page.
    </dl>
 */

include_once( "classes/eztemplate.php" );
include_once( "eznews/admin/eznewsitem/eznewsimageviewer.php" );
include_once( "eznews/admin/eznewsitem/eznewsitemcreator.php" );
include_once( "eznews/classes/eznewsitem.php" );  
include_once( "eznews/classes/eznewsitemtype.php" );  
include_once( "eznews/classes/eznewsoutput.php" );  
include_once( "classes/ezurl.php" );

class eZNewsItemViewer
{
    /*!
        Just initalizing some variables.
        
        \in
            \$inURLObject An eZURL object.
            \$inNewsConfigFileName The name of the file with the global config options.
     */
    function eZNewsItemViewer( $inNewsConfigFileName )
    {
        #echo "eZNewsItemViewer::eZNewsItemViewer( \$inURLObject = $inURLObject, \$inIniFileName = $inIniFileName )<br>\n";

        $this->URLObject = new eZURL();
        $this->IniObject = new eZNewsOutput( $inNewsConfigFileName );
        
        $this->inNewsConfigFileName = $inNewsConfigFileName;
    }



    /*!
        Function which finds out if we're in an admin site, or if we're
        in a normal site.
        
        /return
            Returns true if an action was taken.
     */
    function doActions()
    {
        #echo "eZNewsItemViewer::doActions()<br>\n";

        global $SERVER_NAME;
        global $REQUEST_URI;
        $value = false;
        
        $Adminsite = $this->IniObject->GlobalIni->read_var( "eZNewsAdmin", "Adminsite" );
        
        if( ereg( $Adminsite, $SERVER_NAME ) || ereg( $Adminsite, $REQUEST_URI ) )
        {
            $value = $this->doAdmin();
        }
        else
        {
            $value = $this->doNormal();
        }
        
        return $value;
    }
    
    
    
    function doAdmin()
    {
        #echo "eZNewsItemViewer::doAdmin()<br>\n";
        $count = $this->URLObject->getURLCount();

        if( $count >= 3 )
        {
            switch( $this->URLObject->getURLPart( 1 ) )
            {
                case "itemtype":
                case "changetype":
                    break;
                case "id":
                case "article":
                    $this->doAdminAction( $this->URLObject->getURLPart( 2 ) );
                    break;
                case "date":
                    //$item = $this->parseDate();
                    break;
                case "author":
                    //$item = $this->parseAuthor();
                    break;
                case "category":
                case "path":
                case "definition":
                    //$item = $this->parseCategory();
                    break;
                default:
                    $this->doAdminTopAction();
                    break;
                
            }
        }
        if( $count == 2 )
        {
            $this->doAdminAction( $this->URLObject->getURLPart( 1 ) );
        }
        
        if( $count == 1 )
        {
            $this->doAdminTopAction();
        }
    }


   
    /*!
        This function will show the apropriate interface for the top of a tree.
        \return
            Returns true if an action was taken.
     */
    function doAdminTopAction()
    {
        $value = true;
        
        $this->IniObject->readAdminTemplate( "eznewsitem", "eznewsitem.php" );        
        $this->IniObject->set_file( array( "eznewscommand" => "eznewsitemtop.tpl" ) );

        $this->Item = new eZNewsItem( 1 );
        
        $this->doAdminOrphans();
        $this->doAdminNavigate();
        
        // Output the admin page
        $this->IniObject->setAllStrings();
        $this->IniObject->pparse( "output", "eznewscommand" );
        
        return $value;
    }
    
    
    
    /*!
        This function will show the apropriate interface for an item or action.
        
        The interface shown is dependant on the uri sent in, and if the uri has
        legal values.
        
        \return
            Returns true if an action was taken.
     */
    function doNormalAction( $itemNo )
    {
        #echo "eZNewsItemViewer::doAdminAction()<br>\n";
        $continue = true;
        $value = false;
        
        if( $continue )
        {
            $value = $this->doNormalBrowse( $itemNo );
            $continue = false;
        }
        
        return $value;
    }
    
    
    /*!
        This function will show the apropriate interface for an item or action.
        
        The interface shown is dependant on the uri sent in, and if the uri has
        legal values.
        
        \return
            Returns true if an action was taken.
     */
    function doAdminAction( $itemNo )
    {
        #echo "eZNewsItemViewer::doAdminAction( \$itemNo = $itemNo )<br>\n";
        $continue = true;
        $value = false;
        
        $this->URLObject->getQueries( $queries, "image" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $item = new eZNewsImageViewer( $this->IniObject, $itemNo );
            
            if( !$item->isFinished() )
            {
                $continue = false;
            }
        }
        
        
        
        $this->URLObject->getQueries( $queries, "file" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            #$item = new eZNewsImageViewer( $this->IniObject, $itemNo );
            
            #if( !$item->isFinished() )
            #{
            #    $continue = false;
            #}
        }
        
        
        
        $this->URLObject->getQueries( $queries, "^add" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminAdd( $itemNo );
            $continue = false;
        }
        
        
        
        $this->URLObject->getQueries( $queries, "^delete" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminDelete( $itemNo );
            $continue = false;
        }
        


        $this->URLObject->getQueries( $queries, "^create" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminCreate( $itemNo );
            $continue = false;
        }
        


        $this->URLObject->getQueries( $queries, "^edit" );
        $count = count( $queries );
        
        if( $count && $continue  )
        {
            $value = $this->doAdminEdit( $itemNo );
            $continue = false;
        }


        
        if( $continue )
        {
            $value = $this->doAdminBrowse( $itemNo );
            $continue = false;
        }
        
        return $value;
    }
    
    
    
    /*!
        This function will find the correct thing to add to a news item based
        on the add argument sent in with the url.
        
        If the function isn't in the defined list, the search will fail.
        
        Default is to show the brows interface.
        
        \return
            Returns true when an action has been taken.
     */
    function doAdminAdd( &$itemNo )
    {
        #echo "eZNewsItemViewer::doAdminAdd()<br>\n";
        
        $this->URLObject->getQueries( $queries, "^add\+parent" );
        $count = count( $queries );
        $continue = true;
        $value = false;
        
        if( $count && $continue )
        {
            $value = $this->doAdminAddParent( $itemNo );
            $continue = false;
        }
        
        $this->URLObject->getQueries( $queries, "^add\+child" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminAddChild( $itemNo );
            $continue = false;
        } 
        
        if( $continue )
        {
            $value = $this->doAdminBrowse( $this->URLObject->getURLPart( 2 ) );
            $value = false;
        }
        
        return $value;
    }



    function doAdminCreate( &$itemNo )
    {
        echo "eZNewsItemViewer::doAdminCreate()<br>\n";
        $this->URLObject->getQueries( $queries, "^create\+parent" );
        $count = count( $queries );
        $continue = true;
        $value = false;
        
        if( $count && $continue )
        {
            $value = $this->doAdminCreateParent( $itemNo );
            $continue = false;
        }
        
        $this->URLObject->getQueries( $queries, "^create\+child" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $item = new eZNewsItemCreator( $this->inNewsConfigFileName, $itemNo );
        
            if( !$item->doAction( "create", "child" ) )
            {
                $continue = false;
            }
        } 
        
        $this->URLObject->getQueries( $queries, "^create\+this" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $item = new eZNewsItemCreator( $this->inNewsConfigFileName, $itemNo );
        
            if( !$item->doAction( "create", "this" ) )
            {
                $continue = false;
            }
        } 
        
       if( $continue )
        {
            $value = $this->doAdminBrowse( $this->URLObject->getURLPart( 2 ) );
            $value = false;
        }
        
        return $value;
    }



    /*!
        This function takes care of editing of objects.
     */
    function doAdminEdit( &$itemNo )
    {
        echo "eZNewsItemViewer::doAdminEdit()<br>\n";
        $item = new eZNewsItemCreator( $this->inNewsConfigFileName, $itemNo );
        $item->doAction( "edit", "this" );
    }



    /*!
        This function takes care of deletion of objects.
     */
    function doAdminDelete( &$inItemNo )
    {
        #echo "eZNewsItemViewer::doAdminDelete( \$inItemNo = $inItemNo )<br>\n";
        
        global $delete;
        $value = true;

        $this->IniObject->readAdminTemplate( "eznewsitem", "eznewsitem.php" );
        $this->initalizeItem( $inItemNo );
        
        if( isset( $delete ) )
        {
            $this->IniObject->set_file( array( "eznewsitem" => "eznewsitemdeleted.tpl" ) );

            $this->doThis();

            $this->IniObject->setAllStrings();

            $this->Item->delete();
            
            // Output the admin page
            $this->IniObject->pparse( "output", "eznewsitem" );
        }
        else
        {
            $this->IniObject->set_file( array( "eznewsitem" => "eznewsitemdelete.tpl" ) );

            $this->doThis();

            $this->IniObject->setAllStrings();

            // Output the admin page
            $this->IniObject->pparse( "output", "eznewsitem" );
        }
        return $value;
    }



    function doNormalBrowse( &$inItemNo )
    {
        $value = true;
        // Checks if we''re dealing with a special customer.
        $special = $this->IniObject->GlobalIni->read_var( "eZNewsMain", "Customer" );
        if( !strcmp( $special, "true" ) )
        {
            global $ItemID;
            $ItemID = $inItemNo;
            $tempItem = new eZNewsItem( $inItemNo );
            $itemType = new eZNewsItemType( $tempItem->itemTypeID() );

            $class = $itemType->eZClass();
            $class = $class . "Viewer";

            include_once( strtolower( "eznews/admin/eznewsitem/" . $class . ".php" ) );
            
            $object = new $class( $this->inNewsConfigFileName, $inItemNo );
            $object->doAction( "view", "this" );
        }
    }
    
    function doAdminBrowse( &$inItemNo )
    {
        #echo "eZNewsItemViewer::doAdminBrowse( \$inItemNo = $inItemNo )<br>\n";
        $value = true;
        
        // Checks if we''re dealing with a special customer.
        $special = $this->IniObject->GlobalIni->read_var( "eZNewsMain", "Customer" );
        if( !strcmp( $special, "true" ) )
        {
            global $ItemID;
            $ItemID = $inItemNo;
            $tempItem = new eZNewsItem( $inItemNo );
            $itemType = new eZNewsItemType( $tempItem->itemTypeID(), true );

            $class = $itemType->eZClass();
            $class = $class . "Creator";

            include_once( strtolower( "eznews/admin/eznewsitem/" . $class . ".php" ) );
            
            $object = new $class( $this->inNewsConfigFileName, $inItemNo );
            $object->doAction( "view", "this" );
        }
        else
        {
            $this->IniObject->readAdminTemplate( "eznewsitem", "eznewsitem.php" );
            if( $this->initalizeItem( $inItemNo ) )
            {
                $this->IniObject->set_file( array( "eznewsitem" => "eznewsitem.tpl" ) );
                $this->IniObject->set_block( "eznewsitem", "item_template", "item" );

                $this->fillInHiearchy( "parent" );
                $this->fillInHiearchy( "child" );
                $this->fillInHiearchy( "image" );
                $this->fillInHiearchy( "file" );
                $this->fillInHiearchy( "error" );

            }
            else
            {
                $this->IniObject->set_file( array( "eznewsitem" => "eznewsitem.tpl" ) );
                $this->IniObject->set_block( "eznewsitem", "item_template", "item" );
                $this->IniObject->set_block( "eznewsitem", "image_template", "image" );
                $this->IniObject->set_block( "eznewsitem", "images_template", "images" );
                $this->IniObject->set_block( "eznewsitem", "no_images_template", "no_images" );
                $this->IniObject->set_block( "eznewsitem", "parents_template", "parents" );
                $this->IniObject->set_block( "eznewsitem", "no_parents_template", "no_parents" );
                $this->IniObject->set_block( "eznewsitem", "children_template", "children" );
                $this->IniObject->set_block( "eznewsitem", "no_children_template", "no_children" );
                $this->IniObject->set_block( "eznewsitem", "file_template", "file" );
                $this->IniObject->set_block( "eznewsitem", "files_template", "files" );
                $this->IniObject->set_block( "eznewsitem", "no_files_template", "no_files" );

                $this->IniObject->set_var( "item", "" );
                $this->IniObject->set_var( "image", "" );
                $this->IniObject->set_var( "images", "" );
                $this->IniObject->set_var( "no_images", "" );
                $this->IniObject->set_var( "parents", "" );
                $this->IniObject->set_var( "no_parents", "" );
                $this->IniObject->set_var( "children", "" );
                $this->IniObject->set_var( "no_children", "" );
                $this->IniObject->set_var( "file", "" );
                $this->IniObject->set_var( "files", "" );
                $this->IniObject->set_var( "no_files", "" );

                $this->fillInHiearchy( "error" );
            }

            $this->doThis();
            $this->IniObject->setAllStrings();

            // Output the admin page
            $this->IniObject->pparse( "output", "eznewsitem" );
        }
        return $value;
    }
    
    function doNormal()
    {
        #echo "eZNewsItemViewer::doNormal()<br>\n";
        #echo "eZNewsItemViewer::doAdmin()<br>\n";
        $count = $this->URLObject->getURLCount();

        if( $count >= 3 )
        {
            switch( $this->URLObject->getURLPart( 1 ) )
            {
                case "itemtype":
                case "changetype":
                    break;
                case "id":
                case "article":
                    $this->doNormalAction( $this->URLObject->getURLPart( 2 ) );
                    break;
                case "date":
                    //$item = $this->parseDate();
                    break;
                case "author":
                    //$item = $this->parseAuthor();
                    break;
                case "category":
                case "path":
                case "definition":
                    //$item = $this->parseCategory();
                    break;
                default:
                    $this->doNormalTopAction();
                    break;                
            }
        }
        
        if( $count == 2 )
        {
            $this->doNormalAction( $this->URLObject->getURLPart( 1 ) );
        }
        
        if( $count == 1 )
        {
            $this->doNormalTopAction();
        }
    }



    /*!
        Initalizes the item for this object.
     */
    function initalizeItem( $inItemNo )
    {
        #echo "eZNewsItemViewer::initalizeItem( \$inItemNo = $inItemNo )<br>\n";

        $value = false;
    
        $this->Item = new eZNewsItem( $inItemNo );

        if( $this->Item->ID() != 0 )
        {
            $type = new eZNewsItemType( $this->Item->ItemTypeID() );

            $class = $type->eZClass();

            // Change to correct sub class, in order to make sure we delete correctly.

            include_once( "eznews/classes/" . strtolower( $class ) . ".php" );
            $this->Item = new $class( $inItemNo );
            
            $value = true;
        }
        
        return $value;
    }






    /*!
        This function fills in all the items of a certain type.
        
        \in
            \$what This is the type of item we're filling in.
     */
    
    function fillInHiearchy( $what )
    {
        #echo "eZNewsItemViewer::fillInHiearchy( \$what = $what )<br>\n";
        switch( $what )
        {
            case "child":
                $this->IniObject->set_block( "eznewsitem", "children_template", "children" );
                $this->IniObject->set_block( "eznewsitem", "no_children_template", "no_children" );
                $this->Item->getChildren( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "error":
                $this->IniObject->set_block( "eznewsitem", "error_template", "error" );
                $this->IniObject->set_block( "eznewsitem", "errors_template", "errors" );
                $this->IniObject->set_block( "eznewsitem", "no_errors_template", "no_errors" );
                $returnArray = $this->Item->Errors();
                #echo $returnArray;
                $maxCount = count( $returnArray );
                #echo $maxCount;
                break;
            case "file":
                $this->IniObject->set_block( "eznewsitem", "file_template", "file" );
                $this->IniObject->set_block( "eznewsitem", "files_template", "files" );
                $this->IniObject->set_block( "eznewsitem", "no_files_template", "no_files" );
                $maxCount = 0; empty( $returnArray );
                #$this->Item->getfiles( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "image":
                $this->IniObject->set_block( "eznewsitem", "image_template", "image" );
                $this->IniObject->set_block( "eznewsitem", "images_template", "images" );
                $this->IniObject->set_block( "eznewsitem", "no_images_template", "no_images" );
                $this->Item->getImages( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "parent":
                $this->IniObject->set_block( "eznewsitem", "parents_template", "parents" );
                $this->IniObject->set_block( "eznewsitem", "no_parents_template", "no_parents" );
                $this->Item->getParents( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            default:
                break;
        }

        $count = count( $returnArray );

        if( $count > 0 )
        {
            switch( $what )
            {
                case "parent":
                    $this->IniObject->pluralize2( $outString, "parents_plural", "parents_singular", $maxCount );
                    
                    $this->IniObject->set_var( "parents_string", $this->IniObject->read_var( "strings", $outString ) );
                    $this->IniObject->set_var( "parents_count", $maxCount );
                    $this->IniObject->set_var( "parents_direction", $direction );
                    break;
                case "child":
                    $this->IniObject->pluralize2( $outString, "children_plural", "children_singular", $maxCount );
                    
                    $this->IniObject->set_var( "children_string", $this->IniObject->read_var( "strings", $outString ) );
                    $this->IniObject->set_var( "children_count", $maxCount );
                    $this->IniObject->set_var( "children_direction", $direction );
                    break;
                case "image":
                    $this->IniObject->pluralize2( $outString, "image_plural", "image_singular", $maxCount );
                    
                    $this->IniObject->set_var( "image_string", $this->IniObject->read_var( "strings", $outString ) );
                    $this->IniObject->set_var( "image_count", $maxCount );
                    $this->IniObject->set_var( "image_direction", $direction );
                    break;
                case "file":
                    $this->IniObject->pluralize2( $outString, "file_plural", "file_singular", $maxCount );
                    
                    $this->IniObject->set_var( "file_string", $this->IniObject->read_var( "strings", $outString ) );
                    $this->IniObject->set_var( "file_count", $maxCount );
                    $this->IniObject->set_var( "file_direction", $direction );
                    break;
                case "error":
                    $this->IniObject->pluralize2( $outString, "error_plural", "error_singular", $maxCount );
                    
                    $this->IniObject->set_var( "error_string", $this->IniObject->read_var( "strings", $outString ) );
                    $this->IniObject->set_var( "error_count", $maxCount );
                    break;
                default:
                    break;
            }
            

            $i = 0;
            switch( $what )
            {
                case "parent":
                case "child":
                    foreach( $returnArray as $item )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->IniObject->set_var( "item_id", $item->ID() );
                        $this->IniObject->set_var( "item_name", $item->Name() );
                        $this->IniObject->set_var( "item_createdat", $item->CreatedAt() );
                        $this->IniObject->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->IniObject->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->IniObject->set_var( "color", "bgdark" );
                        }
                        
                        $this->IniObject->parse( "item", "item_template", true );
                    }
                    break;
                case "file":
                    foreach( $returnArray as $file )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->IniObject->set_var( "file_id", $item->ID() );
                        $this->IniObject->set_var( "file_name", $item->Name() );
                        $this->IniObject->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->IniObject->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->IniObject->set_var( "color", "bgdark" );
                        }
                        
                        $this->IniObject->parse( "file", "file_template", true );
                    }
                    break;
                case "image":
                    foreach( $returnArray as $image )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->IniObject->set_var( "image_id", $image->ID() );
                        $this->IniObject->set_var( "image_name", $image->Name() );
                        $this->IniObject->set_var( "image_caption", $image->Caption() );
                        $this->IniObject->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->IniObject->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->IniObject->set_var( "color", "bgdark" );
                        }
                        
                        $this->IniObject->parse( "image", "image_template", true );
                    }
                    break;
                case "error":
                    foreach( $returnArray as $error )
                    {
                        $this->IniObject->set_var( "error_text", $error );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->IniObject->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->IniObject->set_var( "color", "bgdark" );
                        }
                        
                        $this->IniObject->parse( "error", "error_template", true );
                    }
                    break;
                default:
                    break;
            }

            switch( $what )
            {
                case "parent":
                    $this->IniObject->set_var( "parent_items", $this->IniObject->get_var( "item" ) );
                    $this->IniObject->parse( "parents", "parents_template" );
            
                    $this->IniObject->set_var( "item", "" );
                    $this->IniObject->set_var( "no_parents", "" );
                    break;
                case "child":
                    $this->IniObject->set_var( "child_items", $this->IniObject->get_var( "item" ) );
                    $this->IniObject->parse( "children", "children_template" );
           
                    $this->IniObject->set_var( "item", "" );
                    $this->IniObject->set_var( "no_children", "" );
                    break;
                case "file":
                    $this->IniObject->set_var( "file_items", $this->IniObject->get_var( "file" ) );
                    $this->IniObject->parse( "files", "files_template" );

                    $this->IniObject->set_var( "file", "" );
                    $this->IniObject->set_var( "no_files", "" );
                    break;
                case "image":
                    $this->IniObject->set_var( "image_items", $this->IniObject->get_var( "image" ) );
                    $this->IniObject->parse( "images", "images_template" );
                    $this->IniObject->set_var( "image", "" );
                    $this->IniObject->set_var( "no_images", "" );
                    break;
                case "error":
                    $this->IniObject->set_var( "error_items", $this->IniObject->get_var( "error" ) );
                    $this->IniObject->parse( "errors", "errors_template" );
                    $this->IniObject->set_var( "error", "" );
                    $this->IniObject->set_var( "no_errors", "" );
                    break;
                default:
                    break;
            }
        }
        else
        {
            switch( $what )
            {
                case "parent":
                    $this->IniObject->set_var( "parents", "" );
                    $this->IniObject->set_var( "item", "" );
                    $this->IniObject->parse( "no_parents", "no_parents_template" );
                    break;
                case "child":
                    $this->IniObject->set_var( "children", "" );
                    $this->IniObject->set_var( "item", "" );
                    $this->IniObject->parse( "no_children", "no_children_template" );
                    break;
                case "file":
                    $this->IniObject->set_var( "files", "" );
                    $this->IniObject->set_var( "file", "" );
                    $this->IniObject->parse( "no_files", "no_files_template" );
                    break;
                case "image":
                    $this->IniObject->set_var( "images", "" );
                    $this->IniObject->set_var( "image", "" );
                    $this->IniObject->parse( "no_images", "no_images_template" );
                    break;
                case "error":
                    $this->IniObject->set_var( "errors", "" );
                    $this->IniObject->set_var( "error", "" );
                    $this->IniObject->parse( "no_errors", "no_errors_template" );
                    break;
                default:
                    break;
            }
        }
    }



    /*!
        This function initalizes the template with all the this data.
     */
    function doThis()
    {
        #echo "eZNewsItemViewer::doThis()<br>\n";
        include_once( "eznews/classes/eznewsitemtype.php" );
        $type = new eZNewsItemType( $this->Item->getItemTypeID() );
        
        $this->IniObject->set_var( "this_type", $type->Name() );
        $this->IniObject->set_var( "this_id", $this->Item->ID() );
        $this->IniObject->set_var( "this_name", $this->Item->Name() );
    }
    function orphansDirection()
    {
        $returnString = "";
        $continue = false;
        
        $this->URLObject->removeRegexpDuplicates( "^orphan=sortby." );
        $this->URLObject->getQueries( $QueryArray, "^orphan=sortby." );
        
        $count = count( $QueryArray );
        #echo $count;
        switch( $count )
        {
            case 0:
                $returnString = $this->IniObject->read_var( "strings", "sort_date_adverb" );
                $this->OrphansSortBy = "CreatedAt";
                break;
            case 1:
                $continue = true;
                break;
            default:
                $returnString = $this->IniObject->read_var( "strings", "sort_date_adverb" );
                $this->OrphansSortBy = "CreatedAt";
                break;
        }
        
        if( $continue )
        {
            $stringArray = explode( "=", $QueryArray[0] );
            $string = explode( "+", $stringArray[1] );
            #echo $stringArray[0];
        }
        
        return $returnString;
    }
    
    function doAdminOrphans()
    {
        $this->IniObject->set_block( "eznewscommand", "orphans_template", "orphans" );
        $this->IniObject->set_block( "orphans_template", "orphan_item_template", "orphan_item" );
        
        $maxItems = $this->IniObject->GlobalIni->read_var( "eZNewsAdmin", "OrphansMainPage" );
        
        // Show orphans
        $this->Item->getOrphans( $returnArray, $this->OrphansSortBy, "asc", 0, $maxItems );
        
        if( $returnArray )
        {
            $count = count( $returnArray );
            
            $direction = $this->orphansDirection();
            
            $this->IniObject->pluralize( "orphans_string", "orphan", $count );
            
            $this->IniObject->set_var( "orphans_count", $count );
            $this->IniObject->set_var( "orphans_direction", $direction );
            $this->IniObject->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

            foreach( $returnArray as $item )
            {
                $item->get($outID, $item->ID() );
                $this->IniObject->set_var( "orphan_id", $item->ID() );
                $this->IniObject->set_var( "orphan_name", $item->Name() );
                $this->IniObject->set_var( "orphan_createdat", $item->CreatedAt() );
                $this->IniObject->parse( "orphan_item", "orphan_item_template", true );
            }
            
            $this->IniObject->parse( "orphans", "orphans_template" );
        }
        else
        {
            $this->IniObject->set_var( "orphans", "" );
        }
        
    }
    
    function doAdminNavigate()
    {
        $this->IniObject->set_block( "eznewscommand", "navigate_template", "navigate" );
        $this->IniObject->set_block( "navigate_template", "navigate_item_template", "navigate_item" );
        
        $maxItems = $this->IniObject->GlobalIni->read_var( "eZNewsAdmin", "NavigationMainPage" );
        
        // Show navigate
        $this->Item->getChildren( $returnArray, $this->NavigatesSortBy, "asc", 0, $maxItems );
        
        if( $returnArray )
        {
            $count = count( $returnArray );
            
            #$direction = $this->navigateDirection();
            
            $this->IniObject->pluralize( "navigate_string", "navigate", $count );
            
            $this->IniObject->set_var( "navigate_count", $count );
            $this->IniObject->set_var( "navigate_direction", $direction );
            $this->IniObject->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

            foreach( $returnArray as $item )
            {
                $item->get($outID, $item->ID() );
                $this->IniObject->set_var( "navigate_id", $item->ID() );
                $this->IniObject->set_var( "navigate_name", $item->Name() );
                $this->IniObject->set_var( "navigate_createdat", $item->CreatedAt() );
                $this->IniObject->parse( "navigate_item", "navigate_item_template", true );
            }
            
            $this->IniObject->parse( "navigate", "navigate_template" );
        }
        else
        {
            $this->IniObject->set_var( "navigate", "" );
        }
    }
    

    
    
    // Private members
    
    ///
    var $Item;
    var $ItemTemplate;
    var $IniObject;
    var $URLObject;
    var $ItemSortBy = Name;
    var $ItemDirection = asc;
    
    /* The name of the global configuration file for eznews */
    var $inNewsConfigFileName;
};

?>
