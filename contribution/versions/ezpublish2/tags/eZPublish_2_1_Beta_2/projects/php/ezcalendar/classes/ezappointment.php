<?
// 
// $Id: ezappointment.php,v 1.16 2001/02/22 15:38:37 gl Exp $
//
// Definition of eZAppointment class
//
// Bård Farstad <bf@ez.no>
// Created on: <03-Jan-2001 16:05:37 bf>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
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

//!! eZCalendar
//! eZAppointment handles appointments.
/*!
  
*/

/*!TODO
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "classes/eztime.php" );


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
		                         UserID='$this->UserID',
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Date='$this->Date',
                                 Duration='$this->Duration',
                                 EMailNotice='$this->EMailNotice',
                                 IsPrivate='$this->IsPrivate',
                                 Priority='$this->Priority',
                                 AppointmentTypeID='$this->AppointmentTypeID'" );
            
            $this->ID = $this->Database->insertID();
        }
        else
        {
            $this->Database->query( "UPDATE eZCalendar_Appointment SET
		                         UserID='$this->UserID',
		                         Name='$this->Name',
                                 Description='$this->Description',
                                 Date='$this->Date',
                                 Duration='$this->Duration',
                                 EMailNotice='$this->EMailNotice',
                                 IsPrivate='$this->IsPrivate',
                                 Priority='$this->Priority',
                                 AppointmentTypeID='$this->AppointmentTypeID' WHERE ID='$this->ID'" );
        }
        
        return true;
    }

    /*!
      Deletes a eZAppontment object from the database.

    */
    function delete()
    {
        $this->dbInit();

        if ( isset( $this->ID ) )
        {
            $this->Database->query( "DELETE FROM eZCalendar_Appointment WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $appointment_array, "SELECT * FROM eZCalendar_Appointment WHERE ID='$id'" );
            if ( count( $appointment_array ) > 1 )
            {
                die( "Error: Appointment's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $appointment_array ) == 1 )
            {
                $this->ID =& $appointment_array[0][ "ID" ];
                $this->Name =& $appointment_array[0][ "Name" ];
                $this->Description =& $appointment_array[0][ "Description" ];
                $this->AppointmentTypeID =& $appointment_array[0][ "AppointmentTypeID" ];
                $this->Date =& $appointment_array[0][ "Date" ];
                $this->Duration =& $appointment_array[0][ "Duration" ];
                $this->IsPrivate =& $appointment_array[0][ "IsPrivate" ];
                $this->UserID =& $appointment_array[0][ "UserID" ];
                $this->Priority =& $appointment_array[0][ "Priority" ];

                $this->State_ = "Coherent";
            }
        }
        else
        {
            $this->State_ = "Dirty";
        }
    }

    /*!
      Returns all the appointments found in the database.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getAll()
    {
        $this->dbInit();
        
        $return_array = array();
        $appointment_array = array();
        
        $this->Database->array_query( $appointment_array,
        "SELECT ID FROM eZCalendar_Appointment" );
        
        for ( $i=0; $i<count($appointment_array); $i++ )
        {
            $return_array[$i] = new eZAppointment( $appointment_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }

    /*!
      Returns the appointments thats belongs to another user found in the database.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getAllByOthers()
    {
        $this->dbInit();
        
        $return_array = array();
        $appointment_array = array();
        
        $this->Database->array_query( $appointment_array,
        "SELECT ID FROM eZCalendar_Appointment WHERE ID='$id' AND IsPrivate='0'" );
        
        for ( $i=0; $i<count($appointment_array); $i++ )
        {
            $return_array[$i] = new eZAppointment( $appointment_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }


    /*!
      Returns all the appointments for the given user on the given date.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getByDate( $date, $user, $showPrivate=false )
    {
        $ret = array();

        if ( ( get_class( $date ) == "ezdate" ) && ( get_class( $user ) == "ezuser" ) )
        {
            $this->dbInit();
        
            $return_array = array();
            $appointment_array = array();

            $month = $date->month();
            $month = eZDateTime::addZero( $month );

            $day = $date->day();
            $day = eZDateTime::addZero( $day );

            $userID = $user->id();
            
            $stamp = $date->year() . $month . $day;

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE Date LIKE '$stamp%' AND IsPrivate='0' AND UserID='$userID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE Date LIKE '$stamp%' AND UserID='$userID' ORDER BY Date ASC" );
            }
                
            for ( $i=0; $i<count($appointment_array); $i++ )
            {
                $return_array[] = new eZAppointment( $appointment_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;            
        }
        return $ret;
    }


    /*!
      Returns all the appointments for the given user.

      The appointments are returned as an array of eZAppointment objects.
    */
    function &getByUser( $user, $showPrivate=false )
    {
        $ret = array();

        if ( get_class( $user ) == "ezuser" )
        {
            $this->dbInit();
        
            $return_array = array();
            $appointment_array = array();

            $userID = $user->id();

            if ( $showPrivate == false )
            {
                $this->Database->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE IsPrivate='0' AND UserID='$userID' ORDER BY Date ASC", true );
            }
            else
            {
                $this->Database->array_query( $appointment_array,
                "SELECT ID FROM eZCalendar_Appointment
                 WHERE UserID='$userID' ORDER BY Date ASC" );
            }
                
            for ( $i=0; $i<count($appointment_array); $i++ )
            {
                $return_array[] = new eZAppointment( $appointment_array[$i]["ID"], 0 );
            }

            $ret =& $return_array;            
        }
        return $ret;
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
    function name( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Name );
        }
        else
        {
            return $this->Name;
        }
    }

    /*!
      Returns the type description.
    */
    function description( $htmlchars=true )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if ( $htmlchars == true )
        {           
            return htmlspecialchars( $this->Description );
        }
        else
        {
            return $this->Description;
        }
    }

    /*!
      Returns the date and time of the appointment.
    */
    function dateTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       return $date;
    }

    /*!
      Returns the duration of the appointment as an eZTime object.
    */
    function duration()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $time = new eZTime();
       $time->setMySQLTime( $this->Duration );

       return $time;
    }

    /*!
      Returns the start time of the appointment.
    */
    function &startTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       $time = new eZTime();
       $time->setHour( $date->hour() );
       $time->setMinute( $date->minute() );
       $time->setSecond( 0 );

       return $time;
    }

    /*!
      Returns the end time of the appointment.
    */
    function &stopTime()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $date = new eZDateTime();
       $date->setMySQLTimeStamp( $this->Date );

       $start = $this->startTime();
       $time = $start->add( $this->duration() );

       return $time;
    }

    /*!
      Returns the priority, can be 0,1,2.
    */
    function priority()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       return $this->Priority;
    }

    /*!
      Sets the appointment type.
    */
    function type( )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       $type = new eZAppointmentType( $this->AppointmentTypeID );
       return $type;
    }

    /*!
      Returns true if the appointment is private.
    */
    function &isPrivate()
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $this->IsPrivate == 0 )
           $ret = false;
       else
           $ret = true;
       
       return $ret;
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
      Sets the priority of the appointment.
    */
    function setPriority( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $this->Priority = $value;
    }
    
    /*!
      Sets the appointment date and time. Takes an eZDateTime object
      as argument.
    */
    function setDateTime( $dateTime )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $dateTime ) == "ezdatetime" )
       {
           $this->Date = $dateTime->mysqlTimeStamp();
       }
    }

    /*!
      Sets the appointment type.
    */
    function setType( $type )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $type ) == "ezappointmenttype" )
       {
           $this->AppointmentTypeID = $type->id();
       }
    }

    /*!
      Sets the appointment owner.
    */
    function setOwner( $user )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $user ) == "ezuser" )
       {
           $this->UserID = $user->id();
       }
    }
    
    /*!
      Returns the user ID of the appointment owner.
    */
    function userID()
    {
        return $this->UserID;
    }
    
    /*!
     Sets the appointment to private or not. 
    */
    function setIsPrivate( $value )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

       if ( $value == true )
       {
           $this->IsPrivate = 1;
       }
       else
       {
           $this->IsPrivate = 0;
       }
    }    
    
    /*!
      Sets the appointment duration.
    */
    function setDuration( $time )
    {
       if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
       
       if ( get_class( $time ) == "eztime" )
       {
           $this->Duration = $time->mysqlTime();
       }
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

    var $UserID;
    var $Date;
    var $Duration;
    var $AppointmentTypeID;
    var $EMailNotice;

    /// boolean stored as an int
    var $IsPrivate;

    /// The priority of the appointment, values can be 0, 1, 2 where 1 is normal.
    var $Priority;
    
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}

?>

