<?
// 
// $Id: eznewsxml.php,v 1.3 2000/10/02 19:07:02 pkej-cvs Exp $
//
// Definition of eZNewsXML class and support functions.
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <28-Sep-2000 13:42:10 pkej>
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
    }
    
    //!! eZNews
    //! startElement takes care of the start tags in an XML document.
    /*
        This function is abstract, but you'll find example code inside
        for how you could implement this in your own class.
     */
    function startElement( $parser, $inName, $attrs='' )
    {
//         die("Take care of startElement() in a subclass of eZNewsXML,
//          and make sure that you override the functions
//          startElement and endElement. ");

        global $theXMLParser;

        $theXMLParser->currentTag = $inName;

        if ( $format == $openTags[$inName] )
        {
            switch( $inName )
            {
                case 'pictureid':
                    $theXMLParser->parsedXMLAttributes[ $theXMLParser->currentTag ]["value"] = $attrs["value"];
                    break;
                case 'categoryid':
                    $theXMLParser->parsedXMLAttributes[ $theXMLParser->currentTag ]["value"] = $attrs["value"];
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
//         die("Take care of endElement() in a subclass of eZNewsXML,
//          and make sure that you override the functions
//          startElement and endElement. ");

        global $theXMLParser;

        if( $format == $closeTags[ $inName ] )
        {
            switch( $inName )
            {
                case 'ezflower':
                    $theXMLParser->page( );
                    $theXMLParser->parsedXML = '';
                    break;
                case 'product':
                echo "extremt";
                    $theXMLParser->item( );
                    $theXMLParser->parsedXML = '';
                    break;
                default:
                    break;
            }
        }
    }
    
    
    //!! eZNews
    //! addToOldTag adds data from the stream to the last tag parsed.
    /*
     This function does the magic of ensuring that all character data
     is stored in the correct data element of the $temp array.
     */
    function addToOldTag( $data )
    {
        global $theXMLParser;
        
        $data = utf8_decode( $data );

        $this->parsedXML[ $this->lastTag ] = $theXMLParser->parsedXML[ $theXMLParser->lastTag ] . $data;
        if( $data == "\n" || $data == "\r" )
        {
                $theXMLParser->parsedXML[ $theXMLParser->lastTag ] = $theXMLParser->parsedXML[ $theXMLParser->lastTag ] . "<br>\n";
        }
        if( $data == "\t"  )
        {
                $theXMLParser->parsedXML[ $theXMLParser->lastTag ] = $theXMLParser->parsedXML[ $theXMLParser->lastTag ] . "\t";
        }
    }
    

    //!! eZNews
    //! characterData stores data in the value array.
    /*
     This function takes character data and stores it in an array where
     the key is the tag name.
     */
    function characterData( $parser, $data )
    {
        global $theXMLParser;
        if( empty( $theXMLParser->currentTag ) )
        {
            $theXMLParser->addToOldTag( $data );
        }
        else
        {
            $theXMLParser->parsedXML[ $theXMLParser->currentTag ] = utf8_decode( $data );
            $theXMLParser->lastTag = $theXMLParser->currentTag;
            $theXMLParser->currentTag = '';
        }
    }

    /// This is used for storing XML data. The data for each tag can be found in this associative array.
    var $parsedXML = array();

    /// This is used for storing XML attributes. The attribute for each  tag can be found in this associative array.
    var $parsedXMLAttributes = array();
    
    var $currentTag;
    var $lastTag;
    var $item;
    var $page;

}
?>
