</head>

<body bgcolor="#b5b5b5" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif')">

<h1>eZ publish v2.0 Beta 1</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f0f0f0">
	<!-- Meny start! -->

	<table width="100%" cellspacing="0" cellpadding="2" border="0">
    <tr>
		<td colspan="2" class="menuhead">News</td>
	</tr>
	<tr>
		<td width="1%" valign="top"><img src="/images/dot.gif" width="10" height="12"><br /></td>
		<td width="99%"><a class="menu" href="/article/archive/0/">Latest</a></td>
	</tr>
	<tr>
		<td width="1%" valign="top"><img src="/images/dot.gif" width="10" height="12"><br /></td>
		<td width="99%"><a class="menu" href="/article/articleheaderlist/0/">Archive</a></td>
	</tr>
	<tr>
		<td colspan="2" class="menuspacer">&nbsp;</td>
	</tr>
	</table>

	<table width="100%" cellspacing="0" cellpadding="2" border="0">
    <tr>
		<td colspan="2" class="menuhead">Calendar</td>
	</tr>
	<tr>
		<td width="1%" valign="top"><img src="/images/dot.gif" width="10" height="12"><br /></td>
		<td width="99%"><a class="menu" href="/calendar/monthview/">Month view</a></td>
	</tr>
	<tr>
		<td width="1%" valign="top"><img src="/images/dot.gif" width="10" height="12"><br /></td>
		<td width="99%"><a class="menu" href="/calendar/appointmentedit/new/">New appointment</a></td>
	</tr>
	<tr>
		<td colspan="2" class="menuspacer">&nbsp;</td>
	</tr>
	</table>
	
    <?
    include( "eztodo/user/menubox.php" );
    ?>

    <?
    include( "ezbug/user/menubox.php" );
    ?>

	<?
	include( "ezcontact/user/menubox.php" );
	?>


	<?
	include( "ezarticle/user/menubox.php" );
	?>

	<?
	include( "ezforum/user/menubox.php" );
	?>

	<?
	include( "ezlink/user/menubox.php" );
	?>

	<?
	include( "ezfilemanager/user/menubox.php" );
	?>

	<?
	include( "ezimagecatalogue/user/menubox.php" );
	?>

	<table width="100%" cellspacing="0" cellpadding="2" border="0">
    <tr>
		<td colspan="2" class="menuhead">Static pages</td>
	</tr>
<?
     // include the static pages for category 2
     $CategoryID = 2;
     include( "ezarticle/user/articlelinks.php" );
?>
	</table>

	<!-- Meny end! -->
	
	<img src="/images/1x1.gif" width="130" height="1" border="0"><br />
	</td>

	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>
    <td width="96%" bgcolor="#ffffff">
