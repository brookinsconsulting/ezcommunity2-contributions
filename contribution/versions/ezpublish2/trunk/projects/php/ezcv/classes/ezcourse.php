<?
// 
// $Id: ezcourse.php,v 1.1 2000/12/21 16:58:11 ce Exp $
//
// Definition of eZCourse class
//
// <Christoffer A. Elo><ce@ez.no>
// Created on: <21-Dec-2000 11:24:49 ce>
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

//!! eZCourse
//! eZCourse handles educational information.
/*!
 */
 
class eZCourse
{
    /*!
        Constructs an eZCourse object.
      
        If $id is set, the object's values are fetched from the database.
     */
    function eZCourse( $id='', $fetch=false )
    {
        $this->IsConnected = false;
        if( !empty( $id ) )
        {
            $this->ID = $id;
            if( $fetch == true )
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
        Store this object's data.
     */
    function store()
    {
        $this->dbInit();
        if( !isSet( $this->ID ) )
        {
            $query = ( "INSERT INTO eZCV_Course SET CourseStart='$this->CourseStart',
                                                    CourseStop='$this->CourseStop',
                                                    CourseName='$this->CourseName',
                                                    CoursePlace='$this->CoursePlace'" );

            $this->Database->query( $query );

            $this->ID = mysql_insert_id();            
            $this->State_ = "Coherent";
        }
        else
        {
            $query = ( "UPDATE eZCV_Course SET CourseStart='$this->CourseStart', CourseStop='$this->CourseStop', CourseName='$this->CourseName', CoursePlace='$this->CoursePlace' WHERE ID='$this->ID'" );

            $this->Database->query( $query );
            $this->State_ = "Coherent";
        }
    }
    
    
    /*!
        Get this object's data.
     */
    function get( $id )
    {
        $this->dbInit();    
        if( $id != "" )
        {
            $this->Database->array_query( $objectArray, "SELECT * FROM eZCV_Course WHERE ID='$id'" );
            if( count( $objectArray ) > 1 )
            {
                die( "Feil: Flere cver med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if( count( $objectArray ) == 1 )
            {
                $this->ID =& $objectArray[ 0 ][ "ID" ];
                $this->CourseStart =& $objectArray[ 0 ][ "CourseStart" ];
                $this->CourseStop =& $objectArray[ 0 ][ "CourseStop" ];
                $this->CourseName =& $objectArray[ 0 ][ "CourseName" ];
                $this->CoursePlace =& $objectArray[ 0 ][ "CoursePlace" ];
            }
        }
    }
    
    /*!
        Get all objects.
     */
    function getAll()
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = array();
    
        $this->Database->array_query( $item_array, "SELECT ID FROM eZCV_Course ORDER BY CourseStop" );

        foreach( $item_array as $item )
        {
            $return_array[] = new eZCourse( $item["ID"] );
        }
        return $return_array;
    }
    
    /*!
        Returns the ID of this object.
     */
    function id()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }
    
    /*!
        Set the ID of this object to $value.
    */
    function setID( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ID = $value;
    }

    /*!
        Returns the CourseStart of this object.
     */
    function &courseStart()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CourseStart;
    }
    
    /*!
        Set the CourseStart of this object to $value.
    */
    function setCourseStart( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->CourseStart = $value;
    }
    /*!
        Returns the CourseStop of this object.
     */
    function &courseStop()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CourseStop;
    }
    
    /*!
        Set the CourseStop of this object to $value.
    */
    function setCourseStop( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->CourseStop = $value;
    }
    /*!
        Returns the CourseName of this object.
     */
    function &courseName()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CourseName;
    }
    
    /*!
        Set the CourseName of this object to $value.
    */
    function setCourseName( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->CourseName = $value;
    }
    
    /*!
        Returns the CoursePlace of this object.
     */
    function &coursePlace()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->CoursePlace;
    }
    
    /*!
        Set the CoursePlace of this object to $value.
    */
    function setCoursePlace( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->CoursePlace = $value;
    }

    /*!
      \private
      Used by this class to connect to the database.
    */
    function dbInit()
    {
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $CourseStart;
    var $CourseStop;
    var $CoursePlace;
    var $CourseName;
    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
};
?>
