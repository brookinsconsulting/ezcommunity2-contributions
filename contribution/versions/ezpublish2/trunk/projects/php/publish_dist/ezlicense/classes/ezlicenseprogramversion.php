<?
// 
// $Id: ezlicenseprogramversion.php,v 1.1 2001/11/02 07:55:03 pkej Exp $ø
//
// eZLicenseProgramVersion class
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This ProgramVersion is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This ProgramVersion is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this ProgramVersion; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//

//!! ezlicenseprogramversion
//! ezlicenseprogramversion documentation
/*!
*/

include_once( "ezlicense/classes/ezlicenseprogram.php" );

class eZLicenseProgramVersion
{
    /*!
      Constructs a new object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZLicenseProgramVersion( $id=-1 )
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

        $ret = false;

        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLicense_ProgramVersion" );
            $nextID = $db->nextID( "eZLicense_ProgramVersion", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZLicense_ProgramVersion
                      ( ID, ProgramID, Major, Minor )
                      VALUES
                      ( '$nextID',
                        '$this->ProgramID',
                        '$this->Major',
                        '$this->Minor'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZLicense_ProgramVersion SET
                                     ProgramID='$this->ProgramID',
                                     Major='$this->Major',
                                     Minor='$this->Minor'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return $res;
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

            $res = $db->query( "DELETE FROM eZLicense_ProgramVersion WHERE ID='$this->ID'" );

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
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_ProgramVersion WHERE ID='$id'",
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
        $this->ProgramID =& $rowArray[$db->fieldName( "ProgramID" )];
        $this->Major =& $rowArray[$db->fieldName( "Major" )];
        $this->Minor =& $rowArray[$db->fieldName( "Minor" )];
    }

    /*!
      Returns every versions in the database. If an ID for a program is supplied, 
      then the versions for that program only is returned.
    */
    function &versions( $inProgram="",$offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        if ( $inProgram == "" )
        {
            $query = "SELECT ID FROM eZLicense_ProgramVersion";
        }
        elseif ( is_numeric( $inProgram ) )
        {
            $query = "SELECT ID FROM eZLicense_ProgramVersion WHERE ProgramID=$inProgram";
        }
        
        $db->array_query( $objectArray, $query, array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseProgramVersion( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }
    

    /*!
      Returns all the versions in the database.
      
      The articles are returned as an array of eZLicenseProgramversions objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_ProgramVersion";

        $db->array_query( $objectArray, $query );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseProgramVersion( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }


    /*!
      Returns the id of this object
    */
    function &id()
    {
       return $this->ID;
    }

    /*!
      Returns the program object.
    */
    function &program( )
    {
       return new eZLicenseProgram( $this->ProgramID );
    }

    /*!
      Returns the program id.
    */
    function &programID( )
    {
       return $this->ProgramID;
    }

    /*!
      Returns the major version number.
    */
    function &major( )
    {
       return $this->Major;
    }

    /*!
      Returns the minor version number.
    */
    function &minor( )
    {
       return $this->Minor;
    }

    /*!
      Overloaded function, accepts both object and a numeric argument.
     
        Sets the program id of the version we're creating.
    */
    function setProgramID( $inValue )
    {
        if ( get_class( $inValue == "ezlicenseprogram" ) )
        {
            $value =& $inValue;
        }
        else
        {
            $value =& $inValue;
        }
    
        $this->ProgramID =& $value;        
    }

    /*!
      Sets the programs major version number.
    */
    function setMajor( $value )
    {
        $this->Major =& $value;        
    }

    /*!
      Sets the programs minor version number.
    */
    function setMinor( $value )
    {
        $this->Minor =& $value;        
    }

    var $ID;
    var $ProgramID;
    var $Major;
    var $Minor;

}
?>
