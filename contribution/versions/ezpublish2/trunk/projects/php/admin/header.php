<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>eZ publish administrasjon</title>
<link rel="stylesheet" type="text/css" href="/<? echo $SiteStyle; ?>.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>

<SCRIPT LANGUAGE="JavaScript1.2">
<!--//
function verify( msg, url )
{
    if ( confirm( msg ) )
    {
        this.location = url;
    }
}

//-->
</SCRIPT>  

</head>



<body bgcolor="#777777">

<?
// This page should have templates, but because of speed concerns
// we have not implemented this yet. So this code looks ugly.
include_once( "classes/INIFile.php" );
$ini = new INIFIle( "site.ini" );
$Language = $ini->read_var( "eZUserMain", "Language" );


$LanguageIni = new INIFIle( "intl/" . $Language . "/header.php.ini", false );

$userLogin = $LanguageIni->read_var( "strings", "login_user" );
$status = $LanguageIni->read_var( "strings", "status" );
$passwordChange = $LanguageIni->read_var( "strings", "password_change" );

$user =& eZUser::currentUser();

if ( $user )
{
    $firstName =& $user->firstName();
    $lastName =& $user->lastName();
}

?>

<h1>HEADER</h1>
    

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" valign="top">
    <table width="150" border="0" cellspacing="0" cellpadding="0">
