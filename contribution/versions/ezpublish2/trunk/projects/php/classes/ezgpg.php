<?php
/****************************************
** Title.........: GPG Encrytion class
** Version.......: 1.0
** Author........: Chris Mason <chris@net.ai>
** Filename......: ezgpg.php
** Last changed..: 04-Apr-01
*****************************************/

class ezgpg{


        var $body;
	var $keyname = array();
	var $pathtogpg = "/usr/bin/";
	var $pp;
	var $fp;
	var $pcmd;
	var $encryptcommand = "gpg --encrypt --batch";
	var $signcommand = "gpg --sign --batch";
	var $home="HOME=/var/www";



//encrypt function
	function ezgpg($plaintxt, $keyname, $wwwuser) {
	
		putenv("HOME=/var/www");
		$boundary = md5(uniqid(time()));
		
		$this->keyname=$keyname;
		if(sizeof($this->keyname) == 0) Echo "WARNING: No Keys Specified";

		$this->pcmd = "echo '$plaintxt' | ";
		$this->pcmd .= $this->pathtogpg.$this->encryptcommand;
		$this->pcmd.= " -a -q --no-tty -e -u $wwwuser -r'". $this->keyname ."' ";
		$this->pcmd.= " -o".$boundary;

		$pp = popen($this->pcmd, w);
		fwrite($pp, $this->body);
		pclose($pp);

		$fp = fopen($boundary,r);
		$this->body = fread($fp, filesize($boundary));
		fclose($fp);
		
		unlink($boundary);
		
		
	}

		


} // End of Class
