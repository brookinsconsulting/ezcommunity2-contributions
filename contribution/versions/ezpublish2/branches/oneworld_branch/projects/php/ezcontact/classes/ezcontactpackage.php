<?php
//
// Definition of eZContactPackage class
//
// Created on: <31-May-2002 10:28:41 jhe>
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
//! The class eZContactPackage does
/*!

*/

include_once( "classes/ezdb.php" );

class eZContactPackage
{
    function eZContactPackage( $id = -1 )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id > -1 )
        {
            $this->get( $id );
        }
    }

    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $result = array();
        
        $db->query_single( $result, "SELECT * FROM eZContact_Package WHERE ID='$id'" );
        $this->fill( $result );
    }

    function fill( $res )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $res[$db->fieldName( "ID" )];
        $this->Name =& $res[$db->fieldName( "Name" )];
        $this->Description =& $res[$db->fieldName( "Description" )];
    }

    function store()
    {
        $db =& eZDB::globalDatabase();
        $name = $db->escapeString( $this->Name );
        $desc = $db->escapeString( $this->Description );

        if ( isSet( $this->ID ) )
        {
            $db->query( "UPDATE eZContact_Package SET
                         Name='$name',
                         Description='$desc'
                         WHERE ID='$this->ID'" );
        }
        else
        {
            $db->lock( "eZContact_Package" );
            $this->ID = $db->nextID( "eZContact_Package", "ID" );
            $db->query( "INSERT INTO eZContact_Package (ID, Name, Description)
                         VALUES
                         ('$this->ID', '$name', '$desc')" );
            $db->unlock();
        }
    }

    function delete()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_PackageCompanyLink WHERE PackageID='$this->ID'" );
        $db->query( "DELETE FROM eZContact_PackagePermission WHERE PackageID='$this->ID'" );
        $db->query( "DELETE FROM eZContact_Package WHERE ID='$this->ID'" );
        unset( $this->ID );
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

    function description()
    {
        return $this->Description;
    }

    function setDescription( $value )
    {
        $this->Description = $value;
    }

    function addCompany( $company, $packageID = -1 )
    {
        if ( get_class( $company ) == "ezcompany" )
            $companyID = $company->id();
        else
            $companyID = $company;

        if ( $packageID == -1 )
            $packageID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->query_single( $res, "SELECT Count(ID) as Count FROM eZContact_PackageCompanyLink
                                  WHERE CompanyID='$companyID' AND PackageID='$packageID'" );

        if ( $res[$db->fieldName( "Count" )] == 0 )
        {
            $db->lock( "eZContact_PackageCompanyLink" );
            $nextID = $db->nextID( "eZContact_PackageCompanyLink", "ID" );
            $db->query( "INSERT INTO eZContact_PackageCompanyLink (ID, CompanyID, PackageID)
                         VALUES ('$nextID', '$companyID', '$packageID')" );
        }
    }

    function removeCompany( $company, $packageID = -1 )
    {
        if ( get_class( $company ) == "ezcompany" )
            $companyID = $company->id();
        else
            $companyID = $company;

        if ( $packageID == -1 )
            $packageID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_PackageCompanyLink WHERE CompanyID='$companyID' AND PackageID='$packageID'" );
    }

    function companies( $as_object = true, $packageID = -1 )
    {
        if ( $packageID == -1 )
            $packageID = $this->ID;
        $res = array();
        $result_array = array();
        
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT CompanyID FROM eZContact_PackageCompanyLink WHERE PackageID='$packageID'" );
        
        foreach ( $res as $comp )
        {
            $result_array[] = $as_object ? new eZCompany( $comp[$db->fieldName( "CompanyID" )] ) : $comp[$db->fieldName( "CompanyID" )];
        }
        return $result_array;
    }

    function getAll( $as_object = true )
    {
        $res = array();
        $result_array = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT * FROM eZContact_Package ORDER BY Name" );
        foreach ( $res as $pak )
        {
            $result_array[] = $as_object ? new eZContactPackage( $pak[$db->fieldName( "ID" )] ) : $pak[$db->fieldName( "ID" )];
        }
        return $result_array;
    }

    function addCompany( $company )
    {
        if ( get_class( $company ) == "ezcompany" )
            $companyID = $company->id();
        else
            $companyID = $company;

        $db =& eZDB::globalDatabase();
        $db->lock( "eZContact_PackageCompanyLink" );
        $nextID = $db->nextID( "eZContact_PackageCompanyLink", "ID" );
        $db->query( "INSERT INTO eZContact_PackageCompanyLink (ID, PackageID, CompanyID)
                     VALUES
                     ('$nextID', '$this->ID', '$companyID')" );
        $db->unlock();
    }

    function removeAllCompanies()
    {
        $db =& eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_PackageCompanyLink WHERE PackageID='$this->ID'" );
    }

    function getCompanies( $as_object = true )
    {
        $res = array();
        $db =& eZDB::globalDatabase();
        $db->array_query( $res, "SELECT CompanyID FROM eZContact_PackageCompanyLink WHERE PackageID='$this->ID'" );
        $return_array = array();
        foreach ( $res as $r )
        {
            $return_array[] = $as_object ? new eZCompany( $r[$db->fieldName( "CompanyID" )] ) : $r[$db->fieldName( "CompanyID" )];
        }
        return $return_array;
    }
    
    var $ID;
    var $Name;
    var $Description;
}

?>
