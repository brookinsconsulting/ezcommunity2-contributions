<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title>eZ publish administrasjon</title>
<link rel="stylesheet" type="text/css" href="/<? echo $SiteStyle; ?>.css"/>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
</head>

<body bgcolor="<? echo $SiteBackground; ?>">

<?
// add template to this page
$user = eZUser::currentUser();
if ( $user )
{
    $firstName =& $user->firstName();
    $lastName =& $user->lastName();
}
?>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%"><img src="/images/<? echo $SiteStyle; ?>/top-logo.gif" width="300" height="70" border="0"></td>
	<td width="96%" background="/images/<? echo $SiteStyle; ?>/top-m.gif">&nbsp;</td>
	<td width="1%" background="/images/<? echo $SiteStyle; ?>/top-m.gif" valign="top">
    <img src="/images/<? echo $SiteStyle; ?>/1x1.gif" width="120" height="16" border="0"><br><div class="top">Innlogget bruker:</div>
    <div class="topusername"><? print( $firstName . " " . $lastName ); ?></div></td>
	<td width="1%" background="/images/<? echo $SiteStyle; ?>/top-m.gif" valign="top"><img src="/images/<? echo $SiteStyle; ?>/1x1.gif" width="120" height="16" border="0"><br />
    <img src="/images/<? echo $SiteStyle; ?>//topmenu-arrow.gif" width="20" height="10"><a class="topmenu">Brukerstatus</a><br />
    <img src="/images/<? echo $SiteStyle; ?>/topmenu-arrow.gif" width="20" height="10" border="0"><a href="/user/passwordchange/" class="topmenu">Endre passord</a></td>
	<td width="1%">	<img src="/images/<? echo $SiteStyle; ?>/top-logout.gif" width="50" height="70" usemap="#topmap" border="0"></td>
</tr>
</table>

<map name="topmap">
<area shape="rect" coords="0,10,35,48" href="/user/login/logout/">
</map>


<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td width="1%" valign="top">
    <table width="150" border="0" cellspacing="0" cellpadding="0">
