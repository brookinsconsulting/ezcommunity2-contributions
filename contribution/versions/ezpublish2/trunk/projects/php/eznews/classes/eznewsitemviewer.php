<?php

include_once( "eznews/admin/eznewsitem/eznewsimageviewer.php" );
include_once( "eznews/admin/eznewsitem/eznewsitemcreator.php" );
include_once( "eznews/classes/eznewsitem.php" );  
include_once( "eznews/classes/eznewsitemtype.php" );  
include_once( "classes/eztemplate.php" );

class eZNewsItemViewer
{

    function eZNewsItemViewer( &$IniFile, &$Query, $type )
    {

        $this->Ini = $IniFile;
        $this->ItemQuery = $Query;
        
        if( $type == "admin" )
        {
            $this->doAdmin();
        }
        else
        {
            $this->doNormal();
        }
    }
    
    
    function doAdmin()
    {
        $count = $this->ItemQuery->getURLCount();

        if( $count >= 3 )
        {
            switch( $this->ItemQuery->getURLPart( 1 ) )
            {
                case "id":
                case "article":
                    $this->doAdminAction( $this->ItemQuery->getURLPart( 2 ) );
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
                    // error unknown string redirect...
                    break;
                
            }
        }
        
        if( $count == 2 )
        {
            $this->doAdminAction( $this->ItemQuery->getURLPart( 1 ) );
        }
        
        if( $count == 1 )
        {
            $this->doAdminAction( $this->ItemQuery->getURLPart( 1 ) );
        }
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
        $continue = true;
        $value = false;
        
        $this->ItemQuery->getQueries( $queries, "image" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $item = new eZNewsImageViewer( $this->Ini, $this->ItemQuery, $itemNo );
            
            if( !$item->isFinished() )
            {
                $continue = false;
            }
        }
        
        
        
        $this->ItemQuery->getQueries( $queries, "file" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            #$item = new eZNewsImageViewer( $this->Ini, $this->ItemQuery, $itemNo );
            
            #if( !$item->isFinished() )
            #{
            #    $continue = false;
            #}
        }
        
        
        
        $this->ItemQuery->getQueries( $queries, "^add" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminAdd( $itemNo );
            $continue = false;
        }
        
        
        
        $this->ItemQuery->getQueries( $queries, "^delete" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminDelete( $itemNo );
            $continue = false;
        }
        


        $this->ItemQuery->getQueries( $queries, "^create" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminCreate( $itemNo );
            $continue = false;
        }
        


        $this->ItemQuery->getQueries( $queries, "^edit" );
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
        $this->ItemQuery->getQueries( $queries, "^add\+parent" );
        $count = count( $queries );
        $continue = true;
        $value = false;
        
        if( $count && $continue )
        {
            $value = $this->doAdminAddParent( $itemNo );
            $continue = false;
        }
        
        $this->ItemQuery->getQueries( $queries, "^add\+child" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $value = $this->doAdminAddChild( $itemNo );
            $continue = false;
        } 
        
        if( $continue )
        {
            $value = $this->doAdminBrowse( $this->ItemQuery->getURLPart( 2 ) );
            $value = false;
        }
        
        return $value;
    }
    
    function doAdminCreate( &$itemNo )
    {
        $this->ItemQuery->getQueries( $queries, "^create\+parent" );
        $count = count( $queries );
        $continue = true;
        $value = false;
        
        if( $count && $continue )
        {
            $value = $this->doAdminCreateParent( $itemNo );
            $continue = false;
        }
        
        $this->ItemQuery->getQueries( $queries, "^create\+child" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            include_once( "eznews/admin/eznewsitem/eznewsitemcreator.php" );
            $item = new eZNewsItemCreator( $this->Ini, $this->ItemQuery, $itemNo );
 
            if( !$item->isFinished() )
            {
                $continue = false;
            }
        } 
        
        if( $continue )
        {
            $value = $this->doAdminBrowse( $this->ItemQuery->getURLPart( 2 ) );
            $value = false;
        }
        
        return $value;
    }
    
    function doAdminEdit( &$itemNo )
    {
        $item = new eZNewsItemCreator( $this->Ini, $this->ItemQuery, $itemNo );
    }
    
    
    function doAdminDelete( &$itemNo )
    {
        $value = true;
        
        global $delete;
        
        $this->Item = new eZNewsItem( $itemNo );
        $type = new eZNewsItemType( $this->Item->ItemTypeID() );
        
        $class = $type->eZClass();
        include_once( "eznews/classes/" . strtolower( $class) . ".php" );
        $this->Item = new $class( $itemNo );

        $Language = $this->Ini->read_var( "eZNewsMain", "Language" );
        $DocumentDir = $this->Ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TemplateDir = $this->Ini->read_var( "eZNewsMain", "TemplateDir" );
        $TemplatePath = $DocumentDir . "/admin/" . $TemplateDir . "/eznewsitem/";
        $LanguagePath = $DocumentDir . "/admin/intl/";
        $LanguageFile = "eznewsitem/eznewsitem.php";
        
        $this->ItemTemplate = new eZTemplate( $TemplatePath,  $LanguagePath, $Language, $LanguageFile );                    
        $this->ItemIni = new INIFile( $LanguagePath . $Language . "/" . $LanguageFile . ".ini", false );

        if( isset( $delete ) )
        {
            $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitemdeleted.tpl" ) );

            $this->ItemTemplate->setAllStrings();

            $this->doThis();

            $this->Item->delete();
            
            // Output the admin page
            $this->ItemTemplate->pparse( "output", "eznewsitem" );
        }
        else
        {
            $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitemdelete.tpl" ) );

            $this->ItemTemplate->setAllStrings();

            $this->doThis();

            // Output the admin page
            $this->ItemTemplate->pparse( "output", "eznewsitem" );
        }
        return $value;
    }
    
    function doAdminBrowse( &$itemNo )
    {
        $value = true;
        
        include_once( "eznews/classes/eznewsitem.php" );  
        $this->Item = new eZNewsItem( $itemNo );
        
        include_once( "classes/eztemplate.php" );

        $Language = $this->Ini->read_var( "eZNewsMain", "Language" );
        $DocumentDir = $this->Ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TemplateDir = $this->Ini->read_var( "eZNewsMain", "TemplateDir" );
        $TemplatePath = $DocumentDir . "/admin/" . $TemplateDir . "/eznewsitem/";
        $LanguagePath = $DocumentDir . "/admin/intl/";
        $LanguageFile = "eznewsitem/eznewsitem.php";
        
        $this->ItemTemplate = new eZTemplate( $TemplatePath,  $LanguagePath, $Language, $LanguageFile );                    
        $this->ItemIni = new INIFile( $LanguagePath . $Language . "/" . $LanguageFile . ".ini", false );
        $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitem.tpl" ) );
        $this->ItemTemplate->set_block( "eznewsitem", "item_template", "item" );
        
        $this->ItemTemplate->setAllStrings();
        
        $this->fillInHiearchy( "parent" );
        $this->fillInHiearchy( "child" );
        $this->fillInHiearchy( "image" );
        $this->fillInHiearchy( "file" );
        $this->doThis();

        // Output the admin page
        $this->ItemTemplate->pparse( "output", "eznewsitem" );
        
        return $value;
    }
    
    function doNormal()
    {
    }
    
    
    /*!
        Initalizes the output template for this object.
        
        \in
            \$IniFile The ini-file object to find required in.
     */
    function readTemplate( )
    {
        include_once( "classes/eztemplate.php" );

        $Language = $this->Ini->read_var( "eZNewsMain", "Language" );
        $DocumentDir = $this->Ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TemplateDir = $this->Ini->read_var( "eZNewsMain", "TemplateDir" );
        $TemplatePath = $DocumentDir . "/admin/" . $TemplateDir . "/";
        $LanguagePath = $DocumentDir . "/admin/intl/";
        $LanguageFile = "eznewsitem.php";
        
        $this->ItemTemplate = new eZTemplate( $TemplatePath,  $LanguagePath, $Language, $LanguageFile );            
        
        $this->ItemIni = new INIFile( $LanguagePath . $Language . "/" . $LanguageFile . ".ini", false );

        $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitem.tpl" ) );
    }



    /*!
        This function will put a plural or singular version of a string into a template string, based
        on the number count.
     */
    function pluralize( &$outputString, $pluralString, $singularString, $count )
    {
        if( $count == 1 )
        {
            $outputString = $singularString;
        }
        else
        {
            $outputString = $pluralString;
        }
    }
    
    function fillInHiearchy( $what )
    {
        switch( $what )
        {
            case "parent":
                $this->ItemTemplate->set_block( "eznewsitem", "parents_template", "parents" );
                $this->ItemTemplate->set_block( "eznewsitem", "no_parents_template", "no_parents" );
                $this->Item->getParents( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "child":
                $this->ItemTemplate->set_block( "eznewsitem", "children_template", "children" );
                $this->ItemTemplate->set_block( "eznewsitem", "no_children_template", "no_children" );
                $this->Item->getChildren( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "image":
                $this->ItemTemplate->set_block( "eznewsitem", "image_template", "image" );
                $this->ItemTemplate->set_block( "eznewsitem", "images_template", "images" );
                $this->ItemTemplate->set_block( "eznewsitem", "no_images_template", "no_images" );
                $this->Item->getImages( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
                break;
            case "file":
                $this->ItemTemplate->set_block( "eznewsitem", "file_template", "file" );
                $this->ItemTemplate->set_block( "eznewsitem", "files_template", "files" );
                $this->ItemTemplate->set_block( "eznewsitem", "no_files_template", "no_files" );
                $maxCount = 0; empty( $returnArray );
                #$this->Item->getfiles( $returnArray, $maxCount, $this->ItemSortBy, $this->ItemDirection, 0, $maxItems );
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
                    $this->pluralize( $outString, "parents_plural", "parents_singular", $maxCount );
                    
                    $this->ItemTemplate->set_var( "parents_string", $this->ItemIni->read_var( "strings", $outString ) );
                    $this->ItemTemplate->set_var( "parents_count", $maxCount );
                    $this->ItemTemplate->set_var( "parents_direction", $direction );
                    break;
                case "child":
                    $this->pluralize( $outString, "children_plural", "children_singular", $maxCount );
                    
                    $this->ItemTemplate->set_var( "children_string", $this->ItemIni->read_var( "strings", $outString ) );
                    $this->ItemTemplate->set_var( "children_count", $maxCount );
                    $this->ItemTemplate->set_var( "children_direction", $direction );
                    break;
                case "image":
                    $this->pluralize( $outString, "image_plural", "image_singular", $maxCount );
                    
                    $this->ItemTemplate->set_var( "image_string", $this->ItemIni->read_var( "strings", $outString ) );
                    $this->ItemTemplate->set_var( "image_count", $maxCount );
                    $this->ItemTemplate->set_var( "image_direction", $direction );
                    break;
                case "file":
                    $this->pluralize( $outString, "file_plural", "file_singular", $maxCount );
                    
                    $this->ItemTemplate->set_var( "file_string", $this->ItemIni->read_var( "strings", $outString ) );
                    $this->ItemTemplate->set_var( "file_count", $maxCount );
                    $this->ItemTemplate->set_var( "file_direction", $direction );
                    break;
                default:
                    break;
            }
            

            switch( $what )
            {
                case "parent":
                case "child":
                    foreach( $returnArray as $item )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->ItemTemplate->set_var( "item_id", $item->ID() );
                        $this->ItemTemplate->set_var( "item_name", $item->Name() );
                        $this->ItemTemplate->set_var( "item_createdat", $item->CreatedAt() );
                        $this->ItemTemplate->set_var( "query_string", $this->ItemQuery->createQueryString( "&" ) );
                        $this->ItemTemplate->parse( "item", "item_template", true );
                    }
                    break;
                case "file":
                    foreach( $returnArray as $file )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->ItemTemplate->set_var( "file_id", $item->ID() );
                        $this->ItemTemplate->set_var( "file_name", $item->Name() );
                        $this->ItemTemplate->set_var( "query_string", $this->ItemQuery->createQueryString( "&" ) );
                        $this->ItemTemplate->parse( "file", "file_template", true );
                    }
                    break;
                case "image":
                    foreach( $returnArray as $image )
                    {
                        #$item->get( $outID, $item->ID() );
                        $this->ItemTemplate->set_var( "image_id", $image->ID() );
                        $this->ItemTemplate->set_var( "image_name", $image->Name() );
                        $this->ItemTemplate->set_var( "image_caption", $image->Caption() );
                        $this->ItemTemplate->set_var( "query_string", $this->ItemQuery->createQueryString( "&" ) );
                        $this->ItemTemplate->parse( "image", "image_template", true );
                    }
                    break;
                default:
                    break;
            }

            switch( $what )
            {
                case "parent":
                    $this->ItemTemplate->set_var( "parent_items", $this->ItemTemplate->get_var( "item" ) );
                    $this->ItemTemplate->parse( "parents", "parents_template" );
            
                    $this->ItemTemplate->set_var( "item", "" );
                    $this->ItemTemplate->set_var( "no_parents", "" );
                    break;
                case "child":
                    $this->ItemTemplate->set_var( "child_items", $this->ItemTemplate->get_var( "item" ) );
                    $this->ItemTemplate->parse( "children", "children_template" );
           
                    $this->ItemTemplate->set_var( "item", "" );
                    $this->ItemTemplate->set_var( "no_children", "" );
                    break;
                case "file":
                    $this->ItemTemplate->set_var( "file_items", $this->ItemTemplate->get_var( "file" ) );
                    $this->ItemTemplate->parse( "files", "files_template" );

                    $this->ItemTemplate->set_var( "file", "" );
                    $this->ItemTemplate->set_var( "no_files", "" );
                    break;
                case "image":
                    $this->ItemTemplate->set_var( "image_items", $this->ItemTemplate->get_var( "image" ) );
                    $this->ItemTemplate->parse( "images", "images_template" );
                    $this->ItemTemplate->set_var( "image", "" );
                    $this->ItemTemplate->set_var( "no_images", "" );


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
                    $this->ItemTemplate->set_var( "parents", "" );
                    $this->ItemTemplate->set_var( "item", "" );
                    $this->ItemTemplate->parse( "no_parents", "no_parents_template" );
                    break;
                case "child":
                    $this->ItemTemplate->set_var( "children", "" );
                    $this->ItemTemplate->set_var( "item", "" );
                    $this->ItemTemplate->parse( "no_children", "no_children_template" );
                    break;
                case "file":
                    $this->ItemTemplate->set_var( "files", "" );
                    $this->ItemTemplate->set_var( "file", "" );
                    $this->ItemTemplate->parse( "no_files", "no_files_template" );
                    break;
                case "image":
                    $this->ItemTemplate->set_var( "images", "" );
                    $this->ItemTemplate->set_var( "image", "" );
                    $this->ItemTemplate->parse( "no_images", "no_images_template" );
                    break;
                default:
                    break;
            }
        }
    }
    
    function doThis()
    {
        include_once( "eznews/classes/eznewsitemtype.php" );
        $type = new eZNewsItemType( $this->Item->getItemTypeID() );
        
        $this->ItemTemplate->set_var( "this_type", $type->Name() );
        $this->ItemTemplate->set_var( "this_id", $this->Item->ID() );
        $this->ItemTemplate->set_var( "this_name", $this->Item->Name() );
    }

    
    
    // Private members
    
    ///
    var $Item;
    var $ItemTemplate;
    var $ItemIni;
    var $ItemQuery;
    var $ItemSortBy = Name;
    var $ItemDirection = asc;
    var $Ini;
};

?>
