<?
/*!
    $Id: ezforumcategory.php,v 1.5 2000/07/26 17:03:13 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:01:24 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include_once( "ezforum/dbsettings.php" );
include_once( "$DOCROOT/classes/ezdb.php" );

class eZforumCategory
{
    var $Id;
    var $Name;
    var $Description;
    var $Private;
        
    function newCategory()
    {
        unset($this->Id);
    }
        
    function get($Id)
    {
        global $PREFIX;
        
        openDB();
            
        $Id = addslashes($Id);
            
        $query_id = mysql_query("SELECT Name, Description, Private FROM $PREFIX"."CategoryTable WHERE Id='$Id'")
             or die("eZforumCategory::get($id) failed, dying...");
            
        $this->id = $Id;
        $this->Name = mysql_result($query_id, 0, "Name" );
        $this->Description = mysql_result($query_id, 0, "Description" );
        $this->Private = mysql_result($query_id, 0, "Private" );
    }
        
    function getAllCategories()
    {
        global $PREFIX;
        
        openDB();
        $query_id = mysql_query( "SELECT * FROM $PREFIX" . "CategoryTable" )
             or die("eZforumCategory::getAllCategories() failed, dying...");
            
        for ($i = 0;$i < mysql_num_rows( $query_id ); $i++ )
        {
            $returnArray[$i] = mysql_fetch_array($query_id);   
        }
        return $returnArray;
    }
        
    function store()
    {
        global $PREFIX;
        
        openDB();
            
        $this->Name = addslashes($this->Name);
        $this->Description = addslashes($this->Description);
        $this->Private = addslashes($this->Private);            
        
        if ($this->Id)
        {
            $query_id = mysql_query("UPDATE $PREFIX"."CategoryTable SET Name='$this->Name',
                                                             Description='$this->Description',
                                                             Private='$this->Private'
                                         WHERE Id='$this->Id'")
                 or die("store() near UPDATE...");
        }
        else
        {
            $query_id = mysql_query("INSERT INTO $PREFIX"."CategoryTable(Name, Description, Private)
                                                     VALUES('$this->Name', '$this->Description', '$this->Private')")
                 or die("store() near INSERT...");
            return mysql_insert_id();
        }
    }
        
    function delete($Id)
    {
        global $PREFIX;
        
        openDB();
        
        mysql_query("DELETE FROM $PREFIX"."CategoryTable WHERE Id='$Id'")
            or die("delete($Id)");
    }
        
    function id()
    {
        return $this->Id;
    }
        
    function setName($newName)
    {
        $this->Name = $newName;
    }

    function name()
    {
        return $this->Name;
    }
        
    function setDescription($newDescription)
    {
        $this->Description = $newDescription;
    }
        
    function description()
    {
        return $this->Description;
    }
        
    function setPrivate($newPrivate)
    {
        $this->Private = $newPrivate;
    }
        
    function private()
    {
        return $this->Private;
    }

    function categoryForumInfo($Id)
    {
        global $PREFIX;
        
        openDB();
    
        $query_id = mysql_query("SELECT $PREFIX"."ForumTable.Name AS ForumName,
                                $PREFIX"."CategoryTable.Name AS CategoryName
                                FROM $PREFIX"."ForumTable, $PREFIX"."CategoryTable
                                WHERE $PREFIX"."CategoryTable.Id = $PREFIX"."ForumTable.CategoryId
                                AND $PREFIX"."ForumTable.Id = '$Id'")
             or die("categoryForumInfo()");
        
        return mysql_fetch_array($query_id);
    }
}
?>
