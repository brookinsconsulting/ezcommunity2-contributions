</head>

<body bgcolor="#8a8ab3" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onLoad="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif')">

<img src="/sitedesign/trade/images/ezpublish-trade.gif" height="40" width="610" border="0" alt="" />

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f6f6fa">

	<!-- Meny start! -->

	<?
	include( "ezarticle/user/menubox.php" );
	?>

	<? include( "eztrade/user/categorylist.php" ); ?>

	<? include( "eztrade/user/menubox.php" ); ?>

    <? include( "eztrade/user/hotdealslist.php" ); ?>     

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

