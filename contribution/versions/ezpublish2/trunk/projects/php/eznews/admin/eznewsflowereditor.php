<?
class eZNewsArticleProductEditor
{
    function eZNewsArticleProductEditor( $url_array )
    {
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
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );

        
        switch( $url_array[4] )
        {
            case "validate":
                if( !$this->validateForm() )
                {
                    $url_array[4] = "insert";
                    $this->create( $url_array );
                }
                else
                {
                    $this->fillInForm( "validate" );
                }
                break;
            case "insert":
                break;
            case "help":
                echo "help ". $url_array[5];
                break;
            default:
                $this->fillInForm( "validate" );
                break;
        }
    }

    /*!
        $returnErrors is empty if there were no errors.
     */

    function validateForm()
    {
        unset($returnErrors);
        
        global $Price;
        global $Story;
        global $Title;

        include_once( "classes/ezcurrency.php" );
        $c = new eZCurrency( $Price );

        if( !is_numeric( $Price ) )
        {
            $returnErrors[] = "intl-price_error";
        }

        if( empty( $Story ) )
        {
            $returnErrors[] = "intl-story_error";
        }

        if( empty( $Title ) )
        {
            $returnErrors[] = "intl-title_error";
        }

        return $returnErrors;
    }

    function fillInForm( $action )
    {
        global $Price;
        global $Story;
        global $Title;

        $ini = new INIFIle( "site.ini" );
        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        $t = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/eznewsarticleeditor/eznewsarticleproducteditor/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsarticleproducteditor.php" );
        
        $t->set_file( array(
                      "create_page" => "create_page.tpl",
                      "category" => "category.tpl"
                      )
                    );

        $t->setAllStrings();


        $this->fillInCategories( $t );
        
        $t->set_var( "action_value", $action );
        
        $t->set_var( "price_value", htmlspecialchars( $Price ) );
        $t->set_var( "story_value", htmlspecialchars( $Story ) );
        $t->set_var( "title_value", htmlspecialchars( $Title ) );

        $t->pparse( "output", "create_page" );
    }
    
    function fillInCategories( &$t )
    {
        global $ParentID;
        
        include_once( "eznews/classes/eznewscategory.php" );
        
        $cat = new eZNewsCategory();
        $cat->getByName( "News" );
        $categories = $cat->getAllChildrenCategories();

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
