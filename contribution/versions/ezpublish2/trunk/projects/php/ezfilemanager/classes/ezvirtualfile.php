<?
// 
// $Id: ezvirtualfile.php,v 1.3 2001/01/04 16:25:08 ce Exp $
//
// Definition of eZVirtualFile class
//
// Bård Farstad <bf@ez.no>
// Created on: <10-Dec-2000 15:36:36 bf>
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

//!! eZFileManager
//! The eZVirtualFile represents a file in the virtual file manager.
/*!
  
*/

/*!TODO
 */

include_once( "classes/ezdb.php" );


class eZVirtualfile
{
    /*!
      Constructs a new eZVirtualfile object.
    */
    function eZVirtualfile( $id="", $fetch=true )
    {
        $this->IsConnected = false;

        if ( $id != "" )
        {

            $this->ID = $id;
            if ( $fetch == true )
            {
                
                $this->get( $this->ID );
            }
            else
            {
                $this->State_ = "Dirty";
                
            }
        }
        else
        {
            $this->State_ = "New";
        }
    }

    /*!
      Stores a eZVirtualFile object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZFileManager_File SET
                                 Name='$this->Name',
                                 Description='$this->Description',
                                 FileName='$this->FileName',
                                 OriginalFileName='$this->OriginalFileName',
                                 Read='$this->Read',
                                 Write='$this->Write'
                                 " );
        }
        else
        {
            $this->Database->query( "UPDATE eZFileManager_File SET
                                 Name='$this->Name',
                                 Description='$this->Description',
                                 FileName='$this->FileName',
                                 OriginalFileName='$this->OriginalFileName',
                                 Read='$this->Read',
                                 Write='$this->Write'
                                 WHERE ID='$this->ID'
                                 " );
        }
        
        $this->ID = mysql_insert_id();

        $this->State_ = "Coherent";
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $this->dbInit();

        $ret = false;
        if ( $id != "" )
        {
            $this->Database->array_query( $virtualfile_array, "SELECT * FROM eZFileManager_File WHERE ID='$id'" );
            if ( count( $virtualfile_array ) > 1 )
            {
                die( "Error: VirtualFile's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $virtualfile_array ) == 1 )
            {
                $this->ID =& $virtualfile_array[0][ "ID" ];
                $this->Name =& $virtualfile_array[0][ "Name" ];
                $this->Description =& $virtualfile_array[0][ "Description" ];
                $this->FileName =& $virtualfile_array[0][ "FileName" ];
                $this->OriginalFileName =& $virtualfile_array[0][ "OriginalFileName" ];
                $this->Read =& $virtualfile_array[0][ "Read" ];
                $this->Write =& $virtualfile_array[0][ "Write" ];

                $this->State_ = "Coherent";
                $ret = true;

            }
            else if( count( $virtualfile_array ) < 1 )
            {
                $this->State_ = "Dirty";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }

        return $ret;
    }


    /*!
      Returns all the files found in the database.

      The files are returned as an array of eZVirtualFile objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $category_array = array();
        
        $this->Database->array_query( $category_array, "SELECT ID FROM eZFileManager_File ORDER BY Name" );
        
        for ( $i=0; $i<count($category_array); $i++ )
        {
            $return_array[$i] = new eZVirtualFile( $category_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    
    /*!
      Returns the id of the virtual file.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->ID;
    }

    
    
    /*!
      Returns the name of the virtual file.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the description of the virtual file.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }    

    /*!
      Returns the filename of the virtual file.
    */
    function fileName()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->FileName;
    }

    /*!
      Returns the original file name of the virtual file.
    */
    function originalFileName()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->OriginalFileName;
    }

    /*!
      Returns the write permission of the virtual file.
    */
    function write()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Write;
    }

    /*!
      Returns the read permission of the virtual file.
    */
    function read()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Read;
    }

    /*!
      Returns a eZUser object.
    */
    function user();
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $this->UserID != 0 )
        {
            $ret = new eZUser( $this->UserID );
        }
        
        return $ret;
    }


    /*!
      Returns the path and filename to the original virtualfile.

      If $relative is set to true the path is returned relative.
      Absolute is default.
    */
    function &filePath( $relative=false )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $relative == true )
       {
           $path = "ezfilemanager/files/" . $this->FileName;
       }
       else
       {
           $path = "/ezfilemanager/files/" . $this->FileName;
       }
       
       return $path;
    }

    /*!
      Sets the virtual file name.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the virtual file description.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the original virtual filename.
    */
    function setOriginalFileName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->OriginalFileName = $value;
    }

    /*!
      Sets the write permission of the virtual filename.

      1 = User
      2 = Group
      3 = All
      
    */
    function setWrite( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;
           
           case "Group":
           {
               $value = 2;
           }
           break;
           
           case "All":
           {
               $value = 3;
           }
           break;
           
           default:
               $value = 1;
       }
       
       $this->Write = $value;
    }

    /*!
      Sets the read permission of the virtual filename.

      1 = User
      2 = Group
      3 = All
      
    */
    function setRead( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );


       switch ( $value )
       {
           case "User":
           {
               $value = 1;
           }
           break;
           
           case "Group":
           {
               $value = 2;
           }
           break;
           
           case "All":
           {
               $value = 3;
           }
           break;
           
           default:
               $value = 1;
       }
       
       $this->Read = $value;
    }

    /*!
      Sets the user of the file.
    */
    function setUser( $user )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( get_class( $user ) == "ezuser" )
        {
            $userID = $user->id();

            $this->UserID = $userID;
        }
    }

    /*!
      Makes a copy of the file and stores the file in the file manager.
      
    */
    function setfile( &$file )
    {
       if ( $this->State_ == "Dirty" )
           $this->get( $this->ID );
        
       if ( get_class( $file ) == "ezfile" )
       {
           print( "storing virtualfile" );

           $this->OriginalFileName = $file->name();

           $suffix = "";
           if ( ereg( "\\.([a-z]+)$", $this->OriginalFileName, $regs ) )
           {
               // We got a suffix, make it lowercase and store it
               $suffix = strtolower( $regs[1] );
           }

           // the path to the catalogue

           // Copy the file since we support it directly
           $file->copy( "ezfilemanager/files/" . basename( $file->tmpName() ) . $postfix );

           $this->FileName = basename( $file->tmpName() ) . $postfix;

           $name = $file->name();
           
           $this->OriginalFileName =& $name;
       }
    }
    
    /*!
        Checks if the object is in the coherent state. This check can be applied
        after a get to check if the object data really exists.
        
    */

    function isCoherent()
    {
        $value = false;
        
        if ( $this->State_ == "Coherent" )
        {
            $value = true;
        }
        
        return $value;
    }

    
    /*!
      \private
      Open the database for read and write. Gets all the database information from site.ini.
    */
    function dbInit()
    {
        if ( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }

    var $ID;
    var $Name;
    var $Caption;
    var $Description;
    var $FileName;
    var $OriginalFileName;
    var $Read;
    var $Write;
    var $UserID;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;

}

?>
