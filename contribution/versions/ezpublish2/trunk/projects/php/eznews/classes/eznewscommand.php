<?
// 
// $Id: eznewscommand.php,v 1.10 2000/10/11 19:59:19 pkej-cvs Exp $
//
// Definition of eZNewsCommand class
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
//! eZNewsCommand handles commands
/*!

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
 
include_once( "classes/ezurl.php" );
include_once( "classes/INIFile.php" );        
include_once( "eznews/classes/eznewsitemviewer.php" );  

class eZNewsCommand
{
    function eZNewsCommand()
    {
        $this->Ini = new INIFile( "site.ini" );
        $this->Query = new eZURL();
        
        $this->decodeTopLevel(  );
    }
    
    function decodeTopLevel()
    {
        global $SERVER_NAME;
        global $REQUEST_URI;
        
        $this->Customer = $this->Ini->read_var( "eZNewsMain", "Customer" );

        $this->AI = new eZNewsItemViewer( $this->Query, "site.ini" );
        $this->AI->doActions();
        
        if( $this->Customer == "false" )
        {
            $this->CustomerName = $this->Ini->read_var( "eZNewsCustomer", "Name" );
            $CustomerClass = $this->Ini->read_var( "eZNewsCustomer", "Class" );

            $path = "eznews/classes/" . strtolower( $CustomerClass ) .".php";

            if( include_once( $path ) )
            {
                $customer = new $CustomerClass( $this->URLArray );

            }
            else
            {
                echo "The customer " . $this->CustomerName . " has no class defined.";
            }
        }
    }
    
    function doAdmin()
    {
        include_once( "classes/eztemplate.php" );
        include_once( "eznews/classes/eznewsitem.php" );

        $Language = $this->Ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $this->Ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $this->Ini->read_var( "eZTradeMain", "TemplateDir" );
        $strings = "$DOC_ROOT/admin/intl/$Language/eznewscommand.php.ini";
                
        $this->AS = new INIFile( $strings, false );
        $this->AT = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewscommand.php" );
        $this->AI = new eZNewsItem( 1 );
        
        $this->AT->set_file( array( "eznewscommand" => "eznewscommand.tpl" ) );
        
        $this->doAdminOrphans();
        $this->doAdminNavigate();

        // Output the admin page
        $this->AT->setAllStrings();
        $this->AT->pparse( "output", "eznewscommand" );

    }
    
    function pluralize( $outputString, $insertString, $count )
    {
        if( $count == 1 )
        {
            $this->AT->set_var( $outputString, $this->AS->read_var( "strings", $insertString . "_singular" ) );
        }
        else
        {
            $this->AT->set_var( $outputString, $this->AS->read_var( "strings", $insertString . "_plural" ) );
        }
    }
    
    function orphansDirection()
    {
        $returnString = "";
        $continue = false;
        
        $this->Query->removeRegexpDuplicates( "^orphan=sortby." );
        $this->Query->getQueries( $QueryArray, "^orphan=sortby." );
        
        $count = count( $QueryArray );
        #echo $count;
        switch( $count )
        {
            case 0:
                $returnString = $this->AS->read_var( "strings", "sort_date_adverb" );
                $this->OrphansSortBy = "CreatedAt";
                break;
            case 1:
                $continue = true;
                break;
            default:
                $returnString = $this->AS->read_var( "strings", "sort_date_adverb" );
                $this->OrphansSortBy = "CreatedAt";
                break;
        }
        
        if( $continue )
        {
            $stringArray = explode( "=", $QueryArray[0] );
            $string = explode( "+", $stringArray[1] );
            echo $stringArray[0];
        }
        
        return $returnString;
    }
    
    function doAdminOrphans()
    {
        $this->AT->set_block( "eznewscommand", "orphans_template", "orphans" );
        $this->AT->set_block( "orphans_template", "orphan_item_template", "orphan_item" );
        
        $maxItems = $this->Ini->read_var( "eZNewsAdmin", "OrphansMainPage" );
        
        // Show orphans
        $this->AI->getOrphans( $returnArray, $this->OrphansSortBy, "asc", 0, $maxItems );
        
        if( $returnArray )
        {
            $count = count( $returnArray );
            
            $direction = $this->orphansDirection();
            
            $this->pluralize( "orphans_string", "orphan", $count );
            
            $this->AT->set_var( "orphans_count", $count );
            $this->AT->set_var( "orphans_direction", $direction );
            $this->AT->set_var( "query_string", $this->Query->createQueryString( "&" ) );

            foreach( $returnArray as $item )
            {
                $item->get($outID, $item->ID() );
                $this->AT->set_var( "orphan_id", $item->ID() );
                $this->AT->set_var( "orphan_name", $item->Name() );
                $this->AT->set_var( "orphan_createdat", $item->CreatedAt() );
                $this->AT->parse( "orphan_item", "orphan_item_template", true );
            }
            
            $this->AT->parse( "orphans", "orphans_template" );
        }
        else
        {
            $this->AT->set_var( "orphans", "" );
        }
        
    }
    
    function doAdminNavigate()
    {
        $this->AT->set_block( "eznewscommand", "navigate_template", "navigate" );
        $this->AT->set_block( "navigate_template", "navigate_item_template", "navigate_item" );
        
        $maxItems = $this->Ini->read_var( "eZNewsAdmin", "NavigationMainPage" );
        
        // Show navigate
        $this->AI->getChildren( $returnArray, $this->NavigatesSortBy, "asc", 0, $maxItems );
        
        if( $returnArray )
        {
            $count = count( $returnArray );
            
            #$direction = $this->navigateDirection();
            
            $this->pluralize( "navigate_string", "navigate", $count );
            
            $this->AT->set_var( "navigate_count", $count );
            $this->AT->set_var( "navigate_direction", $direction );
            $this->AT->set_var( "query_string", $this->Query->createQueryString( "&" ) );

            foreach( $returnArray as $item )
            {
                $item->get($outID, $item->ID() );
                $this->AT->set_var( "navigate_id", $item->ID() );
                $this->AT->set_var( "navigate_name", $item->Name() );
                $this->AT->set_var( "navigate_createdat", $item->CreatedAt() );
                $this->AT->parse( "navigate_item", "navigate_item_template", true );
            }
            
            $this->AT->parse( "navigate", "navigate_template" );
        }
        else
        {
            $this->AT->set_var( "navigate", "" );
        }
    }
    
    function decode_settings()
    {
        switch ( $arguments )
        {
            case "":
                break;
            default:
                break;
        }
    }
    
    var $Query;
    var $Adminsite;
    var $Usersite;
    var $Customer;
    var $CustomerName;
    var $Ini;
    var $OrphansSortBy;
    
    /// Admin template
    var $AT;
    
    /// Admin item
    var $AI;
    
    /// Admin internationalization
    var $AS;
};
?>
