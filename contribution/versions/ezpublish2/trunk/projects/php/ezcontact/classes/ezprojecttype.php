<?
// 
// $Id: ezprojecttype.php,v 1.5 2001/04/05 09:27:29 fh Exp $
//
// Definition of eZProjectType class
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
//! eZProjectType handles project types.
/*!

  Example code:
  \code
  $type = new eZProjectType();
  $type->setName( "Negotiating" );
  $type->store();

  $stored_type = new eZProjectType( $consultID );
  $types = eZProjectType::findTypes();
  foreach ( $types as $type )
  {
      print( $type->name() );
  }

  \endcode

  \sa eZProject
*/

include_once( "classes/ezdb.php" );

class eZProjectType
{
    /*!
      Constructs a new eZProjectType object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZProjectType( $id="-1", $fetch=true )
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
      Stores the project type to the database.
    */
    function store( )
    {
        $db =& eZDB::globalDatabase();
        $name = addslashes( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZContact_ProjectType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;
            $db->query( "INSERT INTO eZContact_ProjectType SET
                                                  Name='$name',
                                                  ListOrder='$listorder'" );
            $this->ID = mysql_insert_id();
        }
        else
        {
            $db->query( "UPDATE eZContact_ProjectType SET
                                                  Name='$name',
                                                  ListOrder='$this->ListOrder'
                                                  WHERE ID='$this->ID'" );
        }

        return true;
    }

    /*!
      Deletes an eZProject object from the database, if $relations is true all relations to this item is deleted too.
    */
    function delete( $relations = false )
    {
        if ( isSet( $this->ID ) )
        {
            $db =& eZDB::globalDatabase();
            $db->query( "DELETE FROM eZContact_PersonProjectDict WHERE ProjectID='$this->ID'" );
            $db->query( "DELETE FROM eZContact_CompanyProjectDict WHERE ProjectID='$this->ID'" );
            $db->query( "DELETE FROM eZContact_ProjectType WHERE ID='$this->ID'" );
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
            $db =& eZDB::globalDatabase();
            $db->query_single( $consulttype_array, "SELECT * FROM eZContact_ProjectType WHERE ID='$id'" );
            $this->fill( $consulttype_array );

            $ret = true;
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$consulttype_array )
    {
        $this->ID = $consulttype_array["ID"];
        $this->Name = $consulttype_array["Name"];
        $this->ListOrder = $consulttype_array["ListOrder"];
    }

    /*!
      Sets the name of the project type.
    */

    function setName( $name )
    {
        $this->Name = $name;
    }

    /*!
      Returns the id of the project type.
    */

    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the project type.
    */

    function name()
    {
        return $this->Name;
    }

    /*!
      Returns the number of external items using this item.
    */

    function &count()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $person_qry, "SELECT count( PersonID ) as Count
                                         FROM eZContact_PersonProjectDict
                                         WHERE ProjectID='$this->ID'" );
        $db->query_single( $company_qry, "SELECT count( CompanyID ) as Count
                                          FROM eZContact_CompanyProjectDict
                                          WHERE ProjectID='$this->ID'" );
        return $person_qry["Count"] + $company_qry["Count"];
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ProjectType
                                  WHERE ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZContact_ProjectType
                                  WHERE ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZContact_ProjectType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      \static
      Finds all project types.
      Returns an array with eZProjectType objects taken from the database.
    */

    function &findTypes( $as_object = true )
    {
        $qry_array = array();
        $db =& eZDB::globalDatabase();
        if ( $as_object )
            $select = "*";
        else
            $select = "ID";
        $db->array_query( $qry_array, "SELECT $select FROM eZContact_ProjectType ORDER BY ListOrder" );
        $ret_array = array();
        if ( $as_object )
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZProjectType( $qry );
            }
        }
        else
        {
            foreach ( $qry_array as $qry )
            {
                $ret_array[] = $qry["ID"];
            }
        }
        return $ret_array;
    }

    var $ID;
    var $Name;
    var $ListOrder;
}

?>
