<?php 
/////////////////////////////////////////////////////////////////////////  
//  
//  class.INIfile.php  -  implements  a  simple  INIFile Parser  
//   
//  Author:  MO 
//   
//  Description:  
//    I just wondered how to save simple parameters not in a database but in a file 
//  So starting every time from scratch isn''t comfortable and I decided to write this 
//  small unit for working with ini like files 
//  Some  Examples:  
//     
//    $ini = new INIFile("./ini.ini"); 
//  //Read entire group in an associative array 
//    $grp = $ini->read_group("MAIN"); 
//    //prints the variables in the group 
//    if ($grp) 
//    for(reset($grp); $key=key($grp); next($grp)) 
//    { 
//        echo "GROUP ".$key."=".$grp[$key]."<br>"; 
//    } 
//    //set a variable to a value 
//    $ini->set_var("NEW","USER","JOHN"); 
//  //Save the file 
//    $ini->save_data(); 
//
//    Modified by Jo Henrik Endrerud <jhe@ez.no> for eZ systems
//    Modified by Bård Farstad <bf@ez.no>

//!! eZCommon
//! The INIFile class provides .ini file functions.
/*!

  \code
  include_once( "classes/INIFile.php" );
  $ini = new INIFile( "site.ini" );

  $PageCaching = $ini->read_var( "eZArticleMain", "PageCaching" );

  $arrayTest = $ini->read_array( "site", "ArrayTest" );

  foreach ( $arrayTest as $test )
  {
    print( "test: ->$test<-<br>" );
  }  
  \endcode
*/

class INIFile
{ 

    /*!
      Constructs a new INIFile object.
    */
    function INIFile( $inifilename="", $write=true )
    {
        // echo "INIFile::INIFile( \$inifilename = $inifilename,\$write = $write )<br />\n";
        
        $this->WRITE_ACCESS = $write;
        if ( !empty($inifilename) )
        {
            if ( !file_exists($inifilename) )
            { 
                $this->error( "This file ($inifilename) does not exist!"); 
                return; 
            }
        }
        $this->parse($inifilename);
    } 


    /*!
      Parses the ini file.
    */
    function parse( $inifilename )
    { 
        $this->INI_FILE_NAME = $inifilename;
        
        if( $this->WRITE_ACCESS )
            $fp = fopen( $inifilename, "r+" ); 
        else
            $fp = fopen( $inifilename, "r" );
        
        $contents =& fread($fp, filesize($inifilename)); 
        $ini_data =& split( "\n",$contents); 
         
        while( list($key, $data) = each($ini_data) ) 
        { 
            $this->parse_data($data); 
        } 
         
        fclose( $fp ); 
    } 

    /*!
      Parses the variabled.
    */
    function parse_data( $data ) 
    { 
        if( ereg( "\[([[:alnum:]]+)\]",$data,$out) ) 
        { 
            $this->CURRENT_GROUP=$out[1]; 
        } 
        else 
        { 
            $split_data =& split( "=", $data); 
            $this->GROUPS[$this->CURRENT_GROUP][$split_data[0]]=$split_data[1]; 
        } 
    } 

    /*!
      Saves the ini file.
    */
    function save_data() 
    {
        $fp = fopen($this->INI_FILE_NAME, "w");

        if ( empty($fp) ) 
        { 
            $this->Error( "Cannot create file $this->INI_FILE_NAME"); 
            return false; 
        } 
         
        $groups = $this->read_groups(); 
        $group_cnt = count($groups); 
         
        for($i=0; $i<$group_cnt; $i++) 
        { 
            $group_name = $groups[$i];
            if ( $i == 0 )
            {
                $res = sprintf( "[%s]\n",$group_name);
            }
            else
            {
                $res = sprintf( "\n[%s]\n",$group_name);
            }
            fwrite($fp, $res); 
            $group = $this->read_group($group_name); 
            for(reset($group); $key=key($group);next($group)) 
            { 
                $res = sprintf( "%s=%s\n",$key,$group[$key]); 
                fwrite($fp,$res); 
            } 
        } 
         
        fclose($fp); 
    } 

    /*!
      Returns the number of groups.
    */
    function get_group_count() 
    { 
        return count($this->GROUPS); 
    } 
     
    /*!
      Returns an array with the names of all the groups.
    */
    function read_groups() 
    { 
        $groups = array(); 
        for (reset($this->GROUPS);$key=key($this->GROUPS);next($this->GROUPS)) 
            $groups[]=$key; 
        return $groups; 
    } 
     
    /*!
      Checks if a group exists.
    */
    function group_exists( $group_name )
    { 
        $group =& $this->GROUPS[$group_name]; 
        if (empty($group)) return false; 
        else return true; 
    } 

    /*!
      Returns an associative array of the variables in one group.
    */
    function read_group($group) 
    { 
        $group_array =& $this->GROUPS[$group]; 
        if(!empty($group_array))  
            return $group_array; 
        else  
        { 
            $this->Error( "Group $group does not exist"); 
            return false; 
        } 
    } 
     
    /*!
      Adds a new group to the ini file.
    */
    function add_group($group_name) 
    { 
        $new_group = $this->GROUPS[$group_name]; 
        if ( empty($new_group) ) 
        { 
            $this->GROUPS[$group_name] = array(); 
        } 
        else
        {
            $this->Error( "Group $group_name exists");
        }
    } 

    /*!
      Clears a group.
    */
    function empty_group($group_name) 
    { 
        unset( $this->GROUPS[$group_name] );
        $this->GROUPS[$group_name] = array();
    } 

    /*!
      Reads a variable from a group.
    */
    function read_var( $group, $var_name )
    { 
        $var_value =& $this->GROUPS[$group][$var_name]; 
        if ( !empty($var_value) )
        {
            return $var_value;
        }
        else 
        { 
            $this->Error( "$var_name does not exist in $group"); 
            return false; 
        } 
    }

    /*!
      Reads a variable from a group and returns the result as an
      array of strings.

      The variable is splitted on ; characters.
    */
    function read_array( $group, $var_name )
    { 
        $var_value =& $this->GROUPS[$group][$var_name]; 
        if ( !empty($var_value) )
        {
            $var_array =& explode( ";", $var_value );
            return $var_array;
        }
        else 
        { 
            $this->Error( "$var_name does not exist in $group"); 
            return false; 
        } 
    }
     
    /*!
      Sets a variable in a group.
    */
    function set_var( $group, $var_name, $var_value )
    {
        $this->GROUPS[$group][$var_name] = $var_value;
    }     


    /*!
      Prints the error message.
    */
    function error($errmsg) 
    { 
        $this->ERROR = $errmsg; 
        echo  "Error:".$this->ERROR. "<br>\n"; 
        return; 
    }

    var $INI_FILE_NAME =  ""; 
    var $ERROR =  ""; 
    var $GROUPS = array(); 
    var $CURRENT_GROUP =  "";
    var $WRITE_ACCESS = ""; 
    
} 

?>
