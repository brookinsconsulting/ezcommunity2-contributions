<?
// 
// $Id: ezlicensecost.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
//
// eZLicenseCost class
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

//!! ezlicensecost
//! ezlicensecost documentation
/*!
*/

class eZLicenseCost
{
    /*!
      Constructs a new object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZLicenseCost( $id=-1 )
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
        $res = false;
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLicense_LicenseCost" );
            $nextID = $db->nextID( "eZLicense_LicenseCost", "ID" );            

            $res = $db->query( "INSERT INTO eZLicense_LicenseCost
                      ( ID, ProgramVersionID, LicenseTypeID, Cost,CostNonProfessional, ProductID )
                      VALUES
                      ( '$nextID',
                        '$this->ProgramVersionID',
                        '$this->LicenseTypeID',
                        '$this->Cost',
                        '$this->CostNonProfessional',
                        '$this->ProductID'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZLicense_LicenseCost SET
                                    ProgramVersionID = '$this->ProgramVersionID',
                                    LicenseTypeID='$this->LicenseTypeID',
                                     Cost='$this->Cost',
                                     CostNonProfessional='$this->CostNonProfessional',
                                     ProductID='$this->ProductID'
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

            $res = $db->query( "DELETE FROM eZLicense_LicenseCost WHERE ID='$this->ID'" );

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
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_LicenseCost WHERE ID='$id'",
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
      Returns every cost in the database. If an ID for a version is supplied, 
      then the costs for that version only is returned.
    */
    function &costs( $inProgram="",$offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        if ( $inProgram == "" )
        {
            $query = "SELECT ID FROM eZLicense_LicenseCost ORDER BY ID";
        }
        elseif ( is_numeric( $inProgram ) )
        {
            $query = "SELECT ID FROM eZLicense_LicenseCost WHERE ProgramVersionID=$inProgram ORDER BY ID";
        }
        
        $db->array_query( $objectArray, $query, array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseCost( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }
    

    /*!
      Returns all the versions in the database.
      
      The articles are returned as an array of eZLicenseCosts objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_LicenseCost";

        $db->array_query( $objectArray, $query );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLicenseCost( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }



    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function getByUnique( $ProgramVersionID, $LicenseTypeID )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_LicenseCost
            WHERE ProgramVersionID='$ProgramVersionID'
            AND LicenseTypeID='$LicenseTypeID'",
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
        
        echo "prog: $ProgramVersionID here $LicenseTypeID" ;
        
        return $ret;
        
    }

    /*!
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function getByProgramVersionID( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_LicenseCost WHERE ProgramVersionID='$id'",
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
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function getByProductID( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_LicenseCost WHERE ProductID='$id'",
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
      Fetches the object information from the database.

      True is retuned if successful, false (0) if not.
    */
    function getByLiscenseType( $id=-1 )
    {
        $db =& eZDB::globalDatabase();

        $ret = false;
        if ( $id != "" )
        {
            $db->array_query( $rowArray, "SELECT * FROM eZLicense_LicenseCost WHERE LicenseType='$id'",
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
        $this->ProgramVersionID =& $rowArray[$db->fieldName( "ProgramVersionID" )];
        $this->LicenseTypeID =& $rowArray[$db->fieldName( "LicenseTypeID" )];
        $this->Cost =& $rowArray[$db->fieldName( "Cost" )];
        $this->CostNonProfessional =& $rowArray[$db->fieldName( "CostNonProfessional" )];
        $this->ProductID =& $rowArray[$db->fieldName( "ProductID" )];
    }

    /*!
      Returns the id of the object.
    */
    function &id( )
    {
       return $this->ID;
    }

    /*!
      Returns the cost of the object.
    */
    function &cost( )
    {
       return $this->Cost;
    }
    
    /*!
      Returns the cost of the object.
    */
    function &costNonProfessional( )
    {
       return $this->CostNonProfessional;
    }
    
    /*!
      Returns the product id of the object.
    */
    function &ProductID( )
    {
       return $this->ProductID;
    }

    /*!
      Returns the ProgramVersionID of the object.
    */
    function &programVersionID( )
    {
       return $this->ProgramVersionID;
    }

    /*!
      Returns the LicenseTypeID of the object.
    */
    function &licenseTypeID( )
    {
       return $this->LicenseTypeID;
    }

    /*!
      Sets the product id of this item.
    */
    function setProductID($value)
    {
       $this->ProductID =& $value;        
    }

    /*!
      Sets the ProgramVersionID.
    */
    function setProgramVersionID( $value )
    {
       $this->ProgramVersionID =& $value;        
    }
    
    /*!
      Sets the LicenseTypeID.
    */
    function setLicenseTypeID( $value )
    {
       $this->LicenseTypeID =& $value;        
    }
    
    /*!
      Sets the Cost.
    */
    function setCost( $value )
    {
       $this->Cost =& $value;        
    }
    /*!
      Sets the Cost for non professional use.
    */
    function setCostNonProfessional( $value )
    {
       $this->CostNonProfessional =& $value;        
    }
    
    var $ID;
    var $ProgramVersionID;
    var $LicenseTypeID;
    var $ProductID;
    var $Cost;
    var $CostNonProfessional;
}
?>
