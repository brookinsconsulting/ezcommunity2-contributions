<?
// 
// $Id: eznewsoutput.php,v 1.1 2000/10/12 11:08:20 pkej-cvs Exp $
//
// Definition of eZNewsOutput class
//
// Paul K Egell-Johnsen <pkej@ez.no>
// Created on: <12-Okt-2000 10:59:00 pkej>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZNews
//! eZNewsOutput handles some utility functions for output of data.
/*!
    This class is used by all classes which are doing html output in the eZNews hiearchy.
    
 */

include_once( "classes/eztemplate.php" );
include_once( "classes/INIFile.php" );        
include_once( "eznews/classes/eznewsitemviewer.php" );

class eZNewsOutput extends eZTemplate
{
    /*!
        The constructor for this object.
     */
    function eZNewsOutput( $inNewsConfigFileName )
    {
        $this->ini = new INIFile( $inNewsConfigFileName );
        
        $DocumentPath = $this->read_var( "eZNewsMain", "DocumentRoot" );
        $TemplatePath = $this->read_var( "eZNewsMain", "TemplateDir" );
        $Language    = $this->read_var( "eZNewsMain", "Language" );
        

        $this->AdminLanguagePath = $DocumentPath . "/admin/intl/";
        $this->AdminTemplatePath = $DocumentPath . "/admin/" . $TemplatePath;
        $this->LanguagePath = $DocumentPath . "/intl/" . $Language;
        $this->TemplatePath = $DocumentPath . $TemplatePath;
        $this->DocumentPath = $DocumentPath;
        $this->Language = $Language;
    }
    
    
    /*!
        A wrapper enabling read_var on the local object ini...
        
        This kludge is neccessary because we can't inherit from
        more than one class.
     */
    function read_var( $section, $variable )
    {
        return $this->ini->read_var( $section, $variable );
    }
    
    /*!
        This function inializes the template of this object to point to files
        in the admin section.
     */
    function readAdminTemplate( $inSubPath, $inLanguageFileName )
    {
        #echo "eZNewsOutput::readAdminTemplate( \$inSubPath = $inSubPath, \$inLanguageFileName = $inLanguageFileName )<br \>\n";
        #$this->printObject();
        
        eZTemplate::eZTemplate( $this->AdminTemplatePath . $inSubPath, $this->AdminLanguagePath, $this->Language, $inSubPath . "/" . $inLanguageFileName );
        
        $this->LanguageConfig = $this->ini();
    }



    /*!
        This function inializes the template of this object to point to files
        in the public section.
     */
    function readTemplate( $inSubPath, $inLanguageFileName )
    {
        #echo "eZNewsOutput::readTemplate( \$inSubPath = $inSubPath, \$inLanguageFileName = $inLanguageFileName )<br \>\n";

        eZTemplate::eZTemplate( $this->TemplatePath . $inSubPath, $this->LanguagePath, $this->Language, $inSubPath . "/" . $inLanguageFileName );
        
        $this->LanguageConfig = $this->ini();
    }


    /*!
        Prints the info in all the variables of this object.
     */
    function printObject()
    {
        echo "\$this->TemplatePath = " . $this->TemplatePath;
        echo ", \$this->LanguagePath = " . $this->LanguagePath;
        echo ", \$this->AdminTemplatePath = " . $this->AdminTemplatePath;
        echo ", \$this->AdminLanguagePath = " . $this->AdminLanguagePath;
        echo ", \$this->Language = " . $this->Language;
        echo ", \$this->DocumentPath = " . $this->DocumentPath;
        echo "<br \>\n";
    }

    /*!
        This function will set a variable to plural or singular based on the count sent in.

     */
    function pluralize( $outputString, $insertString, $count )
    {
        if( $count == 1 )
        {
            $this->set_var( $outputString, $this->LanguageConfig->read_var( "strings", $insertString . "_singular" ) );
        }
        else
        {
            $this->set_var( $outputString, $this->LanguageConfig->read_var( "strings", $insertString . "_plural" ) );
        }
    }


    
    /*!
        This function will put a plural or singular version of a string into a template string, based
        on the number count.
     */
    function pluralize2( &$outputString, $pluralString, $singularString, $count )
    {
        #echo "eZNewsItemViewer::doPluralize()<br>\n";
        if( $count == 1 )
        {
            $outputString = $singularString;
        }
        else
        {
            $outputString = $pluralString;
        }
    }
    
    
    
    /* The local language configuration object */
    var $LanguageConfig;
    
    /* The directory of the language files */
    var $LanguagePath;
    
    /* The directory of the language files */
    var $LanguageFileName;
    
    /* The directory of the template files */
    var $TemplatePath;
    
    /* The directory of the language files for the administration */
    var $AdminLanguagePath;
    
    /* The directory of the template files for the administration */
    var $AdminTemplatePath;
    
    /* The document dir of this program */
    var $DocumentPath;
    
    /* The language of this session */
    var $Language;
};
