<?php
// 
// $Id: eztemplate.php,v 1.21 2001/01/24 10:45:48 jb Exp $
//
// Definition of eZTemplate class
//
// Lars Wilhelmsen <lw@ez.no>
// Created on: <11-Sep-2000 22:10:06 bf>
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
//
// IMPORTANT NOTE: You may NOT copy this file or any part of it into
// your own programs or libraries.
//

include_once( "classes/INIFile.php" );

//!! eZCommon
//! The eZTemplate class provides template functions. In regard to locale information.
/*!
  This class provides functions for using templates with internationalized language.
  Template variables which start with intl- are looked up in the language file and
  replaced with text in the described language.
    
*/

class eZTemplate
{

    /*!
      Constructs a new eZTemplate object.
    */
    function eZTemplate( $templateDir = "", $intlDir = "", $language = "", $phpFile = "", $style = false, $module_dir = false, $state = false )
    {
        $this->intlDir =& $intlDir;
        $this->language =& $language;
        $this->phpFile =& $phpFile;
        $this->set_root($templateDir);
        $this->set_unknowns("remove");
        $this->style = $style;
        $this->module_dir = $module_dir;
        $this->TextStrings = array();
        $this->state = $state;

        $this->languageFile = $intlDir . "/" . $language . "/" . $phpFile . ".ini";
        if ( file_exists( $this->languageFile ) )
        {
            $this->ini = new INIFile( $intlDir . "/" . $language . "/" . $phpFile . ".ini", false );
            $this->TextStrings = $this->ini->read_group( "strings" );
        }
        else
        {
            print( "<br><b>Error: language file, $this->languageFile, could not be found.</b><br>" );
        }

        if ( empty( $this->style ) || empty( $this->module_dir ) )
        {
            $this->CacheSuffix = "";
            $this->CacheDir = "";
        }
        else
        {
            $this->CacheDir = $module_dir . "/cache";
            if ( !empty( $state ) )
                $state = "-" . $state;
            $this->CacheSuffix = $style ."-" . $language . $state . ".cache";
        }
    }

    /*!
      Sets all internationalisations.
    */
    function setAllStrings()
    {
        reset( $this->TextStrings );
        $tmp =& each( $this->TextStrings );
        while( $tmp )
        {
            $tmp_key = "intl-" . $tmp[0];
            $this->set_var_internal( $tmp_key, $tmp[1] );
            $tmp =& each( $this->TextStrings );
        }
    }

    function &cacheFile()
    {
        $CacheFile = $this->CacheDir . "/" . $this->files[0] . "-" . $this->CacheSuffix;
        return $CacheFile;
    }

    function hasCache()
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        if ( empty( $this->files[0] ) )
            return false;
        $CacheFile =& $this->cacheFile();
        if ( file_exists( $CacheFile ) )
        {
            $template_m = filemtime( $this->filename( $this->files[0] ) );
            $lang_m = filemtime( $this->languageFile );
            $cache_m = filemtime( $CacheFile );
            if ( $template_m <= $cache_m && $lang_m <= $cache_m )
                return true;
        }
        return false;
    }

    function &cache()
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        if ( empty( $this->files[0] ) )
            return false;
        $CacheFile =& $this->cacheFile();
        if ( file_exists( $CacheFile ) )
        {
            $fd = fopen( $CacheFile, "r" );
            $str =& fread( $fd, filesize( $CacheFile ) );
            fclose( $fd );
            return $str;
        }
        return false;
    }

    function &storeCache( $target, $handle, $print = true )
    {
        if ( empty( $this->CacheSuffix ) )
            return false;
        $str =& $this->parse( $target, $handle );
        $CacheFile =& $this->cacheFile();
        if ( !file_exists( $this->CacheDir ) )
        {
            print( "<br /><b>TemplateCache: directory $this->CacheDir does not exist, cannot create cache file</b><br />" );
        }
        else
        {
            $fd = fopen( $CacheFile, "w" );
            fwrite( $fd, $str );
            fclose( $fd );
        }
        if ( $print )
        {
            print $str;
        }
        return $str;
    }

    function &translate( $key )
    {
        if ( isset( $this->varvals[$key] ) )
        {
            return $this->varvals[$key];
        }
        else
        {
            return $key;
        }
    }

    /*!
      Returns a reference to the ini file object.
    */
    function &ini()
    {
        return $this->ini;
    }
    
    var $TextStrings;
    var $ini;
    var $style;
    var $module_dir;
    var $state;
    var $languageFile;
    var $CacheSuffix;
    var $CacheDir;
    var $files = array();

    var $classname = "Template";

    /* if set, echo assignments */
    var $debug     = false;

    /* $file[handle] = "filename"; */
    var $file  = array();

    /* relative filenames are relative to this pathname */
    var $root   = "";

    /* $varkeys[key] = "key"; $varvals[key] = "value"; */
    var $varkeys = array();
    var $varvals = array();

    /* "remove"  => remove undefined variables
     * "comment" => replace undefined variables with comments
     * "keep"    => keep undefined variables
     * "nbsp"    => replace all undefined variables with &nbsp; (very nice in tables with bg color)
     */
    var $unknowns = "remove";
  
    /* "yes" => halt, "report" => report error, continue, "no" => ignore error quietly */
    var $halt_on_error  = "report";
  
    /* last error message is retained here */
    var $last_error     = "";

    /* public: setroot(pathname $root)
     * root:   new template directory.
     */  
    function set_root($root)
    {
        if (!is_dir($root)) {
            $this->halt("set_root: $root is not a directory.");
            return false;
        }
    
        $this->root = $root;
        return true;
    }

  /* public: set_unknowns(enum $unknowns)
   * unknowns: "remove", "comment", "keep", "nbsp"
   *
   */
  function set_unknowns($unknowns = "keep") {
    $this->unknowns = $unknowns;
  }

  /* public: set_file(array $filelist)
   * filelist: array of handle, filename pairs.
   *
   * public: set_file(string $handle, string $filename)
   * handle: handle for a filename,
   * filename: name of template file
   */
    function set_file($handle, $filename = "")
    {
        if (!is_array($handle))
        {
            if ($filename == "")
            {
                $this->halt("set_file: For handle $handle filename is empty.");
                return false;
            }
            $this->file[$handle] = $this->filename($filename);
            $this->files = array();
            $this->files[0] = $filename;
        }
        else
        {
            reset($handle);
            while(list($h, $f) = each($handle))
            {
                $this->file[$h] = $this->filename($f);
                $this->files = array();
                $this->files[] = $f;
            }
        }
    }

  /* public: set_block(string $parent, string $handle, string $name = "")
   * extract the template $handle from $parent, 
   * place variable {$name} instead.
   */
    function set_block($parent, $handle, $name = "")
    {
        if (!$this->loadfile($parent)) {
            $this->halt("subst: unable to load $parent.");
            return false;
        }
        if ($name == "")
            $name = $handle;

        $str = $this->get_var($parent);
        $reg = "/<!--\s+BEGIN $handle\s+-->(.*)\n\s*<!--\s+END $handle\s+-->/sm";
        preg_match($reg, $str, $m);
        $str =& preg_replace($reg, "{" . "$name}", $str);
        $this->set_var_internal($handle, $m[1]);
        $this->set_var_internal($parent, $str);
    }

    /* public: set_var(array $values)
     * values: array of variable name, value pairs.
     *
     * public: set_var(string $varname, string $value)
     * varname: name of a variable that is to be defined
     * value:   value of that variable
     */
    function set_var( $varname, $value = "")
    {
        $this->set_var_internal( $varname, $value );
    }

    function set_var_internal( &$varname, &$value )
    {
        if (!is_array($varname))
        {
            $this->varkeys[$varname] =& preg_quote("/{".$varname."}/");
            $this->varvals[$varname] =& $value;
//              $var = "{".$varname."}";
//              $this->varkeys[$varname] =& $value;
//              $this->varvals[$var] =& $value;
        }
        else
        {
            reset($varname);
            while(list($k, $v) = each($varname))
            {
                $this->varkeys[$k] =& preg_quote("/{".$k."}/");
                $this->varvals[$k] =& $v;
//                  $var = "{".$k."}";
//                  $this->varkeys[$k] =& $v;
//                  $this->varvals[$var] =& $v;
            }
        }
    }

    /* public: subst(string $handle)
     * handle: handle of template where variables are to be substituted.
     */
    function &subst($handle)
    {
        if (!$this->loadfile($handle))
        {
            $this->halt("subst: unable to load $handle.");
            return false;
        }

        $str = $this->get_var($handle);
        $str =& preg_replace( $this->varkeys, $this->varvals, $str);
//          $str =& strtr( $str, $this->varvals );
        return $str;
    }
  
  /* public: psubst(string $handle)
   * handle: handle of template where variables are to be substituted.
   */
    function psubst($handle)
    {
        print $this->subst($handle);
    
        return false;
    }

    /* public: parse(string $target, string $handle, boolean append)
     * public: parse(string $target, array  $handle, boolean append)
     * target: handle of variable to generate
     * handle: handle of template to substitute
     * append: append to target handle
     */
    function &parse( $target, $handle, $append = false )
    {
        if (!is_array($handle))
        {
            $str =& $this->subst($handle);
            if ($append)
            {
                $tmp = $this->get_var($target) . $str;
                $this->set_var_internal($target, $tmp );
            }
            else
            {
                $this->set_var_internal($target, $str);
            }
        }
        else
        {
            reset($handle);
            while(list($i, $h) = each($handle))
            {
                $str =& $this->subst($h);
                $this->set_var_internal($target, $str);
            }
        }
        return $str;
    }
  
    function pparse($target, $handle, $append = false)
    {
        print $this->parse($target, $handle, $append);
        return false;
    }
  
  /* public: get_vars()
   */
    function &get_vars()
    {
        reset($this->varkeys);
        while(list($k, $v) = each($this->varkeys))
            while(list($k, $v) = each($this->varkeys))
            {
                $result[$k] = $v;
            }
    
        return $result;
//          return $this->varkeys;
    }

    /* public: get_var(string varname)
     * varname: name of variable.
     *
     * public: get_var(array varname)
     * varname: array of variable names
     */
    function &get_var($varname)
    {
        if (!is_array($varname))
        {
            return $this->varvals[$varname];
//              return $this->varkeys[$varname];
        }
        else
        {
            reset($varname);
            while(list($k, $v) = each($varname))
            {
                $result[$k] =& $this->varvals[$k];
//                  $result[$k] =& $this->varkeys[$k];
            }
            return $result;
        }
    }
  
  /* public: get_undefined($handle)
   * handle: handle of a template.
   */
    function get_undefined($handle)
    {
        if (!$this->loadfile($handle))
        {
            $this->halt("get_undefined: unable to load $handle.");
            return false;
        }

        preg_match_all("/\{([^}]+)\}/", $this->get_var($handle), $m);
        $m = $m[1];
        if (!is_array($m))
            return false;

        reset($m);
        while(list($k, $v) = each($m))
        {
          if (!isset($this->varkeys[$v]))
              $result[$v] = $v;
        }
    
        if (count($result))
            return $result;
        else
            return false;
    }

  /* public: finish(string $str)
   * str: string to finish.
   */
    function finish($str)
    {
        switch ($this->unknowns)
        {
            case "keep":
                break;
      
            case "remove":
                $str = preg_replace('/{[^ \t\r\n}]+}/', "", $str);
            break;

            case "comment":
                $str = preg_replace('/{([^ \t\r\n}]+)}/', "<!-- Template $handle: Variable \\1 undefined -->", $str);
            break;
      
            case "nbsp":
                $str = preg_replace('/{[^ \t\r\n}]+}/', "&nbsp;", $str);
        }
    
        return $str;
    }

  /* public: p(string $varname)
   * varname: name of variable to print.
   */
  function p($varname) {
    print $this->finish($this->get_var($varname));
  }

  function get($varname) {
    return $this->finish($this->get_var($varname));
  }
    
  /***************************************************************************/
  /* private: filename($filename)
   * filename: name to be completed.
   */
  function filename($filename) {
    if (substr($filename, 0, 1) != "/") {
      $filename = $this->root."/".$filename;
    }
    
    if (!file_exists($filename))
      $this->halt("filename: file $filename does not exist.");

    return $filename;
  }
  
    /* private: varname($varname)
     * varname: name of a replacement variable to be protected.
     * unused.
     */
    function &varname($varname)
    {
        return preg_quote("/{".$varname."}/");
    }

  /* private: loadfile(string $handle)
   * handle:  load file defined by handle, if it is not loaded yet.
   */
    function loadfile($handle)
    {
        if (isset($this->varkeys[$handle]) and !empty($this->varvals[$handle]))
            return true;
//          if (isset($this->varkeys[$handle]) and !empty($this->varkeys[$handle]))
//              return true;

        if (!isset($this->file[$handle]))
        {
            $this->halt("loadfile: $handle is not a valid handle.");
            return false;
        }
        $filename = $this->file[$handle];

        $fd = fopen( $filename, "r" );
        $str =& fread($fd, filesize($filename));
        fclose( $fd );
        if (empty($str))
        {
            $this->halt("loadfile: While loading $handle, $filename does not exist or is empty.");
            return false;
        }

        $this->set_var_internal($handle, $str);
    
        return true;
    }

  /***************************************************************************/
  /* public: halt(string $msg)
   * msg:    error message to show.
   */
  function halt($msg) {
    $this->last_error = $msg;
    
    if ($this->halt_on_error != "no")
      $this->haltmsg($msg);
    
    if ($this->halt_on_error == "yes")
      die("<b>Halted.</b>");
    
    return false;
  }
  
  /* public, override: haltmsg($msg)
   * msg: error message to show.
   */
  function haltmsg($msg) {
    printf("<b>Template Error:</b> %s<br>\n", $msg);
  }

}

?>
