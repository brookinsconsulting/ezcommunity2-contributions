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

class eZNewsFlowerViewer
{
    function eZNewsFlowerViewer( $item, $URLArray )
    {
        global $theViewer;
        global $data;
        
        $item->polymorphSelf( $this->className );

        $this->Item = $item;
        $this->URLArray = $URLArray;

        $theViewer = $this;

        $data = $item->Story();

        if( empty( $data ) )
        {
            die(" no data");
        }
        
        if( @include_once( "eznews/classes/xml_parser.php" ) )
        {
        }
        else
        {
            echo "erorrfadsfø øldfjølasd jlø";
        }
    }
    
    function printPage( $parsedXML, $parsedXMLAttributes )
    {
        include_once( "ezimagecatalogue/classes/ezimage.php" );        
        $file = new eZImage( $this->Item->ImageID[0] );
        
        include_once( "classes/INIFile.php" );
        include_once( "classes/eztemplate.php" );
        
        $ini = new INIFIle( "site.ini" );
        $Language = $ini->read_var( "eZNewsMain", "Language" );
        $DOC_ROOT = $ini->read_var( "eZNewsMain", "DocumentRoot" );
        
        $TEMPLATE_DIR = $ini->read_var( "eZTradeMain", "TemplateDir" );
        $t = new eZTemplate( $DOC_ROOT . $TEMPLATE_DIR . "/eznewsarticleview/eznewsflowerview/",  $DOC_ROOT . "/admin/intl/", $Language, "eznewsflowereditor.php" );
        
        $t->set_file( array(
                      "article_file" => "article.tpl"
                      )
                    );
        
        $t->set_block( "article_file", "errors_tpl", "errors" );
        $t->set_block( "errors_tpl", "error_tpl", "error" );
        $t->set_block( "article_file", "product_tpl", "product" );
        $t->set_block( "product_tpl", "picture_tpl", "picture" );
        
        $t->setAllStrings();
        
        if( $parsedXMLAttributes["pictureid"]["value"] )
        {
            include_once( "ezimagecatalogue/classes/ezimage.php" );        
            $file = new eZImage( $parsedXMLAttributes["pictureid"]["value"] );
            $variation = $file->requestImageVariation( $t->ini->read_var("strings", "image_width") , $t->ini->read_var("strings", "image_height") );

            $t->set_var( "picture_path", "/" . $variation->imagePath() );
            $t->set_var( "picture_width", $variation->width() );
            $t->set_var( "picture_height", $variation->height() );
            $t->set_var( "picture_alt", $file->caption() );
            $t->set_var( "picture_title", $file->caption() );
            $t->parse( "picture", "picture_tpl" );
        }
        else
        {
                 $t->set_var( "picture", "" );
        }
        
        $t->set_var( "product_name", $parsedXML["name"] );
        $t->set_var( "product_text", $parsedXML["description"] );
        $t->set_var( "product_price", $parsedXML["price"] );
        $t->parse( "product", "product_tpl" );
        
        $t->set_var( "error_text", "" );
        $t->set_var( "error", "" );
        $t->set_var( "errors", "" );
        #$t->parse( "error", "error_tpl" );        
        #$t->parse( "errors", "errors_tpl" );

        $t->pparse( "output", "article_file" );
    }
    
    var $Item;
    var $URLArray;
    var $className = "eZNewsFlower";
    var $parentName = "Heistad Hagesenter";
    var $parentPath = "news";
};



function return_page()
{
    global $parsedXML;
    global $parsedXMLAttributes;
    global $theViewer;
    global $data;

    $theViewer->printPage( $parsedXML, $parsedXMLAttributes );
}


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
            case 'categoryid':
                $parsedXMLAttributes[ $current_tag ]["value"] = $attrs["value"];
                break;
            case 'pictureid':
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
    
    if ($format == $close_tags[$name])
    {
        switch($name)
        {
            case 'product':
                return_page();
                $parsedXML = '';
                break;
            default:
                break;
        }
    }
}

# ne regexpt <([\w]+)>([^<]*)</\1>
?>
