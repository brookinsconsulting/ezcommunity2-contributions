<?
// 
// $Id: eznewsxml.php,v 1.2 2000/09/28 12:48:35 pkej-cvs Exp $
//
// Definition of eZNewsXML class and support functions.
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-Sep-2000 13:42:10 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsXML handles XML callbacks.
/*!
    Create a child class extending this class. Create two functions in the
    child class, one called startElement the other endElement. Check out
    the global callbacks startElement and checkElement for example of how
    you use the functions in your object.
    
    Your object file also needs to have opening tags and closing tags in
    order to be usable.
    
    The startElement and endElement in this base class are abstract. The
    code inside it would have worked with the XML in example 1.
    
    This code is based on this <A href="http://www.phpbuilder.com/columns/joe20000907.php3">
    article</A> by Joe Stump.
    
    Example:
    \code
    
    // Example 1 - XML
    
    <?xml version="1.0"?>
    <ezflower>
    <product>
        <name>
            The name of the product.
        </name>
        <description>
            Information about this product.
        </description>
        <price>
            Any text over several lines.
        </price>
        <pictureid value="1"/>
        <categoryid value="4"/>        
    </product>
    </ezflower>
    \endcode
 */
 
class eZNewsXML
{
    
    /*!
        Constructs a new eZNewsCommand object. This must be called from
        base classes. ( eZNewsXML::eZNewsXML() in childs constructor )
     */
    function eZNewsXML()
    {
        global $theXMLParser;
        $theViewer = &$this;
    }
    
    //!! eZNews
    //! startElement takes care of the start tags in an XML document.
    /*
        This function is abstract, but you'll find example code inside
        for how you could implement this in your own class.
     */
    function startElement( $parser, $inName, $attrs='' )
    {
        die("Take care of startElement() in a subclass of eZNewsXML,
         and make sure that you override the functions
         startElement and endElement. ");
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

    //!! eZNews
    //! endElement takes care of the end tags in an XML document.
    /*
        This function is abstract, but you'll find example code inside
        for how you could implement this in your own class.
     */
    function endElement( $parser, $inName, $attrs='' )
    {
        die("Take care of endElement() in a subclass of eZNewsXML,
         and make sure that you override the functions
         startElement and endElement. ");

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

    /// This is used for storing XML data. The data for each tag can be found in this associative array.
    var $parsedXML = array();

    /// This is used for storing XML attributes. The attribute for each  tag can be found in this associative array.
    var $parsedXMLAttributes = array();
    
    var $currentTag;

}

//!! eZNews
//! theXMLParser the global xml object needed by the parser callbacks.

global $theXMLParser;

//!! eZNews
//! startElement global callback used by the XML parser.
/*
 This function is a callback. It requires a global instance of an object.
 That object must have a function called startElement.
 */
function startElement( $parser, $name, $attrs='' )
{
    global $theXMLParser;
    $theXMLParser->startElement( $parser, $name, $attrs='' );
}

//!! eZNews
//! endElement global callback used by the XML parser.
/*
 This function is a callback. It requires a global instance of an object.
 That object must have a function called endElement.
 */
 
function endElement($parser, $name, $attrs='')
{
    global $theXMLParser;
    $theXMLParser->endElement( $parser, $name, $attrs='' );
}





//!! eZNews
//! addToOldTag global callback used by the XML parser.
/*
 This function does the magic of ensuring that all character data
 is stored in the correct data element of the $temp array.
 */
function addToOldTag( $data )
{
    global $last_tag;
    global $parsedXML;
    
    $data = utf8_decode( $data );
    
    $parsedXML[ $last_tag ] = $parsedXML[ $last_tag ] . $data;
    if( $data == "\n" || $data == "\r" )
    {
            $parsedXML[ $last_tag ] = $parsedXML[ $last_tag ] . "<br>\n";
    }
    if( $data == "\t"  )
    {
            $parsedXML[ $last_tag ] = $parsedXML[ $last_tag ] . "\t";
    }
}

//!! eZNews
//! characterData global callback used by the XML parser.
/*
 This function takes character data and stores it in an array where
 the key is the tag name.
 */

function characterData($parser, $data)
{
    global $current_tag;
    global $parsedXML;
    global $last_tag;
    
    if( empty( $current_tag ) )
    {
        addToOldTag( $data );
    }
    else
    {
        $parsedXML[ $current_tag ] = utf8_decode( $data );
        $last_tag = $current_tag;
        $current_tag = '';
    }
}
    

//!! eZNews
//! $data an unfortunate item which is needed by the parser.
global $data;

// declare the character set - UTF-8 is the default
$type = 'US-ASCII';
$type = 'ISO-8859-1';
$type = 'UTF-8';

// create our parser
$xml_parser = xml_parser_create($type);

// set some parser options 
xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, false);
xml_parser_set_option($xml_parser, XML_OPTION_TARGET_ENCODING, $type);

// this tells PHP what functions to call when it finds an element
// these funcitons also handle the element s attributes
xml_set_element_handler($xml_parser, 'startElement','endElement');

// this tells PHP what function to use on the character data
xml_set_character_data_handler($xml_parser, 'characterData');

#if (!($fp = fopen($xml_file, 'r'))) {
#    die("Could not open $xml_file for parsing!\n");
#}
// loop through the file and parse baby!
    if( !( $data = utf8_encode($data) ) )
    {
        
    }
    
    if( !xml_parse( $xml_parser, $data ) )
    {
        die(sprintf( "XML error: %s at line %d\n\n",
        xml_error_string(xml_get_error_code($xml_parser)),
        xml_get_current_line_number($xml_parser)));
    }

xml_parser_free($xml_parser);
?>
