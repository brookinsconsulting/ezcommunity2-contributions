</head>

<body bgcolor="#b5b5b5" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif')">

<img src="/sitedesign/standard/images/ezpublish-standard.gif" height="40" width="610" border="0" alt="" />

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f0f0f0">
	<!-- Meny start! -->
   
	<?
	include( "eznewsfeed/user/menubox.php" );
	?>
      
	<?
	include( "ezarticle/user/menubox.php" );
	?>
	
	<?
	include( "ezmail/user/menubox.php" );
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

	
	<table width="100%" cellspacing="0" cellpadding="2" border="0">
	<tr>
		<td colspan="2" class="menuhead">News</td>
	</tr>
	<?
	     // include the static pages for category 2
	     $CategoryID = 2;
	     include( "ezarticle/user/articlelinks.php" );
	?>
	<tr>
		<td colspan="2" class="menuspacer">&nbsp;</td>
	</tr>
	</table>

	<!-- Meny end! -->
	
	<img src="/images/1x1.gif" width="130" height="8" border="0"><br />
	</td>

	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>
    <td width="96%" bgcolor="#ffffff">


    <!-- Banner -->

    <div align="center">
        <?
        
        $CategoryID = $ini->read_var( "eZAdMain", "DefaultCategory" );
        $Limit = 1; 
        include( "ezad/user/adlist.php" );

        ?>
    </div><br />
