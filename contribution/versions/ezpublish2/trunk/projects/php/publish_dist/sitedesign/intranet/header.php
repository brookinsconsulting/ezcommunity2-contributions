</head>

<body bgcolor="#7ca37c" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onload="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif')">

<img src="/sitedesign/intranet/images/ezpublish-intranet.gif" height="40" width="690" border="0" alt="" />

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f4fbf4">

   	<!-- Left menu start -->

	<?
	include( "ezarticle/user/menubox.php" );
	?>

    <?
    include( "ezbug/user/menubox.php" );
    ?>

	<?
	include( "ezcontact/user/menubox.php" );
	?>
    
	<?
	include( "ezforum/user/menubox.php" );
    include( "ezforum/user/latestmessages.php" );
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

	<?
    // include the static pages for category 2
    $CategoryID = 2;
    include( "ezarticle/user/articlelinks.php" );
	?>


   	<!-- Left menu end -->
		
	<img src="/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />
	</td>

	<td width="1%" bgcolor="#ffffff"><img src="/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>
    <td width="96%" bgcolor="#ffffff">
	
    <!-- Banner start -->

    <div align="center">
        <?
        
        $CategoryID = $ini->read_var( "eZAdMain", "DefaultCategory" );
        $Limit = 1; 
        include( "ezad/user/adlist.php" );

        ?>
    </div><br />

    <!-- Banner end -->

	<!-- Main content view start -->
