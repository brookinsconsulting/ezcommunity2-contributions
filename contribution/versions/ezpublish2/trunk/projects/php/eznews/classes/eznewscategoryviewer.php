<?php

/*! eZNews
    This has to be gobal. We store all the character data from an XML
    in this array.
 */
global $parsedXML;
global $parsedXMLAttributes;

/*! eZNews
    This is the array of opening tags.
 */

$open_tags = array
(
    'ezflower' => '<ezflower>',
    'price' => '<price>',
    'name' => '<name>',
    'description' => '<description>',
    'pictureid' => '<pictureid/>',
    'categoryid' => '<categoryid/>',
    'product' => '<product>',
    'id' => '<id/>'
);

/*! eZNews
    This is the array of closing tags. All character data between an opening
    and closing tag is stored in the global $parsedXML array, with the name
    of the tag as the key to the data.
 */
$close_tags = array
(
    'ezflower' => '</ezflower>',
    'price' => '</price>',
    'name' => '</name>',
    'description' => '</description>',
    'product' => '</product>'
);

class eZNewsCategoryViewer
{
    function eZNewsCategoryViewer( $item, $URLArray )
    {
        global $theViewer;
        global $data;
        global $parsedXMLAttributes;
        
       
        $item->polymorphSelf( $this->className );
        $this->Item = $item;
        $theViewer = $this;

        $parsedXMLAttributes["categoryid"]["value"] = $this->Item->ID();
        
        $this->URLArray = $URLArray;

        $kids = $item->getAllChildren("date", "reverse" );
        $description = $item->publicDescriptionID();

        include_once( "eznews/classes/eznewsflower.php" );
        
        $firstTime = true;
        
        $this->initalizeTemplate();
        
        foreach( $kids as $kid )
        {
            if( $kid->getClass() == "eZNewsFlower" )
            {
                $kid->polymorphSelf( "eZNewsFlower" );
                $this->kid = $kid;
                if( $firstTime == true )
                {
                    $tempStory = $kid->Story();
                    $tempStory = str_replace( "</ezflower>", "", $tempStory );
                    $data = $tempStory;
                    $firstTime = false;
                }
                else
                {
                    $tempStory = $kid->Story();
                    $tempStory = str_replace( "<?xml version=\"1.0\"?>", "", $tempStory );
                    $tempStory = str_replace( "<ezflower>", "", $tempStory );
                    $tempStory = str_replace( "</ezflower>", "", $tempStory );
                    $data = $data . "\n\n" . $tempStory;
                }
            }
        }

        if( empty( $data ) )
        {
            
        }
        else
        {
            $data = $data . "</ezflower>";
            @include_once( "eznews/classes/xml_parser.php" );
        }
    }
    
    function initalizeTemplate()
    {
        global $template;
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );
        
        $ini = new INIFIle( "site.ini" );
        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        $template = new eZTemplate( $DOC_ROOT . $TEMPLATE_DIR . "/eznewsarticleview/eznewsflowerview/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsflowereditor.php" );
        
        $template->set_file( array(
                      "article_file" => "article_list.tpl"
                      )
                    );
        
        $template->set_block( "article_file", "errors_tpl", "errors" );
        $template->set_block( "errors_tpl", "error_tpl", "error" );
        $template->set_block( "article_file", "category_tpl", "category" );
        $template->set_block( "article_file", "product_tpl", "product" );
        $template->set_block( "product_tpl", "picture_tpl", "picture" );
        
        $template->setAllStrings();
    }
    
    function printItem( $parsedXML, $parsedXMLAttributes )
    {
        global $template;
        if( $parsedXMLAttributes["pictureid"]["value"] )
        {
            include_once( "ezimagecatalogue/classes/ezimage.php" );        
            $file = new eZImage( $parsedXMLAttributes["pictureid"]["value"] );
            $variation = $file->requestImageVariation( $template->ini->read_var("strings", "image_width") , $template->ini->read_var("strings", "image_height") );

            $template->set_var( "picture_path", "/" . $variation->imagePath() );
            $template->set_var( "picture_width", $variation->width() );
            $template->set_var( "picture_height", $variation->height() );
            $template->set_var( "picture_alt", $file->caption() );
            $template->set_var( "picture_title", $file->caption() );
            $template->parse( "picture", "picture_tpl" );
        }
        else
        {
            $template->set_var( "picture", "" );
        }
        $template->set_var( "product_name", $parsedXML["name"] );
        $template->set_var( "product_text", $parsedXML["description"] );
        $template->set_var( "product_price", $parsedXML["price"] );
        $template->parse( "product", "product_tpl", true );
    }
    
    function printPage( $parsedXML, $parsedXMLAttributes )
    {
        global $template;
        if( !empty( $parsedXMLAttributes["categoryid"]["value"] ) )
        {
            $template->set_var( "category_name", "Begravelse");
            $template->set_var( "category_info", "infodajføldasjøl askløfjlkø");
            $template->parse( "category", "category_tpl" );
            $parsedXMLAttributes["categoryid"]["value"] = '';
        }
        else
        {
            $template->set_var( "category", "" );
        }

        $template->set_var( "error_text", "" );
        $template->set_var( "error", "" );
        $template->set_var( "errors", "" );
        #$template->parse( "error", "error_tpl" );        
        #$template->parse( "errors", "errors_tpl" );
        $template->pparse( "output", "article_file" );
    }
    
    var $Item;
    var $URLArray;
    var $className = "eZNewsCategory";
    var $parentName = "Heistad Hagesenter";
    var $parentPath = "news";
    var $t;
};

// handles the attributes for opening tags
// $attrs is a multidimensional array keyed by attribute
// name and having the value of that attribute
function startElement($parser, $name, $attrs='')
{
    global $open_tags;
    global $parsedXML;
    global $parsedXMLAttributes;
    global $current_tag;
    
    
    $current_tag = $name;
    
    if ( $format == $open_tags[$name] )
    {
        switch( $name )
        {
            case 'pictureid':
                $parsedXMLAttributes[ $current_tag ]["value"] = $attrs["value"];
                break;
            case 'categoryid':
                $parsedXMLAttributes[ $current_tag ]["value"] = $attrs["value"];
                break;
            default:
                break;
        }
    }
}

// $current_tag lets us know what tag we are currently
// dealing with - we use that later in the characterData
// function.
//

function endElement($parser, $name, $attrs='')
{
    global $close_tags;
    global $parsedXML;
    global $parsedXMLAttributes;
    global $current_tag;
    global $theViewer;
    
    if ($format == $close_tags[$name])
    {
        switch($name)
        {
            case 'ezflower':
                $theViewer->printPage( $parsedXML, $parsedXMLAttributes );;
                $parsedXML = '';
                break;
            case 'product':
                $theViewer->printItem( $parsedXML, $parsedXMLAttributes );;
                $parsedXML = '';
                break;
            default:
                break;
        }
    }
}

# ne regexpt <([\w]+)>([^<]*)</\1>
?>
