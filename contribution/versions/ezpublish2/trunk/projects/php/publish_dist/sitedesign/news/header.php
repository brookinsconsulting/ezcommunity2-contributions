</head>

<body bgcolor="#bc9090" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onload="MM_preloadImages('<? print $wwwDir; ?>/images/redigerminimrk.gif','<? print $wwwDir; ?>/images/slettminimrk.gif','<? print $wwwDir; ?>/images/downloadminimrk.gif','<? print $wwwDir; ?>/images/addminimrk.gif')">

<img src="<? print $wwwDir; ?>/sitedesign/news/images/ezpublish-news.gif" height="40" width="610" border="0" alt="" />

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#fbf7f7">

	<!-- Left menu start -->

	<?
	include( "ezarticle/user/menubox.php" );
	?>
	
	<?
	include( "eznewsfeed/user/menubox.php" );
	?>

	<?
	include( "ezforum/user/menubox.php" );
	?>

    <?
    include( "ezbulkmail/user/menubox.php" );
    ?>
    
	<?
    // include the static pages for category 2
    $CategoryID = 2;
    include( "ezarticle/user/articlelinks.php" );
	?>

	<!-- Left menu end -->
	
	<img src="<? print $wwwDir; ?>/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />
	</td>

	<td width="1%" bgcolor="#ffffff"><img src="<? print $wwwDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="" /></td>
    <td width="96%" bgcolor="#ffffff">

    <!-- Banner start -->
	s
    <div align="center">
        <?
        $CategoryID = 4;
        $Limit = 1; 
        include( "ezad/user/adlist.php" );
        ?>
    </div><br />

    <!-- Banner end-->
		
	<!-- Main content view start -->
