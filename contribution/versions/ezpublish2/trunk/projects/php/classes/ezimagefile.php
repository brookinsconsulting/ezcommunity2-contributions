<?
// 
// $Id: ezimagefile.php,v 1.7 2001/03/05 15:39:37 jb Exp $
//
// Definition of eZCompany class
//
// Bård Farstad <bf@ez.no>
// Created on: <21-Sep-2000 11:22:21 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

    Image:<br>
       <input name="userfile" type="file" /><br>
       <input type="submit" value="OK" />
    </form>

    And in the script_url you put code like:
  
    $file = new eZImageFile();

    // note: userfile is not a variable it's a "text" string. The value
    // must be the same as the one used in the input.
    if ( $file->getFile( "userfile" ) )
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
            $lock_file = $dest . ".lock";
            if ( file_exists( $lock_file ) )
            {
                // If image file is locked we need to wait until it's finished
                $i = 0;
                while( file_exists( $lock_file ) and $i < 5*5 ) // Wait max 5 seconds
                {
                    usleep( 200000 ); // Sleep 1/5 of a second
                    clearstatcache();
                    $i++;
                }
                return "locked";
            }
            touch( $lock_file );
            $ini =& INIFile::globalINI();
            $image_prog = "convert";
            if ( $ini->has_var( "classes", "ImageConversionProgram" ) )
                $image_prog = $ini->read_var( "classes", "ImageConversionProgram" );
            $execstr = "$image_prog -geometry \"$width" . "x" . "$height" . ">\" "  . $this->TmpFileName . " " . $dest;

            $err = system( $execstr, $ret_code );
            unlink( $lock_file );

            if ( $ret_code == 0 )
            {
                $ret = true;
            }
            else
            {
                print( "<br><b>error in scaleCopy: $err</b><br>" );
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
            $ini =& INIFile::globalINI();
            $image_prog = "convert";
            if ( $ini->has_var( "classes", "ImageConversionProgram" ) )
                $image_prog = $ini->read_var( "classes", "ImageConversionProgram" );
            $execstr = "$image_prog -quality 95 " . $this->TmpFileName . " " . $dest;

            $err = system( $execstr, $ret_code );
            $ret = true;
            if ( $ret_code == 0 )
            {
                print( "<br><b>error in convertCopy: $err</b><br>" );
                $ret = false;
            }
        }
        
        return $ret;
    }
    
}

?>
