<?
// 
// $Id: ezcertificate.php,v 1.2 2000/12/21 16:58:11 ce Exp $
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
        
            $this->Created = gmdate( "YmdHis", time());
            
            $this->Database->query( "INSERT INTO eZCV_Certificate SET CertificateTypeID='$this->CertificateTypeID', Received='$this->Received', End='$this->End'" );

            $this->ID = mysql_insert_id();            
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query
            ( "
                UPDATE
                    eZCV_Certificate
                SET
                    CertificateTypeID='$this->CertificateTypeID',
                    Received='$this->Received',
                    End='$this->End'
                WHERE
                    ID='$this->ID'
            " );
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
                $this->ID = $objectArray[ 0 ][ "ID" ];
                $this->CertificateTypeID = $objectArray[ 0 ][ "CertificateTypeID" ];
                $this->Received = $objectArray[ 0 ][ "Received" ];
                $this->End = $objectArray[ 0 ][ "End" ];
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
        Fetches all the company types in the db and return them as an array of objects.
     */
    function getByTypeID( $id = 0, $OrderBy = "ID", $LimitStart = "None", $LimitBy = "None" )
    {
        $this->dbInit();

        switch( strtolower( $OrderBy ) )
        {
            case "start":
            case "received":
                $OrderBy = "ORDER BY Received";
                break;
            case "end":
            case "expires":
            case "expire":
                $OrderBy = "ORDER BY End";
                break;
            case "id":
                $OrderBy = "ORDER BY ID";
                break;
            case "typeid":
                $OrderBy = "ORDER BY CertificateTypeID";
                break;
            default:
                $OrderBy = "ORDER BY ID";
                break;
        }
        
        if( is_numeric( $LimitStart ) )
        {
            $LimitClause = "LIMIT $LimitStart";
            
            if( is_numeric( $LimitBy ) )
            {
                $LimitClause = $LimitClause . ", $LimitBy";
            }
        }
        else
        {
            $LimitClause = "";
        }
        
        $company_type_array = array();
        $return_array = array();
        
        $this->Database->array_query( $company_type_array, "SELECT CertificateTypeID FROM eZCV_Certificate WHERE CertificateTypeID='$id' $OrderBy $LimitClause" );

        foreach( $company_type_array as $companyTypeItem )
        {
            $return_array[] = new eZCertificateType( $companyTypeItem["CertificateTypeID"] );
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

    function path( $id = 0 )
    {    
        if( $id == 0 )
        {
            $id = $this->CertificateTypeID;
        }
        
        $type = new eZCertificateType( $id );
        
        return $type->path();
    }

    function name()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        
        $type = new eZCertificateType( $id );
        
        return $type->name();
    }

    function type()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        $type = new eZCertificateType( $id );
        return $type->name();
    }

    function typeID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        
        $type = new eZCertificateType( $id );
        
        return $type->id();
    }

    /*!
        Returns the description of this certificate.
     */
    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        
        $type = new eZCertificateType( $id );
        
        return $type->description();
    }

    /*!
        Returns the name of the category of this certificate.
     */
    function category()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        
        $type = new eZCertificateType( $id );
        $category = $type->certificateCategory();
        return $category->name();
    }
    
    /*!
        Returns the ID of the category of this certificate.
     */
    function categoryID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $id = $this->CertificateTypeID;
        
        $type = new eZCertificateType( $id, fetch );
        $category = $type->certificateCategory();
        return $category->id();
    }
    
    /*!
        Returns the name of the issuer of this certificate.
     */
    function institution()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( $id == 0 )
        {
            $id = $this->CertificateTypeID;
        }
        
        $type = new eZCertificateType( $id );
        $category = $type->certificateCategory();
        return $category->institution();
    }

   /*!
        Returns the Received of this object.
     */
    function received()
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
    function end()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->End;
    }
    
    /*!
        Returns the expiry date of this object.
     */
    function expires()
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
        Returns the CertificateType of this object.
     */
    function certificateType()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( is_numeric( $this->CertificateTypeID ) )
        {
            $returnValue = new eZCertificateType( $this->CertificateTypeID );
        }
        else
        {
            $returnValue = 0;
        }

        return $returnValue;
    }
    
    /*!
        Returns the CertificateType of this object.
     */
    function certificateTypeID()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        if( is_numeric( $this->CertificateTypeID ) )
        {
            $returnValue = $this->CertificateTypeID;
        }
        else
        {
            $returnValue = 0;
        }

        return $returnValue;
    }
    /*!
        Set the CertificateTypeID of this object to $certificate.
    */
    function setCertificateType( $certificate )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $certificate ) == "ezcertificate" )
        {
            $certificateID = $certificate->id();

        }
        elseif(is_numeric( $certificate ) )
        {
            $certificateID = $certificate;
        }
        
        if(is_numeric( $certificateID ) )
        {
            $this->CertificateTypeID = $certificateID;
            $ret = true;
        }
        
        return $ret;
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
    var $CertificateTypeID;
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
