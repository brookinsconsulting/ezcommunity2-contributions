<?php
//
// $Id: ezworksector.php,v 1.1.2.1 2002/06/04 11:23:50 br Exp $
//
// Definition of eZWorkSector class
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <23-May-2002 16:41:00 br>
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

//!! 
//! The class eZWorkSector does
/*!

*/

class eZWorkSector
{
    /*!
      Constructs a new eZWorkType object.
    */
    function eZWorkSector( $id = "" )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a work type to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $name = $db->escapeString( $this->Name );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZJob_Sectors" );
            $this->ID = $db->nextID( "eZJob_Sectors", "ID" );

            $res[] = $db->query( "INSERT INTO eZJob_Sectors
                    ( ID, Name)
                    VALUES
                    ( '$this->ID', '$name' )" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZJob_Sectors
                    SET Name='$name'
                    WHERE ID='$this->ID'" );            

        }        
        eZDB::finish( $res, $db );
        return $dbError;
    }
  
    /*!
      Fetches an work type with object id==$id;
    */  
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $workType_array, "SELECT * FROM eZJob_Sectors WHERE ID='$id'" );
            if ( count( $workType_array ) == 1 )
            {
                $this->fill( $workType_array[0] );
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$workType_array )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID =& $workType_array[$db->fieldName( "ID" )];
        $this->Name =& $workType_array[$db->fieldName( "Name" )];
    }

    /*!
      Returns the total number of work type names
    */
    function &getAllCount()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $workTypes, "SELECT COUNT( ID ) as Count FROM eZJob_Sectors" );
        return $workTypes[$db->fieldName( "Count" )];
    }

    /*!
      Returns the total number of work type names to an id
    */
    function &getCountByID( $id = "" )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $workType, "SELECT ID FROM eZJob_Sectors ORDER BY Name" );
        $count = 0;
        if ( count( $workType ) > 0 )
        {
            foreach( $workType as $workTypeItem )
            {
                if ( $workTypeItem[$db->fieldName("ID")] == $id )
                {
                    break;
                }
                
                $count++;
            }
        }
        
        return $count;
    }
        
    /*!
      Returns every workType as a eZWork Type object
    */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();

        $workType_array = 0;
        $return_array = array();
    
        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $workType_array, "SELECT $select FROM eZJob_Sectors
                                           ORDER BY Name", $limit );
        
        if ( $as_object )
        {
            foreach ( $workType_array as $workType )
            {
                $return_array[] = new eZWorkSector( $workType[ $db->fieldName( "ID" ) ] );
            }
        }
        else
        {
            foreach ( $workType_array as $workType )
            {
                $return_array[] = $workType[$db->fieldName( "ID" )];
            }
        }
        return $return_array;
    }

    /*!
      Returns every work type as an array. This function is faster then the one above.
    */
    function &getAllArray( $offset = 0, $limit = -1 )
    {
        $db =& eZDB::globalDatabase();
        $workType_array = 0;
        $return_array = array();

        if ( $limit >= 0 && is_numeric( $offset ) && is_numeric( $limit ) )
        {
            $limitArray = array( "Limit" => $limit,
                            "Offset" => $offset );
        }

        
        $db->array_query( $workType_array, "SELECT * FROM eZJob_Sectors
                                           ORDER BY Title", $limitArray );
        foreach ( $workType_array as $workType )
        {
            $return_array[] = array( "ID" => $workType[$db->fieldName( "ID" )],
                                     "Name" => $workType[$db->fieldName( "Name" )] );
        }
        return $return_array;
    }
    
    /*!
      Sletter adressen med ID == $id;
     */
    function delete( $id = false)
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();

        $db->begin();
        $res[] = $db->query( "DELETE FROM eZJob_Sectors WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }    
    

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the object Name.
    */
    function name()
    {
        return $this->Name;
    }
     
    var $ID;
    var $Name;
}

?>
