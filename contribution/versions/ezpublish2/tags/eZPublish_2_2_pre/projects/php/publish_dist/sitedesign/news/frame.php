<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title><?
if ( isset( $Title ) )
    print( $Title );
else
	print( "eZ publish" );
    ?></title>

<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/news/style.css" />

<script language="JavaScript1.2">
<!--//

	function MM_swapImgRestore() 
	{
		var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
	}

	function MM_preloadImages() 
	{
		var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
		var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
		if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
	}

	function MM_findObj(n, d) 
	{
		var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
		d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
		if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
		for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document); return x;
	}

	function MM_swapImage() 
	{
		var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
		if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
	}
	
//-->
</script> 



</head>

<body bgcolor="#bc9090" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onload="MM_preloadImages('<? print $GlobalSiteIni->WWWDir; ?>/images/redigerminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/slettminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/downloadminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/addminimrk.gif')">

<img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/news/images/ezpublish-news.gif" height="40" width="610" border="0" alt="" />

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
	
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />
	</td>

	<td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="" /></td>
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

    <?
    print( $MainContents );
    ?> 

	<!-- Main content view end -->
	
	<br />
    </td>
   	<td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="" /></td>

	<td width="1%" bgcolor="#fbf7f7">

   	<!-- Right menu start -->
	    
    <?
    include( "ezuser/user/userbox.php" );
	?>

	<?
	$CategoryID = 1;
	include( "eznewsfeed/user/headlines.php" );
	?>
    
    <?
    include( "ezpoll/user/votebox.php" );
    ?>
        

	<hr noshade="noshade" size="4" />
	
	
    <?
    // change design on the fly
    
$session =& eZSession::globalSession();

if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();    
}

if ( $Design == 1 )
{
    $session->setVariable( "SiteDesign", "standard" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}

if ( $Design == 2 )
{
    $session->setVariable( "SiteDesign", "trade" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}


if ( $Design == 3 )
{
    $session->setVariable( "SiteDesign", "intranet" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) || ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }
        
    eZHTTPTool::header( "Location: $redir" );
    exit();
}

?>

	<h2>Alternative sitedesigns:</h2>
    <a href="<? print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $REQUEST_URI . "?Design=1"); ?>"><b>Portal</b></a><br />
    <a href="<? print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $REQUEST_URI . "?Design=3"); ?>"><b>Intranet</b></a><br />
    <a href="<? print( $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index . $REQUEST_URI . "?Design=2"); ?>"><b>Trade</b></a><br />

   	<!-- Right menu end -->
	
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="20" border="0" alt="" /><br />
	
	<div align="center">
	<a target="_blank" href="http://publish.ez.no"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/powered-by-ezpublish-100x35-trans-lgrey.gif" width="100" height="35" border="0" alt="Powered by eZ publish" /></a>
	</div>
	
	<img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />
	 
	</td>
  </tr>
</table>

<?
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // callback for storing the stats
    $imgSrc = "$GlobalSiteIni->WWWDir/stats/store" . $REQUEST_URI . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );    
}

?>

</body>
</html>
