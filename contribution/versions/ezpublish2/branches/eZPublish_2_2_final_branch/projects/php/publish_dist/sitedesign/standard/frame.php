<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="no" lang="no">

<head>
<title><?php
// set the site title

$SiteTitle = $ini->read_var( "site", "SiteTitle" );

if ( isset( $SiteTitleAppend ) )
    print( $SiteTitle . " - " . $SiteTitleAppend );
else
    print( $SiteTitle );

?></title>


<?php

// check if we need a http-equiv refresh
if ( isset( $MetaRedirectLocation ) && isset( $MetaRedirectTimer ) )
{
    print( "<META HTTP-EQUIV=Refresh CONTENT=\"$MetaRedirectTimer; URL=$MetaRedirectLocation\" />" );
}

?>

<link rel="stylesheet" type="text/css" href="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/style.css" />

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

<!-- set the content meta information -->

<meta name="author" content="<?php

    $SiteAuthor = $ini->read_var( "site", "SiteAuthor" );
    print( $SiteAuthor );

?>" />
<meta name="copyright" content="<?php

    $SiteCopyright = $ini->read_var( "site", "SiteCopyright" );
    print( $SiteCopyright );

?>" />
<meta name="description" content="<?php

if ( isset( $SiteDescriptionOverride ) )
{
    print( $SiteDescriptionOverride );
}
else
{
    $SiteDescription = $ini->read_var( "site", "SiteDescription" );
    print( $SiteDescription );
}

?>" />
<meta name="keywords" content="<?php
if ( isset( $SiteKeywordsOverride ) )
{
    print( $SiteKeywordsOverride );
}
else
{
    $SiteKeywords = $ini->read_var( "site", "SiteKeywords" );
    print( $SiteKeywords );
}

?>" />
<meta name="MSSmartTagsPreventParsing" content="TRUE">

</head>

<body bgcolor="#999999" topmargin="6" marginheight="6" leftmargin="6" marginwidth="6"  onload="MM_preloadImages('<? print $GlobalSiteIni->WWWDir; ?>/images/redigerminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/slettminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/downloadminimrk.gif','<? print $GlobalSiteIni->WWWDir; ?>/images/addminimrk.gif')">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   <td>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   <td class="tdmini" width="99%">
<img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/ezpublish-yourcontentmadeeasy.gif" height="20" width="290" border="0" alt="" /><br />
   </td>
   <td class="tdmini" width="1%" align="right">

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/tab-mrk-left.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#ffffff" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-standard/">Standard</a>&nbsp;&nbsp;</td>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/tab-mrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-intranet/">Intranet</a>&nbsp;&nbsp;</td>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-trade/">Trade</a>&nbsp;&nbsp;</td>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-unmrk.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
   <td class="tab" bgcolor="#dcdcdc" width="23%">&nbsp;&nbsp;<a href="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/section-news/">News</a>&nbsp;&nbsp;</td>
   <td class="tdmini" width="1%">
   <img src="<? print $GlobalSiteIni->WWWDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/images/tab-unmrk-right.gif" height="20" width="20" border="0" alt="" /><br />
   </td>
</tr>
</table>

   </td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="4">
<tr valign="top">
    <td width="1%" bgcolor="#f0f0f0">
   <!-- Left menu start -->

   <?
    $CategoryID = 0;
   include( "ezarticle/user/menubox.php" );
   ?>

    <?
    $CategoryID = 1;
   include( "ezarticle/user/headlines.php" );
    ?>

   <?
   include( "ezlink/user/menubox.php" );
   ?>

   <?
    // include the static pages for category 2
    $CategoryID = 1;
    include( "ezarticle/user/articlelinks.php" );
   ?>

   <!-- Left menu end -->

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="8" border="0"><br />
   </td>

   <td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="0" /></td>
   <td width="96%" bgcolor="#ffffff">
<!--
    <?
    $CategoryID = 1;
    $Limit = 1;
   include( "ezad/user/adlist.php" );
    ?>
-->


    <!-- Banner start -->

    <!-- Banner end-->

<!-- Main content view start -->

   <?
   print( $MainContents );
   ?>

<!-- Main content view end -->

    <br />
    </td>
    <td width="1%" bgcolor="#ffffff"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="2" height="1" border="0" alt="" /></td>

    <td width="1%" bgcolor="#f0f0f0">

    <!-- Right menu start -->

    <?
    $NoAddress = true;
   include( "ezuser/user/userbox.php" );
    ?>

    <?
    // a short list of articles from the given category
    // shows $Limit number starting from offset $Offset
    $CategoryID=1;
    $Offset=1;
    $Limit=1;
    include( "ezarticle/user/smallarticlelist.php" );
    ?>

    <?
    include( "ezpoll/user/votebox.php" );
    ?>

<table width="100%" cellspacing="0" cellpadding="2" border="0">
<tr>
   <td class="menuhead">Site search</td>
</tr>
<tr>
   <td>
<form action="<? print $GlobalSiteIni->WWWDir . $GlobalSiteIni->Index; ?>/search/" method="get" style="margin-top: 0px; margin-bottom: 0px; padding: 0px;">
    <input type="hidden" name="SectionIDOverride" value="1" />
    <input type="text" size="10" name="SearchText" value="" style="width: 130px;" />
    <input class="stdbutton" type="submit" name="Search" value="search" />
</form>
   </td>
</tr>
<tr>
   <td class="menuspacer">&nbsp;</td>
</tr>
</table>

<!-- Right menu end -->

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="20" border="0" alt="" /><br />

   <div align="center"><a class="path" href="?PrintableVersion=enabled">Printable page</a></div><br />

   <div align="center">
   <a target="_blank" href="http://developer.ez.no"><img src="<? print $GlobalSiteIni->WWWDir; ?>/images/powered-by-ezpublish-100x35-trans-lgrey.gif" width="100" height="35" border="0" alt="Powered by eZ publish" /></a>
   </div>

   <img src="<? print $GlobalSiteIni->WWWDir; ?>/images/1x1.gif" width="130" height="8" border="0" alt="" /><br />

   </td>
</tr>
</table>

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
    // create a random string to prevent browser caching.
    $seed = md5( microtime() );
    // callback for storing the stats
    $imgSrc = $GlobalSiteIni->WWWDir . "/stats/store/rx$seed-" . $REQUEST_URI . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );
}

?>

</body>
</html>
