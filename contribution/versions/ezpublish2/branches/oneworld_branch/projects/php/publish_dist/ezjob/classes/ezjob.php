<?php
//
// $Id: ezjob.php,v 1.1.2.1 2002/06/04 11:23:49 br Exp $
//
// Definition of eZJob class
//
// <Bjørn Reiten> <br@ez.no>
// Created on: <22-May-2002 16:16:59 br>
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

//!! 
//! The class eZJob does
/*!

*/

class eZJob
{
    /*!
      Constructs a new eZJob object.
    */
    function eZJob( $id = "" )
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
      Stores a job to the database.
    */  
    function store()
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        
        $title = $db->escapeString( $this->Title );
        $description = $db->escapeString( $this->Description );
        $location = $db->escapeString( $this->Location );
        $salary = $db->escapeString( $this->Salary );
        $url = $db->escapeString( $this->$URL );
        $volunteer = $db->escapeString( $this->Volunteer );
        $instructions = $db->escapeString( $this->Instructions );
        
        if ( !isset( $this->ID ) )
        {
            $db->lock( "eZJob_Job" );
            $this->ID = $db->nextID( "eZJob_Job", "ID" );

            $res[] = $db->query( "INSERT INTO eZJob_Job
                    ( ID, Title, Description, Location, Salary, OrganisationID, URL, Volunteer, Instructions, CareerSectorID )
                    VALUES
                    ( '$this->ID', '$title', '$description', '$location', '$salary', '$this->OrganisationID',
                      '$url', '$volunteer', '$instructions', '$this->CareerSectorID' )" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZJob_Job
                    SET Title='$title',
                        Description='$description',
                        Location='$location',
                        Salary='$salary',
                        OrganisationID='$this->OrganisationID',
                        URL='$url',
                        Volunteer='$volunteer',
                        Instructions='$instructions',
                        CareerSectorID='$this->CareerSectorID'
                    WHERE ID='$this->ID'" );            

        }        
        eZDB::finish( $res, $db );
        return $dbError;
    }
  
    /*!
      Fetches an job with object id==$id;
    */  
    function get( $id="" )
    {
        $db =& eZDB::globalDatabase();
        if ( $id != "" )
        {
            $db->array_query( $job_array, "SELECT * FROM eZJob_Job WHERE ID='$id'" );
            if ( count( $job_array ) == 1 )
            {
                $this->fill( $job_array[0] );
            }
        }
    }

    /*!
      Extracts the information from the array and puts it in the object.
    */
    function fill( &$job_array )
    {
        $db =& eZDB::globalDatabase();
        
        $this->ID =& $job_array[ $db->fieldName( "ID" ) ];
        $this->Title =& $job_array[ $db->fieldName( "Title" ) ];
        $this->Description =& $job_array[ $db->fieldName( "Description" ) ];
        $this->Location =& $job_array[ $db->fieldName( "Location" ) ];
        $this->Salary =& $job_array[ $db->fieldName( "Salary" ) ];
        $this->OrganisationID =& $job_array[ $db->fieldName( "OrganisationID" ) ];
        $this->URL =& $job_array[ $db->fieldName( "URL" ) ];
        $this->Volunteer =& $job_array[ $db->fieldName( "Volunteer" ) ];
        $this->Instructions =& $job_array[ $db->fieldName( "Instructions" ) ];
        $this->CareerSectorID =& $job_array[ $db->fieldName( "CareerSectorID" ) ];
    }

    /*!
      Returns the total number of job names
    */
    function &getAllCount()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $jobs, "SELECT COUNT( ID ) as Count FROM eZJob_Job" );
        return $jobs[$db->fieldName( "Count" )];
    }

    /*!
      Returns the total number of job names to an id
    */
    function &getCountByID( $id = "" )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $languages, "SELECT ID FROM eZJob_Job ORDER BY Name" );
        $count = 0;
        if ( count( $languages ) > 0 )
        {
            foreach( $languages as $language )
            {
                if ( $language[$db->fieldName("ID")] == $id )
                {
                    break;
                }
                
                $count++;
            }
        }
        
        return $count;

    }
        
    /*!
      Returns every job as a eZJob object
    */
    function &getAll( $as_object = true, $search = "", $offset = 0, $max = -1 )
    {
        $db =& eZDB::globalDatabase();

        $job_array = 0;
        $return_array = array();
    
        if ( $max >= 0 && is_numeric( $offset ) && is_numeric( $max ) )
        {
            $limit = array( "Limit" => $max,
                            "Offset" => $offset );
        }

        if ( !empty( $search ) )
        {
            $query = new eZQuery( array( "Name" ), $search );
            $search_arg = "AND " . $query->buildQuery();
        }

        if ( $as_object )
            $select = "*";
        else
            $select = "ID";

        $db->array_query( $job_array, "SELECT $select FROM eZAddress_Language
                                           ORDER BY Name", $limit );
        
        if ( $as_object )
        {
            foreach ( $job_array as $job )
            {
                $return_array[] = new eZLanguage( $job[ $db->fieldName( "ID" ) ] );
            }
        }
        else
        {
            foreach ( $job_array as $job )
            {
                $return_array[] = $job[$db->fieldName( "ID" )];
            }
        }
        return $return_array;
    }

    /*!
      Returns every job as an array. This function is faster then the one above.
    */
    function &getAllArray( $offset = 0, $limit = -1 )
    {
        $db =& eZDB::globalDatabase();
        $job_array = 0;
        $return_array = array();

        if ( $limit >= 0 && is_numeric( $offset ) && is_numeric( $limit ) )
        {
            $limitArray = array( "Limit" => $limit,
                            "Offset" => $offset );
        }

        
        $db->array_query( $job_array, "SELECT * FROM eZAddress_Language
                                           ORDER BY Title", $limitArray );
        foreach ( $job_array as $job )
        {
            $return_array[] = array( "ID" => $job[$db->fieldName( "ID" )],
                                     "Title" => $job[$db->fieldName( "Title" )],
                                     "Description" => $job[$db->fieldName( "Description" )],
                                     "Location" => $job[$db->fieldName( "Location" )],
                                     "Salary" => $job[$db->fieldName( "Salary" )],
                                     "OrganisationID" => $job[$db->fieldName( "OrganisationID" )],
                                     "URL" => $job[$db->fieldName( "URL" )],
                                     "Volunteer" => $job[$db->fieldName( "Volunteer" )],
                                     "Instructions" => $job[$db->fieldName( "Instructions" )],
                                     "CareerSectorID" => $job[$db->fieldName( "CareerSectorID" )] );
        }
        return $return_array;
    }
    
    /*!
      delete address with ID == $id;
     */
    function delete( $id = false)
    {
        if ( !$id )
            $id = $this->ID;
        $db =& eZDB::globalDatabase();

        $db->begin();
        $res[] = $db->query( "DELETE FROM eZJob_Job WHERE ID='$id'" );
        eZDB::finish( $res, $db );
    }    

    /*!
      add type of work to the job.
    */
    function addTypeOfWork( $idArray )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZJob_JobWorkTypeLink WHERE JobID='$this->ID'" );

        if ( is_Array( $idArray ) && count( $idArray ) > 0 )
        {
            $db->lock( "eZJob_JobWorkTypeLink" );
            foreach ( $idArray as $idItem )
            {
                $newID = $db->nextID( "eZJob_JobWorkTypeLink", "ID" );

                $res[] = $db->query( "INSERT INTO eZJob_JobWorkTypeLink ( ID, JobID, WorkTypeID )
                              VALUES
                              ( $newID, $this->ID, $idItem )" );
            }
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Add related sectors to the job.
    */
    function addRelatedSector( $idArray )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZJob_JobRelatedSectorLink WHERE JobID='$this->ID'" );

        if ( is_Array( $idArray ) && count( $idArray ) > 0 )
        {
            $db->lock( "eZJob_JobRelatedSectorLink" );
            foreach ( $idArray as $idItem )
            {
                $newID = $db->nextID( "eZJob_JobRelatedSectorLink", "ID" );

                $res[] = $db->query( "INSERT INTO eZJob_JobRelatedSectorLink ( ID, JobID, WorkTypeID )
                              VALUES
                              ( $newID, $this->ID, $idItem )" );
            }
            eZDB::finish( $res, $db );
        }
    }

    /*!
      Add needed languages to the job.
    */
    function addLanguage( $idArray )
    {
        $db =& eZDB::globalDatabase();
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZJob_JobLanguageLink WHERE JobID='$this->ID'" );

        if ( is_Array( $idArray ) && count( $idArray ) > 0 )
        {
            $db->lock( "eZJob_JobLanguageLink" );
            foreach ( $idArray as $idItem )
            {
                $newID = $db->nextID( "eZJob_JobLanguageLink", "ID" );

                $res[] = $db->query( "INSERT INTO eZJob_JobLanguageLink ( ID, JobID, LanguageID )
                              VALUES
                              ( $newID, $this->ID, $idItem )" );
            }
            eZDB::finish( $res, $db );
        }
    }
    
    /*!
      Sets the title of the job.
    */
    function setTitle( $value )
    {
       $this->Title = $value;
    }

    /*!
      Set the description for the job
     */
    function setDescription( $value )
    {
        $this->Description = $value;
    }

    /*!
      Set the career sector id for the job
     */
    function setCareerSectorID( $value )
    {
        $this->CareerSectorID = $value;
    }
    
    /*!
      Set the url for the job
     */
    function setURL( $value )
    {
        $this->URL = $value;
    }

    /*!
      Set the salary for the job
     */
    function setSalary( $value )
    {
        $this->Salary = $value;
    }

    /*!
      Set the instructions for the job
     */
    function setInstructions( $value )
    {
        $this->Instructions = $value;
    }
        
    /*!
      Returns the object ID.
    */
    function id()
    {
        return $this->ID;
    }
    
    /*!
      Returns the title of the job.
    */
    function title()
    {
        return $this->Title;
    }



    var $ID;
    var $Title;
    var $Description;
    var $Location;
    var $Salary;
    var $OrganisationID;
    var $URL;
    var $Volunteer;
    var $Instructions;
}

?>
