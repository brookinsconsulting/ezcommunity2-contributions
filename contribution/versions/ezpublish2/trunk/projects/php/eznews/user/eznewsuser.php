<?php
// 
// $Id: eznewsuser.php,v 1.1 2000/10/13 20:55:50 pkej-cvs Exp $
//
// Definition of eZNewsUser class
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
//! eZNewsUser handles the kind of info the user sees.
/*!
 */

include_once( "classes/ezurl.php" );
include_once( "eznews/classes/eznewsitem.php" );  
include_once( "eznews/classes/eznewsitemtype.php" );  
include_once( "eznews/classes/eznewsoutput.php" );  

/*!TODO
    Implement the doDefault function

    Implement the doItem (call to viewer class)
    
    Write examples.
 */
class eZNewsUser
{
    /*!
        Just initalizing some variables.
        
        \in
            \$inURLObject An eZURL object.
            \$inNewsConfigFileName The name of the file with the global config options.
     */
    function eZNewsUser( $inConfigFileName )
    {
        #echo "eZNewsUser::eZNewsUser( \$inConfigFileName = $inConfigFileName )<br />\n";

        $this->URLObject = new eZURL();
        $this->IniObject = new eZNewsOutput( $inConfigFileName );
        
        $this->ConfigFileName = $inConfigFileName;
    }
    
    
    
    /*!
        This function will try to find out what the user is trying to access.
        It will pass control to the function which best determines what item/
        items to show.
        
        \return
            Returns true if successful.
     */
    function doActions()
    {
        #echo "eZNewsItemViewer::doActions()<br />\n";
        $value = false;
        
        $count = $this->URLObject->getURLCount();

        $urlLength = $this->IniObject->GlobalIni->read_var( "eZNewsMain", "URLLength" );

        $count = $count - $urlLength;

        // We don't have any parts of the url which isn't defined as something special.
        // Lets do default action.
        if( $count == 0 )
        {
            $value = $this->doDefault();
        }
        else
        {
            // Lets see what we have.
            
            $urlPathOne = $this->URLObject->getURLPart( 0 + $urlLength );
            $urlPathLast = $this->URLObject->getURLPart( $count );
            
            switch( $urlPathOne )
            {
                case "itemtype":
                case "changetype":
                    break;
                case "id":
                case "article":
                    break;
                case "date":
                    break;
                case "author":
                    break;
                case "category":
                case "path":
                case "definition":
                    break;
                default:
                    // If it''s only one element in the path then we just
                    // try to make an item from that.
                    // Else we try with the last element.
                    if( $count == 1 )
                    {
                        $value = $this->doItem( $urlPathOne );
                    }
                    else
                    {
                        $value = $this->doItem( $urlPathLast );
                    }
                    break;
                
            }
        }
        
        if( $value == false )
        {
            $value = $this->doDefault();
        }
        
        return $value;
    }



    /*!
        This function will do a default action based on the initalization value found
        in the eZNewsMain category and the key URLDefault.
        
        \return
            Returns true if successful.
     */
    function doDefault()
    {
        #echo "eZNewsUser::doDefault()<br />\n";
        $value = false;

        echo $this->IniObject->GlobalIni->read_var( "eZNewsMain", "URLDefault" );
        $value = true;

        return $value;
    }



    /*!
        This function will try to show us an item based on the incoming data.
        If it finds an item and corresponding viewer it will use the viewer
        to show the info about the item.
        
        \in
            \$inItemData    An item's name or id.
        
        \return
            Returns true if successful.
     */
    function doItem( $inItemData )
    {
        #echo "eZNewsUser::doItem( \$inItemData = $inItemData )<br />\n";
        $value = false;
        
        $tempItem = new eZNewsItem( $inItemData );

        // If the temp item was found, ie. we have something to show.
        if( $tempItem->isCoherent() == true )
        {
            $changeType = new eZNewsChangeType( "publish" );

            // If the temp item we found is published.
            if( $changeType->id() == $tempItem->status() )
            {
                $itemType = new eZNewsItemType( $tempItem->itemTypeID() );

                $class = $itemType->eZClass() . "Viewer";
                $classPath = "eznews/user/" . $class . ".php";
                $classPath = strtolower( $classPath );

                include_once( $classPath );
                
                $viewer = new $class( $tempItem, $this->IniObject, $this->URLObject );
                
                $value = $viewer->renderPage( $outPage );
                echo $outPage;
            }
        }
        
        return $value;
    }



    // Private members
    
    /// The global initalization file, usually "site.ini"
    var $IniObject;

    /// The object which decodes the url and url query.
    var $URLObject;
    
    /// The name of the global configuration file for eznews (ie. "site.ini")
    var $ConfigFileName;

































   
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
};

?>
