<?
/*!
    $Id: ezforumforum.php,v 1.4 2000/07/25 09:59:15 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:02:57 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include("ezforum/dbsettings.php");
include("$DOCROOT/classes/ezdb.php");

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
        openDB();
        
        $query_id = mysql_query("SELECT CategoryId, Name, Description, Moderated, Private FROM ForumTable WHERE Id='$Id'")
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
        openDB();
        
        if ($CategoryId)
        {
            $query_id = mysql_query( "SELECT * FROM ForumTable WHERE CategoryId='$CategoryId'" )
                 or die( "getAllForums() near select all." );
        }
        else
        {
            $query_id = mysql_query( "SELECT * FROM ForumTable" )
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
        openDB();
            
        $this->CategoryId = addslashes( $this->CategoryId );
        $this->Name = addslashes( $this->Name );
        $this->Description = addslashes( $this->Description );
        $this->Moderated = addslashes( $this->Moderated );
        $this->Private = addslashes( $this->Private );
            
        if ($this->Id)
        {
            //update
            $query_id = mysql_query("UPDATE ForumTable SET CategoryId='$this->CategoryId',
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
                
            $query_id = mysql_query("INSERT INTO ForumTable(CategoryId,
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
        openDB();
            
        mysql_query("DELETE FROM ForumTable WHERE Id='$Id'")
            or die("delete()");
    }
        
    function categoryId()
    {
        return $this->Id;    
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
}
?>
