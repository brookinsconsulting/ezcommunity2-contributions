<?php
//
// $Id: ezprocurementbid.php,v 1.0.1.01 2004/11/13 10:17:10 gb Exp $
//
// Definition of eZProcurementBid class
//
// Created on: <13-Nov-2004 06:38:24 gb>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 2001-2003 Brookins Consulting.  All rights reserved.
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

//!! eZProcurementBid
//! eZProcurementBid handles procurement bids.
/*!

  Example code:
  \code
  $bid = new eZProcurementBid();
  $bid->setAmount( 21999.99 );
  $bid->setUserID( 32 );

  $bid->store();
  \endcode

  \sa eZProcurementBid

*/

/*!TODO
  Add ... user bid features
*/

include_once( "classes/ezdb.php" );
//include_once( "classes/ezdatetime.php" );

include_once( "ezuser/classes/ezuser.php" );
include_once( "ezuser/classes/ezusergroup.php" );
include_once( "ezcontact/classes/ezperson.php" );

// include_once( "ezrfp/classes/fnc_viewArray.php" );


class eZProcurementBid
{
    /*!
      Constructs a new eZProcurementBid object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZProcurementBid( $id="" )
    {
        if ( is_array( $id ) )
        {
            $this->fill( $id );
        }
        else if ( $id != "" )
        {
            $this->ID = $id;
            $this->get( $this->ID );
        }
    }

    /*!
      Stores a product to the database.
    */
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();

        $amount = $db->escapeString( $this->Amount );
        $rank = $this->RankID;
        $procurement = $this->ProcurementID;
	$user = $this->UserID;
        $person = $this->PersonID;
        $company = $this->CompanyID:
	$winner = $this->Winner;
        $removed = $this->Removed;

	/*
        if ( is_object( $this->PublishDate ) and $this->PublishDate->isValid() )
            $publishDate = $this->PublishDate->timeStamp();
        else
            $publishDate = $this->PublishDate;

        if ( is_object( $this->ResponseDueDate ) and $this->ResponseDueDate->isValid() )
            $responceDueDate = $this->ResponseDueDate->timeStamp();
        else
            $responceDueDate = $this->ResponseDueDate;
	*/

        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZProcurement_Bid" );
            $nextID = $db->nextID( "eZProcurement_Bid", "ID" );

	    /*
            $timeStamp =& eZDateTime::timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;


            // fix for informix blob field
            $contentsStr = "'$contents'";
	    */

	    // i don't think we need to support informix, can we?
            if ( $db->isA() == "informix" )
            {
	      /*
                $textid = ifx_create_blob( 0, 0, $this->Contents );
		//                $textid = ifx_create_char( $contents );
                $blobIDArray[] = $textid;
                $contentsStr = "?";
                $db->setBlobArray( $blobIDArray );
	      */
            }

            $ret = $db->query( "INSERT INTO eZProcurement_Bid
		               ( ID,
                                 Amount,
                                 RankID,
                                 UserID,
				 PersonID,
                                 CompanyID,
                                 ProcurementID,
                                 Winner,
                                 Removed )
                                 VALUES
                                 ( '$nextID',
		                   '$amount',
                                    $rank,
                                   '$user',
                                   '$person',
                                   '$company',
                                   '$procurement',
                                   '$winner',
                                   '$removed' )
                                 " );

			$this->ID = $nextID;
        }
        else
        {

            // fix for informix blob field
            $contentsStr = "Contents='$contents',";

            if ( $db->isA() == "informix" )
            {
                ifx_textasvarchar(0);
                $db->array_query( $res, "SELECT ID, Contents FROM eZProcurement_Bid WHERE ID='$this->ID'" );


                $bid = $res[0][$db->fieldName("Contents")];
                // fetch the blob id
                $res = ifx_update_blob( $bid, $this->Contents );

                if ( !$res  )
                {
                    print( "Error updating informix text blob" );
                    die();
                }

                $blobIDArray[] = $bid;
                $db->setBlobArray( $blobIDArray );

                $contentsStr = "Contents=?,";
                ifx_textasvarchar(1);
            }


            $db->array_query( $res, "SELECT ID FROM eZProcurement_Bid WHERE Removed ='0' AND ID='$this->ID'" );

	    /*
            $timeStamp =& eZDateTime::timeStamp( true );

            if ( $this->PublishedOverride != 0 )
                $published = $this->PublishedOverride;
            else
                $published = $timeStamp;
	    */

            if ( ( count( $res ) > 0 ) && ( $this->IsPublished == "1" ) )
            {
                $ret = $db->query( "UPDATE eZProcurement_Bid SET
		                 Amount='$amount',
                                 ProcurementID='$procurement',
                                 UserID='$user',
                                 PersonID='$person',
                                 CompanyID='$company',
                                 Winner='$winner',
                                 Removed='$removed',

                                 WHERE ID='$this->ID'
                                 " );
            }
            else
            {
                if ( $this->PublishedOverride != 0 )
                    $published = $this->PublishedOverride;
                else
                    $published = $this->Published;

		$ret = $db->query( "UPDATE eZProcurement_Bid SET
                                 Amount='$amount',
                                 ProcurementID='$procurement',
                                 UserID='$user',
                                 PersonID='$person',
                                 CompanyID='$company',
                                 Winner='$winner',
                                 Removed='$removed',

                                 WHERE ID='$this->ID'
                                 " );
            }
        }

        $db->unlock();

        if ( $ret == false )
            $db->rollback( );
        else
            $db->commit();

        return true;
    }

    /*!
      Fetches the object information from the database.
    */
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        $ret = false;

        if ( $id != "" )
        {
            $db->array_query( $bid_array, "SELECT * FROM eZProcurement_Bid WHERE ID='$id'" );
            if ( count( $bid_array ) > 1 )
            {
                die( "Error: Bid's with the same ID was found in the database. This should not happen, Aborting ..." );
            }
            else if ( count( $bid_array ) == 1 )
            {
                $this->fill( $bid_array[0] );
                $ret = true;
            }
        }
        return $ret;
    }

    function fill( $bid_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID =& $bid_array[$db->fieldName("ID")];
        $this->Amount =& $bid_array[$db->fieldName("Amount")];
        $this->ProcurementID =& $bid_array[$db->fieldName("ProcurementID")];
        $this->UserID =& $bid_array[$db->fieldName("UserID")];
        $this->PersonID =& $bid_array[$db->fieldName("PersonID")];
        $this->CompanyID =& $bid_array[$db->fieldName("CompanyID")];
        $this->Winner =& $article_array[$db->fieldName("Winner")];
        $this->Removed =& $bid_array[$db->fieldName("Removed")];

	/*
        if ( $this->PublishDate == 0 )
            $this->PublishDate = false;
        if ( $this->ResponseDueDate == 0 )
            $this->ResponseDueDate = false;
	*/
    }

    /*!
        \static
        Returns the one, and only if one exists, rfp with the name

        Returns an object of eZProcurementBid.
     */
    function &getByCompany( $company )
    {
        $db =& eZDB::globalDatabase();

        $bid =& new eZProcurementBid();

        $company = $company;

        if( $company != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZProcurement_Bid WHERE CompanyID='$company'" );

            if( count( $author_array ) == 1 )
            {
                $bid =& new eZProcurementBid( $author_array[0] );
            }
        }

        return $bid;
    }

    /*!
        \static
        Returns the one, and only if one exists, rfp with the import id

        Returns an object of eZProcurementBid.
     */
    function &getByImportID( $name )
    {
        $db =& eZDB::globalDatabase();

        $topic =& new eZProcurementBid();

        $name = $db->escapeString( $name );

        if( $name != "" )
        {
            $db->array_query( $author_array, "SELECT * FROM eZProcurement_Bid WHERE ImportID='$name'" );

            if( count( $author_array ) == 1 )
            {
                $topic =& new eZProcurementBid( $author_array[0] );
            }
        }

        return $topic;
    }


    /*!
      Deletes a eZProcurementBid object from the database.
    */
    function delete()
    {
        $db =& eZDB::globalDatabase();

        if ( isSet( $this->ID ) )
        {
	  /*
            $imageList =& $this->images();
            $fileList =& $this->files();
            foreach( $imageList as $image )
            {
//                print_r( $image );

//                $image->delete();
            }
            foreach( $fileList as $file )
            {
//                $file->delete();
            }

	  */

            $db->begin();

//            $forum = $this->forum();
//            $forum->delete();

            $res = array();

            $res[] = $db->query( "DELETE FROM eZProcurement_Bid WHERE ID='$this->ID'" );

	    /*
            $res[] = $db->query( "DELETE FROM eZProcurement_BidCategoryDefinition WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurement_BidImageLink WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurement_BidImageDefinition WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurement_BidPermission WHERE ObjectID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurement_Bid WHERE ID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurementBid_AttributeValue WHERE RfpID='$this->ID'" );
            $res[] = $db->query( "DELETE FROM eZProcurement_BidForumLink WHERE RfpID='$this->ID'" );
	    */

            if ( in_array( false, $res ) )
                $db->rollback( );
            else
                $db->commit();

        }

        return true;
    }

    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the bid amount
    */
    function &amount( $asFormated = true )
    {
        if( $asFormated == true )
            return number_format($this->Amount);

        return $this->Amount;
    }

    /*!
      Returns the bid's procurement id
    */
    function &procurementID( )
    {
        return $this->ProcurementID;
    }

    /*!
      Returns the bid user id
    */
    function &userID( )
    {
        return $this->UserID;
    }

    /*!
      Returns the bid person id
    */
    function &personID( )
    {
        return $this->PersonID;
    }

    /*!
      Returns the bid rank id
    */
    function &rankID( )
    {
        return $this->RankID;
    }

    /*!
      Returns the bid person : company id
    */
    function &companyID( )
    {
        return $this->CompanyID;
    }

    /*!
      Returns true if the bid is a winner
    */
    function &winner( )
    {
        return $this->Winner;
    }

    /*!
      Returns true if the bid is removed
    */
    function &removed( )
    {
        return $this->Removed;
    }


    /*!
      Sets the bid's amount
    */
    function setAmount( $value )
    {
        $this->Amount = $value;
    }


    /*!
      Sets the bid's procurement id
    */
    function setProcurementID( $value )
    {
        $this->ProcurementID = $value;
    }

    /*!
      Sets the bid rank id
    */
    function setRankID( $value )
    {
        $this->RankID = $value;
    }

    /*!
      Sets assign bid winner value
    */
    function setWinner( $value )
    {
        $this->Winner = $value;
    }

    /*!
      Sets the bid removed value
    */
    function setRemoved( $value )
    {
        $this->Removed = $value;
    }


    // #################################################################################

    // #################################################################################

    // #################################################################################


    /*!
      Returns all the rfps in the database.

      The rfps are returned as an array of eZProcurementBid objects.
    */
    function &getAll( )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $rfpArray = array();

        $db->array_query( $rfpArray, "SELECT * FROM eZProcurement_Bid" );
        for ( $i=0; $i < count($rfpArray); $i++ )
        {
	                // $rfpArray[$i]['dueDateFormatted'] = date("m-d-Y H:i:s", $rfpArray[$i]['ResponseDueDate']);

			//print($rfpArray[$i]['dueDateFormatted'] ."=".date("m-d-Y H:i:s", $rfpArray[$i]['ResponseDueDate']).'<br>');
			//print("<b><br />".$returnArray[$i]['ResponseDueDate']."<br>".$returnArray[$i]['dueDateFormatted']."</b><br />");
			//v_array($rfpArray[$i]);
			//print("getAll: ".$returnArray[$i]." <br>"); //dylan take it out

			/*
			if ($i==2) {
				$rfpArray[$i]['ResponseDueDate'] = 1073755322;
			//    v_array($rfpArray[$i]);
			}
			*/

            $returnArray[$i] = new eZProcurementBid( $rfpArray[$i] );
        }

        return $returnArray;
    }

    // #################################################################################

    /*!
      Returns all the bids that is not valid now.

      The bids are returned as an array of eZProcurementBid objects.
    */
    function &getAllUnValid( $isRemoved=true )
    {
        $db =& eZDB::globalDatabase();

        $returnArray = array();
        $bidArray = array();

        if ( !$isRemoved )
            $removed = "  Removed='0' ";
        else
            $removed = "  Removed='1' ";

        $db->array_query( $bidArray, "SELECT *
                                          FROM eZProcurement_Bid
                                          WHERE $removed
                                          ORDER BY ID
                                          " );

        for ( $i=0; $i < count($bidArray); $i++ )
        {
            $returnArray[$i] = new eZProcurementBid( $bidArray[$i] );
        }

        return $returnArray;
    }

    // #################################################################################
    // class : paramiters
    // #################################################################################

    var $ID;
    var $ProcurementID;
    var $RankID;

    var $UserID;
    var $PersonID:
    var $CompanyID;

    var $Amount;

    var $Winner;
    var $Removed;
}

?>
