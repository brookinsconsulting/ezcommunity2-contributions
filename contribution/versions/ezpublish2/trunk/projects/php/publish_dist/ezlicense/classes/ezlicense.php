<?
// 
// $Id: ezlicense.php,v 1.1 2001/11/02 07:55:03 pkej Exp $
//
// eZLicense class
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

//!! ezlicense
//! ezlicense documentation
/*!
*/

include_once( "classes/ezdate.php" );

class eZLicense
{
    /*!
      Constructs a new object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZLicense( $id=-1 )
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
        
        $mailTo = $db->escapeString( $this->MailTo );
        $programName = $db->escapeString( $this->ProgramName );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLicense_License" );
            $nextID = $db->nextID( "eZLicense_License", "ID" );            

            $res = $db->query( "INSERT INTO eZLicense_License
                      ( ID, Created, Price, Available, KeyNumber, UserID, ProductID, TotalValue )
                      VALUES
                      ( '$nextID',
                        '$this->LicenseType',
                        '$this->UserLimit',
                        '$this->StartDate',
                        '$this->ExpiryDate',
                        '$this->Price',
                        '$this->MailTo',
                        '$this->Reminder',
                        '$this->ProgramName',
                        '$this->Major',
                        '$this->Minor'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZLicense_License SET
                                     LicenseType='$this->LicenseType',
                                     UserLimit='$this->UserLimit',
                                     StartDate='$this->StartDate',
                                     ExpiryDate='$this->ExpiryDate',
                                     Price='$this->Price',
                                     MailTo='$mailTo',
                                     Reminder='$this->Reminder',
                                     ProgramName='$programName',
                                     Major='$this->Major',
                                     Minor='$this->Minor'
                                     WHERE ID='$this->ID'" );
        }
        $db->unlock();
    
        if ( $res == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Returns every license 
    */
    function &licenses( $offset=0, $limit=50 )
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_License";

        $db->array_query( $objectArray, $query, array( "Limit" => $limit, "Offset" => $offset ) );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLiscense( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }
    

    /*!
      Returns all the licenses in the database.
      
      The articles are returned as an array of eZLicense objects.
    */
    function &getAll()
    {
        $db =& eZDB::globalDatabase();
        
        $returnArray = array();
        $objectArray = array();

        $query = "SELECT ID FROM eZLicense_License";

        $db->array_query( $objectArray, $query );

        for ( $i=0; $i < count($objectArray); $i++ )
        {
            $returnArray[$i] = new eZLiscense( $objectArray[$i][$db->fieldName("ID")] );
        }

        return $returnArray;
    }
    
    var $ID;
    var $LicenseType;
    var $UserLimit;
    var $StartDate;
    var $ExpiryDate;
    var $Price;
    var $MailTo;
    var $Reminder;
    var $ProgramName;
    var $Major;
    var $Minor;
    

}
?>
