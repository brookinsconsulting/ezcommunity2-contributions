<?
// 
// $Id: ezonlinetype.php,v 1.2 2001/04/05 09:12:15 fh Exp $
//
// Definition of eZOnline class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <09-Nov-2000 18:44:38 ce>
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
//!! eZAddress
//! eZOnlineType handles online types.
/*!

  Example code:
  \code
  $onlinetype = new eZOnlineType();
  $onlinetype->setName( "/a/path/here" );
  $onlinetype->setURLPrefix( "http://" ) // Sets the url prefix to be http
  $onlinetype->store(); // Store or updates to the database.
  \code
  \sa eZOnlineType eZCompany eZPerson eZOnline eZPhone eZOnline
*/

//  include_once( "ezaddress/classes/ezperson.php" );
//  include_once( "ezaddress/classes/ezcompany.php" );

class eZOnlineType
{
    /*!
      Constructs a new eZOnlineType object.
    */
    function eZOnlineType( $id="-1", $fetch=true )
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
      Stores a eZOnline object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        $ret = false;

        $name = addslashes( $this->Name );
        if ( !isSet( $this->ID ) )
        {
            $db->query_single( $qry, "SELECT ListOrder from eZAddress_OnlineType ORDER BY ListOrder DESC LIMIT 1" );
            $listorder = $qry["ListOrder"] + 1;
            $this->ListOrder = $listorder;

            $db->query( "INSERT INTO eZAddress_OnlineType SET
                         Name='$name',
                         ListOrder='$this->ListOrder',
                         URLPrefix='$this->URLPrefix',
                         PrefixLink='$this->PrefixLink',
                         PrefixVisual='$this->PrefixVisual'" );

            $this->ID = mysql_insert_id();

            $ret = true;
        }
        else
        {
            $db->query( "UPDATE eZAddress_OnlineType set
                                     Name='$name',
                                     ListOrder='$this->ListOrder',
                                     URLPrefix='$this->URLPrefix',
                                     PrefixLink='$this->PrefixLink',
                                     PrefixVisual='$this->PrefixVisual'
                                     WHERE ID='$this->ID'" );

            $ret = true;
        }
        return $ret;
    }

    /*
      Deletes from the database where id = $this->ID,
      if $relations is true all relations to this item is deleted too,
      if $relations is "full" all persons and companies are deleted too.
     */
    function delete( $relations = false, $id = false )
    {
        $db =& eZDB::globalDatabase();
        if ( !$id )
            $id = $this->ID;
//          if ( $relations == "full" )
//          {
//              $db->array_query( $person_qry, "SELECT Pe.ID
//                                              FROM eZContact_Person AS Pe, eZContact_PersonOnlineDict AS POD,
//                                                   eZAddress_Online AS Online
//                                              WHERE Pe.ID = POD.PersonID AND POD.OnlineID = Online.ID AND OnlineTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      eZPerson::delete( $person["ID"] );
//                  }
//              $db->array_query( $company_qry, "SELECT Co.ID
//                                               FROM eZContact_Company AS Co, eZContact_CompanyOnlineDict AS COD,
//                                                    eZAddress_Online AS Online
//                                               WHERE Co.ID = COD.CompanyID AND COD.OnlineID = Online.ID AND OnlineTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      eZCompany::delete( $company["ID"] );
//                  }
//          }
//          else if ( $relations )
//          {
//              $db->array_query( $person_qry, "SELECT A.PersonID, A.OnlineID
//                                              FROM eZContact_PersonOnlineDict AS A, eZAddress_Online AS B
//                                              WHERE A.OnlineID = B.ID AND B.OnlineTypeID='$id'" );
//              foreach( $person_qry as $person )
//                  {
//                      $person_id = $person["PersonID"];
//                      $online_id = $person["OnlineID"];
//                      $db->query( "DELETE FROM eZContact_PersonOnlineDict WHERE PersonID='$person_id' AND OnlineID='$online_id'" );
//                      $db->query( "DELETE FROM eZAddress_Online WHERE ID='$online_id'" );
//                  }
//              $db->array_query( $company_qry, "SELECT A.CompanyID, A.OnlineID
//                                               FROM eZContact_CompanyOnlineDict AS A, eZAddress_Online AS B
//                                               WHERE A.OnlineID = B.ID AND B.OnlineTypeID='$id'" );
//              foreach( $company_qry as $company )
//                  {
//                      $company_id = $company["CompanyID"];
//                      $online_id = $company["OnlineID"];
//                      $db->query( "DELETE FROM eZContact_CompanyOnlineDict WHERE CompanyID='$company_id' AND OnlineID='$online_id'" );
//                      $db->query( "DELETE FROM eZAddress_Online WHERE ID='$online_id'" );
//                  }
//          }
//          $db->query( "DELETE FROM eZAddress_OnlineType WHERE ID='$id'" );
        $db->query( "UPDATE eZAddress_OnlineType SET Removed=1 WHERE ID='$id'" );
    }
    
    /*
      Fetches out a online type where id = $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $online_type_array, "SELECT * FROM eZAddress_OnlineType WHERE ID='$id'",
                              0, 1 );
            if ( count( $online_type_array ) == 1 )
            {
                $this->fill( $online_type_array[0] );
            }
            else
            {
                $this->ID = "";
            }
        }
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$online_type_array )
    {
        $this->ID = $online_type_array[ "ID" ];
        $this->Name = $online_type_array[ "Name" ];
        $this->ListOrder = $online_type_array[ "ListOrder" ];
        $this->URLPrefix = $online_type_array[ "URLPrefix" ];
        $this->PrefixLink = $online_type_array[ "PrefixLink" ];
        $this->PrefixVisual = $online_type_array[ "PrefixVisual" ];
    }

    /*
      Fetches out all the online types that is stored in the database.
    */
    function &getAll( $as_object = true )
    {
        $db =& eZDB::globalDatabase();
        $online_type_array = 0;

        $online_type_array = array();
        $return_array = array();

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $online_type_array, "SELECT $select FROM eZAddress_OnlineType
                                               WHERE Removed=0
                                               ORDER BY ListOrder" );

        if ( $as_object )
        {
            foreach ( $online_type_array as $onlineTypeItem )
            {
                $return_array[] = new eZOnlineType( $onlineTypeItem );
            }
        }
        else
        {
            foreach ( $online_type_array as $onlineTypeItem )
            {
                $return_array[] = $onlineTypeItem["ID"];
            }
        }
    
        return $return_array;
    }

    /*!
      Sets the name of the object.
    */
    function setName( $value )
    {
        $this->Name = $value;
    }

    /*!
      Sets the name of the object.
    */
    function setURLPrefix( $value )
    {
        $this->URLPrefix = $value;
    }

    /*!
      Sets whether the prefix must always be applied for links.
    */
    function setPrefixLink( $value )
    {
        if ( $value )
            $this->PrefixLink = 1;
        else
            $this->PrefixLink = 0;
    }

    /*!
      Sets whether the prefix must always be applied for visuals (the visual part of a link).
    */
    function setPrefixVisual( $value )
    {
        if ( $value )
            $this->PrefixVisual = 1;
        else
            $this->PrefixVisual = 0;
    }

    /*!
      Returns the name of the object.
    */
    function name(  )
    {
        return $this->Name;
    }

    /*!
      Returns the URL prefix of the object.
    */
    function urlPrefix(  )
    {
        return $this->URLPrefix;
    }

    /*!
      Returns true if the prefix must always be applied for links.
    */
    function prefixLink(  )
    {
        return $this->PrefixLink == 1;
    }

    /*!
      Returns true if the prefix must always be applied for visuals (the visual part of a link).
    */
    function prefixVisual(  )
    {
        return $this->PrefixVisual == 1;
    }

    /*!
      Returns the id of the object.
    */
    function id(  )
    {
        return $this->ID;
    }
    
    /*!
      Returns the number of external items using this item.
    */

    function &count()
    {
        $db =& eZDB::globalDatabase();
//          $db->array_query( $person_qry,  "SELECT count( Pe.ID ) as Count
//                                           FROM eZContact_Person AS Pe, eZContact_PersonOnlineDict AS POD,
//                                                eZAddress_Online AS Online, eZAddress_OnlineType AS OT
//                                           WHERE Pe.ID = POD.PersonID AND POD.OnlineID = Online.ID AND Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
//          $db->array_query( $company_qry, "SELECT count( Co.ID ) as Count
//                                           FROM eZContact_Company AS Co, eZContact_CompanyOnlineDict AS COD,
//                                                eZAddress_Online AS Online, eZAddress_OnlineType AS OT
//                                           WHERE Co.ID = COD.CompanyID AND COD.OnlineID = Online.ID AND Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
        $db->array_query( $qry, "SELECT count( Online.ID ) as Count
                                 FROM eZAddress_Online AS Online, eZAddress_OnlineType AS OT
                                 WHERE Online.OnlineTypeID = OT.ID AND OnlineTypeID='$this->ID'" );
        $cnt = 0;
//          if ( count( $company_qry ) > 0 )
//              $cnt += $company_qry[0]["Count"];
//          if ( count( $person_qry ) > 0 )
//              $cnt += $person_qry[0]["Count"];
        if ( count( $qry ) > 0 )
            $cnt += $qry[0]["Count"];
        return $cnt;
    }

    /*!
      Moves this item up one step in the order list, this means that it will swap place with the item above.
    */

    function moveUp()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_OnlineType
                                  WHERE Removed=0 AND ListOrder<'$this->ListOrder' ORDER BY ListOrder DESC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    /*!
      Moves this item down one step in the order list, this means that it will swap place with the item below.
    */

    function moveDown()
    {
        $db =& eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT ID, ListOrder FROM eZAddress_OnlineType
                                  WHERE Removed=0 AND ListOrder>'$this->ListOrder' ORDER BY ListOrder ASC LIMIT 1" );
        $listorder = $qry["ListOrder"];
        $listid = $qry["ID"];
        $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$listorder' WHERE ID='$this->ID'" );
        $db->query( "UPDATE eZAddress_OnlineType SET ListOrder='$this->ListOrder' WHERE ID='$listid'" );
    }

    var $ID;
    var $Name;
    var $ListOrder;
    var $URLPrefix;
    var $PrefixLink;
    var $PrefixVisual;
}

?>
