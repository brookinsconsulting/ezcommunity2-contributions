<?
// 
// $Id: ezlicense.php,v 1.2 2001/11/02 10:10:10 pkej Exp $
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
        
        $licenseNumber = $db->escapeString( $this->LicenseNumber );
        $mailTo = $db->escapeString( $this->MailTo );
        $programName = $db->escapeString( $this->ProgramName );

        if ( is_object( $this->StartDate ) and $this->StartDate->isValid() )
            $startDate = $this->StartDate->timeStamp();
        else
            $startDate = "0";

        if ( is_object( $this->ExpiryDate ) and $this->ExpiryDate->isValid() )
            $expiryDate = $this->ExpiryDate->timeStamp();
        else
            $expiryDate = "0";

        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZLicense_License" );
            $nextID = $db->nextID( "eZLicense_License", "ID" );            

            $res = $db->query( "INSERT INTO eZLicense_License
                      ( ID, ProgramVersionID, LicenseNumber, LicenseTypeID,
                      UserLimit, StartDate, ExpiryDate, Price, MailTo,
                      Reminder, ProgramName, Major, Minor, UserID,
                      OrderID, CartID, ProductID, LicenseQTY  )
                      VALUES
                      ( '$nextID',
                        '$this->ProgramVersionID',
                        '$licenseNumber',
                        '$this->LicenseTypeID',
                        '$this->UserLimit',
                        '$startDate',
                        '$expiryDate',
                        '$this->Price',
                        '$mailTo',
                        '$this->Reminder',
                        '$programName',
                        '$this->Major',
                        '$this->Minor',
                        '$this->UserID',
                        '$this->OrderID',
                        '$this->CartID',
                        '$this->ProductID',
                        '$this->LicenseQTY'
                            )" );

			$this->ID = $nextID;
        }
        elseif ( is_numeric( $this->ID ) )
        {
            $res = $db->query( "UPDATE eZLicense_License SET
                                     ID='$this->ID',
                                     ProgramVersionID='$this->ProgramVersionID',
                                     LicenseNumber='$licenseNumber',
                                     LicenseTypeID='$this->LicenseTypeID',
                                     UserLimit='$this->UserLimit',
                                     StartDate='$startDate',
                                     ExpiryDate='$expiryDate',
                                     Price='$this->Price',
                                     MailTo='$mailTo',
                                     Reminder='$this->Reminder',
                                     ProgramName='$programName',
                                     Major='$this->Major',
                                     Minor='$this->Minor',
                                     UserID='$this->UserID',
                                     OrderID='$this->OrderID',
                                     CartID='$this->CartID',
                                     ProductID='$this->ProductID',
                                     LicenseQTY='$this->LicenseQTY'
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

    /*!
      Sets the ProgramVersionID.
    */
    function setProgramVersionID( $value )
    {
       $this->ProgramVersionID =& $value;        
    }
    
    /*!
    /*!
      Sets the LicenseNumber.
    */
    function setLicenseNumber( $value )
    {
       $this->LicenseNumber =& $value;        
    }
    
      Sets the LicenseTypeID.
    */
    function setLicenseTypeID( $value )
    {
       $this->LicenseTypeID =& $value;        
    }
    
    /*!
      Sets the UserLimit.
    */
    function setUserLimit( $value )
    {
       $this->UserLimit =& $value;        
    }
    
    /*!
      Sets the StartDate.
    */
    function setStartDate( $date )
    {
        if ( get_class( $date ) == "ezdatetime" )
        {
            $this->StartDate = $date->timeStamp();
        }
        else
        {
            $this->StartDate =& $date;
        }      
    }
    
    /*!
      Sets the ExpiryDate.
    */
    function setExpiryDate( $date )
    {
        if ( get_class( $date ) == "ezdatetime" )
        {
            $this->ExpiryDate = $date->timeStamp();
        }
        else
        {
            $this->ExpiryDate =& $date;
        }      
    }
    
    /*!
      Sets the Price.
    */
    function setPrice( $value )
    {
       $this->Price =& $value;        
    }
    
    /*!
      Sets the MailTo.
    */
    function setMailTo( $value )
    {
       $this->MailTo =& $value;        
    }
    
    /*!
      Sets the Reminder.
    */
    function setReminder( $value )
    {
       $this->Reminder =& $value;        
    }
    
    /*!
      Sets the ProgramName.
    */
    function setProgramName( $value )
    {
       $this->ProgramName =& $value;        
    }
    
    /*!
      Sets the Major.
    */
    function setMajor( $value )
    {
       $this->Major =& $value;        
    }
    
    /*!
      Sets the Minor.
    */
    function setMinor( $value )
    {
       $this->Minor =& $value;        
    }
    
    /*!
      Sets the UserID.
    */
    function setUserID( $value )
    {
       $this->UserID =& $value;        
    }
    
    /*!
      Sets the OrderID.
    */
    function setOrderID( $value )
    {
       $this->OrderID =& $value;        
    }
    
    /*!
      Sets the CartID.
    */
    function setCartID( $value )
    {
       $this->CartID =& $value;        
    }
    
    /*!
      Sets the ProductID.
    */
    function setProductID( $value )
    {
       $this->ProductID =& $value;        
    }
     
    /*!
      Sets the LicenseQTY.
    */
    function setLicenseQTY( $value )
    {
       $this->LicenseQTY =& $value;        
    }
   
    /*!
      Returns the ID of the object.
    */
    function &id( )
    {
       return $this->ID;
    }

    /*!
      Returns the ProgramVersionID of the object.
    */
    function &programVersionID( )
    {
       return $this->ProgramVersionID;
    }
    /*!
      Returns the LicenseNumber of the object.
    */
    function &licenseNumber( )
    {
       return $this->LicenseNumber;
    }

    /*!
      Returns the LicenseTypeID of the object.
    */
    function &licenseTypeID( )
    {
       return $this->LicenseTypeID;
    }

    /*!
      Returns the UserLimit of the object.
    */
    function &userLimit( )
    {
       return $this->UserLimit;
    }

    
    /*!
      Returns the StartDate of the object.
    */
    function &startDate( )
    {
       return $this->StartDate;
    }

    /*!
      Returns the ExpiryDate of the object.
    */
    function &expiryDate( )
    {
       return $this->ExpiryDate;
    }

    /*!
      Returns the Price of the object.
    */
    function &price( )
    {
       return $this->Price;
    }

    /*!
      Returns the MailTo of the object.
    */
    function &mailTo( )
    {
       return $this->MailTo;
    }

    /*!
      Returns the Reminder of the object.
    */
    function &reminder( )
    {
       return $this->Reminder;
    }

    /*!
      Returns the ProgramName of the object.
    */
    function &programName( )
    {
       return $this->ProgramName;
    }

    /*!
      Returns the Major of the object.
    */
    function &major( )
    {
       return $this->Major;
    }

    /*!
      Returns the Minor of the object.
    */
    function &minor( )
    {
       return $this->Minor;
    }

    /*!
      Returns the UserID of the object.
    */
    function &userID( )
    {
       return $this->UserID;
    }

    /*!
      Returns the OrderID of the object.
    */
    function &orderID( )
    {
       return $this->OrderID;
    }

    /*!
      Returns the CartID of the object.
    */
    function &cartID( )
    {
       return $this->CartID;
    }

    /*!
      Returns the ProductID of the object.
    */
    function &productID( )
    {
       return $this->ProductID;
    }

    /*!
      Returns the LicenseQTY of the object.
    */
    function &licenseQTY( )
    {
       return $this->LicenseQTY;
    }

    /*!
      Fills in information to the object taken from the array.
    */
    function fill( &$rowArray )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $rowArray[$db->fieldName( "ID" )];
        $this->ProgramVersionID =& $rowArray[$db->fieldName( "ProgramVersionID" )];
        $this->LicenseNumber =& $rowArray[$db->fieldName( "LicenseNumber" )];
        $this->LicenseTypeID =& $rowArray[$db->fieldName( "LicenseTypeID" )];
        $this->UserLimit =& $rowArray[$db->fieldName( "UserLimit" )];
        $this->StartDate =& $rowArray[$db->fieldName( "StartDate" )];
        $this->ExpiryDate =& $rowArray[$db->fieldName( "ExpiryDate" )];
        $this->Price =& $rowArray[$db->fieldName( "Price" )];
        $this->MailTo =& $rowArray[$db->fieldName( "MailTo" )];
        $this->Reminder =& $rowArray[$db->fieldName( "Reminder" )];
        $this->ProgramName =& $rowArray[$db->fieldName( "ProgramName" )];
        $this->Major =& $rowArray[$db->fieldName( "Major" )];
        $this->Minor =& $rowArray[$db->fieldName( "Minor" )];
        $this->UserID =& $rowArray[$db->fieldName( "UserID" )];
        $this->OrderID =& $rowArray[$db->fieldName( "OrderID" )];
        $this->CartID =& $rowArray[$db->fieldName( "CartID" )];
        $this->ProductID =& $rowArray[$db->fieldName( "ProductID" )];
        $this->LicenseQTY =& $rowArray[$db->fieldName( "LicenseQTY" )];
    }

    
    var $ID;
    var $ProgramVersionID;
    var $LicenseNumber;
    var $LicenseTypeID;
    var $UserLimit;
    var $StartDate;
    var $ExpiryDate;
    var $Price;
    var $MailTo;
    var $Reminder;
    var $ProgramName;
    var $Major;
    var $Minor;
    var $UserID;
    var $OrderID;
    var $CartID;
    var $ProductID;
    var $LicenseQTY;
    

}
?>
