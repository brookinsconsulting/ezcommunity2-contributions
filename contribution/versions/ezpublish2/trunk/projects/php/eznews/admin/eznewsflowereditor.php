<?
class eZNewsFlowerEditor
{
    function eZNewsFlowerEditor( $url_array )
    {
            echo "eZNewsFlowerEditor::eZNewsFlowerEditor( url_array = $url_array ) <br>\n";
        if( empty( $url_array[3] ) )
        {
            // Okay give some search and stuff.
        }
        else
        {
            $foundCommand = false;
            foreach( $this->commands as $command )
            {
                if( $url_array[3] == $command )
                {
                    $foundCommand = true;
                }
            }

            if( $foundCommand == true )
            {
                $call = $url_array[3];

                $this->$call( $url_array );
            }
            else
            {
                if( is_numeric( $url_array[3] ) )
                {
                    // Give us the article of number $url_array[3].
                }
                else
                {
                    // Give us all articles by trying to look up the title.
                }

            }
        }
    }
    
    function create( $url_array )
    {
            echo "eZNewsFlowerEditor::create( url_array = $url_array ) <br>\n";
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );

        
        switch( $url_array[4] )
        {
            case "validate":
                $errors = $this->validateForm();
                
                if( !$errors )
                {
                    $url_array[4] = "insert";
                    $this->create( $url_array );
                }
                else
                {
                    $this->fillInForm( "validate", $errors );
                }
                
                break;
            case "insert":
                $this->insert();
                break;
            case "help":
                echo "help ". $url_array[5];
                break;
            default:
                $this->fillInForm( "validate", "" );
                break;
        }
    }
    
    function insert()
    {
            echo "eZNewsFlowerEditor::insert(  ) <br>\n";
        global $Price;
        global $Story;
        global $Title;
        global $Picture;
        global $PictureID;
        global $ParentID;

        if( !empty( $PictureID ) || !empty( $Picture ) )
        {
            $this->storeImage();
        }
        echo $this->storeArticle( '5' );

        $ini = new INIFIle( "site.ini" );
        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        $t = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/eznewsarticleeditor/eznewsflowereditor/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsflowereditor.php" );
        
        $t->set_file( array(
                      "create_page" => "preview_page.tpl"
                      )
                    );

        $t->setAllStrings();

        $t->set_var( "action_value", $action );
        $t->set_var( "type", "flower" );
        
        $t->set_var( "price_value", htmlspecialchars( $Price ) );
        $t->set_var( "story_value", htmlspecialchars( $Story ) );
        $t->set_var( "title_value", htmlspecialchars( $Title ) );
        
        
        $t->pparse( "output", "create_page" );
        
    }
    

    /*!
        $returnErrors is empty if there were no errors.
     */

    function validateForm( )
    {
            echo "eZNewsFlowerEditor::validateForm(  ) <br>\n";
        unset($returnErrors);

        global $Price;
        global $Story;
        global $Title;

        if( empty( $Title ) )
        {
            $returnErrors[] = "create_title_error";
        }

        if( empty( $Story ) )
        {
            $returnErrors[] = "create_story_error";
        }

        if( empty( $Price ) )
        {
            $returnErrors[] = "create_price_error";
        }

        return $returnErrors;
    }

    function fillInForm( $action, $errors )
    {
            echo "eZNewsFlowerEditor::fillInForm( action = $action, errors = $errors ) <br>\n";
        global $Price;
        global $Story;
        global $Title;
        global $Picture;
        global $PictureID;

        $ini = new INIFIle( "site.ini" );
        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        $t = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/eznewsarticleeditor/eznewsflowereditor/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsflowereditor.php" );
        
        $t->set_file( array(
                      "create_page" => "create_page.tpl"
                      )
                    );

        $t->set_block( "create_page", "error_template", "error_messages_tpl" );
        $t->set_block( "error_template", "error_row_template", "error_rows" );
                        
        if( is_array( $errors ) )
        {
            
            foreach( $errors as $error )
            {
                $t->set_var( "error_text", $t->TextStrings["$error"] );
                $t->parse( "error_rows", "error_row_template", true );
            }
            $t->parse( "error_messages_tpl", "error_template" );
        }
        else
        {
            $t->set_var( "error_messages_tpl", "" );
        }

        $t->setAllStrings();

        $this->fillInCategories( $t );
        
        $t->set_var( "action_value", $action );
        $t->set_var( "type", "flower" );
        
        $t->set_var( "price_value", htmlspecialchars( $Price ) );
        $t->set_var( "story_value", htmlspecialchars( $Story ) );
        $t->set_var( "title_value", htmlspecialchars( $Title ) );
        
        
        $t->set_block( "create_page", "upload_picture_template", "upload_picture" );
        $t->set_block( "create_page", "picture_uploaded_template", "picture_uploaded" );
        
        if( !empty( $Picture ) )
        {
            /* A picture has been uploaded */
            $PictureID = $this->storeImage();
            
            $t->set_var( "picture_value", htmlspecialchars( $Picture ) );
            $t->set_var( "picture_id", $PictureID );
            $t->set_var( "upload_picture", "" );
            $t->parse( "picture_uploaded", "picture_uploaded_template" );
        }
        else
        {
            $t->set_var( "picture_value", htmlspecialchars( $Picture ) );
            $t->set_var( "picture_uploaded", "" );
            $t->parse( "upload_picture", "upload_picture_template" );
        }
        
        $t->pparse( "output", "create_page" );
    }
    
    function storeArticle( $status )
    {
            echo "eZNewsFlowerEditor::storeArticle( status = $status ) <br>\n";
        global $PictureID;
        global $Title;
        global $Story;
        global $Price;
        global $ParentID;
        
        $Title = htmlspecialchars( strip_tags( $Title ) );
        $Story = htmlspecialchars( strip_tags( $Story ) );
        $Price = htmlspecialchars( strip_tags( $Price ) );

        include_once( "eznews/classes/eznewsarticle.php" );
        include_once( "eznews/classes/eznewsitemtype.php" );
        
        $article = new eZNewsArticle();

        $xml = <<<EOD
<?xml version="1.0"?>
<ezflower>
<product>
<name>$Title</name>
<description>$Story</description>
<price>$Price</price>
<pictureid value="$PictureID"/>
<categoryid value="$ParentID"/>
</product>
</ezflower>
EOD;
        
        $article->setName( $Title );
        $article->setStory( $xml );
        $article->setAuthorText( "Heistad Hagesenter" );
        $article->setCreatedBy( 0 );
        $article->setStatus( $status );

        $itemType = new eZNewsItemType();
            
        if( $itemType->existsClass( "eZNewsFlower" ) )
        {
            $article->setItemTypeID( $itemType->ID() );
        }
        else
        {
            die( "Wrong ITEM type ");
        }
        
        $article->setImageID( $PictureID );
        
        $article->store();
        
        $article->storeParent( $ParentID );
       
        return $article->ID();
    }
    
    function storeImage()
    {
            echo "eZNewsFlowerEditor::storeImage(  ) <br>\n";
        global $Picture;
        global $Title;
        global $PictureID;
        
        $id = 0;
                
        if( empty( $Title ) )
        {
            // Just creating a title for now.
            $Title = time();
        }
        
        include_once( "ezimagecatalogue/classes/ezimage.php" );
        
        $file = new eZImageFile();
        
        if( $file->getUploadedFile( 'Picture' ) )
        {
            $Picture = $file->name();
            
            $image = new eZImage();
            $image->setName( $Title );
            $image->setCaption( $Picture );

            $image->setImage( $file );

            $image->store();
            $id = $image->ID();
            $PictureID = $id;
        }
        else
        {
            die( "Die motherfucker die" );
        }
        
        return $id;
    }
    
    function fillInCategories( &$t )
    {
            echo "eZNewsFlowerEditor::fillInCategories( t = $t ) <br>\n";
        global $ParentID;
        
        include_once( "eznews/classes/eznewscategory.php" );
        
        $cat = new eZNewsCategory();
        $cat->getByName( "Heistad Hagesenter" );
        $categories = $cat->getAllChildrenCategories();

        $t->set_block( "create_page", "category", "categories" );
                        
        foreach( $categories as $category )
        {
            $t->set_var( "ID", $category->ID() );
            $t->set_var( "Name", $category->Name() );
            if( $category->ID == $ParentID )
            {
                $t->set_var( "Selected", "selected" );
            }
            else
            {
                $t->set_var( "Selected", "" );
            }
            $t->parse( "categories", "category", true );
        }
    }


    
    function browse( $url_array )
    {
            echo "eZNewsFlowerEditor::browse( url_array = $url_array ) <br>\n";
        include_once( "eznews/classes/eznewsitem.php" );
        include_once( "eznews/classes/eznewsarticle.php" );
        $t= new eZNewsArticle();
        $t->setName("ny item, you bastard");
        $t->setItemTypeID(5);
        echo $t->store();
    }
    
    var $commands = array(
        "create",
        "edit",
        "view",
        "id",
        "path"
    );
};
?>
