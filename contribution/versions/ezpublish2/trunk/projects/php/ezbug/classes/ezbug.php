<?
// 
// $Id: ezbug.php,v 1.20 2001/03/19 15:33:22 fh Exp $
//
// Definition of eZBug class
//
// Bård Farstad <bf@ez.no>
// Created on: <27-Nov-2000 19:43:24 bf>
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

//!! eZBug
//! eZBug handles bug repports.
/*!
  The eZBug class handles bug reports. Each bug report can be a member
  of one or more modules. The modules are handled by the eZBugModule
  class.

  Each bug report is assigned to a bug category. The categories are handled
  by the eZBugCategory class.

  A bug which gets reported is assigned to unhandled bugs list. Handled bugs
  is stored in the archive. A handled bug is assigned a priority and a status.
  
  The priorities are handled by the eZBugPriority class. Priorities can be e.g. urgent,
  medium and low.

  The statuses are handled by the eZBugStatus class. Statuses ca be e.g. started, done
  and will not be fixed.  
  
  Example:
  \code
  // include the class
  include_once( "ezbug/classes/ezbug.php" );

  // create a new eZBug object.
  $bug = new eZBug();

  // set the object properties and save it to the database.
  $bug->setUser( eZUser::currentUser() );
  $bug->setName( "Empty search result" );
  $bug->setDescription( "The product search does not return anything." );
  $bug->setIsHandled( false );
  $bug->store();
  \endcode
  \sa eZBug eZBugCategory eZBugModule
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezmail.php" );

include_once( "ezuser/classes/ezuser.php" );

include_once( "ezbug/classes/ezbugpriority.php" );
include_once( "ezbug/classes/ezbugstatus.php" );
include_once( "ezimagecatalogue/classes/ezimage.php" );
include_once( "ezfilemanager/classes/ezvirtualfile.php" );

class eZBug
{
    /*!
      Constructs a new eZBug object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZBug( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
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
      Stores a eZBug object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZBug_Bug SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 IsHandled='$this->IsHandled',
                                 IsClosed='$this->IsClosed',
                                 PriorityID='$this->PriorityID',
                                 StatusID='$this->StatusID',
                                 UserEmail='$this->UserEmail',
                                 Created=now(),
                                 UserID='$this->UserID',
                                 OwnerID='$this->OwnerID',
                                 IsPrivate='$this->IsPrivate'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZBug_Bug SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 IsHandled='$this->IsHandled',
                                 IsClosed='$this->IsClosed',
                                 Created=Created,
                                 PriorityID='$this->PriorityID',
                                 StatusID='$this->StatusID',
                                 UserEmail='$this->UserEmail',
                                 UserID='$this->UserID',
                                 OwnerID='$this->OwnerID',
                                 IsPrivate='$this->IsPrivate'
                                 WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZBugGroup object from the database.
    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZBug_BugModuleLink WHERE BugID='$this->ID'" );
            $this->Database->query( "DELETE FROM eZBug_BugCategoryLink WHERE BugID='$this->ID'" );

            $this->Database->query( "DELETE FROM eZBug_Bug WHERE ID='$this->ID'" );
        }
        
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $this->dbInit();
        
        if ( $id != "" )
        {
            $this->Database->array_query( $module_array, "SELECT * FROM eZBug_Bug WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Bugs with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $module_array ) == 1 )
            {
                $this->ID =& $module_array[0][ "ID" ];
                $this->Name =& $module_array[0][ "Name" ];
                $this->Description =& $module_array[0][ "Description" ];
                $this->UserID =& $module_array[0][ "UserID" ];
                $this->UserEmail =& $module_array[0][ "UserEmail" ];
                $this->Created =& $module_array[0][ "Created" ];
                $this->IsHandled =& $module_array[0][ "IsHandled" ];
                $this->IsClosed =& $module_array[0][ "IsClosed" ];
                $this->PriorityID =& $module_array[0][ "PriorityID" ];
                $this->StatusID =& $module_array[0][ "StatusID" ];
                $this->OwnerID =& $module_array[0][ "OwnerID" ];
                $this->IsPrivate =& $module_array[0][ "IsPrivate" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Bug ORDER BY Name" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns all the unhandled bugs found in the database.

      The bugs are returned as an array of eZBug objects.
    */
    function &getUnhandled()
    {
        $this->dbInit();
        
        $return_array = array();
        $module_array = array();
        
        $this->Database->array_query( $module_array, "SELECT ID FROM eZBug_Bug
                                                      WHERE IsHandled='false'
                                                      ORDER BY Created" );
        
        for ( $i=0; $i<count($module_array); $i++ )
        {
            $return_array[$i] = new eZBug( $module_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
    
    
    /*!
      Returns the object id.
    */
    function id()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->ID;
    }

    /*!
      Returns the name of the bug.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the email address to the reporter.
    */
    function userEmail()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->UserEmail;
    }
    
    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return htmlspecialchars( $this->Description );
    }
    
    /*!
      Returns the creation time of the bug.

      The time is returned as a eZDateTime object.
    */
    function &created()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $dateTime = new eZDateTime();
       $dateTime->setMySQLTimeStamp( $this->Created );
       
       return $dateTime;
    }


    /*!
      Returns true if the bug is handled false if not.
    */
    function isHandled()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsHandled == "true" )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      Returns true if the bug is closed false if not.
    */
    function isClosed()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       if ( $this->IsClosed == "true" )
       {
           $ret = true;
       }
       return $ret;
    }

    /*!
      This function returns true if the bug is private, false if not. A private bug can only be seen by priveliged
      users and admins.
     */
    function isPrivate()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
        if ( $this->IsPrivate == "true" )
        {
            $ret = true;
        }
        return $ret;

        return $IsPrivate;
    }
    
    /*!
      Returns the user as an eZUser object.

      Returns 0 if the user was not set.
    */
    function user()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $user = new eZUser( );
       
       if ( $user->get( $this->UserID ) )
           $ret = $user;
       
       return $ret;
    }
    
        /*!
      Returns the bug owner as an eZUser object.

      Returns 0 if the user was not set.
    */
    function owner()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       $user = new eZUser( );
       
       if ( $user->get( $this->OwnerID ) )
           $ret = $user;
       
       return $ret;
    }

    /*!
      Sets the name of the module.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the email address to the user.

      False is returned if the e-mail address is not valid.
    */
    function setUserEmail( $mail )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $ret = false;
       
       if ( eZMail::validate( $mail ) )
       {
           $this->UserEmail = $mail;
           $ret = true;
       }
       
       return $ret;
    }
    
    /*!
      Sets the description of the module.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
     Sets the bug to handled or not. 
    */
    function setIsHandled( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsHandled = "true";
       }
       else
       {
           $this->IsHandled = "false";           
       }
    }

    /*!
     Sets the bug to closed or not. 
    */
    function setIsCLosed( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsClosed = "true";
       }
       else
       {
           $this->IsClosed = "false";           
       }
    }
    
    /*!
      Sets the user whom reported the bug.
    */
    function setUser( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $user ) == "ezuser" )
       {
           $this->UserID = $user->id();
       }
    }

    /*!
      Sets the priority assigned to the bug.
    */
    function setPriority( $pri )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $pri ) == "ezbugpriority" )
       {
           $this->PriorityID = $pri->id();
       }
    }

    /*!
      Sets the status assigned to the bug.
    */
    function setStatus( $status )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( get_class( $status ) == "ezbugstatus" )
       {
           $this->StatusID = $status->id();
       }
    }

    /*!
      Sets the owner of the bug.
      If $user is not of type eZUser the owner is set to NULL.
     */
    function setOwner( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if( get_class( $user ) == "ezuser" )
       {
           $this->OwnerID = $user->id();
       }
       else
       {
           $this->OwnerID = NULL;
       }
    }

    /*!
      Sets the bug to be private if the parameter is true. A private bug can only be seen by priveliged users
      and admins.
     */
    function setIsPrivate( $priv )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $priv == true )
        {
            $this->IsPrivate = "true";
        }
        else
        {
            $this->IsPrivate = "false";           
        }
    }
    
   /*!
      Returns the priority assigned to the bug as an
      eZBugPriority object.

      If no priority is assigned 0 / false is returned.
    */
    function priority()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;

        if ( $this->PriorityID != 0 )
        {
            $ret = new eZBugPriority( $this->PriorityID );
        }

        return $ret;        
    }

    /*!
      Returns the status assigned to the bug as an
      eZBugStatus object.

      If no staus is assigned 0 / false is returned.
    */
    function status()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;

        if ( $this->StatusID != 0 )
        {
            $ret = new eZBugStatus( $this->StatusID );
        }

        return $ret;        
    }

    /*!
      Returns the module which the bug is a part of.

      If the bug is not assigned to any module false is returned.
    */
    function module( $IDOnly=false )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
        
        $this->Database->array_query( $module_array, "SELECT ModuleID
                                                   FROM eZBug_BugModuleLink
                                                   WHERE BugID='$this->ID'" );

        $ret = false;
        if ( count( $module_array ) == 1 )
        {
            $ret = $IDOnly ? $module_array[0]["ModuleID"] :
                new eZBugModule( $module_array[0]["ModuleID"] );
        }

        return $ret;
    }

    /*!
      Returns the category which the bug is a part of.

      If the bug is not assigned to any category false is returned.
    */
    function category()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
        
        $this->Database->array_query( $category_array, "SELECT CategoryID
                                                   FROM eZBug_BugCategoryLink
                                                   WHERE BugID='$this->ID'" );

        $ret = false;
        if ( count( $category_array ) == 1 )
        {
            $ret = new eZBugCategory( $category_array[0]["CategoryID"] );
        }

        return $ret;
    }

    /*!
      Removes the category assignments.
    */
    function removeFromCategories()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
        
        $this->Database->query( "DELETE
                                 FROM eZBug_BugCategoryLink
                                 WHERE BugID='$this->ID'" );

    }

    /*!
      Removes the module assignments.
    */
    function removeFromModules()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->dbInit();
        
        $this->Database->query( "DELETE
                                 FROM eZBug_BugModuleLink
                                 WHERE BugID='$this->ID'" );

    }
    
    /*!
      Searches the bug database and returns the result as an array
      of eZBug objects.
      
      Default limit is set to 25.
    */
    function search( $query, $offset=0, $limit=25 )
    {
        $this->dbInit();
        $link_array = array();
        $return_array = array();

        $query = new eZQuery( array( "Name", "Description" ), $query );
        
        $query_str =  "SELECT ID FROM eZBug_Bug WHERE (" .
             $query->buildQuery()  .
             ") ORDER BY Name LIMIT $offset, $limit";

        $this->Database->array_query( $bug_array, $query_str );
        $ret = array();

        foreach( $bug_array as $bugItem )
        {
            $ret[] = new eZBug( $bugItem["ID"] );
        }
        return $ret;
    }

    /*!
      Connects the image $image of type eZImage with this bug.
     */
    function addImage( $image )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $image ) == "ezimage" )
        {
            $imageID = $image->id();
            $this->dbInit();
            $this->Database->query( "INSERT INTO eZBug_BugImageLink SET BugID='$this->ID', ImageID='$imageID'" );
        }
    }

    /*!
      Deletes an eZImage screenshot from the bug.
     */
    function deleteImage( $image )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( get_class( $image ) == "ezimage" )
        {
            $this->dbInit();

            $imageID = $image->id();
            $image->delete();
            $this->Database->query( "DELETE FROM eZBug_BugImageLink WHERE BugID='$this->ID' AND ImageID='$imageID'" );
        }
    }

    /*!
      Returns all images set to this bug as a array of eZImage objects.
     */
    function images()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $image_array = array();
       
       $this->Database->array_query( $image_array, "SELECT ImageID FROM eZBug_BugImageLink WHERE BugID='$this->ID' ORDER BY Created" );
       
       for ( $i=0; $i<count($image_array); $i++ )
       {
           $return_array[$i] = new eZImage( $image_array[$i]["ImageID"], false );
       }
       return $return_array;
    }



    /*!
      Adds an file to the bug.
    */
    function addFile( $file )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $file ) == "ezvirtualfile" )
        {
            $this->dbInit();

            $fileID = $file->id();

            $this->Database->query( "INSERT INTO eZBug_BugFileLink SET BugID='$this->ID', FileID='$fileID'" );
        }
    }

    /*!
      Deletes an file from the article.
    */
    function deleteFile( $file )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        if ( get_class( $file ) == "ezvirtualfile" )
        {
            $this->dbInit();

            $fileID = $file->id();
            $file->delete();
            $this->Database->query( "DELETE FROM eZBug_BugFileLink WHERE BugID='$this->ID' AND FileID='$fileID'" );
        }
    }
    
    /*!
      Returns every file to a article as a array of eZFile objects.
    */
    function files()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $this->dbInit();
       
       $return_array = array();
       $file_array = array();
       
       $this->Database->array_query( $file_array, "SELECT FileID FROM eZBug_BugFileLink WHERE BugID='$this->ID' ORDER BY Created" );
       
       for ( $i=0; $i<count($file_array); $i++ )
       {
           $return_array[$i] = new eZVirtualFile( $file_array[$i]["FileID"], false );
       }
       
       return $return_array;
    }


    
    /*!
      Private function.
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
    var $Description;
    var $IsHandled;
    var $IsClosed;
    var $Created;
    var $UserID;
    var $UserEmail;
    var $PriorityID;
    var $StatusID;    
    var $OwnerID;
    var $IsPrivate="false";
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>
