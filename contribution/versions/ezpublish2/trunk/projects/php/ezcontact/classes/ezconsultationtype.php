<?
// 
// $Id: ezconsultationtype.php,v 1.6 2001/04/06 13:17:03 jb Exp $
//
// Definition of eZConsultationType class
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

//!! eZContact
//! eZConsultationType handles consultation types.
/*!

  Example code:
  \code
  $type = new eZConsultationType();
  $type->setName( "Negotiating" );
  $type->store();

  $stored_type = new eZConsultationType( $consultID );
  $types = eZConsultationType::findTypes();
  foreach ( $types as $type )
  {
      print( $type->name() );
  }

  \endcode

  \sa eZConsultation
*/

include_once( "classes/ezdb.php" );

class eZConsultationType
{
    /*!
      Constructs a new eZConsultationType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZConsultationType( $id="-1", $fetch=true )
    {
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
            {
                $this->get( $this->ID );
            }
        }
    }

    /*!
      Stores the consultation type to the database.
    */
    function store( )
    {
        $db = eZDB::globalDatabase();
        $name = addslashes( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_ConsultationType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;
            $db->query( "INSERT INTO eZContact_ConsultationType SET
                                                  Name='$name',
                                                  ListOrder='$listorder'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $db->query( "UPDATE eZContact_ConsultationType SET
                                                  Name='$name',
                                                  ListOrder='$this->ListOrder'
                                                  WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Deletes an eZConsultation object from the database, if $relations is true all relations to this item is deleted too.
    */
    function delete( $relations = false )
    {
        if ( isSet( $this->ID ) )
        {
            $db = eZDB::globalDatabase();
            if ( $relations )
            {
                $user = eZUser::currentUser();
                $user_id = $user->id();
                $db->array_query( $consultations, "SELECT A.ID FROM eZContact_Consultation AS A, eZContact_ConsultationPersonUserDict AS B
                                                   WHERE A.ID = B.ConsultationID AND B.UserID='$user_id' AND A.StateID='$this->ID'" );
                foreach( $consultations as $consultation )
                    {
                        eZConsultation::delete( $consultation["ID"] );
                    }
//                  $db->query( "DELETE FROM eZContact_Consultation where StateID='$this->ID'" );
            }
            $db->query( "DELETE FROM eZContact_ConsultationType WHERE ID='$this->ID'" );
        }
        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id=-1 )
    {
        $ret = false;

        if ( $id != "" )
        {
            $db = eZDB::globalDatabase();
            $db->query_single( $consulttype_array, "SELECT * FROM eZContact_ConsultationType WHERE ID='$id'" );
            $this->ID = $consulttype_array["ID"];
            $this->Name = $consulttype_array["Name"];
            $this->ListOrder = $consulttype_array["ListOrder"];

            $ret = true;
        }
        return $ret;
    }

    /*!
      Sets the name of the consultation type.
    */

    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the id of the consultation type.
    */

    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the consultation type.
    */

    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the number of external items using this item.
    */

    function count()
    {
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ID ) as Count FROM eZContact_Consultation WHERE StateID='$this->ID'" );
        return $qry["Count"];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ConsultationType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ConsultationType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_ConsultationType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      \static
      Finds all consultation types.
      Returns an array with eZConsultationType objects taken from the database.
    */

    function findTypes()
    {
        $qry_array = array();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT ID FROM eZContact_ConsultationType ORDER BY ListOrder" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZConsultationType( $qry["ID"] );
            }
        return $ret_array;
    }

    var $ID;
    var $Name;
    var $ListOrder;
}

?>
