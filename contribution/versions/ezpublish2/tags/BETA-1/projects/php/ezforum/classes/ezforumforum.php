<?
/*!
    $Id: ezforumforum.php,v 1.9 2000/08/29 12:08:53 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:02:57 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
//include("ezforum/dbsettings.php");
//include_once("$DOCROOT/classes/ezdb.php");

class eZforumForum
{
    var $Id;
    var $CategoryId;
    var $Name;
    var $Description;
    var $Moderated;
    var $Private;
        
    function get( $Id )
    {
        $this->openDB();
        
        $query_id = mysql_query("SELECT CategoryId, Name, Description, Moderated, Private FROM ezforum_ForumTable WHERE Id='$Id'")
             or die("eZforumForum::get($Id) failed, dying...");    
            
        $this->Id = $Id;
        $this->CategoryId = mysql_result($query_id,0,"CategoryId");
        $this->Name = mysql_result($query_id,0,"Name");
        $this->Description = mysql_result($query_id,0,"Description");
        $this->Moderated = mysql_result($query_id,0,"Moderated");
        $this->Private = mysql_result($query_id,0,"Private");
    }
        
    function newForum()
    {
        unset($this->Id);
    }
        
    function getAllForums( $CategoryId = "" )
    {
        $this->openDB();
        
        if ($CategoryId)
        {
            $query_id = mysql_query( "SELECT * FROM ezforum_ForumTable WHERE CategoryId='$CategoryId'" )
                 or die( "getAllForums() near select all." );
        }
        else
        {
            $query_id = mysql_query( "SELECT * FROM ezforum_ForumTable" )
                 or die("getAllForums()");
        }
            
        for ($i = 0; $i < mysql_num_rows( $query_id ); $i++)
        {
            $resultArray[$i] = mysql_fetch_array( $query_id );
        }
            
        return $resultArray;
    }
        
    function store()
    {
        global $PREFIX;
        
        $this->openDB();
            
        $this->CategoryId = addslashes( $this->CategoryId );
        $this->Name = addslashes( $this->Name );
        $this->Description = addslashes( $this->Description );
        $this->Moderated = addslashes( $this->Moderated );
        $this->Private = addslashes( $this->Private );
            
        if ($this->Id)
        {
            //update
            $query_id = mysql_query("UPDATE ezforum_ForumTable SET CategoryId='$this->CategoryId',
                                                               Name='$this->Name',
                                                               Description='$this->Description',
                                                               Moderated='$this->Moderated',
                                                               Private='$this->Private'
                                         WHERE Id='$this->Id'")
                 or die("store() near update");
                
            return $this->Id;
        }
        else
        {
                
            $query_id = mysql_query("INSERT INTO ezforum_ForumTable(CategoryId,
                                                                Name,
                                                                Description,
                                                                Moderated,
                                                                Private)
                                                         VALUES('$this->CategoryId',
                                                                '$this->Name',
                                                                '$this->Description',
                                                                '$this->Moderated',
                                                                '$this->Private') ")
                 or die("store() near insert");
            return mysql_insert_id();
        }            
    }
        
    function delete($Id)
    {
        $this->openDB();
            
        mysql_query("DELETE FROM ezforum_ForumTable WHERE Id='$Id'")
            or die("delete()");
    }
    
    function id()
    {
        return $this->Id;
    }
        
    function categoryId()
    {
        return $this->CategoryId;
    }
        
    function setCategoryId($newCategoryId)
    {
        $this->CategoryId = $newCategoryId;
    }
        
    function name()
    {
        return $this->Name;
    }
        
    function setName($newName)
    {
        $this->Name = $newName;
    }
        
    function description()
    {
        return $this->Description;
    }
        
    function setDescription($newDescription)
    {
        $this->Description = $newDescription;
    }
        
    function moderated()
    {
        return $this->Moderated;
    }
        
    function setModerated($newModerated)
    {
        $this->Moderated = $newModerated;
    }
        
    function private()
    {
        return $this->Private;
    }
        
    function setPrivate($newPrivate)
    {
        $this->Private = $newPrivate;
    }

    /*!
      Privat funksjon, skal kun brukes ac ezuser klassen.
      Funksjon for å åpne databasen.
    */
    function openDB( )
    {
        include_once( "class.INIFile.php" );

        $ini = new INIFile( "site.ini" );
        
        $SERVER = $ini->read_var( "site", "Server" );
        $DATABASE = $ini->read_var( "site", "Database" );
        $USER = $ini->read_var( "site", "User" );
        $PWD = $ini->read_var( "site", "Password" );
        
        mysql_pconnect( $SERVER, $USER, $PWD ) or die( "Kunne ikke kople til database" );
        mysql_select_db( $DATABASE ) or die( "Kunne ikke velge database" );
    }
    
}
?>
