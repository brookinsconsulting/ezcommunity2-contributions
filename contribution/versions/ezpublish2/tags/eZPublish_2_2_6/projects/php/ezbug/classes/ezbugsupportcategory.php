<?php
//
// $Id: ezbugsupportcategory.php,v 1.2 2001/12/04 14:14:28 jhe Exp $
//
// Definition of eZBugSupportCategory class
//
// Created on: <05-Nov-2001 14:35:41 jhe>
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
/*!

*/

class eZBugSupportCategory
{
    function eZBugSupportCategory( $id = -1 )
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

    function fill( &$support_array )
    {
        $db =& eZDB::globalDatabase();
        $this->ID = $support_array[$db->fieldName( "ID" )];
        $this->Name = $support_array[$db->fieldName( "Name" )];
        $this->BugModuleID = $support_array[$db->fieldName( "BugModuleID" )];
        $this->Email = $support_array[$db->fieldName( "Email" )];
        $this->ReplyTo = $support_array[$db->fieldName( "ReplyTo" )];
        $this->Password = $support_array[$db->fieldName( "Password" )];
        $this->MailServer = $support_array[$db->fieldName( "MailServer" )];
        $this->MailServerPort = $support_array[$db->fieldName( "MailServerPort" )];
        $this->SupportNo = $support_array[$db->fieldName( "SupportNo" )];
    }
    
    function store()
    {
        $db =& eZDB::globalDatabase();
        $name = $db->escapeString( $this->Name );
        $email = $db->escapeString( $this->Email );
        $password = $db->escapeString( $this->Password );
        $mailserver = $db->escapeString( $this->MailServer );
        $replyTo = $db->escapeString( $this->ReplyTo );
        $db->begin();
        if ( !isSet( $this->ID ) )
        {
            $db->lock( "eZBug_SupportCategory" );
			$this->ID = $db->nextID( "eZBug_SupportCategory", "ID" );

            $res[]= $db->query( "INSERT INTO eZBug_SupportCategory
                                            (ID,
                                             Name,
                                             BugModuleID,
                                             Email,
                                             ReplyTo,
                                             Password,
                                             MailServer,
                                             MailServerPort,
                                             SupportNo)
                                           VALUES
                                            ('$this->ID',
                                             '$name',
                                             '$this->BugModuleID',
                                             '$email',
                                             '$replyTo',
                                             '$password',
                                             '$mailserver',
                                             '$this->MailServerPort',
                                             '$this->SupportNo')" );
            $db->unlock();
        }
        else
        {
            $res[] = $db->query( "UPDATE eZBug_SupportCategory SET
		                        Name='$name',
                                BugModuleID='$this->BugModuleID',
                                Email='$this->Email',
                                ReplyTo='$replyTo',
                                Password='$password',
                                MailServer='$mailserver',
                                MailServerPort='$this->MailServerPort',
                                SupportNo='$this->SupportNo'
                                WHERE ID='$this->ID'" );
        }

        eZDB::finish( $res, $db );
        return true;
    }

    function delete( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id == -1 && isSet( $this->ID ) )
        {
            $id = $this->ID;
        }
        
        $db->begin();
        $res[] = $db->query( "DELETE FROM eZBug_SupportCategory WHERE ID='$id'" );

        eZDB::finish( $res, $db );
        return true;
    }
    
    /*!
      Fetches the object information from the database.
    */
    function get( $id = -1 )
    {
        $db =& eZDB::globalDatabase();
        
        if ( $id == -1 )
        {
            $id = $this->ID;
        }
        
        $db->array_query( $module_array, "SELECT * FROM eZBug_SupportCategory WHERE ID='$id'" );
        if ( count( $module_array ) > 1 )
        {
            die( "Error: Bugs with the same ID was found in the database. This shouldent happen." );
        }
        else if ( count( $module_array ) == 1 )
        {
            $this->ID =& $module_array[0][$db->fieldName( "ID" )];
            $this->Name =& $module_array[0][$db->fieldName( "Name" )];
            $this->BugModuleID =& $module_array[0][$db->fieldName( "BugModuleID" )];
            $this->Email =& $module_array[0][$db->fieldName( "Email" )];
            $this->ReplyTo =& $module_array[0][$db->fieldName( "ReplyTo" )];
            $this->Password =& $module_array[0][$db->fieldName( "Password" )];
            $this->MailServer =& $module_array[0][$db->fieldName( "MailServer" )];
            $this->MailServerPort =& $module_array[0][$db->fieldName( "MailServerPort" )];
            $this->SupportNo =& $module_array[0][$db->fieldName( "SupportNo" )];
        }
    }

    function getAll( $offset = 0, $limit = 10 )
    {
        $db =& eZDB::globalDatabase();

        $db->array_query( $support_array, "SELECT * FROM eZBug_SupportCategory ORDER BY Name",
                          array( "Limit" => $limit, "Offset" => $offset ) );
        $return_array = array();
        if ( count( $support_array ) > 0 )
        {
            foreach ( $support_array as $supportItem )
            {
                $return_array[] = new eZBugSupportCategory( $supportItem );
            }
        }
        return $return_array;
    }

    function getAllCount()
    {
        $db =& eZDB::globalDatabase();

        $db->query_single( $result, "SELECT Count(ID) as Count FROM eZBug_SupportCategory" );
        return $result[$db->fieldName( "Count" )];
    }
    
    /*!
      Returns the object id.
    */
    function id()
    {
        return $this->ID;
    }

    /*!
      Returns the name of the bug.
    */
    function name()
    {
        return $this->Name;
    }

    function setName( $name )
    {
        $this->Name = $name;
    }

    function bugModuleID()
    {
        return $this->BugModuleID;
    }

    function setBugModuleID( $id )
    {
        $this->BugModuleID = $id;
    }

    function email()
    {
        return $this->Email;
    }

    function setEmail( $email )
    {
        $this->Email = $email;
    }

    function password()
    {
        return $this->Password;
    }

    function setPassword( $password )
    {
        $this->Password = $password;
    }

    function mailServer()
    {
        return $this->MailServer;
    }

    function setMailServer( $server )
    {
        $this->MailServer = $server;
    }

    function mailServerPort()
    {
        return $this->MailServerPort;
    }

    function setMailServerPort( $port )
    {
        $this->MailServerPort = $port;
    }
    
    function supportNo()
    {
        if ( $this->SupportNo == 1 )
            return true;
        else
            return false;
    }

    function setSupportNo( $support )
    {
        if ( $support )
            $this->SupportNo = 1;
        else
            $this->SupportNo = 0;
    }
    
    function replyTo()
    {
        return $this->ReplyTo;
    }

    function setReplyTo( $email )
    {
        $this->ReplyTo = $email;
    }

    var $ID;
    var $Name;
    var $BugModuleID;
    var $Email;
    var $Password;
    var $MailServer;
    var $MailServerPort;
    var $SupportNo;    
    var $ReplyTo;
}

?>
