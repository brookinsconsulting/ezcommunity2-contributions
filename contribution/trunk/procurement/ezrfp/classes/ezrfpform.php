<?php
// 
// $Id: ezrfpform.php,v 1.3 2001/07/19 12:19:21 jakobn Exp $
//
// ezrfpform class
//
// Created on: <11-Jun-2001 12:07:57 pkej>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

//!! eZRfp
//! ezrfpform documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "ezrfp/classes/ezrfp.php" );

class eZRfpForm
{

    /*!
      Constructs a new eZRfpForm object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZRfpForm( $id=-1, $fetch=true )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores a eZRfpForm object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        
        if( get_class( $this->Form ) == "ezform" )
        {
            $FormID = $this->Form->id();
        }

        if( get_class( $this->Rfp ) == "ezrfp" )
        {
            $RfpID = $this->Rfp->id();
        }
        
        $setValues = "
            FormID='$FormID',
            RfpID='$RfpID'
        ";
        
        if ( empty( $this->ID ) )
        {
            $db->query( "INSERT INTO eZRfp_RfpFormDict SET $setValues" );

			$this->ID = $db->insertID();
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $db->query( "UPDATE eZRfp_RfpFormDict SET $setValues WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Deletes a eZRfpForm object from the database.
    */
    function delete( $formID=-1 )
    {
        if ( $formID == -1 )
            $formID = $this->ID;

        $db =& eZDB::globalDatabase();
        
        $db->query( "DELETE FROM eZRfp_RfpFormDict WHERE ID=$formID" );
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
            $db->array_query( $formArray, "SELECT * FROM eZRfp_RfpFormDict WHERE ID='$id'",
                              0, 1 );
            if( count( $formArray ) == 1 )
            {
                $this->fill( &$formArray[0] );
                $ret = true;
            }
            elseif( count( $formArray ) != 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$formArray )
    {
        $this->ID =& $formArray[ "ID" ];
        
        $this->Rfp =& new eZRfp( $formArray[ "RfpID" ] );
        $this->Form =& new eZForm( $formArray[ "FormID" ] );
    }

    /*!
      \static
      Returns all the objects found in the database.

      The objects are returned as an array of eZRfpForm objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $formArray = array();

        if ( $limit == false )
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZRfp_RfpFormDict
                                           " );

        }
        else
        {
            $db->array_query( $formArray, "SELECT ID
                                           FROM eZRfp_RfpFormDict
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($formArray); $i++ )
        {
            $returnArray[$i] = new eZRfpForm( $formArray[$i]["ID"] );
        }

        return $returnArray;
    }

    /*!
      \static
      Returns the total count of objects in the database.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZRfp_RfpFormDict" );
        $ret = $result["Count"];
        return $ret;
    }

    /*!
      Returns the object ID. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the form of the object.
    */
    function &form()
    {
        return $this->Form;
    }

    /*!
      Returns the rfp of the object.
    */
    function &receiver()
    {
        return $this->Rfp;
    }

   /*!
      Sets the rfp of the object.
    */
    function setRfp( &$object )
    {
        if( get_class( $object ) == "ezrfp" )
        {
            $this->Rfp = $object;
        }
    }

   /*!
      Sets the form of the object.
    */
    function setForm( &$object )
    {
        if( get_class( $object ) == "ezform" )
        {
            $this->Form = $object;
        }
    }

    /*!
        \static
        Returns the form if the rfp has a form.
        
        The rfp is sent in as an eZRfp object.
        The form is returned as an eZForm object.
    */
    function &rfpHasForm( &$object )
    {
        $returnArray = array();
        $formArray = array();
        
        if( get_class( $object ) == "ezrfp" )
        {
            $RfpID = $object->id();
        }
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT FormID FROM eZRfp_RfpFormDict WHERE RfpID='$RfpID'" );

        $ret =& new eZForm( $qry["FormID"] );

        return $ret;
    }

    /*!
        \static
        Returns the rfp if the form has an rfp.
        
        The form is sent in as an eZForm object.
        The rfp is returned as an eZRfp object.
    */
    function &formHasRfp( &$object )
    {
        $returnArray = array();
        $formArray = array();
        
        if( get_class( $object ) == "ezform" )
        {
            $FormID = $object->id();
        }
        
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT RfpID FROM eZRfp_RfpFormDict WHERE FormID='$FormID'" );

        $ret =& eZRfp( $qry["RfpID"] );

        return $ret;
    }

    var $ID;
    var $Form;
    var $Rfp;
}

?>
