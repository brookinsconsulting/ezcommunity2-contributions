<?
// 
// $Id: ezappointment.php,v 1.1 2001/01/07 18:39:54 bf Exp $
//
// Definition of eZAppointment class
//
// Bård Farstad <bf@ez.no>
// Created on: <03-Jan-2001 16:05:37 bf>
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
//! eZVirtualFolder manages virtual folders.
/*!
  
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );


class eZAppointment
{
    /*!
      Constructs a new eZAppointment object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZAppointment( $id=-1, $fetch=true )
    {
        $this->IsConnected = false;
        $this->ExcludeFromSearch = "false";
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
      Stores a eZAppointment object to the database.
    */
    function store()
    {
        $this->dbInit();

        if ( !isset( $this->ID ) )
        {
            $this->Database->query( "INSERT INTO eZCalendar_Appointment SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Date='$this->Date',
                                 Duration='$this->Duration',
                                 EMailNotice='$this->EMailNotice',
                                 IsPrivate='$this->IsPrivate',
                                 AppointmentTypeID='$this->AppointmentTypeID'" );
            
            $this->ID = mysql_insert_id();
        }
        else
        {
            $this->Database->query( "UPDATE eZFileManager_Folder SET
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Date='$this->Date',
                                 Duration='$this->Duration',
                                 EMailNotice='$this->EMailNotice',
                                 IsPrivate='$this->IsPrivate',
                                 AppointmentTypeID='$this->AppointmentTypeID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZArticleGroup object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZFileManager_FileFolderLink WHERE FolderID='$this->ID'" );
            
            $this->Database->query( "DELETE FROM eZFileManager_Folder WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $appointment_array, "SELECT * FROM eZFileManager_Folder WHERE ID='$id'" );
            if ( count( $appointment_array ) > 1 )
            {
                die( "Error: Appointment's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $appointment_array ) == 1 )
            {
                $this->ID = $appointment_array[0][ "ID" ];
                $this->Name = $appointment_array[0][ "Name" ];
                $this->Description = $appointment_array[0][ "Description" ];
                $this->ParentID = $appointment_array[0][ "ParentID" ];
            }
                 
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the appointments found in the database.

      The categories are returned as an array of eZAppointment objects.
    */
    function getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $appointment_array = array();
        
        $this->Database->array_query( $appointment_array, "SELECT ID FROM eZFileManager_Folder ORDER BY Name" );
        
        for ( $i=0; $i<count($appointment_array); $i++ )
        {
            $return_array[$i] = new eZAppointment( $appointment_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }


    /*!
      Returns the object ID to the appointment. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the name of the appointment.
    */
    function name()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Name;
    }

    /*!
      Returns the group description.
    */
    function description()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        return $this->Description;
    }


    /*!
      Sets the name of the appointment.
    */
    function setName( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Name = $value;
    }

    /*!
      Sets the description of the appointment.
    */
    function setDescription( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Description = $value;
    }

    /*!
      Sets the appointment date and time. Takes an eZDateTime object
      as argument.
    */
    function setDate( $dateTime )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $dateTime ) == "ezdatetime" )
       {
           $this->Date = $dateTime->mysqlTimeStamp();
       }
    }

    /*!
      Sets the appointmentduration.
    */
//      function 

    
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

    var $UserID;
    var $Date;
    var $Duration;
    var $AppointmentTypeID;
    var $EMailNotice;
    var $IsPrivate;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

