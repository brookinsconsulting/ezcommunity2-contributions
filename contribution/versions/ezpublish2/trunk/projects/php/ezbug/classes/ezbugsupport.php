<?php
// 
// $Id: ezbugsupport.php,v 1.3 2001/11/06 12:33:54 jhe Exp $
//
// Definition of eZBugSupport class
//
// Created on: <27-Oct-2001 13:12:23 jhe>
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
//! The class eZBugSupport does
/*!

*/

class eZBugSupport
{
    function eZBugSupport( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    function fill( &$support_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $support_array[$db->fieldName( "ID" )];
        $this->Name = $support_array[$db->fieldName( "Name" )];
        $this->UserEmail = $support_array[$db->fieldName( "Email" )];
        $this->ExpiryDate = $support_array[$db->fieldName( "ExpiryDate" )];
    }
    
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        $name = $db->escapeString( $this->Name );
        $email = $db->escapeString( $this->UserEmail );

        $db->begin();
        
        $timeStamp = eZDate::timeStamp( true );
        
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZBug_Support" );
			$this->ID = $db->nextID( "eZBug_Support", "ID" );

            $res = $db->query( "INSERT INTO eZBug_Support
                                            (ID,
                                             Name,
                                             Email,
                                             ExpiryDate)
                                        VALUES
                                            ('$this->ID',
                                             '$name',
                                             '$email',
                                             '$this->ExpiryDate')" );
            $db->unlock();
        }
        else
        {
            $res = $db->query( "UPDATE eZBug_Support SET
		                        Name='$name',
                                Email='$email',
                                ExpiryDate='$this->ExpiryDate'
                                WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id == -1 && isSet( $this->ID ) )
        {
            $id = $this->ID;
        }
        
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZBug_Support WHERE ID='$id'" );

        eZDB::finish( $res, $db );
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id != "" )
        {
            $db->array_query( $module_array, "SELECT * FROM eZBug_Support WHERE ID='$id'" );
            if ( count( $module_array ) > 1 )
            {
                die( "Error: Bugs with the same ID was found in the database. This shouldent happen." );
            }
            else if ( count( $module_array ) == 1 )
            {
                $this->ID =& $module_array[0][$db->fieldName( "ID" )];
                $this->Name =& $module_array[0][$db->fieldName( "Name" )];
                $this->UserEmail =& $module_array[0][$db->fieldName( "Email" )];
                $this->ExpiryDate =& $module_array[0][$db->fieldName( "ExpiryDate" )];
            }
        }
    }

    function getAll( $offset = 0, $limit = 10 )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $support_array, "SELECT * FROM eZBug_Support ORDER BY Name",
                          array( "Limit" => $limit, "Offset" => $offset ) );
        $return_array = array();
        if ( count( $support_array ) > 0 )
        {
            foreach ( $support_array as $supportItem )
            {
                $return_array[] = new eZBugSupport( $supportItem );
            }
        }
        return $return_array;
    }

    function getAllCount()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $result, "SELECT Count(ID) as Count FROM eZBug_Support" );
        return $result[$db->fieldName( "Count" )];
    }
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the bug.
    */
    function name()
    {
        return $this->Name;
    }

    function userEmail()
    {
        return $this->UserEmail;   
    }

    function &expiryDate()
    {
       $dateTime = new eZDate();
       $dateTime->setTimeStamp( $this->ExpiryDate );
       
       return $dateTime;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function setUserEmail( $email )
    {
        $this->UserEmail = $email;
    }

    function setExpiryDate( $expirydate )
    {
        if ( get_class( $expirydate ) == "ezdate" )
            $this->ExpiryDate = $expirydate->timeStamp();
        else
            $this->ExpiryDate = $expirydate;
    }

    var $ID;
    var $Name;
    var $Email;
    var $ExpiryDate;
    var $UserEmail;
}

?>
