<?
// 
// $Id: ezcclog.php,v 1.1.2.3 2002/04/16 10:30:44 ce Exp $
//
// ezcclog class
//
// Christoffer A. Elo <jb@ez.no>
// Created on: <05-Jun-2001 14:51:30 ce>
//
// Copyright (C) Christoffer A. Elo.  All rights reserved.
//

//!! ezcclog
//! ezcclog documentation.
/*!

  Example code:
  \code
  \endcode

*/

include_once( "classes/ezdate.php" );
include_once( "classes/eztime.php" );
include_once( "classes/ezdb.php" );

	      
class eZCCLog
{

    /*!
      constructor
    */
    function eZCCLog( $id=-1, $fetch=true  )
    {
        $this->IsConnected = false;
        if ( $id != -1 )
        {
            $this->ID = $id;
            if ( $fetch == true )
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
      Stores a log to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();

        if ( is_object( $this->Date ) )
            $date = $this->Date->mySQLDate();
        else
            $date = $this->Date;
        if ( is_object( $this->Time ) )
            $time = $this->Time->mySQLTime();
        else
            $time = $this->Time;
            
        if ( !isSet( $this->ID ) )
        {
            $db->query( "INSERT INTO eZCC_Log SET
                ID='$this->ID',
                Type='$this->Type',
                PreOrderID='$this->PreOrderID',
                TA_ID='$this->TAID',
                Date='$date',
                Time='$time',
                Amount='$this->Amount',
		BLZ='$this->BLZ',
		ACCTNR='$this->ACCTNR',
                Status='$this->Status',
                RC_CODE='$this->RCCODE',
                RC_TEXT='$this->RCTEXT'" );
            $this->ID = $db->insertID();
        }
        else
        {
            $db->query( "UPDATE eZCC_Log SET
                         Type='$this->Type',
                         PreOrderID='$this->PreOrderID',
                         TA_ID='$this->TAID',
                         Date='$date',
                         Time='$time',
                         Amount='$this->Amount',
		         BLZ='$this->BLZ',
			 ACCTNR='$this->ACCTNR',			 
                         Status='$this->Status',
                         RC_CODE='$this->RCCODE',
                         RC_TEXT='$this->RCTEXT' WHERE ID='$this->ID'");
        }
    }


    /*!
      Gets the cc log object from the database, where ID == $id
    */
    function get( $id )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;
        
        if ( $id != "" )
        {
            $db->array_query( $cclog_array, "SELECT * FROM eZCC_Log WHERE ID='$id'" );
            if ( count( $cclog_array ) > 1 )
            {
                die( "Error: Cclog's with the same ID was found in the database. This shouldent happen." );
            }
            else if( count( $cclog_array ) == 1 )
            {
                $this->ID = $cclog_array[0][ "ID" ];
                $this->Type = $cclog_array[0][ "Type" ];
                $this->PreOrderID = $cclog_array[0][ "PreOrderID" ];
                $this->TAID = $cclog_array[0][ "TA_ID" ];
                $this->Date = new eZDate();
                $this->Date->setMySQLDate( $cclog_array[0][ "Date" ] );
                $this->Time = new eZTime();
                $this->Time->setMySQLTime( $cclog_array[0][ "Time" ] );
                $this->Amount = $cclog_array[0][ "Amount" ];
		$this->BLZ    = $cclog_array[0][ "BLZ" ];
		$this->ACCTNR = $cclog_array[0][ "ACCTNR" ];
                $this->Status = $cclog_array[0][ "Status" ];
                $this->RCCODE = $cclog_array[0][ "RC_CODE" ];
                $this->RCTEXT = $cclog_array[0][ "RC_TEXT" ];

                $ret = true;
            }
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
    */
    function &getAll( $status="unhandled", $limit=20, $offset=0 )
    {
        $db =& eZDB::globalDatabase();
	
        $returnArray = array();
        $cclog_array = array();
	
        switch( $status )
        {
            case "cutover":
                $where = " WHERE Status='1' ";
                break;
            case "cancel":
                $where = " WHERE Status='2' ";
                break;
            case "invalid":
                $where = " WHERE Status='3' ";
                break;
	    case "unhandled":
                $where = " WHERE Status='0' ";		
		break;
	}
																		
        if ( $limit == false )
        {
            $db->array_query( $cclog_array, "SELECT ID
                                              FROM eZCC_Log
					      $where" );
        }
        else
	{
	      $db->array_query( $cclog_array, "SELECT ID
				                FROM eZCC_Log
						$where
						ORDER BY ID DESC",
						array( "Limit" => $limit, "Offset" => $offset ) );
        }

        for ( $i=0; $i < count($cclog_array); $i++ )
        {
            $returnArray[$i] = new eZCCLog( $cclog_array[$i]["ID"], 0 );
        }
	
        return $returnArray;
    }
    
    function count( $status="unhandled" )
    {
        switch( $status )
        {
            case "cutover":
                $where = " WHERE Status='1' ";
                break;
            case "cancel":
                $where = " WHERE Status='2' ";
                break;
            case "invalid":
                $where = " WHERE Status='3' ";
                break;
	    case "unhandled":
                $where = " WHERE Status='0' ";		
		break;
	}

        $db =& eZDB::globalDatabase();
        $ret = false;
			
        $db->query_single( $result, "SELECT COUNT(ID) as Count
                                     FROM eZCC_Log
				     $where" );
	$ret = $result[$db->fieldName( "Count" )];
	return $ret;
    }
											     

/*
    function getAll( $status="unhandled" )
    {
        $db =& eZDB::globalDatabase();

        $return_array = array();
        $cclog_array = array();

        switch( $status )
        {
            case "cutover":
                $where = " WHERE Status='1' ";
            break;
            case "cancel":
                $where = " WHERE Status='2' ";
            break;
            case "invalid":
                $where = " WHERE Status='3' ";
            break;
            case "unhandled":
                $where = " WHERE Status='0' ";
            break;
        }
        
        $db->array_query( $cclog_array, "SELECT ID FROM eZCC_Log $where ORDER BY ID DESC" );
        
        for ( $i=0; $i<count($cclog_array); $i++ )
        {
            $return_array[$i] = new eZCCLog( $cclog_array[$i]["ID"], 0 );
        }
        
        return $return_array;
    }
*/
    function getAllELV()
    {
        $db =& eZDB::globalDatabase();

        $elv_array = array();
			    
        $db->array_query( $elv_array, "SELECT ID FROM eZCC_Log WHERE Type='ELV' AND Status='0'" );
	
        return $elv_array;					
    }

    /*!
    */
    function getByRefID( $refID=0 )
    {
        $db =& eZDB::globalDatabase();

        $cclog_array = array();

        $db->array_query( $cclog_array, "SELECT ID FROM eZCC_Log WHERE TA_ID='$refID'" );

        $this->get( $cclog_array[0]["ID"], 0 );
    }

    /*!
    */
    function id()
    {
        return $this->Category;
    }

    /*!
    */
    function type()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Type;
    }

    /*!
    */
    function preOrderID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PreOrderID;
    }

    /*!
     */
    function taID()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->TAID;
    }

    /*!
    */
    function date()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Date;
    }

    /*!
    */
    function time()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Time;
    }

    /*!
    */
    function amount()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Amount;
    }

    /*!
    */
    function status()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Status;
    }

    /*!
    */
    function rcCode()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->RCCODE;
    }

    /*!
    */
    function rcText()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->RCTEXT;
    }
    
    /*!
    */
    function blz() // SF
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->BLZ );

        return $this->BLZ;
    }    

    /*!
    */
    function acctNr() // SF
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ACCTNR );

        return $this->ACCTNR;
    }

    /*!
      Sets the type.
    */
    function setType( $value )
    {
        $this->Type = $value;
    }

    /*!
      Sets the PreOrderID.
    */
    function setPreOrderID( $value )
    {
        $this->PreOrderID = $value;
    }

    /*!
      Sets the TaID.
    */
    function setTaID( $value )
    {
        $this->TAID = $value;
    }

    /*!
      Sets the Date.
    */
    function setDate( $value )
    {
        $this->Date = $value;
    }


        /*!
      Sets the Time.
    */
    function setTime( $value )
    {
        $this->Time = $value;
    }

        /*!
      Sets the Status.
    */
    function setStatus( $value )
    {
        $this->Status = $value;
    }

    /*!
      Sets the Amount.
    */
    function setAmount( $value )
    {
        $this->Amount = $value;
    }

        /*!
      Sets the Rccode.
    */
    function setRcCode( $value )
    {
        $this->RCCODE = $value;
    }

        /*!
      Sets the Rctext.
    */
    function setRcText( $value )
    {
        $this->RCTEXT = $value;
    }
    
        /*!
      Sets the BLZ.
    */
    function setBLZ( $value ) // SF
    {
        $this->BLZ = $value;
    }    
    
        /*!
      Sets the AccountNo.
    */
    function setAcctNR( $value ) // SF
    {
        $this->ACCTNR = $value;
    }    

    /*
     */
    function setAsCutovered()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $value_array, "SELECT ID, Type, Date, UNIX_TIMESTAMP(Date) as Check, UNIX_TIMESTAMP( now() + 0 ) AS NOW
                                         FROM eZCC_Log WHERE Status='0' AND ( Type='MCARD' OR Type='VISA' ) HAVING Check < NOW" );

        foreach( $value_array as $value )
        {
            $cc = new eZCCLog( $value["ID"] );
            $cc->setStatus( 1 );
            $cc->store();
        }
    }

    function setELVasCutovered()
    {
        $db =& eZDB::globalDatabase();
        $db->array_query( $value_array, "SELECT ID, Type, Date, UNIX_TIMESTAMP(Date) as Check, UNIX_TIMESTAMP( now() + 0 ) AS NOW
                                         FROM eZCC_Log WHERE Status='0' AND Type='ELV' HAVING Check < NOW" );

        foreach( $value_array as $value )
        {
            $cc = new eZCCLog( $value["ID"] );
            $cc->setStatus( 1 );
            $cc->store();
        }
    }
    

    var $ID;
    var $Type;
    var $PreOrderID;
    var $TAID;
    var $Date;
    var $Time;
    var $Amount;
    var $Status;
    var $RCCODE;
    var $RCTEXT;
    var $BLZ;
    var $ACCTNR;
}

?>
