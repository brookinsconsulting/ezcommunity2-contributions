
</head>


<body bgcolor="#ffffff">

<h1>eZ publish v2.0 Beta 1</h1>
<table width="100%" border="2" cellspacing="0" cellpadding="4">
 <tr valign="top">
    <td width="1%" bgcolor="#ffffff">
	<!-- Meny start! -->
	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td><img src="/images/1x1.gif" width="130" height="1" border="0"> </td>
	</tr>

    <tr>
		<td class="menuhead" bgcolor="#c82828">News</td>
	</tr>
	<tr>
		<td><img src="/images/dot.gif" width="12" height="10"><a class="menu" href="/article/archive/0/">Latest</a></td>
	</tr>
	<tr>
		<td><img src="/images/dot.gif" width="12" height="10"><a class="menu" href="/article/articleheaderlist/0/">Archive</a></td>
	</tr>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
    <tr>
		<td class="menuhead" bgcolor="#c82828">Calendar</td>
	</tr>
	<tr>
		<td><img src="/images/dot.gif" width="12" height="10"><a class="menu" href="/calendar/monthview/">Month view</a></td>
	</tr>        
	<tr>
		<td><img src="/images/dot.gif" width="12" height="10"><a class="menu" href="/calendar/appointmentedit/new/">New appointment</a></td>
	</tr>        
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>

<?
include( "ezarticle/user/menubox.php" );
?>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
<?
include( "ezforum/user/menubox.php" );
?>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
<?
include( "ezlink/user/menubox.php" );
?>
	<tr>
		<td class="menuspacer">&nbsp;</td>
	</tr>
    <tr>
		<td class="menuhead" bgcolor="#c82828">Static pages</td>
	</tr>
<?
     // include the static pages for category 2
     $CategoryID = 2;
     include( "ezarticle/user/articlelinks.php" );
?>
	</table>
	<!-- Meny end! -->

	</td>
    <td width="94%" bgcolor="#ffffff">

