<?
// 
// $Id: ezcertificate.php,v 1.3 2000/12/21 18:16:39 ce Exp $
//
// Definition of eZCertificate class
//
// <Paul K Egell-Johnsen><pkej@ez.no>
// Created on: <20-Nov-2000 10:34:14 pkej>
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

//!! eZCertificate
//! eZCertificate handles certificate information.
/*!
 */

include_once( "ezcv/classes/ezcertificatetype.php" );
include_once( "ezcv/classes/ezcertificatecategory.php" );
 
class eZCertificate
{
    /*!
        Constructs an eZCertificate object.
      
        If $id is set, the object's values are fetched from the database.
     */
    function eZCertificate( $id='', $fetch=false )
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
            $this->Database->query( "INSERT INTO eZCV_Certificate SET Name='$this->Name', Institution='$this->Institution', Received='$this->Received', End='$this->End'" );

            $this->ID = mysql_insert_id();            
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query( "UPDATE eZCV_Certificate SET Name='$this->Name', Institution='$this->Institution', Received='$this->Received', End='$this->End' WHERE ID='$this->ID'" );
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
            $this->Database->array_query( $objectArray, "SELECT * FROM eZCV_Certificate WHERE ID='$id'" );
            if( count( $objectArray ) > 1 )
            {
                die( "Feil: Flere cver med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if( count( $objectArray ) == 1 )
            {
                $this->ID =& $objectArray[ 0 ][ "ID" ];
                $this->Name =& $objectArray[ 0 ][ "Name" ];
                $this->Institution =& $objectArray[ 0 ][ "Institution" ];
                $this->Received =& $objectArray[ 0 ][ "Received" ];
                $this->End =& $objectArray[ 0 ][ "End" ];
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
    
        $this->Database->array_query( $item_array, "SELECT ID FROM eZCV_Certificate ORDER BY End" );

        foreach( $item_array as $item )
        {
            $return_array[] = new eZCertificate( $item["ID"] );
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

    function &name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Name;
    }

    /*!
        Returns the institution of the issuer of this certificate.
     */
    function &institution()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Institution;
    }

    /*!
        Set the institution of this object to $value.
    */
    function setInstitution( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Institution = $value;
    }

    /*!
        Set the name of this object to $value.
    */
    function setName( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Name = $value;
    }


   /*!
        Returns the Received of this object.
     */
    function &received()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Received;
    }
    
    /*!
        Set the Received of this object to $value.
    */
    function setReceived( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Received = $value;
    }
    /*!
        Returns the End of this object.
     */
    function &end()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->End;
    }
    
    /*!
        Returns the expiry date of this object.
     */
    function &expires()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->End;
    }
    
    /*!
        Set the End of this object to $value.
    */
    function setEnd( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->End = $value;
    }

    /*!
        Set the Expiry date of this object to $value.
    */
    function setExpires( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->End = $value;
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
    var $Name;
    var $Institution;
    var $Received;
    var $End;

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
};
?>
