<?
/*! eZNews
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

/*! eZNews
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
    
    function printArray( &$array )
    {
        if( is_array( $array ) )
        {
            foreach( $array as $item )
            {
                if( is_array( $item )  )
                {
                    $this->printArray( $item );
                }
                else
                {
                    echo htmlspecialchars( $item ) . "<br>";
                }
            }
        }
        else    
        {
            echo htmlspecialchars( $array ) . " a<br>";
        }
    }

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
