<?
class eZNewsCategoryEditor
{
    function eZNewsCategoryEditor( $url_array )
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
        
        $t = new eZTemplate( $DOC_ROOT . "/admin/" . $TEMPLATE_DIR . "/ezcategoryeditor/",  $DOC_ROOT . "/admin/intl/", $Language, "ezcategoryeditor.php" );
        $t->setAllStrings();
        
        $t->set_file( array(
                      "category_info_page" => "category_info/category_info.tpl",
                      "category_info_canonical_paths" => "category_info/category_info_canonical_path.tpl",
                      "category_info_no_canonical_path" => "category_info/category_info_no_canonical_path.tpl",
                      "category_info_canonical_path_item" => "category_info/category_info_canonical_path_item.tpl",
                      "category_info_parents" => "category_info/category_info_parents.tpl",
                      "category_info_no_parents" => "category_info/category_info_no_parents.tpl",
                      "category_info_no_children" => "category_info/category_info_no_children.tpl",
                      "category_info_children" => "category_info/category_info_children.tpl",
                      "category_info_other_children" => "category_info/category_info_other_children.tpl",
                      "category_info_list_item" => "category_info/category_info_list_item.tpl",
                      "textlink" => "textlink.tpl"
                      )
                    );
        
        if ( !empty( $url_array[3] ) )        
        {
            include_once( "eznews/classes/eznewscategory.php" );
            $category = new eZNewsCategory( $url_array[3] );
            
            
            if ( $category->state() == "Don't Exist" )
            {
                echo "<blink>No such category (" . $url_array[3] . ") exists.</blink> Using annoying blink-tag so that we don't forget a proper error repsonse here.<br>";
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
                        $t->set_var( "second_category_id", $parent->ID() );
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
                        $t->set_var( "second_category_id", $child->ID() );
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
                
                $paths = $category->getCanonicalParentCategories();
                
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
                        echo "There are " . $count . " sub items of type " . $subItem[ "TypeName" ] . "<br>";
                        
                        for( $i = 0; $i < $count; $i++ )
                        {
                            echo " " . $subItem[ "Item" . $i ]->Name() . "<br>";
                        }
                    }
                    else
                    {
                        echo "There are no sub items of type " . $subItem[ "TypeName" ] . "<br>";
                    }
                }
                

                $t->set_var( "current_category_id", $category->ID() );
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

//         global $data;
//         global $parsedXMLAttributes;
//         
//         $item->polymorphSelf( $this->className );
//         $this->Item = $item;
//         $theViewer = $this;
// 
//         $parsedXMLAttributes["categoryid"]["value"] = $this->Item->ID();
//         
//         $this->URLArray = $URLArray;
//         $kids = $item->getAllChildren("date", "reverse" );
//         $description = $item->publicDescriptionID();
// 
//         include_once( "eznews/classes/eznewsflower.php" );
//         
//         $firstTime = true;
//         
//         $this->initalizeTemplate();
//         
//         foreach( $kids as $kid )
//         {
//             if( $kid->getClass() == "eZNewsFlower" )
//             {
//                 $kid->polymorphSelf( "eZNewsFlower" );
//                 $this->kid = $kid;
//                 if( $firstTime == true )
//                 {
//                     $tempStory = $kid->Story();
//                     $tempStory = str_replace( "</ezflower>", "", $tempStory );
//                     $data = $tempStory;
//                     $firstTime = false;
//                 }
//                 else
//                 {
//                     $tempStory = $kid->Story();
//                     $tempStory = str_replace( "<?xml version=\"1.0\"?>", "", $tempStory );
//                     $tempStory = str_replace( "<ezflower>", "", $tempStory );
//                     $tempStory = str_replace( "</ezflower>", "", $tempStory );
//                     $data = $data . "\n\n" . $tempStory;
//                 }
//             }
//         }
// 
//         if( empty( $data ) )
//         {
//             
//         }
//         else
//         {
//             $data = $data . "</ezflower>";
//             include_once( "eznews/classes/xml_parser.php" );
//         }
//     }
//     
//     function initalizeTemplate()
//     {
//         global $template;
//         include_once( "classes/INIFile.php" );
//         include_once( "classes/eztemplate.php" );
//         
//         $ini = new INIFIle( "site.ini" );
//         $Language = $ini->read_var( "eZNewsMain", "Language" );
//         $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
//         
//         $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
//         $template = new eZTemplate( $DOC_ROOT . $TEMPLATE_DIR . "/eznewsarticleview/eznewsflowerview/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsflowereditor.php" );
//         
//         $template->set_file( array(
//                       "article_file" => "article_list.tpl"
//                       )
//                     );
//         
//         $template->set_block( "article_file", "errors_tpl", "errors" );
//         $template->set_block( "errors_tpl", "error_tpl", "error" );
//         $template->set_block( "article_file", "category_tpl", "category" );
//         $template->set_block( "article_file", "product_tpl", "product" );
//         $template->set_block( "product_tpl", "picture_tpl", "picture" );
//         
//         $template->setAllStrings();
//     }
//     
//     function printItem( $parsedXML, $parsedXMLAttributes )
//     {
//         global $template;
//         if( $parsedXMLAttributes["pictureid"]["value"] )
//         {
//             include_once( "ezimagecatalogue/classes/ezimage.php" );        
//             $file = new eZImage( $parsedXMLAttributes["pictureid"]["value"] );
//             $variation = $file->requestImageVariation( $template->ini->read_var("strings", "image_width") , $template->ini->read_var("strings", "image_height") );
// 
//             $template->set_var( "picture_path", "/" . $variation->imagePath() );
//             $template->set_var( "picture_width", $variation->width() );
//             $template->set_var( "picture_height", $variation->height() );
//             $template->set_var( "picture_alt", $file->caption() );
//             $template->set_var( "picture_title", $file->caption() );
//             $template->parse( "picture", "picture_tpl" );
//         }
//         else
//         {
//             $template->set_var( "picture", "" );
//         }
//         $template->set_var( "product_name", $parsedXML["name"] );
//         $template->set_var( "product_text", $parsedXML["description"] );
//         $template->set_var( "product_price", $parsedXML["price"] );
//         $template->parse( "product", "product_tpl", true );
//     }
//     
//     function printPage( $parsedXML, $parsedXMLAttributes )
//     {
//         global $template;
//         if( !empty( $parsedXMLAttributes["categoryid"]["value"] ) )
//         {
//             $template->set_var( "category_name", "Begravelse");
//             $template->set_var( "category_info", "infodajføldasjøl askløfjlkø");
//             $template->parse( "category", "category_tpl" );
//             $parsedXMLAttributes["categoryid"]["value"] = '';
//         }
//         else
//         {
//             $template->set_var( "category", "" );
//         }
// 
//         $template->set_var( "error_text", "" );
//         $template->set_var( "error", "" );
//         $template->set_var( "errors", "" );
//         #$template->parse( "error", "error_tpl" );        
//         #$template->parse( "errors", "errors_tpl" );
//         $template->pparse( "output", "article_file" );
//     }
//     
//     var $Item;
//     var $URLArray;
//     var $className = "eZNewsCategory";
//     var $parentName = "Heistad Hagesenter";
//     var $parentPath = "news";
//     var $t;
# ne regexpt <([\w]+)>([^<]*)</\1>
?>
