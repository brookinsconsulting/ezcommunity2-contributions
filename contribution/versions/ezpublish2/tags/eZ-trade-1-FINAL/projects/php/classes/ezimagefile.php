<?
// 
// $Id: ezimagefile.php,v 1.3 2000/09/22 12:51:34 bf-cvs Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 11:22:21 bf>
//
// Copyright (C) 1999-2000 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

//!! eZCommon
//! The eZImageFile class handles fileuploads, and image specific functions like scale.
/*!
  <b>NOTE:</b> this class requires the ImageMagic convert program. You can
  get it from: http://www.wizards.dupont.com/cristy/ImageMagick.html
  
  Example:
  \code
  You need a HTML file like:
    <form method="post" action="script_url" enctype="multipart/form-data">
       <input type="hidden" name="max_file_size" value="3000000">
       <input type="hidden" name="docp" value="TRUE">

    Image:<br>
       <input name="userfile" type="file" /><br>
       <input type="submit" value="OK" />
    </form>

    And in the script_url you put code like:
  
    $file = new eZImageFile();

    if ( $file->getFile( $HTTP_POST_FILES['userfile'] ) )
    {
        print( $file->name() . " uploaded successfully" );

        // copy and scale the uploaded file
        $file->scaleCopy( "tmp/" . $file->name(), 120, 200 );
    }
    else
    {
        print( "Error uploading file." );
    }
  
  \endcode
*/

include_once( "classes/ezfile.php" );
    

class eZImageFile extends eZFile
{
    /*!
      Constructs a new eZImageFile object.
    */
    function eZImageFile()
    {

    }

    /*!
      Returns true if the uploaded file is a image.

      False is returned if not.
    */
    function isImage()
    {
        if ( ereg( "image", $this->FileType ) )
        {
            $ret = true;
        }
        else
        {
            $ret = false;
        }

        return $ret;
    }


    /*!
      Makes a copy of the image if and scales the copy according
      to the parameters $width and $height. If $aspectScale is true
      the scaling is done with ascpect ratio if not it uses "free form"
      scaling.

      Aspect scaling is the default.
    */
    function scaleCopy( $dest, $width, $height, $aspectScale=true )
    {
        $ret = false;
        if ( $this->isImage() )
        {
        
          $execstr = "convert -geometry \"$width" . "x" . "$height" . ">\" "  . $this->TmpFileName . " " . $dest;

//            print( "<b>" .$execstr."</b>" );
//            print( $err );
          
          $err = system( $execstr );
          
          if ( $err == "" )
          {
              $ret = true;
          }
          else
          {
              $ret = false;              
          }
        }
        
        return $ret;
    }

    /*!
      Makes a copy of the image if and converts it to the correct image type.

    */
    function convertCopy( $dest )
    {
        $ret = false;
        if ( $this->isImage() )
        {
        
          $execstr = "convert -quality 95 " . $this->TmpFileName . " " . $dest;

          $err = system( $execstr );
          $ret = true;
        }
        
        return $ret;
    }
    
}

?>
