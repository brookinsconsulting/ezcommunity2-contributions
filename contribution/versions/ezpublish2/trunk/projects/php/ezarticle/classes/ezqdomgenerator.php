<?php
// 
// $Id: ezqdomgenerator.php,v 1.34 2001/09/17 12:31:06 bf Exp $
//
// Definition of eZQDomGenerator class
//
// Created on: <24-Mar-2001 13:10:33 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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
//!! eZArticle
//! eZTechGenerator generates  XML contents for articles.
/*!
  This class will generate a tech XML article. This class is ment
  as an example of how to write your own special generator.

*/

/*!TODO
  
*/

class eZQDomGenerator
{
    /*!
      Creates a new eZQDomGenerator object.      
    */
    function eZQDomGenerator( &$contents )
    {
        $this->Level = 0;
        $this->PageCount = 0;
        $this->Contents = $contents;

        // user defined tags
        $ini =& INIFile::globalINI();

        $customTags = $ini->read_var( "eZArticleMain", "CustomTags" );

        $this->CustomTagsArray = explode( ";", $customTags );        
    }

    /*!
      Generates valid XML data to use for storage.
    */
    function &generateXML()
    {
        // add the XML header.
        $newContents = "<?xml version=\"1.0\"?>";
        
        //add the generator, this is used for rendering.
        $newContents .= "<article><generator>qdom</generator>\n";

        //add the contents
        $newContents .= "<intro>" . $this->generatePage( $this->Contents[0] ) . "</intro>";

        // get every page in an array
        $pages = split( "<page>" , $this->Contents[1] );

        $body = "";
        foreach ( $pages as $page )
        {
            $tmpPage = $page;

            $tmpPage =& $this->generatePage( $tmpPage );

            $body .= "<page>" . $tmpPage  . "</page>";        
        }

        $this->PageCount = count( $pages );
        

        $newContents .= "<body>" . $body . "</body></article>";

        return $newContents;
    }

    /*!
      \private
      
    */
    function &generatePage( $tmpPage )
    {
        $tmpPage = $this->generateImage( $tmpPage );

        $tmpPage = $this->generateMedia( $tmpPage );
        $tmpPage = $this->generateFile( $tmpPage );

        $tmpPage = $this->generateHeader( $tmpPage );

        $tmpPage = $this->generateHr( $tmpPage );
	
        $tmpPage = $this->generateTable( $tmpPage );
	
        // replace & with &amp; to prevent killing the xml parser..
        // is that a bug in the xmltree(); function ? answer to bf@ez.no
        $tmpPage = ereg_replace ( "&", "&amp;", $tmpPage );

        $tmpPage = $this->generateUnknowns( $tmpPage );

        $tmpPage = $this->generateLink( $tmpPage );

        $tmpPage = $this->generateForm( $tmpPage );
        
//        $tmpPage = $this->generateModule( $tmpPage );
        
//        $tmpPage = $this->generateHTML( $tmpPage );

        return $tmpPage;
    }

    /*!
      \private
      
    */
    function &generateUnknowns( $tmpPage )
    {
        $tmpPage =& preg_replace( "#< #", "&lt; ", $tmpPage );
//        $tmpPage =& preg_replace( "# >#", " &gt;", $tmpPage );
        
        // make unknown tags readable.. look-ahead assertion is used ( ?! ) 
//        $tmpPage = preg_replace( "/<(?!(page|php|\/|image|cpp|shell|sql|hea|lin|iconlink|per|bol|ita|und|str|pre|ver|lis|ezhtml|html|java|ezanchor|mail|module|bullet))/", "&lt;", $tmpPage );

        // look-behind assertion is used here (?<!) 
        // the expression must be fixed width eg just use the 3 last letters of the tag

//        $tmpPage = preg_replace( "#(?<!(age|php|age|cpp|ell|sql|der|erl|old|lic|ine|ike|pre|tim|isp|tml|ava|let))>#", "&gt;", $tmpPage );
        // make better..
//        $tmpPage = preg_replace( "#/&gt;#", "/>", $tmpPage );

        return $tmpPage;
    }

    /*!
      \private
      Converts image tags to XML tags.
    */
    function &generateImage( $tmpPage )
    {
        $tmpPage = preg_replace( "/(<image\s+([0-9]+)\s+([a-z]+)\s+([a-z]+?)\s*>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" />", $tmpPage );
        
        // parse the <image id align size link> tag and convert it
        // link is optional
        // to <image id="id" align="align" size="size" href="link" />
        $tmpPage = preg_replace( "/(<image\s+([0-9]+)\s+([a-z]+)\s+([a-z]+)\s+([^ ]+?)\s*>)/", "<image id=\"\\2\" align=\"\\3\" size=\"\\4\" href=\"\\5\" />", $tmpPage );

        // default image tag <image id>
        $tmpPage = preg_replace( "/(<image\s+?([0-9]+?)\s*?>)/", "<image id=\"\\2\" align=\"center\" size=\"medium\" />", $tmpPage );
        
        return $tmpPage;
    }

    /*!
      \private
      Converts media tags to XML tags.
    */
    function &generateMedia( $tmpPage )
    {
        // default image tag <media id>
        $tmpPage = preg_replace( "/(<media\s+?([0-9]+?)\s*?>)/", "<media id=\"\\2\" />", $tmpPage );
        return $tmpPage;
    }

    /*!
      \private
      Converts file tags to XML tags.
    */
    function &generateFile( $tmpPage )
    {
        // default image tag <file id text>
        $tmpPage = preg_replace( "/(<file\s+?([0-9]+)\s*(.*?)>)/", "<file id=\"\\2\" text=\"\\3\" />", $tmpPage );
        return $tmpPage;
    }
    
    
    function &generateHr( $tmpPage )
    {
        // default horizontal line tag <hr>
        $tmpPage = preg_replace( "/(<hr\s*?>)/", "<hr />", $tmpPage );
        return $tmpPage;
    }

        
    /*!
      \private
      
    */
    function &generateHeader( $tmpPage )
    {
        $tmpPage = preg_replace( "/(<header\s+?([^ ]+?)\s*?>)/", "<header level=\"\\2\">", $tmpPage );

        $tmpPage = preg_replace( "/(<header\s*?>)/", "<header level=\"1\">", $tmpPage );
        
        return $tmpPage;
    }
    
    /*!
      \private
    */      
    function &generateTable( $tmpPage )
    {

        $tmpPage = preg_replace( "/(<table\s+([0-9]+[^ ]??)\s+([0-9]+?)\s*>)/", "<table width=\"\\2\" border=\"\\3\">", $tmpPage );
        $tmpPage = preg_replace( "/(<table\s+([0-9]+[^ ]??)\s*>)/", "<table width=\"\\2\">", $tmpPage );
        
        $tmpPage = preg_replace( "/(<td\s+([0-9]+[^ ]??)\s+?([0-9]+?)\s+([0-9]+?)\s*>)/", "<td width=\"\\2\" colspan=\"\\3\" rowspan=\"\\4\">", $tmpPage );
        $tmpPage = preg_replace( "/(<td\s+([0-9]+[^ ]??)\s+?([0-9]+?)\s*>)/", "<td width=\"\\2\" colspan=\"\\3\">", $tmpPage );
        $tmpPage = preg_replace( "/(<td\s+([0-9]+[^ ]??)\s*>)/", "<td width=\"\\2\">", $tmpPage );
        	
        return $tmpPage;
    }

    /*!
      \private
       
    */
    function &generateForm( $tmpPage )
    {
        $tmpPage = preg_replace( "/(<form\s*?>)/", "<form />", $tmpPage );
        
        return $tmpPage;
    }
    
    /*!
      \private
      Converts the link tags to valid XML tags.
    */
    function &generateLink( $tmpPage )
    {
        // convert <link ez.no ez systems> to valid xml
        // $tmpPage = "<link ez.no ez systems> <link ez.no ez systems>";
        $tmpPage = preg_replace( "#(<link\s+?([^ ]+)\s+?([^>]+)>)#", "<link href=\"\\2\" text=\"\\3\" />", $tmpPage );
        $tmpPage = preg_replace( "#(<popuplink\s+?([^ ]+)\s+?([^>]+)>)#", "<popuplink href=\"\\2\" text=\"\\3\" />", $tmpPage );

        $tmpPage = preg_replace( "#(<iconlink\s+?([^ ]+)\s+?([^>]+)>)#", "<iconlink href=\"\\2\" text=\"\\3\" />", $tmpPage );
        
        // convert <ezanchor anchor> to <ezanchor href="anchor" />
        $tmpPage = preg_replace( "#<ezanchor\s+?(.*?)>#", "<ezanchor href=\"\\1\" />", $tmpPage );
        
        // convert <mail adresse@domain.tld subject line, link text>
        // to valid xml
        $tmpPage = preg_replace( "#<mail\s+?([^ ]*?)\s+?(.*?),\s+?([^>]*?)>#", "<mail to=\"\\1\" subject=\"\\2\" text=\"\\3\" />", $tmpPage );

        return $tmpPage;
    }
    

    /*!
      Decodes the xml chunk and returns the original array to the article.

      If htmlSpecialChars == true the output is converted to HTML special chars like:
      &gt; and &lt;...
    */
    function &decodeXML( $htmlSpecialChars=false )
    {
        $contentsArray = array();

        $xml =& xmltree( $this->Contents );

//        $xml =& qdom_tree( $this->Contents );

        if ( !$xml )
        {
            print( "<br /><b>Error: eZQDomRenderer::docodeXML() could not decode XML</b><br />" );
        }
        else
        {
            $into = "";
            $body = "";

            foreach ( $xml->children as $child )
            {
                if ( $child->name == "article" )
                {
                    foreach ( $child->children as $article )
                    {
                        if ( $article->name == "intro" )
                        {
                            $intro = $this->decodePage( $article );
                        }
                        
                        if ( $article->name == "body" )
                        {
                            $body = $article->children;
                        }
                        
                    }
                }
            }

            $contentsArray[] = $intro;

            $bodyContents = "";
            $i=0;
            // loop on the pages
            foreach ( $body as $page )
            {
                if ( $page->name == "page" )
                {
                    $pageContent = "";

                    $pageContent = $this->decodePage( $page );

                    if ( $i > 0 )
                        $bodyContents .=  "<page>" . $pageContent;
                    else
                        $bodyContents .=  $pageContent;
                    
                    $i++;
                }
            }

            if ( $htmlSpecialChars == true )
                $contentsArray[] = htmlspecialchars( $bodyContents );
            else
                $contentsArray[] = $bodyContents;
        }

        return $contentsArray;
    }

    /*!
      \private

    */
    function decodePage( $page )
    {
        $value = "";
        if ( count( $page->children ) > 0 )
        {            
            foreach ( $page->children as $paragraph )
            {
                $value .= $this->decodeStandards( $paragraph );
                $value .= $this->decodeHeader( $paragraph );
                $value .= $this->decodeImage( $paragraph );
                $value .= $this->decodeMedia( $paragraph );
                $value .= $this->decodeFile( $paragraph );
                $value .= $this->decodeLink( $paragraph );
                $value .= $this->decodeHr( $paragraph );
                $value .= $this->decodeTable( $paragraph );
                $value .= $this->decodeCustom( $paragraph );
            }
        }

        return $value;
    }

    /*!
      Decodes header tags
    */     
    function &decodeHeader(  $paragraph )
    {
        switch ( $paragraph->name )
        {
            case "header" :
            {

                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "#text" or $child->name == "text" )
                    {
                        $content = $child->content;
                    }
                }

                $level = 1;

                if ( count( $paragraph->attributes ) > 0 )                 
                foreach ( $paragraph->attributes as $attr )
                {
                    switch ( $attr->name )
                    {
                        case "level" :
                        {
                            $level = $attr->children[0]->content;
                        }
                        break;
                    }
                }

                
                
                $pageContent .= "<header $level>" . $content . "</header>";
            }
            break;
        }        
        
        return $pageContent;        
    }
    

    /*!
      \private
      
    */
    function &decodeImage( $paragraph )
    {
        // image 
        if ( $paragraph->name == "image" )
        {
            foreach ( $paragraph->attributes as $imageItem )
                {
                    switch ( $imageItem->name )
                    {

                        case "id" :
                        {
                            $imageID = $imageItem->children[0]->content;
                        }
                        break;

                        case "align" :
                        {
                            $imageAlignment = $imageItem->children[0]->content;
                        }
                        break;

                        case "size" :
                        {
                            $imageSize = $imageItem->children[0]->content;
                        }
                        break;

                        case "href" :
                        {
                            $imageHref = $imageItem->children[0]->content;
                        }
                        break;

                        case "caption" :
                        {
                            $imageCaptionOverride = trim( $imageItem->children[0]->content );
                        }
                        break;

                        case "target" :
                        {
                            $imageTarget = trim( $imageItem->children[0]->content );
                        }
                        break;
                        
                    }
                }
            
            if (
                $imageSize != "small"  &&
                $imageSize != "medium" &&
                $imageSize != "large"  &&
                $imageSize != "original"
                )
            {
                $imageSize = "medium";
            }

            if ( $imageCaptionOverride != "" || $imageTarget != "" )
            {
                $captionText = "";
                $targetText = "";
                
                if ( $imageCaptionOverride != "" )
                    $captionText = "caption=\"$imageCaptionOverride\"";

                if ( $imageTarget != "" )
                    $targetText = "target=\"$imageTarget\"";

                if ( $imageHref != "" )
                    $hrefText = "href=\"$imageHref\"";
                
                $pageContent = "<image id=\"$imageID\" align=\"$imageAlignment\" size=\"$imageSize\" $hrefText $captionText $targetText />";
            }
            else
            {
                $pageContent = "<image $imageID $imageAlignment $imageSize $imageHref>";
            }                            

        }
        return $pageContent;
    }

    /*!
      \private
      
    */
    function &decodeMedia( $paragraph )
    {
        // media 
        if ( $paragraph->name == "media" )
        {
            foreach ( $paragraph->attributes as $mediaItem )
                {
                    switch ( $mediaItem->name )
                    {
                        case "id" :
                        {
                            $mediaID = $mediaItem->children[0]->content;
                        }
                        break;
                    }
                }
                        
            $pageContent = "<media $mediaID>";
        }
        return $pageContent;
    }
    

    /*!
      \private
      
    */
    function &decodeFile( $paragraph )
    {
        if ( $paragraph->name == "file" )
        {
            foreach ( $paragraph->attributes as $fileItem )
            {
                switch ( $fileItem->name )
                {
                    case "id" :
                    {
                        $fileID = $fileItem->children[0]->content;
                    }
                    break;

                    case "text" :
                    {
                        $fileText = $fileItem->children[0]->content;
                    }
                    break;
                }
            }
                        
            $pageContent = "<file $fileID $fileText>";
        }
        return $pageContent;
    }
    
    
    function &decodeHr( $paragraph )
    {
        if ( $paragraph->name == "hr" )
        {            
            $pageContent = "<hr>";
        }
        return $pageContent;
    }
    


    /*!
      \private
    */
    function &decodeLink( $paragraph )
    {
        // link
        if ( $paragraph->name == "link" )
        {
            foreach ( $paragraph->attributes as $imageItem )
            {
                switch ( $imageItem->name )
                {

                    case "href" :
                    {
                        $href = $imageItem->children[0]->content;
                    }
                    break;

                    case "text" :
                    {
                        $text = $imageItem->children[0]->content;
                    }
                    break;
                                
                }
            }
                        
            $pageContent .= "<link $href $text>";
        }

        
        // mail
        if ( $paragraph->name == "mail" )
        {
            foreach ( $paragraph->attributes as $mailItem )
            {
                switch ( $mailItem->name )
                {
                    case "to" :
                    {
                        $to = $mailItem->children[0]->content;
                    }
                    break;

                    case "subject" :
                    {
                        $subject = $mailItem->children[0]->content;
                    }
                    break;

                    case "text" :
                    {
                        $text = $mailItem->children[0]->content;
                    }
                    break;
                }
            }
                        
            $pageContent .= "<mail $to $subject, $text>";
        }

        // ez anchor
        if ( $paragraph->name == "ezanchor" )
        {
            foreach ( $paragraph->attributes as $anchorItem )
            {
                switch ( $anchorItem->name )
                {
                    case "href" :
                    {
                        $href = $anchorItem->children[0]->content;
                    }
                    break;
                }
            }
                        
            $pageContent .= "<ezanchor $href>";
        }
        
        return $pageContent;
    }


    /*!
      \private
    */
    function &decodeTable( $paragraph )
    {
        if ( $paragraph->name == "table" )
        {
	
            if  ( count( $paragraph->attributes ) > 0 )
                foreach ( $paragraph->attributes as $attr )
                {
                    switch ( $attr->name )
                    {
                        case "width" :
                        {
                            $tableWidth = $attr->children[0]->content;
                        }
                        break;
                        case "border" :
                        {
                            $tableBorder = $attr->children[0]->content;
                        }
                        break;
                    }
                }
	
            $tmpContent = "";
            foreach ( $paragraph->children as $row )
            {
                if ( $row->name == "tr" )            
                {
                    $tdContent = "";
                    foreach ( $row->children as $data )
                    {
                        if ( $data->name == "td" )
                        {
			
                            $tdWidth="";
                            $tdColspan="";
                            $tdRowspan="";
                            if  ( count( $data->attributes ) > 0 )
                                foreach ( $data->attributes as $attr )
                                {
                                    switch ( $attr->name )
                                    {
                                        case "width" :
                                        {
                                            $tdWidth = $attr->children[0]->content;
                                        }
                                        break;
                                        case "colspan" :
                                        {
                                            $tdColspan = $attr->children[0]->content;
                                        }
                                        break;
                                        case "rowspan" :
                                        {
                                            $tdRowspan = $attr->children[0]->content;
                                        }
                                        break;
                                    }
                                }

                            $tmpData = "";
                            if ( count( $data->children ) > 0 )
                            foreach ( $data->children as $contents )
                            {
                                if ( $contents->name == "#text" or $contents->name == "text" )
                                {
                                    $tmpData .= $contents->content;
                                }
                                else
                                {
                                    $tmpData .= $this->decodeStandards( $contents );
                                    $tmpData .= $this->decodeHeader( $contents );
                                    $tmpData .= $this->decodeImage( $contents );
                                    $tmpData .= $this->decodeMedia( $contents );
                                    $tmpData .= $this->decodeFile( $contents );
                                    $tmpData .= $this->decodeLink( $contents );
                                    $tmpData .= $this->decodeHr( $contents );
                                    $tmpData .= $this->decodeTable( $contents );
                                }                                
                            }
                            $tdContent .= "<td";
                            if ( $tdWidth!="" ) $tdContent .= " $tdWidth";
                            if ( $tdColspan!="" ) $tdContent .= " $tdColspan";
                            if ( $tdRowspan!="" ) $tdContent .= " $tdRowspan";
                            $tdContent .= ">$tmpData</td>";
                            
                        }
                    }
                    
                    $tmpContent .= "<tr>\n$tdContent</tr>\n";
                }
            }
            
            $pageContent = "<table";
            if ( $tableWidth != "" ) $pageContent .= " $tableWidth";
            if ( $tableBorder != "" ) $pageContent .= " $tableBorder";
            $pageContent .= ">\n$tmpContent</table>"; 
        }
        
        
        return $pageContent;
    }

    /*!
      \private
      
    */
    function &decodeStandards(  $paragraph )
    {
        $pageContent = "";
        $tmpContent = "";

        if ( is_array( $paragraph->children ) )
        {
            foreach ( $paragraph->children as $child )
            {
                if ( $child->name == "#text" or $child->name == "text" )
                {
                    $content = $child->content;
                }
                else
                {
                    $content = $this->decodeStandards( $child );
                    $content .= $this->decodeCustom( $child );
                    $content .= $this->decodeLink( $child );
                    $content .= $this->decodeImage( $child );
                    $content .= $this->decodeTable( $child );
                    $content .= $this->decodeMedia( $child );
                    $content .= $this->decodeFile( $child );
                    $content .= $this->decodeHeader( $child );
                }
                
                $tmpContent .=  $content;
            }

            switch ( $paragraph->name )
            {
	    
                case "bold" :
                {                        
                    $pageContent .= "<bold>" . $tmpContent . "</bold>";
                }
                break;
                
                case "italic" :
                {                        
                    $pageContent .= "<italic>" . $tmpContent . "</italic>";
                }
                break;
            
                case "underline" :
                {                        
                    $pageContent .= "<underline>" . $tmpContent . "</underline>";
                }
                break;

                case "strong" :
                {                        
                    $pageContent .= "<strong>" . $tmpContent . "</strong>";
                }
                break;

                case "list" :
                case "bullet" :
                {
                    $itemStr = "";
                    foreach ( $paragraph->children as $child )
                    {
                        if ( $child->name == "li" )
                        {
                            $itemStr = "";
                            foreach ( $child->children as $listItem )
                            {                                
                                if ( $listItem->name == "text" )
                                {
                                    $itemStr .= $listItem->content;                                
                                }
                                else
                                {
                                    $itemStr .= $this->decodeStandards( $listItem );
                                    $itemStr .= $this->decodeCustom( $listItem );
                                    $itemStr .= $this->decodeLink( $listItem );
                                    $itemStr .= $this->decodeImage( $listItem );
                                    $itemStr .= $this->decodeMedia( $listItem );
                                    $itemStr .= $this->decodeFile( $listItem );
                                    $itemStr .= $this->decodeHeader( $listItem );
                                
                                }
                            }
                            $tmpContent .= "<li>$itemStr</li>\n";
                        }
                    }

                    if ( $paragraph->name == "bullet" )                        
                        $pageContent .= "<bullet>\n" . trim( $tmpContent ) . "</bullet>";
                    else
                        $pageContent .= "<list>\n" . trim( $tmpContent ) . "</list>";
                }
                break;

                
                case "factbox" :
                {                        
                    $pageContent .= "<factbox>" . $tmpContent . "</factbox>";
                }
                break;

                case "quote" :
                {                        
                    $pageContent .= "<quote>" . $tmpContent . "</quote>";
                }
                break;

                case "pre" :
                {                        
                    $pageContent .= "<pre>" . $tmpContent . "</pre>";
                }
                break;

            }

        }
        else
        {
            if ( $paragraph->name ==  "form" )
            {
                $pageContent .= "<form>";
            }
            else
            {            
                $pageContent = $paragraph->content;
            }
        
        }
        
        return $pageContent;
    }

    /*!
      \private
      Decodes the custom tags.
    */
    function &decodeCustom(  $paragraph )
    {
        $pageContent = "";
        $tagName = $paragraph->name;
        
        if ( in_array( $tagName, $this->CustomTagsArray ) )
        {
            $content = "";
            if ( count( $paragraph->children ) )
            {
                foreach ( $paragraph->children as $child )
                {
                    if ( $child->name == "text" )
                    {                
                        $content .= eZTextTool::nl2br( $child->content );
                    }
                    else
                    {
                        $content = $this->decodeStandards( $child );
                        $content .= $this->decodeCustom( $child );
                        $content .= $this->decodeLink( $child );
                        $content .= $this->decodeImage( $child );
                        $content .= $this->decodeTable( $child );
                        $content .= $this->decodeMedia( $child );
                        $content .= $this->decodeFile( $child );
                        $content .= $this->decodeHeader( $child );
                    }
                }
            }

            $pageContent = "<$tagName>" . $content ."</$tagName>";
        }

        return $pageContent;
    }

    /*!
      Returns the number of pages found in the article.
    */
    function pageCount( )
    {
        return $this->PageCount;
    }

    var $Contents;
    var $PageCount;

    var $CustomTagsArray;
}


?>
