<?php
// 
// $Id: eznewscustomerhh.php,v 1.2 2000/10/02 19:07:02 pkej-cvs Exp $
//
// Definition of eZNewsCustomerHH class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-Sep-2000 13:03:00 pkej>
//

global $data;
global $theXMLParser;

//!! eZNews
//! $openTags handles the XML open tags.
/*
    This is the array of opening tags. All character data between an opening
    and closing tag is stored in the global $parsedXML array, with the name
    of the tag as the key to the data.
 */

$openTags = array
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

//!! eZNews
//! $closeTags handles the XML close tags.
/*
    This is the array of closing tags. All character data between an opening
    and closing tag is stored in the global $parsedXML array, with the name
    of the tag as the key to the data.
 */
$closeTags = array
(
    'ezflower' => '</ezflower>',
    'price' => '</price>',
    'name' => '</name>',
    'description' => '</description>',
    'product' => '</product>'
);

//!! eZNews
//! eZNewsCustomerHH handles parsing for our customer Heistad Hagesenter.
/*!
 */

include_once( "eznews/classes/eznewsitem.php" );
include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );


global $Ini;
global $Template;
global $Item;

$Item = new eZNewsItem();

$Ini = new INIFile( "site.ini" );

$Language = $this->Ini->read_var( "eZNewsMain", "Language" );

$TemplateDir = $this->Ini->read_var( "eZNewsMain", "TemplateDir" );   
$DocRoot = $this->Ini->read_var( "eZNewsMain", "DocumentRoot" );   

$Template = new eZTemplate( $DocRoot . $TemplateDir . "/eznewsarticleview/eznewsflowerview/",  $DocRoot . "/admin/intl/", $Language, "eznewsflowereditor.php" );

$Template->set_file( array(
              "article_file" => "article_list.tpl"
              )
            );

$Template->set_block( "article_file", "errors_tpl", "errors" );
$Template->set_block( "errors_tpl", "error_tpl", "error" );
$Template->set_block( "article_file", "category_tpl", "category" );
$Template->set_block( "article_file", "product_tpl", "product" );
$Template->set_block( "product_tpl", "picture_tpl", "picture" );

$Template->setAllStrings();

include_once( "eznews/classes/eznewsxml.php" );
include_once( "eznews/classes/eznewsxmlfunctions.php");
class eZNewsCustomerHH extends eZNewsXML
{
    function eZNewsCustomerHH( $URLArray )
    {
        global $Item;
        $itemInfo = $URLArray[2];
        
        $theXMLParser->item = $this->printItem();
        $theXMLParser->page = $this->printItem();

        switch( $itemInfo )
        {
            default:
                $Item->get( $itemInfo, true, true );
                break;
        }
        
        switch( $Item->getItemType() )
        {
            case "category":
                $this->doCategory();
                break;
            case "flower":
                $this->doFlower();
                break;
            default:
                echo $Item->Name();
                $this->doNothing();
                break;
        }
        
    }
    
    
    
    function doFlower()
    {
        echo $Item->getItemType();
    }
    
    function doNothing()
    {
        echo "We shouldn't get here.";
    }
    
    function doCategory()
    {
        global $Item;
        global $data;
        if( $Item->Name() != 'root' )
        {
            $this->parsedXMLAttributes["categoryid"]["value"] = $Item->ID();

            $this->URLArray = $URLArray;
            $kids = $Item->getAllChildren("date", "reverse" );
            $description = $this->Item->publicDescriptionID();

            include_once( "eznews/classes/eznewsflower.php" );

            $firstTime = true;

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
                echo "This is an empty element...contact administrator.";
            }
            else
            {
                $data = $data . "</ezflower>";
                echo htmlspecialchars($data) . "<br>";
                include( "eznews/classes/eznewsxmlparser.php" );
            }
            
        }
    }
    
    function startElement( $parser, $inName, $attrs='' )
    {
        $this->currentTag = $inName;

        if ( $format == $openTags[$inName] )
        {
            switch( $inName )
            {
                case 'pictureid':
                    $this->parsedXMLAttributes[ $this->currentTag ]["value"] = $attrs["value"];
                    break;
                case 'categoryid':
                    $this->parsedXMLAttributes[ $this->currentTag ]["value"] = $attrs["value"];
                    break;
                default:
                    break;
            }
        }
    }
    
    function endElement( $parser, $inName, $attrs='' )
    {
        if( $format == $closeTags[ $inName ] )
        {
            switch( $inName )
            {
                case 'ezflower':
                    $this->printPage(  );
                    $this->parsedXML = '';
                    break;
                case 'product':
                    $this->printItem(  );
                    $this->parsedXML = '';
                    break;
                default:
                    break;
            }
        }
    }
    
    function printItem()
    {
        global $Template;
        global $Ini;
        if( $this->parsedXMLAttributes["pictureid"]["value"] )
        {
            include_once( "ezimagecatalogue/classes/ezimage.php" );        
            $file = new eZImage( $parsedXMLAttributes["pictureid"]["value"] );
            $variation = $file->requestImageVariation( $Ini->read_var("strings", "image_width") , $Ini->read_var("strings", "image_height") );

            $Template->set_var( "picture_path", "/" . $variation->imagePath() );
            $Template->set_var( "picture_width", $variation->width() );
            $Template->set_var( "picture_height", $variation->height() );
            $Template->set_var( "picture_alt", $file->caption() );
            $Template->set_var( "picture_title", $file->caption() );
            $Template->parse( "picture", "picture_tpl" );
        }
        else
        {
            $Template->set_var( "picture", "" );
        }
        $Template->set_var( "product_name", $this->parsedXML["name"] );
        $Template->set_var( "product_text", $this->parsedXML["description"] );
        $Template->set_var( "product_price", $this->parsedXML["price"] );
        $Template->parse( "product", "product_tpl", true );
    }
    
    function printPage()
    {
        if( !empty( $this->parsedXMLAttributes["categoryid"]["value"] ) )
        {
            $Template->set_var( "category_name", "Begravelse");
            $Template->set_var( "category_info", "infodajføldasjøl askløfjlkø");
            $Template->parse( "category", "category_tpl" );
            $this->parsedXMLAttributes["categoryid"]["value"] = '';
        }
        else
        {
            $Template->set_var( "category", "" );
        }

        $Template->set_var( "error_text", "" );
        $Template->set_var( "error", "" );
        $Template->set_var( "errors", "" );
        $Template->parse( "error", "error_tpl" );        
        $Template->parse( "errors", "errors_tpl" );
        $Template->pparse( "output", "article_file" );
    }
};

?>
