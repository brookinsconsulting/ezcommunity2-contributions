<?php
//
// Definition of eZCalendar class
//
// Created on: <27-May-2002 11:10:04 jhe>
//
// Copyright (C) 1999-2002 eZ systems as. All rights reserved.
//
// This source file is part of the eZ publish (tm) Open Source Content
// Management System.
//
// This file may be distributed and/or modified under the terms of the
// "GNU General Public License" version 2 as published by the Free
// Software Foundation and appearing in the file LICENSE.GPL included in
// the packaging of this file.
//
// Licencees holding valid "eZ publish professional licences" may use this
// file in accordance with the "eZ publish professional licence" Agreement
// provided with the Software.
//
// This file is provided AS IS with NO WARRANTY OF ANY KIND, INCLUDING
// THE WARRANTY OF DESIGN, MERCHANTABILITY AND FITNESS FOR A PARTICULAR
// PURPOSE.
//
// The "eZ publish professional licence" is available at
// http://ez.no/home/licences/professional/. For pricing of this licence
// please contact us via e-mail to licence@ez.no. Further contact
// information is available at http://ez.no/home/contact/.
//
// The "GNU General Public License" (GPL) is available at
// http://www.gnu.org/copyleft/gpl.html.
//
// Contact licence@ez.no if any conditions of this licencing isn't clear to
// you.
//

//!! 
//! The class eZCalendar does
/*!

*/

class eZCalendar
{
    function eZCalendar( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->get( $id );
        }
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        if ( isSet( $this->ID ) )
        {
            $db->query( "UPDATE eZCalendar_Calendar SET
                         Name='$this->Name'
                         WHERE ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZCalendar_Calendar" );
            $this->ID = $db->nextID( "eZCalendar_Calendar", "ID" );
            $db->query( "INSERT INTO eZCalendar_Calendar
                               (ID, Name)
                               VALUES
                               ('$this->ID', '$this->Name')" );
            $db->unlock();
        }
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $result_array = array();
        $db->query_single( $result_array, "SELECT * FROM eZCalendar_Calendar WHERE ID='$id'" );
        $this->fill( $result_array );
    }

    function fill( $values )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID = $values[$db->fieldName( "ID" )];
        $this->Name = $values[$db->fieldName( "Name" )];
    }
    
    function id()
    {
        return $this->ID;
    }

    function name()
    {
        return $this->Name;
    }

    function setName( $value )
    {
        $this->Name = $value;
    }

    function delete()
    {
        $db =& eZDB::globalDatabase();

        $db->query( "DELETE FROM eZCalendar_Calendar WHERE ID='$this->ID'" );
        $db->query( "DELETE FROM eZCalendar_CalendarPermission WHERE ObjectID='$this->ID'" );
        unset( $this->ID );
        unset( $this->Name );        
    }

    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $result_array, "SELECT * FROM eZCalendar_Calendar ORDER BY Name" );

        $return_array = array();
        
        foreach ( $result_array as $item )
        {
            $return_array[] = $as_object ? new eZCalendar( $item ) : $item[$db->fieldName( "ID" )];
        }
        return $return_array;
    }

    var $ID;
    var $Name;
}

?>
