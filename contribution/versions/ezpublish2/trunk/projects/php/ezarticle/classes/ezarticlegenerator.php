<?php
// 
// $Id: ezarticlegenerator.php,v 1.2 2000/10/19 18:03:40 bf-cvs Exp $
//
// Definition of eZArticleGenerator class
//
// Bård Farstad <bf@ez.no>
// Created on: <18-Oct-2000 15:47:43 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZArticle
//! eZArticleGenerator handles article XML generation.
/*!
  This class handles generation of XML contents for
  the articles. It will read from the .ini file and find
  the default generator to use.
*/

include_once( "classes/INIFile.php" );

class eZArticleGenerator
{
    function eZArticleGenerator( $generatorType="" )
    {
        if ( $generatorType == "" )
        {
            $ini = new INIFile( "site.ini" );

            $Generator = $ini->read_var( "eZArticleMain", "Generator" );
            $generatorType = $Generator;
        }
        
        switch ( $generatorType )
        {
            case "tech" :
            {
                $this->GeneratorFile = "eztechgenerator.php";
                $this->GeneratorClass = "eZTechGenerator";
            }
            break;
            
            case "simple":
            default :
            {
                $this->GeneratorFile = "ezsimplegenerator.php";
                $this->GeneratorClass = "eZSimpleGenerator";
            }
        }
    }

    /*!
      This function will parse the contents array and return valid
      XML data for insertion in the database.
    */
    function &generateXML( &$contents )
    {
        include_once( "ezarticle/classes/" . $this->GeneratorFile );

        $generator = new $this->GeneratorClass( $contents );
              
        return $generator->generateXML();        
    }
    
    /*!
      This function will return an array containing the original state
      of the article so it can be used in a edit form.
    */
    function &decodeXML( &$contents )
    {
        $xml =& xmltree( $contents );
        
        if ( $xml->root->children[0]->name == "generator" )
        {
            $generator =& $xml->root->children[0]->children[0]->content;

            switch ( $generator )
            {
                case "tech" :
                {
                    $this->GeneratorFile = "eztechgenerator.php";
                    $this->GeneratorClass = "eZTechGenerator";
                }
                break;

                case "simple" :
                {
                    $this->GeneratorFile = "ezsimplegenerator.php";
                    $this->GeneratorClass = "eZSimpleGenerator";
                }
                break;
            }
        }
        else
        {
            print( "<b>Error: eZArticleGenerator::decodeXML()  could not find generator in XML chunk.</b>" );
        }

        include_once( "ezarticle/classes/" . $this->GeneratorFile );

        $generator = new $this->GeneratorClass( $contents );
              
        return $generator->decodeXML();        
        
    }
        
    var $GeneratorClass;
    var $GeneratorFile;
}

?>
