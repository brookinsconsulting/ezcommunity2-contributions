<?
// 
// $Id: ezlicenseprogram.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
//
// eZLicenseProgram class
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

//!! ezlicenseprogram
//! ezlicenseprogram documentation
/*!
*/

class eZLicenseProgram
{
    /*!
      Constructs a new object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZLicenseProgram( $id=-1 )
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

    /*!
      Stores an object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $name = $db->escapeString( $this->Name );       

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLicense_Program" );
            $nextID = $db->nextID( "eZLicense_Program", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZLicense_Program
                      ( ID, Name )
                      VALUES
                      ( '$nextID',
                        '$this->Name'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZLicense_Program SET
                                     Name='$this->Name'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes an object from the database.
    */
    function delete()
    {

        if ( isSet( $this->ID ) )
        {
            $db =& eZDB::globalDatabase();
            $db->begin();

            $res = $db->query( "DELETE FROM eZLicense_Program WHERE ID='$this->ID'" );

            if ( $ret == false )
                $db->rollback( );
            else
                $db->commit();
        }
        return true;
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function get( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_Program WHERE ID='$id'",
                              0, 1 );

            if( count( $rowArray ) == 1 )
            {
                $this->fill( &$rowArray[0] );
                $ret = true;
            }
            elseif( count( $rowArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$rowArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $rowArray[$db->fieldName( "ID" )];
        $this->Name =& $rowArray[$db->fieldName( "Name" )];
    }

    /*!
      Returns every program 
    */
    function &programs( $offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_Program";

        $db->array_query( $objectArray, $query, array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseProgram( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }
    

    /*!
      Returns all the programs in the database.
      
      The articles are returned as an array of eZLicenseProgram objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_Program";

        $db->array_query( $objectArray, $query );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseProgram( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }


    /*!
      Returns the id of the object.
    */
    function &id( )
    {
       return $this->ID;
    }

    /*!
      Returns the name of the object.
    */
    function &name( )
    {
       return htmlspecialchars( $this->Name );
    }

    /*!
      Sets the object name.
    */
    function setName( $value )
    {
       $this->Name =& $value;        
    }
    
    var $ID;
    var $Name;
}
?>
