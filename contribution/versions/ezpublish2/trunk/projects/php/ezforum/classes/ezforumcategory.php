<?
/*!
    $Id: ezforumcategory.php,v 1.2 2000/07/14 13:07:07 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:01:24 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
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
        openDB();
            
        $Id = addslashes($Id);
            
        $query_id = mysql_query("SELECT Name, Description, Private FROM CategoryTable WHERE Id='$Id'")
             or die("eZforumCategory::get($id) failed, dying...");
            
        $this->id = $Id;
        $this->Name = mysql_result($query_id, 0, "Name" );
        $this->Description = mysql_result($query_id, 0, "Description" );
        $this->Private = mysql_result($query_id, 0, "Private" );
    }
        
    function getAllCategories()
    {
        openDB();
            
        $query_id = mysql_query( "SELECT * FROM CategoryTable" )
             or die("getAllCategories");
            
        for ($i = 0;$i < mysql_num_rows( $query_id ); $i++ )
        {
            $returnArray[$i] = mysql_fetch_array($query_id);   
        }
        return $returnArray;
    }
        
    function store()
    {
        openDB();
            
        $this->Name = addslashes($this->Name);
        $this->Description = addslashes($this->Description);
        $this->Private = addslashes($this->Private);            
        
        if ($this->Id)
        {
            $query_id = mysql_query("UPDATE CategoryTable SET Name='$this->Name',
                                                             Description='$this->Description',
                                                             Private='$this->Private'
                                         WHERE Id='$this->Id'")
                 or die("store() near UPDATE...");
        }
        else
        {
            $query_id = mysql_query("INSERT INTO CategoryTable(Name, Description, Private)
                                                     VALUES('$this->Name', '$this->Description', '$this->Private')")
                 or die("store() near INSERT...");
            return mysql_insert_id();
        }
    }
        
    function delete($Id)
    {
        openDB();
        
        mysql_query("DELETE FROM CategoryTable WHERE Id='$Id'")
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
}
?>
