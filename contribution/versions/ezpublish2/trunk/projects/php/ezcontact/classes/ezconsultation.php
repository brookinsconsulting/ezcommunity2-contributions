<?
// 
// $Id: ezconsultation.php,v 1.14 2001/05/04 16:37:24 descala Exp $
//
// Definition of eZConsultation class
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
//! eZConsultation handles consultations with contact persons and companies.
/*!

  Example code:
  \code
  $consult = new eZConsultation();
  $consult->setShortDescription( "Signed multi-million dollar deal with ..." );
  $consult->store();

  $stored_consult = new eZConsultation( $consultID );
  $notificators = $stored_consult->notificators();
  foreach ( $notificators as $notification )
  {
      print( $notification->email() );
  }

  \endcode

  \sa eZPerson eZCompany eZUser
*/

include_once( "classes/ezdb.php" );
include_once( "classes/ezdatetime.php" );
include_once( "ezcontact/classes/ezperson.php" );
include_once( "ezcontact/classes/ezcompany.php" );
include_once( "ezuser/classes/ezusergroup.php" );

include_once( "classes/ezlog.php" );

class eZConsultation
{
    /*!
      Constructs a new eZConsultation object.

      If $id is set the object's values are fetched from the
      database.
    */
    function eZConsultation( $id="-1", $fetch=true )
    {
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
      Stores the consultation to the database.
    */
    function store( )
    {
        $db = eZDB::globalDatabase();
        $date = $this->Date->mySQLDateTime();
        $shortdesc = addslashes( $this->ShortDesc );
        $description = addslashes( $this->Description );
        if ( !isset( $this->ID ) )
        {
            $db->query( "INSERT INTO eZContact_Consultation set
                                                  ShortDesc='$shortdesc',
	                                              Description='$description',
                                                  StateID='$this->State',
                                                  EmailNotifications='$this->EmailNotice',
	                                              Date='$date'" );
			$this->ID = $db->insertID();
            $this->State_ = "Coherent";
        }
        else
        {
            $db->query( "UPDATE eZContact_Consultation set
                                                  ShortDesc='$shortdesc',
	                                              Description='$description',
                                                  StateID='$this->State',
                                                  EmailNotifications='$this->EmailNotice',
	                                              Date='$date'
                                                  WHERE ID='$this->ID'" );
            $this->State_ = "Coherent";
        }

        return true;
    }

    /*!
      Deletes an eZConsultation object from the database.
    */
    function delete( $id = false )
    {  
        if ( !$id )
            $id = $this->ID;

        if ( isset( $id ) && is_numeric( $id ) )
        {
            $db = eZDB::globalDatabase();
            $db->query( "DELETE FROM eZContact_Consultation WHERE ID='$id'" );
            $db->array_query( $qry_array, "SELECT ConsultationID FROM eZContact_ConsultationPersonUserDict WHERE ConsultationID='$id'" );
            if ( count( $qry_array ) > 0 )
            {
                $db->query( "DELETE FROM eZContact_ConsultationPersonUserDict WHERE ConsultationID='$id'" );
            }
            $db->array_query( $qry_array, "SELECT ConsultationID FROM eZContact_ConsultationCompanyUserDict WHERE ConsultationID='$id'" );
            if ( count( $qry_array ) > 0 )
            {
                $db->query( "DELETE FROM eZContact_ConsultationCompanyUserDict WHERE ConsultationID='$id'" );
            }
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
            $db->query_single( $consult_array, "SELECT * FROM eZContact_Consultation WHERE ID='$id'" );
            $this->ID = $consult_array["ID"];
            $this->ShortDesc = $consult_array["ShortDesc"];
            $this->Description = $consult_array["Description"];
            $this->State = $consult_array["StateID"];
            $this->EmailNotice = $consult_array["EmailNotifications"];
            $this->Date = new eZDateTime();
            $this->Date->setMySQLDateTime( $consult_array["Date"] );

            $ret = true;
            $this->State_ = "Coherent";
        }
        else
        {
            $this->State_ = "Dirty";
        }
        return $ret;
    }

    /*!
      Sets the short description for the consultation.
    */

    function setShortDescription( $desc )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->ShortDesc = $desc;
    }

    /*!
      Sets the full description for the consultation.
    */

    function setDescription( $desc )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Description = $desc;
    }

    /*!
      Sets the state type for the consultation.
    */

    function setState( $state )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->State = $state;
    }

    /*!
      Adds a new group to the consultation, you can either use the group id or a eZUserGroup object.
    */

    function addGroup( $group )
    {
        if ( get_class( $group ) == "ezusergroup" )
            $groupid = $group->id();
        else
            $groupid = $group;
        $db = eZDB::globalDatabase();
        $db->query( "INSERT INTO eZContact_ConsultationGroupsDict
                            SET ConsultationID='$this->ID', GroupID='$groupid'" );
    }

    /*!
      Removes an existing group from the consultation.
    */

    function removeGroup( $group )
    {
    }

    /*!
      Removes all groups from the consultation.
    */

    function removeGroups()
    {
        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_ConsultationGroupsDict
                            WHERE ConsultationID='$this->ID'" );
    }

    /*!
      Adds a new email to the consultation.
    */

    function addEmail( $email )
    {
    }

    /*!
      Sets the email list for the consultation.
    */

    function setEmail( $emails )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->EmailNotice = $emails;
    }

    /*!
      Removes an existing email from the consultation.
    */

    function removeEmail( $email )
    {
    }

    /*!
      Sets the date for the consultation.
    */

    function setDate( $date )
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        $this->Date = $date;
    }

    /*!
      Returns the id for the consultation
    */

    function id()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->ID;
    }

    /*!
      Returns the short description for the consultation
    */

    function shortDescription()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->ShortDesc;
    }

    /*!
      Returns the full description for the consultation.
    */

    function description()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Description;
    }

    /*!
      Returns the state of the consultation, this usually tells if the consultation is under negotiation, closing or similar.
    */

    function state()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->State;
    }

    /*!
      Returns an array with eZUserGroup objects, these groups are used for sending an email reply.
    */

    function groupList()
    {
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT GroupID FROM eZContact_ConsultationGroupsDict
                                       WHERE ConsultationID='$this->ID'" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZUserGroup( $qry["GroupID"] );
            }
        return $ret_array;
    }

    /*!
      Returns an array with eZUserGroup IDs.
      \sa groupList
    */

    function groupIDList()
    {
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT GroupID FROM eZContact_ConsultationGroupsDict
                                       WHERE ConsultationID='$this->ID'" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = $qry["GroupID"];
            }
        return $ret_array;
    }

    /*!
      Returns an array with strings, these strings each contain an email address.
    */

    function emailList()
    {
        $emails = preg_split( "/[,;]/", $this->EmailNotice );
        return $emails;
    }

    /*!
      Returns the string with email notifications, this is an unparsed form, use emailList() to get them parsed.
    */

    function emails()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->EmailNotice;
    }

    /*!
      Returns an array with eZNotification objects, this array is built from both the groupList() and the emailList().
    */

    function notificators()
    {
    }

    /*!
      Returns the date of the consultation.
    */

    function date()
    {
        if ( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        return $this->Date;
    }

    /*!
      \static
      Finds all consultations on a specific state type.
    */

    function findConsultationsByState( $state )
    {
    }

    /*!
      \static
      Returns true if the consultation given by $consultationid belongs to $user.
    */

    function belongsTo( $consultationid, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        // Check company
        $db->array_query( $qry_array, "SELECT C.ID FROM eZContact_Consultation AS C,
                                                      eZContact_ConsultationCompanyUserDict AS CCUD
                                       WHERE CCUD.UserID='$user' AND C.ID=CCUD.ConsultationID
                                       AND C.ID='$consultationid'", 0, 1 );
        if ( count( $qry_array ) == 1 )
            return true;
        // Check person
        $db->array_query( $qry_array, "SELECT C.ID FROM eZContact_Consultation AS C,
                                                      eZContact_ConsultationPersonUserDict AS CPUD
                                       WHERE CPUD.UserID='$user' AND C.ID=CPUD.ConsultationID
                                       AND C.ID='$consultationid'", 0, 1 );
        if ( count( $qry_array ) == 1 )
            return true;
        return false;
    }

    /*!
      \static
      Finds all companies which the user has consultations with and returns an array with IDs.
    */

    function findConsultedCompanies( $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT CompanyID FROM eZContact_ConsultationCompanyUserDict
                                       WHERE UserID='$user'
                                       GROUP BY CompanyID" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZCompany( $qry["CompanyID"] );
            }
        return $ret_array;
    }

    /*!
      \static
      Finds all persons which the user has consultations with and returns an array with IDs.
    */

    function findConsultedPersons( $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $qry_array = array();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT PersonID FROM eZContact_ConsultationPersonUserDict
                                       WHERE UserID='$user'
                                       GROUP BY PersonID" );
        $ret_array = array();
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZPerson( $qry["PersonID"] );
            }
        return $ret_array;
    }

    /*!
      \static
      Finds all consultations on a specific contact person or company.
    */

    function findConsultationsByContact( $contact, $user, $is_person = true, $index = 0, $max = -1 )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        if ( $max > 0 )
        {
            $limit = "LIMIT $index, $max";
        }
        else
        {
            $limit = "";
        }

        $qry_array = array();
        $db = eZDB::globalDatabase();
        if ( $is_person )
        {
            $db->array_query( $qry_array, "SELECT CPUD.ConsultationID FROM eZContact_ConsultationPersonUserDict AS CPUD, eZContact_Consultation AS C
                                           WHERE CPUD.PersonID='$contact' AND CPUD.UserID='$user' AND CPUD.ConsultationID = C.ID
                                           ORDER BY C.Date DESC, C.ID DESC $limit" );
        }
        else
        {
            $db->array_query( $qry_array, "SELECT CPCD.ConsultationID FROM eZContact_ConsultationCompanyUserDict AS CPCD, eZContact_Consultation AS C
                                           WHERE CPCD.CompanyID='$contact' AND CPCD.UserID='$user' AND CPCD.ConsultationID = C.ID
                                           ORDER BY C.Date DESC, C.ID DESC $limit" );
        }
        $ret_array = array();
        foreach ( $qry_array as $qry )
        {
            $ret_array[] = new eZConsultation( $qry["ConsultationID"] );
        }
        return $ret_array;
    }

    /*!
      \static
      Finds the n latest consultations.
    */

    function findLatestConsultations( $user, $max )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $qry_array = array();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT C.Date, C.ID
                                          FROM eZContact_ConsultationPersonUserDict AS CPUD, eZContact_Consultation AS C
                                           WHERE CPUD.UserID='$user' AND CPUD.ConsultationID = C.ID
                                           ORDER BY C.Date DESC, C.ID DESC LIMIT $max" );
        $db->array_query_append( $qry_array,
                                          "SELECT C.Date, C.ID
                                           FROM eZContact_ConsultationCompanyUserDict AS CPCD, eZContact_Consultation AS C
                                           WHERE CPCD.UserID='$user' AND CPCD.ConsultationID = C.ID
                                           ORDER BY C.Date DESC, C.ID DESC LIMIT $max" );
        arsort( $qry_array );
        $ret_array = array();
        $qry_array = array_slice( $qry_array, 0, $max );
        foreach ( $qry_array as $qry )
            {
                $ret_array[] = new eZConsultation( $qry["ID"] );
            }
        return $ret_array;
    }

    /*!
     */
    function companyConsultationCount( $company, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ConsultationID ) as Count FROM eZContact_ConsultationCompanyUserDict
                                  WHERE CompanyID='$company' AND UserID='$user'" );
        return $qry["Count"];
    }

    /*!
     */
    function personConsultationCount( $person, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query_single( $qry, "SELECT count( ConsultationID ) as Count FROM eZContact_ConsultationPersonUserDict
                                  WHERE PersonID='$person' AND UserID='$user'" );
        return $qry["Count"];
    }

    /*!
     */
    function company( $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT CompanyID FROM eZContact_ConsultationCompanyUserDict
                                       WHERE ConsultationID='$this->ID' AND UserID='$user'" );
        if ( count( $qry_array ) == 1 )
        {
            return $qry_array[0]["CompanyID"];
        }
        else
        {
            return false;
        }
    }

    /*!
     */
    function person( $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->array_query( $qry_array, "SELECT PersonID FROM eZContact_ConsultationPersonUserDict
                                       WHERE ConsultationID='$this->ID' AND UserID='$user'" );
        if ( count( $qry_array ) == 1 )
        {
            return $qry_array[0]["PersonID"];
        }
        else
        {
            return false;
        }
    }

    /*!
     */
    function addConsultationToPerson( $person, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query( "INSERT INTO eZContact_ConsultationPersonUserDict
                     SET ConsultationID='$this->ID', PersonID='$person', UserID='$user'" );
    }

    /*!
     */
    function addConsultationToCompany( $company, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query( "INSERT INTO eZContact_ConsultationCompanyUserDict
                     SET ConsultationID='$this->ID', CompanyID='$company', UserID='$user'" );
    }

    /*!
     */
    function removeConsultationFromPerson( $person, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_ConsultationPersonUserDict
                     WHERE ConsultationID='$this->ID' AND PersonID='$person' AND UserID='$user'" );
    }

    /*!
     */
    function removeConsultationFromCompany( $company, $user )
    {
        if ( get_class( $user ) == "ezuser" )
            $user = $user->id();
        $db = eZDB::globalDatabase();
        $db->query( "DELETE FROM eZContact_ConsultationCompanyUserDict
                     WHERE ConsultationID='$this->ID' AND CompanyID='$company' AND UserID='$user'" );
    }

    /*!
      \static
      Returns the name of the state id.
    */

    function stateName( $state )
    {
        $db = eZDB::globalDatabase();
        $db->query_single( $state_row, "SELECT Name FROM eZContact_ConsultationType WHERE ID='$state'" );
        return $state_row["Name"];
    }

    var $ID;
    var $ShortDesc;
    var $Description;
    var $State;
    var $Date;
    var $EmailNotice;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
}

?>
 