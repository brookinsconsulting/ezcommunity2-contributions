<?
// 
// $Id: ezfile.php,v 1.1 2000/09/21 12:42:23 bf-cvs Exp $
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
//! The eZFile class handles fileuploads, and other file functions.
/*!
  Example:
  \code
    $file = new eZFile();

    if ( $file->getFile( $HTTP_POST_FILES['userfile'] ) )
    {
        print( $file->name() . " uploaded successfully" );
    }
    else
    {
        print( $file->name() . " not uploaded successfully" );
    }
  \endcode
  
*/

class eZFile
{
    /*!
      Constructs a new eZFile object
    */
    function eZFile( )
    {


    }

    /*!
      Fetches the uploaded file and makes a temporary copy of it.

      The $name_var variable must be of type $HTTP_POST_FILES[].

      See the example for more details.
    */
    function getFile( $name_var )
    {
        $ret = true;

        $this->FileName = $name_var['name'];
        $this->FileType = $name_var['type'];
        $this->FileSize = $name_var['size'];
        $this->TmpFileName = $name_var['tmp_name'];

        if ( ( $this->FileSize == "0" ) || ( $this->FileSize == "" ) )
        {
            $ret = false;
        }
                
        return $ret;
    }

    /*!
      Moves the uploaded file to the desired directory.

      Returns true if successful.
    */
    function move( $dest )
    {
        return move_uploaded_file ( $this->TmpFileName, $dest );
    }

   /*!
      Copies the uploaded file to the desired directory. 

      Returns true if successful.
    */
    function copy( $dest )
    {
        $ret = true;
        
        if ( !copy( $this->TmpFileName, $dest ) )
        {
            $ret = false;            
        }
        
        return $ret;
    }
    
    /*!
      Returns the original file name.
    */
    function name()
    {
        return $this->FileName;
    }
    
    /*!
      Returns the file type.
    */
    function type()
    {
        return $this->FileType;
    }

    /*!
      Returns the file size.
    */
    function size()
    {
        return $this->FileSize;
    }
    
    /*!
      Returns the temporary file name.
    */
    function tmpName()
    {
        return $this->TmpFileName;
    }
    

    var $FileName;
    var $TmpFileName;
    var $FileType;
    var $FileSize;
}


?>
