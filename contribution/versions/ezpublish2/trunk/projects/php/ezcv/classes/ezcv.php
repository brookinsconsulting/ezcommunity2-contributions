<?
// 
// $Id: ezcv.php,v 1.3 2000/12/21 16:08:49 ce Exp $
//
// Definition of eZCV class
//
// <Paul K Egell-Johnsen><pkej@ez.no>
// Created on: <20-Nov-2000 10:34:14 pkej>
//
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2000 eZ systems as
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

//!! eZCV
//! eZCV handles cv information.
/*!
 */

include_once( "ezcv/classes/ezexperience.php" );
include_once( "ezcv/classes/ezeducation.php" );
include_once( "ezcv/classes/ezcertificate.php" );
include_once( "ezcv/classes/ezextracurricular.php" );
include_once( "ezcv/classes/ezcourse.php" );
include_once( "ezcontact/classes/ezperson.php" );

class eZCV
{
    /*!
        Constructs an eZCV object.
      
        If $id is set, the object's values are fetched from the database.
     */
    function eZCV( $id = '', $fetch = false )
    {
        $this->IsConnected = false;
        if( !empty( $id ) )
        {
            $this->ID = $id;
            if( $fetch == true )
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
        This will store the last updated value of this object.
     */
    function update()
    {
        $this->dbInit();
        $this->Updated = gmdate( "YmdHis", time());
        $this->Database->query
        ( "
            UPDATE
                eZCV_CV
            SET
                Updated='$this->Updated'
            WHERE
                ID='$this->ID'
        " );
    }
    /*!
        Store this CV's data.
     */
    function store()
    {
        $this->dbInit();
        $this->Updated = gmdate( "YmdHis", time());
        
        if( !isSet( $this->ID ) )
        {
        
            $this->Created = gmdate( "YmdHis", time());
            
            $this->Database->query
            ( "
                INSERT INTO
                    eZCV_CV
                SET
                    PersonID='$this->PersonID',
                    NationalityID='$this->NationalityID',
                    Sex='$this->Sex',
	                MaritalStatus='$this->MaritalStatus',
	                WorkStatus='$this->WorkStatus',
	                ArmyStatus='$this->ArmyStatus',
                    Children='$this->Children',
                    Comment='$this->Comment',
                    Created='$this->Created',
                    Updated='$this->Updated',
                    ValidUntil='$this->ValidUntil'
            " );
            $this->ID = mysql_insert_id();            
            $this->State_ = "Coherent";
        }
        else
        {
            $this->Database->query
            ( "
                UPDATE
                    eZCV_CV
                SET
                    PersonID='$this->PersonID',
                    NationalityID='$this->NationalityID',
                    Sex='$this->Sex',
	                MaritalStatus='$this->MaritalStatus',
	                WorkStatus='$this->WorkStatus',
	                ArmyStatus='$this->ArmyStatus',
                    Children='$this->Children',
                    Comment='$this->Comment',
                    Created='$this->Created',
                    Updated='$this->Updated',
                    ValidUntil='$this->ValidUntil'
                WHERE
                    ID='$this->ID'
            " );
            $this->State_ = "Coherent";
        }
    }
    
    /*!
        Get this CV's data.
     */
    function get( $id )
    {
        $this->dbInit();    
        if( $id != "" )
        {
            $this->Database->array_query( $cvArray, "SELECT * FROM eZCV_CV WHERE ID='$id'" );
            if( count( $cvArray ) > 1 )
            {
                die( "Feil: Flere cver med samme ID funnet i database, dette skal ikke være mulig. " );
            }
            else if( count( $cvArray ) == 1 )
            {
                $this->ID = $cvArray[ 0 ][ "ID" ];
                $this->PersonID = $cvArray[ 0 ][ "PersonID" ];
                $this->NationalityID = $cvArray[ 0 ][ "NationalityID" ];
                $this->Sex = $cvArray[ 0 ][ "Sex" ];
                $this->MaritalStatus = $cvArray[ 0 ][ "MaritalStatus" ];
                $this->WorkStatus = $cvArray[ 0 ][ "WorkStatus" ];
                $this->ArmyStatus = $cvArray[ 0 ][ "ArmyStatus" ];
                $this->Children = $cvArray[ 0 ][ "Children" ];
                $this->Comment = $cvArray[ 0 ][ "Comment" ];
                $this->Created = $cvArray[ 0 ][ "Created" ];
                $this->Updated = $cvArray[ 0 ][ "Updated" ];
                $this->ValidUntil = $cvArray[ 0 ][ "ValidUntil" ];
            }
        }
    }
    
    /*!
        Get all CVs.
     */
    function getAll()
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = array();
    
        $this->Database->array_query( $item_array, "SELECT ID FROM eZCV_CV ORDER BY Created" );

        foreach( $item_array as $item )
        {
            $return_array[] = new eZCV( $item["ID"] );
        }
        return $return_array;
    }
    
    /*!
        Get all valid CVs.
     */
    function getAllValid()
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = array();
    
        $this->Database->array_query( $item_array, "SELECT ID FROM eZCV_CV WHERE ValidUntil >= 'today()' ORDER BY Created" );

        foreach( $item_array as $item )
        {
            $return_array[] = new eZCV( $item["ID"] );
        }
        return $return_array;
    }
    
    /*!
        Get CV by Person.
     */
    function getByPerson( $person )
    {
        $this->dbInit();
        $item_array = 0;
        $return_item = 0;
    
        if( is_numeric( $person ) )
        {
            $PersonID = $person;
        }
        
        if( get_class( $person ) == "ezperson" )
        {
            $PersonID = $person->id();
        }
        
        if( is_numeric( $PersonID ) )
        {
            $this->Database->array_query( $item_array, "SELECT ID FROM eZCV_CV WHERE PersonID = '$PersonID'" );

            foreach( $item_array as $item )
            {
                $return_item = new eZCV( $item["ID"] );
            }
        }
        return $return_item;
    }
    
    /*!
        Get CV by Extracurricular.
     */
    function getByExtracurricular( $extracurricular )
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = 0;
    
        if( is_numeric( $extracurricular ) )
        {
            $ExtracurricularID = $extracurricular;
        }
        
        if( get_class( $extracurricular ) == "ezextracurricular" )
        {
            $ExtracurricularID = $extracurricular->id();
        }
        
        if( is_numeric( $extracurricular ) )
        {
            $this->Database->array_query( $item_array, "SELECT DISTINCT CVID AS ID FROM eZCV_CVExtracurricularDict WHERE ExtracurricularID = '$ExtracurricularID'" );

            foreach( $item_array as $item )
            {
                $return_array = new eZCV( $item["ID"] );
            }
        }
        return $return_array;
    }
    
    /*!
        Get CV by Certificate.
     */
    function getByCertificate( $certificate )
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = 0;
    
        if( is_numeric( $certificate ) )
        {
            $CertificateID = $certificate;
        }
        
        if( get_class( $certificate ) == "ezcertificate" )
        {
            $CertificateID = $certificate->id();
        }
        
        if( is_numeric( $certificate ) )
        {
            $this->Database->array_query( $item_array, "SELECT DISTINCT CVID AS ID FROM eZCV_CVCertificateDict WHERE CertificateID = '$CertificateID'" );

            foreach( $item_array as $item )
            {
                $return_array = new eZCV( $item["ID"] );
            }
        }
        return $return_array;
    }
    
    /*!
        Get CV by Experience.
     */
    function getByExperience( $experience )
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = 0;
    
        if( is_numeric( $experience ) )
        {
            $ExperienceID = $experience;
        }
        
        if( get_class( $experience ) == "ezexperience" )
        {
            $ExperienceID = $experience->id();
        }
        
        if( is_numeric( $experience ) )
        {
            $this->Database->array_query( $item_array, "SELECT DISTINCT CVID AS ID FROM eZCV_CVExperienceDict WHERE ExperienceID = '$ExperienceID'" );

            foreach( $item_array as $item )
            {
                $return_array = new eZCV( $item["ID"] );
            }
        }
        return $return_array;
    }

    /*!
        Get CV by Course.
     */
    function getByCourse( $course )
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = 0;
    
        if( is_numeric( $course ) )
        {
            $CourseID = $course;
        }
        
        if( get_class( $course ) == "ezcourse" )
        {
            $CourseID = $course->id();
        }
        
        if( is_numeric( $course ) )
        {
            $this->Database->array_query( $item_array, "SELECT DISTINCT CVID AS ID FROM eZCV_CVCourseDict WHERE CourseID = '$CourseID'" );

            foreach( $item_array as $item )
            {
                $return_array = new eZCV( $item["ID"] );
            }
        }
        return $return_array;
    }

    /*!
        Get CV by Education.
     */
    function getByEducation( $education )
    {
        $this->dbInit();
        $item_array = 0;
        $return_array = 0;
    
        if( is_numeric( $education ) )
        {
            $EducationID = $education;
        }
        
        if( get_class( $education ) == "ezeducation" )
        {
            $EducationID = $education->id();
        }
        
        if( is_numeric( $education ) )
        {
            $this->Database->array_query( $item_array, "SELECT DISTINCT CVID AS ID FROM eZCV_CVEducationDict WHERE EducationID = '$EducationID'" );

            foreach( $item_array as $item )
            {
                $return_array = new eZCV( $item["ID"] );
            }
        }
        return $return_array;
    }
    
    /*!
        Delete this CV.
     */
    function delete()
    {
        $this->dbInit();

        if( isSet( $this->ID ) )
        {
            $this->deleteAllExperience();
            $this->deleteAllEducation();
            $this->deleteAllExtracurricular();
            $this->Database->query( "DELETE FROM eZCV_CV WHERE ID='$this->ID'" );
        }
        return true;
    }
    
    /*!
        Adds experience to this CV.
        
        \in
            \$experience  Either an object of the type eZExperience or a numeric id for a tuplet in
                        the eZCV_Experience table.
        \return
            Returns true if the object/id exists or is inserted as a part of this CV.
     */
    function addExperience( $experience )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $experience ) == "ezexperience" )
        {
            $experienceID = $experience->id();

        }
        elseif( is_numeric( $experience ) )
        {
            $experienceID = $experience;
        }
        
        if( is_numeric( $experienceID ) )
        {
            $checkQuery = "SELECT CVID FROM eZCV_CVExperienceDict WHERE ExperienceID='$experienceID'";
            
            $this->Database->array_query( $experience_array, $checkQuery );

            $count = count( $experience_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZCV_CVExperienceDict
                                SET CVID='$this->ID', ExperienceID='$experienceID'" );
            }
            $this->update();
            $ret = true;
        }
        
        return $ret;
    }
    
    /*!
      Adds course to this CV.
        
        \in
            \$course  Either an object of the type eZCourse or a numeric id for a tuplet in
                        the eZCV_Course table.
        \return
            Returns true if the object/id exists or is inserted as a part of this CV.
     */
    function addCourse( $course )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $course ) == "ezcourse" )
        {
            $courseID = $course->id();

        }
        elseif( is_numeric( $course ) )
        {
            $courseID = $course;
        }
        
        if( is_numeric( $courseID ) )
        {
            $checkQuery = "SELECT CVID FROM eZCV_CVCourseDict WHERE CourseID='$courseID'";
            
            $this->Database->array_query( $course_array, $checkQuery );

            $count = count( $course_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZCV_CVCourseDict
                                SET CVID='$this->ID', CourseID='$courseID'" );
            }
            $this->update();
            $ret = true;
        }
        
        return $ret;
    }

    /*!
        Deletes work course from this CV.
     */
    function deleteAllCourse()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    CourseID AS ID
                FROM
                    eZCV_CVCourseDict
                WHERE
                    CVID='$CVID'
            " );
        foreach( $itemArray as $item )
        {
            $itemID = $item["ID"];
            $this->Database->query( "DELETE FROM eZCV_Course WHERE ID='$itemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVCourseDict WHERE CourseID='$itemID'" );
            $this->update();
        }            
    }

    /*!
        Deletes education from this CV.
     */
    function deleteCourse( $item )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        if( get_class() == "ezcourse" )
        {
            $ItemID = $item->id();
        }
        
        if( is_numeric( $item ) )
        {
            $ItemID = $item;
        }
        
        if( is_numeric( $ItemID ) )
        {
            $this->Database->query( "DELETE FROM eZCV_Course WHERE ID='$ItemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVCourseDict WHERE CourseID='$ItemID'" );
            $this->update();
        }
    }


    /*!
        Returns all courses from this CV.
        
        \return
            Returns an array of eZCourse objects.
     */
    function course()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $CVID = $this->ID;
        
        $this->dbInit();

        $this->Database->array_query( $itemArray, "SELECT CourseID AS ID FROM eZCV_CVCourseDict WHERE CVID='$CVID'" );

        foreach( $itemArray as $item )
        {
            $return_array[] = new eZCourse( $item["ID"] );
        }

        return $return_array;
    }

    
    /*!
        Deletes work experience from this CV.
     */
    function deleteAllExperience()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    ExperienceID AS ID
                FROM
                    eZCV_CVExperienceDict
                WHERE
                    CVID='$CVID'
            " );
        foreach( $itemArray as $item )
        {
            $itemID = $item["ID"];
            $this->Database->query( "DELETE FROM eZCV_Experience WHERE ID='$itemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVExperienceDict WHERE ExperienceID='$itemID'" );
            $this->update();
        }            
    }

    /*!
        Deletes work experience from this CV.
     */
    function deleteExperience( $item )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        if( get_class() == "ezexperience" )
        {
            $ItemID = $item->id();
        }
        
        if( is_numeric( $item ) )
        {
            $ItemID = $item;
        }
        
        if( is_numeric( $ItemID ) )
        {
            $this->Database->query( "DELETE FROM eZCV_Experience WHERE ID='$ItemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVExperienceDict WHERE ExperienceID='$ItemID'" );
            $this->update();
        }
    }
    
    
    /*!
        Returns all work experience from this CV.
        
        \return
            Returns an array of eZExperience objects.
     */
    function experience()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $CVID = $this->ID;
        
        $this->dbInit();

        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    ExperienceID AS ID
                FROM
                    eZCV_CVExperienceDict
                WHERE
                    CVID='$CVID'
            " );

        foreach( $itemArray as $item )
        {
            $return_array[] = new eZExperience( $item["ID"] );
        }

        return $return_array;
    }
    
    /*!
        Adds education to this CV.
        
        \in
            \$education  Either an object of the type eZEducation or a numeric id for a tuplet in
                        the eZCV_Education table.
        \return
            Returns true if the object/id exists or is inserted as a part of this CV.
     */
    function addEducation( $education )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $education ) == "ezeducation" )
        {
            $educationID = $education->id();

        }
        elseif( is_numeric( $education ) )
        {
            $educationID = $education;
        }
        
        if( is_numeric( $educationID ) )
        {
            $checkQuery = "SELECT CVID FROM eZCV_CVEducationDict WHERE EducationID='$educationID'";
            
            $this->Database->array_query( $education_array, $checkQuery );

            $count = count( $education_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZCV_CVEducationDict
                                SET CVID='$this->ID', EducationID='$educationID'" );
            }
            $this->update();
            $ret = true;
        }
        
        return $ret;
    }
    
    /*!
        Deletes education from this CV.
     */
    function deleteAllEducation()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    EducationID AS ID
                FROM
                    eZCV_CVEducationDict
                WHERE
                    CVID='$CVID'
            " );
        foreach( $itemArray as $item )
        {
            $itemID = $item["ID"];
            $this->Database->query( "DELETE FROM eZCV_Education WHERE ID='$itemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVEducationDict WHERE EducationID='$itemID'" );
            $this->update();
        }            
    }
    
    /*!
        Deletes education from this CV.
     */
    function deleteEducation( $item )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        if( get_class() == "ezeducation" )
        {
            $ItemID = $item->id();
        }
        
        if( is_numeric( $item ) )
        {
            $ItemID = $item;
        }
        
        if( is_numeric( $ItemID ) )
        {
            $this->Database->query( "DELETE FROM eZCV_Education WHERE ID='$ItemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVEducationDict WHERE EducationID='$ItemID'" );
            $this->update();
        }
    }

    /*!
        Returns all education from this CV.
        
        \return
            Returns an array of eZEducation objects.
     */
    function education()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $CVID = $this->ID;
        
        $this->dbInit();

        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    EducationID AS ID
                FROM
                    eZCV_CVEducationDict
                WHERE
                    CVID='$CVID'
            " );

        foreach( $itemArray as $item )
        {
            $return_array[] = new eZEducation( $item["ID"] );
        }

        return $return_array;
    }

    /*!
        Adds extra curricular activity to this CV.
        
        \in
            \$extracurricular  Either an object of the type eZExtracurricular or a
                               numeric id for a tuplet in the eZCV_Extracurricular table.
        \return
            Returns true if the object/id exists or is inserted as a part of this CV.
     */
    function addExtracurricular( $extracurricular )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $extracurricular ) == "ezextracurricular" )
        {
            $extracurricularID = $extracurricular->id();

        }
        elseif( is_numeric( $extracurricular ) )
        {
            $extracurricularID = $extracurricular;
        }
        
        if( is_numeric( $extracurricularID ) )
        {
            $checkQuery = "SELECT CVID FROM eZCV_CVExtracurricularDict WHERE ExtracurricularID='$extracurricularID'";
            
            $this->Database->array_query( $extracurricular_array, $checkQuery );

            $count = count( $extracurricular_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZCV_CVExtracurricularDict
                                SET CVID='$this->ID', ExtracurricularID='$extracurricularID'" );
            }
            $this->update();
            $ret = true;
        }
        
        return $ret;
    }
    
    /*!
        Deletes extra curricular activity from this CV.
     */
    function deleteAllExtracurricular()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    ExtracurricularID AS ID
                FROM
                    eZCV_CVExtracurricularDict
                WHERE
                    CVID='$CVID'
            " );
        foreach( $itemArray as $item )
        {
            $itemID = $item["ID"];
            $this->Database->query( "DELETE FROM eZCV_Extracurricular WHERE ID='$itemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVExtracurricularDict WHERE ExtracurricularID='$itemID'" );
            $this->update();
        }            
    }
    /*!
        Deletes extracurricular from this CV.
     */
    function deleteExtracurricular( $item )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        if( get_class() == "ezextracurricular" )
        {
            $ItemID = $item->id();
        }
        
        if( is_numeric( $item ) )
        {
            $ItemID = $item;
        }
        
        if( is_numeric( $ItemID ) )
        {
            $this->Database->query( "DELETE FROM eZCV_Extracurricular WHERE ID='$ItemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVExtracurricularDict WHERE ExtracurricularID='$ItemID'" );
            $this->update();
        }
    }
    
    /*!
        Returns all extra curricular activites from this CV.
        
        \return
            Returns an array of eZExtracurricular objects.
     */
    function extracurricular()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $CVID = $this->ID;
        
        $this->dbInit();

        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    ExtracurricularID AS ID
                FROM
                    eZCV_CVExtracurricularDict
                WHERE
                    CVID='$CVID'
            " );

        foreach( $itemArray as $item )
        {
            $return_array[] = new eZExtracurricular( $item["ID"] );
        }

        return $return_array;
    }

    
    /*!
        Adds certificate to this CV.
        
        \in
            \$certificate  Either an object of the type eZertificate or a
                               numeric id for a tuplet in the eZCV_Certificate table.
        \return
            Returns true if the object/id exists or is inserted as a part of this CV.
     */
    function addCertificate( $certificate )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $ret = false;
       
        $this->dbInit();
        if( get_class( $certificate ) == "ezcertificate" )
        {
            $certificateID = $certificate->id();

        }
        elseif( is_numeric( $certificate ) )
        {
            $certificateID = $certificate;
        }
        
        if( is_numeric( $certificateID ) )
        {
            $checkQuery = "SELECT CVID FROM eZCV_CVCertificateDict WHERE CertificateID='$certificateID'";
            
            $this->Database->array_query( $certificate_array, $checkQuery );

            $count = count( $certificate_array );

            if( $count == 0 )
            {
                $this->Database->query( "INSERT INTO eZCV_CVCertificateDict
                                SET CVID='$this->ID', CertificateID='$certificateID'" );
            }
            $this->update();
            $ret = true;
        }
        
        return $ret;
    }
    
    /*!
        Deletes certificates from this CV.
     */
    function deleteAllCertificates()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    CertificateID AS ID
                FROM
                    eZCV_CVCertificateDict
                WHERE
                    CVID='$CVID'
            " );
        foreach( $itemArray as $item )
        {
            $itemID = $item["ID"];
            $this->Database->query( "DELETE FROM eZCV_Certificate WHERE ID='$itemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVCertificateDict WHERE ID='$itemID'" );
            $this->update();
        }            
    }
    
    /*!
        Deletes certificate from this CV.
     */
    function deleteCertificate( $item )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $CVID = $this->ID;

        $this->dbInit();
        
        if( get_class() == "ezcertificate" )
        {
            $ItemID = $item->id();
        }
        
        if( is_numeric( $item ) )
        {
            $ItemID = $item;
        }
        
        if( is_numeric( $ItemID ) )
        {
            $this->Database->query( "DELETE FROM eZCV_Certificate WHERE ID='$ItemID'" );
            $this->Database->query( "DELETE FROM eZCV_CVCertificateDict WHERE CertificateID='$ItemID'" );
            $this->update();
        }
    }

    /*!
        Returns all certificates from this CV.
        
        \return
            Returns an array of eZCertificate objects.
     */
    function certificate()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );
        
        $return_array = array();
        $CVID = $this->ID;
        
        $this->dbInit();

        $this->Database->array_query(
            $itemArray,
            "
                SELECT
                    CertificateID AS ID
                FROM
                    eZCV_CVCertificateDict
                WHERE
                    CVID='$CVID'
            " );

        foreach( $itemArray as $item )
        {
            $return_array[] = new eZCertificate( $item["ID"] );
        }

        return $return_array;
    }

    /*!
        Returns the ID of this object.
     */
    function id()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ID;
    }
    
    /*!
        Set the ID of this object to $value.
    */
    function setID( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ID = $value;
    }

    /*!
        Returns the PersonID of this object.
     */
    function personID()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->PersonID;
    }
    
    /*!
        Sets the PersonID of this object to $value.
    */
    function setPersonID( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->PersonID = $value;
    }

    /*!
        Returns the NationalityID of this object.
     */
    function nationalityID()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->NationalityID;
    }
    
    /*!
        Sets the nationality of this object to $value.
    */
    function setNationalityID( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->NationalityID = $value;
    }

    /*!
        Returns the Sex of this object.
     */
    function sex()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Sex;
    }
    
    /*!
        Sets the Sex of this object to $value.
    */
    function setSex( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Sex = $value;
    }

    /*!
        Returns the marital status of this object.
     */
    function maritalStatus()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->MaritalStatus;
    }
    
    /*!
        Sets the marital status of this object to $value.
    */
    function setMaritalStatus( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->MaritalStatus = $value;
    }

    /*!
        Returns the work status of this object.
     */
    function workStatus()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->WorkStatus;
    }
    
    /*!
        Sets the work status of this object to $value.
    */
    function setWorkStatus( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->WorkStatus = $value;
    }

    /*!
        Returns the army status of this object.
     */
    function armyStatus()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ArmyStatus;
    }
    
    /*!
        Sets the army status of this object to $value.
    */
    function setArmyStatus( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ArmyStatus = $value;
    }

    /*!
        Returns the children of this object.
     */
    function children()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Children;
    }
    
    /*!
        Sets the children of this object to $value.
    */
    function setChildren( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Children = $value;
    }

    /*!
        Returns the comment of this object.
     */
    function comment()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Comment;
    }
    
    /*!
        Sets the comment of this object to $value.
    */
    function setComment( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Comment = $value;
    }

    /*!
        Returns the created of this object.
     */
    function created()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Created;
    }
    
    /*!
        Sets the created of this object to $value.
    */
    function setCreated( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Created = $value;
    }

    /*!
        Returns the updated of this object.
     */
    function updated()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->Updated;
    }
    
    /*!
        Sets the updated of this object to $value.
    */
    function setUpdated( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->Updated = $value;
    }

    /*!
        Returns the valid until of this object.
     */
    function validUntil()
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        return $this->ValidUntil;
    }
    
    
    /*!
        Sets the valid until of this object to $value.
    */
    function setValidUntil( $value )
    {
        if( $this->State_ == "Dirty" )
            $this->get( $this->ID );

        $this->ValidUntil = $value;
    }
    
    /*!
        Returns the sex options
     */
    function sexTypes()
    {
        $this->dbInit();
        $this->Database->array_query( $itemArray, $query="SHOW COLUMNS FROM eZCV_CV LIKE 'Sex'" );
        $items=preg_split( "/'|\,/", $itemArray[0]["Type"], 0, PREG_SPLIT_NO_EMPTY );
        
        $count=count( $items );
        
        for( $i=1; $i < $count - 1; $i++ )
        {
            $returnArray[]=$items[$i];
        }
        return $returnArray;
    }
    
    
    /*!
        Returns the marital status options
     */
    function maritalStatusTypes()
    {
        $this->dbInit();
        $this->Database->array_query( $itemArray, $query="SHOW COLUMNS FROM eZCV_CV LIKE 'MaritalStatus'" );
        $items=preg_split( "/'|\,/", $itemArray[0]["Type"], 0, PREG_SPLIT_NO_EMPTY );
        
        $count=count( $items );
        
        for( $i=1; $i < $count - 1; $i++ )
        {
            $returnArray[]=$items[$i];
        }
        
        return $returnArray;
    }
    
    
    /*!
        Returns the army status options
     */
    function armyStatusTypes()
    {
        $this->dbInit();
        $this->Database->array_query( $itemArray, $query="SHOW COLUMNS FROM eZCV_CV LIKE 'ArmyStatus'" );
        $items=preg_split( "/'|\,/", $itemArray[0]["Type"], 0, PREG_SPLIT_NO_EMPTY );
        
        $count=count( $items );
        
        for( $i=1; $i < $count - 1; $i++ )
        {
            $returnArray[]=$items[$i];
        }
        
        return $returnArray;
    }
    
    
    /*!
        Returns the work status options
     */
    function workStatusTypes()
    {
        $this->dbInit();
        $this->Database->array_query( $itemArray, $query="SHOW COLUMNS FROM eZCV_CV LIKE 'WorkStatus'" );
        $items=preg_split( "/'|\,/", $itemArray[0]["Type"], 0, PREG_SPLIT_NO_EMPTY );
        
        $count=count( $items );
        
        for( $i=1; $i < $count - 1; $i++ )
        {
            $returnArray[]=$items[$i];
        }
        
        return $returnArray;
    }
    
    
    /*!
      \private
      Used by this class to connect to the database.
    */
    function dbInit()
    {
        if( $this->IsConnected == false )
        {
            $this->Database = new eZDB( "site.ini", "site" );
            $this->IsConnected = true;
        }
    }
    
    var $ID;
    var $PersonID;
    var $NationalityID;
    var $Sex;
    var $MaritalStatus;
    var $WorkStatus;
    var $ArmyStatus;
    var $Children;
    var $Comment;
    var $Created;
    var $Updated;
    var $ValidUntil;
    var $Education = array();
    var $Experience = array();
    var $Extracurricular = array();
    var $Certificate = array();

    ///  Variable for keeping the database connection.
    var $Database;

    /// Indicates the state of the object. In regard to database information.
    var $State_;
    /// Is true if the object has database connection, false if not.
    var $IsConnected;
}
?>
