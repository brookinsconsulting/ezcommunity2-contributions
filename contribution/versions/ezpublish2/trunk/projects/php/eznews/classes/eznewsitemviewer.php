<?php

include_once( "classes/eztemplate.php" );
include_once( "eznews/admin/eznewsitem/eznewsimageviewer.php" );
include_once( "eznews/admin/eznewsitem/eznewsitemcreator.php" );
include_once( "eznews/classes/eznewsitem.php" );  
include_once( "eznews/classes/eznewsitemtype.php" );  

class eZNewsItemViewer
{
    /*!
        Just initalizing some variables.
        
        \in
            \$inURLObject An eZURL object.
     */
    function eZNewsItemViewer( $inURLObject, $inIniFileName )
    {
        #echo "eZNewsItemViewer::eZNewsItemViewer( \$inURLObject = $inURLObject, \$inIniFileName = $inIniFileName )<br>\n";

        $this->URLObject = $inURLObject;
        
        $this->IniObject = new INIFile( $inIniFileName );

        $this->Language = $this->IniObject->read_var( "eZNewsMain", "Language" );

        $DocumentDir = $this->IniObject->read_var( "eZNewsMain", "DocumentRoot" );
        $TemplateDir = $this->IniObject->read_var( "eZNewsMain", "TemplateDir" );
        $Language = $this->IniObject->read_var( "eZNewsMain", "Language" );

        $this->AdminLanguageDir = $DocumentDir . "/admin/intl/" . $Language;
        $this->AdminTemplateDir = $DocumentDir . "/admin/" . $TemplateDir;
        $this->LanguageDir = $DocumentDir . "/intl/" . $Language;
        $this->TemplateDir = $DocumentDir . $TemplateDir;
        $this->DocumentDir = $DocumentDir;
        $this->Language = $Language;
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
        
        $Adminsite = $this->IniObject->read_var( "eZNewsAdmin", "Adminsite" );
        
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
        #echo "eZNewsItemViewer::doAdminAction()<br>\n";
        $continue = true;
        $value = false;
        
        $this->URLObject->getQueries( $queries, "image" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            $item = new eZNewsImageViewer( $this->Ini, $this->URLObject, $itemNo );
            
            if( !$item->isFinished() )
            {
                $continue = false;
            }
        }
        
        
        
        $this->URLObject->getQueries( $queries, "file" );
        $count = count( $queries );
        
        if( $count && $continue )
        {
            #$item = new eZNewsImageViewer( $this->Ini, $this->URLObject, $itemNo );
            
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
        #echo "eZNewsItemViewer::doAdminCreate()<br>\n";
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
            include_once( "eznews/admin/eznewsitem/eznewsitemcreator.php" );
            $item = new eZNewsItemCreator( $this->IniObject, $this->URLObject, $itemNo );
 
            if( !$item->isFinished() )
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



    function doAdminEdit( &$itemNo )
    {
        #echo "eZNewsItemViewer::doAdminEdit()<br>\n";
        $item = new eZNewsItemCreator( $this->Ini, $this->URLObject, $itemNo );
    }



    function doAdminDelete( &$inItemNo )
    {
        #echo "eZNewsItemViewer::doAdminDelete( \$inItemNo = $inItemNo )<br>\n";
        
        global $delete;
        $value = true;

        $this->readTemplate();
        $this->initalizeItem( $inItemNo );
        
        if( isset( $delete ) )
        {
            $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitemdeleted.tpl" ) );

            $this->doThis();

            $this->ItemTemplate->setAllStrings();

            $this->Item->delete();
            
            // Output the admin page
            $this->ItemTemplate->pparse( "output", "eznewsitem" );
        }
        else
        {
            $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitemdelete.tpl" ) );

            $this->doThis();

            $this->ItemTemplate->setAllStrings();

            // Output the admin page
            $this->ItemTemplate->pparse( "output", "eznewsitem" );
        }
        return $value;
    }



    function doAdminBrowse( &$inItemNo )
    {
        #echo "eZNewsItemViewer::doAdminBrowse( \$inItemNo = $inItemNo )<br>\n";
        $value = true;
        
        $this->readTemplate();
        $this->initalizeItem( $inItemNo );
        
        $this->ItemTemplate->set_file( array( "eznewsitem" => "eznewsitem.tpl" ) );
        $this->ItemTemplate->set_block( "eznewsitem", "item_template", "item" );
        
        $this->fillInHiearchy( "parent" );
        $this->fillInHiearchy( "child" );
        $this->fillInHiearchy( "image" );
        $this->fillInHiearchy( "file" );
        
        $this->doThis();

        $this->ItemTemplate->setAllStrings();
        
        // Output the admin page
        $this->ItemTemplate->pparse( "output", "eznewsitem" );
        
        return $value;
    }
    
    function doNormal()
    {
        #echo "eZNewsItemViewer::doNormal()<br>\n";
    }



    /*!
        Initalizes the output template for this object.
     */
    function readTemplate()
    {
        #echo "eZNewsItemViewer::readTemplate()<br>\n";

        $TemplatePath = $this->AdminTemplateDir . "/eznewsitem/";
        $LanguagePath = $this->DocumentDir . "/admin/intl/";
        $LanguageFile = "eznewsitem/eznewsitem.php";
        $IniFile      = $this->AdminLanguageDir . "/$LanguageFile.ini";

        $this->ItemTemplate = new eZTemplate( $TemplatePath,  $LanguagePath, $this->Language, $LanguageFile );                    
        $this->ItemIni = new INIFile( $IniFile, false );
    }



    /*!
        Initalizes the item for this object.
     */
    function initalizeItem( $inItemNo )
    {
        #echo "eZNewsItemViewer::initalizeItem( \$inItemNo = $inItemNo )<br>\n";

        $this->Item = new eZNewsItem( $inItemNo );
        $type = new eZNewsItemType( $this->Item->ItemTypeID() );
        
        $class = $type->eZClass();

        // Change to correct sub class, in order to make sure we delete correctly.
        
        include_once( "eznews/classes/" . strtolower( $class ) . ".php" );
        $this->Item = new $class( $inItemNo );

    }



    /*!
        This function will put a plural or singular version of a string into a template string, based
        on the number count.
     */
    function pluralize( &$outputString, $pluralString, $singularString, $count )
    {
        #echo "eZNewsItemViewer::doPluralize()<br>\n";
        if( $count == 1 )
        {
            $outputString = $singularString;
        }
        else
        {
            $outputString = $pluralString;
        }
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
            

            $i = 0;
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
                        $this->ItemTemplate->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->ItemTemplate->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->ItemTemplate->set_var( "color", "bgdark" );
                        }
                        
                        $this->ItemTemplate->parse( "item", "item_template", true );
                    }
                    break;
                case "file":
                    foreach( $returnArray as $file )
                    {
                        $item->get( $outID, $item->ID() );
                        $this->ItemTemplate->set_var( "file_id", $item->ID() );
                        $this->ItemTemplate->set_var( "file_name", $item->Name() );
                        $this->ItemTemplate->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->ItemTemplate->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->ItemTemplate->set_var( "color", "bgdark" );
                        }
                        
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
                        $this->ItemTemplate->set_var( "query_string", $this->URLObject->createQueryString( "&" ) );

                        $i++;
                        if( ( $i % 2 ) == 1 )
                        {
                            $this->ItemTemplate->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $this->ItemTemplate->set_var( "color", "bgdark" );
                        }
                        
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



    /*!
        This function initalizes the template with all the this data.
     */
    function doThis()
    {
        #echo "eZNewsItemViewer::doThis()<br>\n";
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
    var $IniObject;
    var $URLObject;
    var $ItemSortBy = Name;
    var $ItemDirection = asc;
    var $Ini;
    var $LanguageDir;
    var $TemplateDir;
    var $AdminLanguageDir;
    var $AdminTemplateDir;
    var $DocumentDir;
    var $Language;
};

?>
