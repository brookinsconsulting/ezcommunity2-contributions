<?php
//
// $Id: ezxml.php,v 1.1 2001/11/16 14:42:30 bf Exp $
//
// Definition of eZXML class
//
// Bård Farstad <bf@ez.no>
// Created on: <16-Nov-2001 11:26:01 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! eZXML
//! eZXML handles parsing of well formed XML documents.
/*!
  eZXML will create a DOM tree from well formed XML documents.

*/

include_once( "ezxml/classes/ezdomnode.php" );
include_once( "ezxml/classes/ezdomdocument.php" );

class eZXML
{
    /*!
    */
    function eZXML( )
    {

    }

    /*!
      \static
      Will return an DOM object tree from the well formed XML.
    */
    function domTree( $xmlDoc )  
    {
        $TagStack = array();

        // get document version

        // strip header
        $xmlDoc = preg_replace( "#<\?.*?\?>#", "", $xmlDoc );

        $domDocument = new eZDOMDocument();
        $domDocument->version = "1.0";

        $currentNode =& $domDocument;

        $pos = 0;
        $endTagPos = 0;
        while ( $pos < strlen( $xmlDoc ) )
        {
            $char = $xmlDoc[$pos];
            if ( $char == "<" )
            {
                // inside a new tag

                // find tag name
                $endTagPos = strpos( $xmlDoc, ">", $pos );

                // tag name with attributes
                $tagName = substr( $xmlDoc, $pos + 1, $endTagPos - ( $pos + 1 ) );

                // check if it's an endtag </tagname>
                if ( $tagName[0] == "/" )
                {
                    $lastNodeArray = array_pop( $TagStack );
                    $lastTag = $lastNodeArray["TagName"];

                    $lastNode =& $lastNodeArray["ParentNodeObject"];

                    unset( $currentNode );
                    $currentNode =& $lastNode;
                    
                    $tagName = substr( $tagName, 1, strlen( $tagName ) );
                    
                    if ( $lastTag != $tagName )
                    {
                        print( "Error parsing XML, unmatched tags $tagName" );
                        return false;
                    }
                    else
                    {
                        //    print( "endtag name: $tagName ending: $lastTag <br> " );
                    }
                }
                else
                {
                    $tagNameEnd = strpos( $tagName, " " );

                    if ( $tagNameEnd > 0 )
                    {
                        $justName = substr( $tagName, 0, $tagNameEnd );

                    }
                    else
                        $justName = $tagName;

                    // start tag
                    unset( $subNode );
                    $subNode = new eZDOMNode();
                    $subNode->name = $justName;
                    $subNode->type = 1;

                    $currentNode->children[] =& $subNode;

                    // find attributes
                    if ( $tagNameEnd > 0 )
                    {
                        $attributePart =& substr( $tagName, $tagNameEnd, strlen( $tagName ) );
                        $attributeArray = explode( " ", $attributePart );

                        foreach ( $attributeArray as $attributePart )
                        {
                            if ( trim( $attributePart ) != ""  )
                            {
                                $attributeTmpArray = explode( "=", $attributePart );                                

                                $attributeName = $attributeTmpArray[0];
                                $attributeValue = $attributeTmpArray[1];

                                // check that attribute name is valid
                                if ( trim( $attributeName ) == "" )
                                {
                                    print( "Error in XML: invalid attributes near \"$attributePart\"" );
                                    return false;
                                }

                                // check that we have a valid attribute, if not choke
                                if ( $attributeValue[0] != "\"" or ( $attributeValue[ strlen($attributeValue) - 1 ] != "\""  ) )
                                {
                                    print( "Error in XML: invalid attributes near \"$attributePart\"" );
                                    return false;
                                }
                                
                                // remove " from value part
                                $attributeValue = substr( $attributeValue, 1, strlen( $attributeValue ) - 2);

//                                print( $attributeName . "=" . $attributeValue );

                                // start tag
                                unset( $attrNode );
                                $attrNode = new eZDOMNode();
                                $attrNode->name = $attributeName;
                                $attrNode->type = 2;
                                $attrNode->content = $attributeValue;

                                $subNode->attributes[] =& $attrNode;
                                
                            }
                        }
                    }
                    

                    array_push( $TagStack,
                    array( "TagName" => $justName, "ParentNodeObject" => &$currentNode ) );

                    unset( $currentNode );
                    $currentNode =& $subNode;

//                    print( "tag name: $justName <br> " );
                }
            }

            $pos = strpos( $xmlDoc, "<", $pos + 1 );

           
            if ( $pos == false )
            {
                // end of document
                $pos = strlen( $xmlDoc );
            }
            else
            {
                // content tag
                $tagContent = substr( $xmlDoc, $endTagPos + 1, $pos - ( $endTagPos + 1 ) );

                if ( trim( $tagContent ) != "" )
                {
                    unset( $subNode );
                    $subNode = new eZDOMNode();
                    $subNode->name = "text";
                    $subNode->type = 3;
                    $subNode->content = $tagContent;
                    
                    $currentNode->children[] =& $subNode;
                }
            }
        }
        

        return $domDocument;
    }
}

?>
