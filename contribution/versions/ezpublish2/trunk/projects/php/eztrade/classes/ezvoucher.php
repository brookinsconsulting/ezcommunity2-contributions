<?
// 
// $Id: ezvoucher.php,v 1.1 2001/08/02 12:05:03 ce Exp $
//
// eZVoucher class
//
// Christoffer A. Elo <ce@ez.no>
// Created on: <19-Jun-2001 17:41:06 ce>
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

//!! ezquizgame
//! ezquizgame documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
include_once( "ezquiz/classes/ezquizquestion.php" );
	      
class eZVoucher
{

    /*!
      Constructs a new eZVoucher object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZVoucher( $id=-1 )
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
      Stores a eZVoucher object to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $description =& addslashes( $this->Description );
        $startDate =& $this->StartDate->mySQLDate();
        $stopDate =& $this->StopDate->mySQLDate();
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZTrade_Voucher" );
            $nextID = $db->nextID( "eZTrade_Voucher", "ID" );            
            $timeStamp =& eZDateTime::timeStamp( true );
            $password = md5( $this->Password );

            $res = $db->query( "INSERT INTO eZTrade_Voucher
                      ( ID, Description, Created, TypeID, Price, KeyNumber )
                      VALUES
                      ( '$nextID',
                        '$description',
                        '$timeStamp',
                        '$this->TypeID',
                        '$this->Price',
                        '$this->Password' )
                     " );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZTrade_Voucher SET
                                     Description='$description',
                                     Created=Created,
                                     TypeID='$this->TypeID',
                                     Price='$this->Price'
                                     WHERE ID='$this->ID" );
        }
        $db->unlock();
    
        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Deletes a eZVoucher object from the database.
    */
    function delete( $catID=-1 )
    {
        if ( $catID == -1 )
            $catID = $this->ID;

        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $res = $db->query( "DELETE FROM eZTrade_Voucher WHERE ID='$this->ID'" );
    
        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();
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
            $db->array_query( $quizArray, "SELECT * FROM eZTrade_Voucher WHERE ID='$id'",
                              0, 1 );
            if( count( $quizArray ) == 1 )
            {
                $this->fill( &$quizArray[0] );
                $ret = true;
            }
            elseif( count( $quizArray ) == 1 )
            {
                $this->ID = 0;
            }
        }
        return $ret;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$quizArray )
    {
    }

    /*!
      Returns all the categories found in the database.

      The categories are returned as an array of eZVoucher objects.
    */
    function &getAll( $offset=0, $limit=20 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $quizArray = array();

        if ( $limit == false )
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZTrade_Voucher
                                           ORDER BY StartDate DESC
                                           " );

        }
        else
        {
            $db->array_query( $quizArray, "SELECT ID
                                           FROM eZTrade_Voucher
                                           ORDER BY StartDate DESC
                                           LIMIT $offset, $limit" );
        }

        for ( $i=0; $i < count($quizArray); $i++ )
        {
            $returnArray[$i] = new eZVoucher( $quizArray[$i][$db->fieldName( "ID" )] );
        }

        return $returnArray;
    }

    /*!
      Returns the total count.
     */
    function count()
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZTrade_Voucher" );
        $ret = $result[$db->fieldName( "Count" )];
        return $ret;
    }

    /*!
      Returns the object ID to the game. This is the unique ID stored in the database.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the game.
    */
    function &name()
    {
        return htmlspecialchars( $this->Name );
    }

    /*!
      Returns the description of the game.
    */
    function &description()
    {
        return htmlspecialchars( $this->Description );
    }

    /*!
      Sets the login.
    */
    function setName( &$value )
    {
       $this->Name = $value;
    }

    /*!
      Sets the description.
    */
    function setDescription( &$value )
    {
        $this->Description = $value;
    }

    /*!
      Sets the start date for the game.
    */
    function setStartDate( &$date )
    {
        if ( get_class ( $date ) == "ezdate" )
            $this->StartDate = $date;
    }

    /*!
      Sets the start date for the game.
    */
    function setStopDate( &$date )
    {
        if ( get_class ( $date ) == "ezdate" )
            $this->StopDate = $date;
    }

    
    

    var $ID;
    var $Name;
    var $Description;
    var $StartDate;
    var $StopDate;
}

?>
