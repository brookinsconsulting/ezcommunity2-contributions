<?
class eZCategoryEditor
{
    function eZCategoryEditor( $url_array )
    {
        switch($url_array[3])
        {
            case "create":
                $this->createCategory( $url_array );
                break;
            default:
                $this->browseCategory( $url_array );
                break;
        }

    }
    
    function createCategory()
    {
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );

        $ini = new INIFIle( "site.ini" );

        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
    }
    
    function browseCategory( $url_array )
    {
        
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );

        $ini = new INIFIle( "site.ini" );

        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        
        $t = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/ezcategoryeditor/category_info/",  $DOC_ROOT . "/admin/intl/", $Language, "ezcategoryeditor.php" );
        $t->setAllStrings();
        
        $t->set_file( array(
                      "category_info_page" => "category_info.tpl",
                      "category_info_canonical_paths" => "category_info_canonical_path.tpl",
                      "category_info_no_canonical_path" => "category_info_no_canonical_path.tpl",
                      "category_info_canonical_path_item" => "category_info_canonical_path_item.tpl",
                      "category_info_parents" => "category_info_parents.tpl",
                      "category_info_no_parents" => "category_info_no_parents.tpl",
                      "category_info_no_children" => "category_info_no_children.tpl",
                      "category_info_children" => "category_info_children.tpl",
                      "category_info_list_item" => "category_info_list_item.tpl"
                      )
                    );
        
        
        // here we make a loop going through all the items, and inserts them into either
        // wchild or wochild
        
        if ( !empty( $url_array[3] ) )        
        {
            include_once( "eznews/classes/eznewscategory.php" );
            $category = new eZNewsCategory( $url_array[3] );
            
            if ( $category->state() == "Don't Exist" )
            {
                echo "<blink>No such category exists.</blink> Using annoying blink-tag so that we don't forget a proper error repsonse here.<br>";
            }
            else
            {
                $parents = $category->getAllParentCategories();
                if( !empty( $parents ) )
                {
                    $i = 0;
                    foreach( $parents as $parent )
                    {
                        $t->set_var( "second_name", $parent->name() );
                        $t->set_var( "second_category_id", $parent->categoryID() );
                        $t->set_var( "second_id", $parent->ID() );
                        $t->set_var( "what", "parent" );
                        
                        if( $i % 2 == 0 )
                        {
                            $t->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $t->set_var( "color", "bgdark" );
                        }
                        
                        $i++;
                        
                        $t->parse( "current_category_parents", "category_info_list_item", true );    
                    }
                    $t->parse( "current_category_parent", "category_info_parents", true );
                }
                else
                {
                    $t->parse( "current_category_parent", "category_info_no_parents", true );
                }
                
                $children = $category->getAllChildrenCategories();
                
                if( !empty( $children ) )
                {
                    $i = 0;
                    foreach( $children as $child )
                    {
                        $t->set_var( "second_name", $child->name() );
                        $t->set_var( "second_category_id", $child->categoryID() );
                        $t->set_var( "second_id", $child->ID() );
                        $t->set_var( "what", "child" );

                        if( $i % 2 == 0 )
                        {
                            $t->set_var( "color", "bglight" );
                        }
                        else
                        {
                            $t->set_var( "color", "bgdark" );
                        }
                        
                        $i++;
                        $t->parse( "current_category_children", "category_info_list_item", true );    
                    }
                    $t->parse( "current_category_child", "category_info_children", true );    
                }
                else
                {
                    $t->parse( "current_category_child", "category_info_no_children", true );    
                }
                
                $paths = $category->getCanonicalParent();
                
                if( !empty( $paths ) )
                {
                    $count = count( $paths );
                    $i=$count;
                    while( $i >= 0 )
                    {

                        if( is_object( $paths["$i"] ) )
                        {
                            $t->set_var( "canonical_name", $paths["$i"]->Name() );
                            $t->set_var( "canonical_category_id", $paths["$i"]->ID() );
                            $t->parse( "current_category_canonical_paths", "category_info_canonical_path_item", true );
                        }
                        $i--;
                    }
                    $t->parse( "current_category_canonical_path", "category_info_canonical_paths", true );
                }
                else
                {
                        $t->parse( "current_category_canonical_path", "category_info_no_canonical_path", true );
                }

                $subItems = $category->getSubItemCounts();
                
                foreach( $subItems as $subItem )
                {
                    $count = count($subItem);
                    
                    if( $count > 1 )
                    {
                        $count--;
                        echo "There are " . $count . " sub items of type " . $subItem["TypeName"] . "<br>";
                    }
                    else
                    {
                        echo "There are no sub items of type " . $subItem["TypeName"] . "<br>";
                    }
                }
                

                $t->set_var( "current_category_id", $category->categoryID() );
                $t->set_var( "current_category_name", $category->name() );
                $t->pparse( "output", "category_info_page" );
            }
        }
        else
        {
            include_once( "eznews/classes/eznewscategory.php" );
            $category = new eZNewsCategory(); 
            $category->makeRoot();
            $url_array[3] = $category->ID();
#            $this->browseCategory( $url_array );
echo "this part will show orphaned categories, total number of categories, etc.";
        }
        

    }
};
?>
