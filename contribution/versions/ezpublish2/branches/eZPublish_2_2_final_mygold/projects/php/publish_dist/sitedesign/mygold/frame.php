<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
<head>
<title><?php
// set the site title

$SiteTitle = $ini->read_var( "site", "SiteTitle" );
$SiteTitleLong = $ini->read_var( "site", "SiteTitleLong" );
$SiteDescription = $ini->read_var( "site", "SiteDescription" );

if ( isset( $SiteTitleAppend ) )
    print( $SiteTitle . " - " . $SiteTitleAppend );
else
    print( $SiteTitleLong );

?></title>

<?php
// check if we need a http-equiv refresh
if ( isset( $MetaRedirectLocation ) && isset( $MetaRedirectTimer ) )
{
    print( "<META HTTP-EQUIV=Refresh CONTENT=\"$MetaRedirectTimer; URL=$MetaRedirectLocation\" />" );
}

?>

<link rel="stylesheet" type="text/css" href="/sitedesign/mygold/style.css" />
<script language="JavaScript1.2" type="text/javascript">
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

<meta name="description" content="<?php

// set the content meta information
if ( isset( $SiteDescriptionOverride ) )
{
    print( $SiteDescriptionOverride ." " );

}
if ( isset( $SiteTitleAppend ) )
{
    print( $SiteTitleAppend ." " );

}
print( $SiteDescription );


?>" />

<meta name="MSSmartTagsPreventParsing" content="TRUE" />
<meta name="author" content="MyGold.com"/>
<meta name="copyright" content="MyGold.com &copy; 2001"/>
<meta name="keywords" content="<?php if ( isset( $SiteTitleAppend ) ) print( $SiteTitleAppend. ", " ); ?>Schmuck, Gold, Goldschmuck, Ringe, Armband, Halskette, Fusskette, Armkette, Ohrring, Diamant, Brillant, Topas Amethist, Perlen, Collier, Memoire, Soltär, Blautopas, Rubin, Preise, günstig, Geschenk, Geschenkidee, MyGold, Impetex, Silber, Angebot, Shop, Gutschein, Wunschzettel, Zirkonia, Ketten, Safir, Schmuck, Webshop, Geschenke, Gutschein, Ohrringe, Halsketten, Ringe, Diamanten"/>
<meta name="page-topic" content="Branche Produkt"/>
<meta name="page-type" content="Produktinfo"/>
<meta name="audience" content="Alle"/>
<meta name="robots" content="INDEX,FOLLOW"/>
<meta name="robots" content="all"/>
<meta name="revisit-after" content="10 days"/>
<meta name="distribution" content="global"/>
<meta name="rating" content="general"/>
</head>
<body>
<table border="0" cellspacing="0" cellpadding="0" width="100%" bgcolor="#FFFFF">
  <tr>
    <td rowspan="2" align="center" valign="middle" width="18%" bgcolor="#DDDDDD"><a href="/"><img src="/sitedesign/mygold/images/pic.jpg" alt="MyGold.com - Geschenke, Golschmuck, Gold, Schmuck zu fairen Preisen." width="110" height="80" border="0" /></a></td>
    <td width="1%" class="spacer" bgcolor="#DDDDDD">&nbsp;</td>
    <td class="bg1000" align="center" width="60%">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
        <tr>
          <td align="center" valign="middle" width="99%">
	        <table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr> 
                <td align="left" valign="bottom"><a href="/"><img src="/sitedesign/mygold/images/mygold.gif" alt="MyGold.com - Gold is our Business!" width="218" height="58" border="0" /></a></td>
                <td align="center" valign="middle" bgcolor="#FFFFFF" class="bgshim"> 
                  <h1 class="head">Ein MyGold Geschenkgutschein 
                    <br />
                    das ideale Weihnachtsgeschenk!</h1>
                </td>
              </tr>
              <tr> 
                <td align="left" valign="bottom">
                  <h4>Goldschmuck und Geschenke zu fairen Preisen.</h4>
                </td>
                <td align="center" valign="bottom" bgcolor="#FFFFFF" class="bgshim"><span class="small"> 
                  <a href="/trade/cart/">Warenkorb</a> | <a href="/schmuck/gutschein/">Gutschein</a> 
                  | <a href="/trade/wishlist/">Wunschzettel</a> | <a href="/article/archive/10/">News</a></span></td>
              </tr>
              <tr> 
                <td align="left" valign="bottom"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="1" height="5" /></td>
                <td class="bgshim" align="center" valign="bottom"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="1" height="5" /></td>
              </tr>
            </table>
	  </td>
	  <td width="1%" class="bgspacer30"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="30" height="85" /></td>
	</tr>
      </table>
    </td>
    <td class="bgspacer20" align="right" valign="bottom" width="1%"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="20" height="80" /></td>
    <td align="center" valign="middle" rowspan="2" bgcolor="#DDDDDD" width="20%"> 
      <form method="post" action="https://www.trustedshops.de/tshops/siegel.php3" target="_blank">
        <input type="image" src="/sitedesign/mygold/images/trusted_shop.gif" border="0" alt="Trusted Shops G&uuml;tesiegel - Bitte hier klicken" />
        <input name="shop_id" type="hidden" value="XD7D38F69FDE28952D48AC3056C5D449C" />
      </form> 
    </td>
  </tr>
  <tr> 
    <td class="spacer" valign="top" align="left"><img src="/sitedesign/mygold/images/co_li_o_n.gif" alt="" width="20" height="20" /></td>
    <td bgcolor="#FFFFFF" class="spacer">&nbsp;</td>
    <td class="spacer" valign="top" align="right"><img src="/sitedesign/mygold/images/co_re_o_n.gif" alt="" width="20" height="20" /></td>
  </tr>
  <tr>
    <td valign="top" bgcolor="#DDDDDD">
      <?php include( "sitedesign/mygold/menue.php" ); ?>
    </td>
    <td bgcolor="#FFFFFF"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    <td bgcolor="#FFFFFF" valign="top">

<!-- Main content view start -->

<?php
   print( $MainContents );
?>

<!-- Main content view end -->

<?php

$session =& eZSession::globalSession();


if ( $session->fetch() == false )
{
    $session =& eZSession::globalSession();
    $session->store();
}

if ( isset( $Design ) and $Design == 1 )
{
    $session->setVariable( "SiteDesign", "intranet" );
    include_once( "classes/ezhttptool.php" );
    eZHTTPTool::header( "Location: $REQUEST_URI" );
    exit();
}

if ( isset( $Design ) and $Design == 2 )
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

if ( isset( $Design ) and $Design == 3 )
{
    $session->setVariable( "SiteDesign", "news" );
    include_once( "classes/ezhttptool.php" );

    $redir = "/";
    if ( isset( $REQUEST_URI ) && ( $REQUEST_URI != "" ) )
    {
        $redir = $REQUEST_URI;
    }

    eZHTTPTool::header( "Location: $redir" );
    exit();
}


?>

      &nbsp;
    </td>
    <td bgcolor="#FFFFFF"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="1" height="1" /></td>
    <td bgcolor="#DDDDDD" valign="top">
      <?php
	include( "ezuser/user/userbox.php" );
	include( "eztrade/user/hotdealslist.php" );
	include( "eztrade/user/smallcart.php" );
	include( "sitedesign/mygold/user.php" );
	include( "sitedesign/mygold/callback.php" );
      ?>
    </td>
  </tr>
      <!-- Right menu end -->  
  <tr>
    <td class="spacer" bgcolor="#DDDDDD">&nbsp;</td>
    <td class="spacer" valign="bottom" bgcolor="#DDDDDD"><img src="/sitedesign/mygold/images/co_li_u_n.gif" alt="" width="20" height="20" /></td>
    <td class="spacer" bgcolor="#FFFFFF">&nbsp;</td>
    <td class="spacer" valign="bottom" align="right"><img src="/sitedesign/mygold/images/co_re_u_n.gif" alt="" width="20" height="20" /></td>
    <td class="spacer" bgcolor="#DDDDDD">&nbsp;</td>
  </tr>
  <tr>
    <td class="spacer" align="center" valign="middle" bgcolor="#DDDDDD">
      <a href="https://www.thawte.com/cgi/server/certdetails.exe?code=DEIMPE1" onclick="window.open('https://www.thawte.com/cgi/server/certdetails.exe?code=DEIMPE1', 'anew', config='height=400,width=450,toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,directories=no,status=yes'); return false;" target="new"><img src="/sitedesign/mygold/images/stampger_s.gif" width="100" height="63" border="0" alt="Sicheres Bezahlen per SSL" /></a>
    </td>  
    <td class="spacer" bgcolor="#DDDDDD">&nbsp;</td>
    <td valign="top" class="bg1000">
      <table width="100%" border="0" cellspacing="0" cellpadding="0" align="right">
        <tr>
          <td align="center" valign="middle" class="bg1000" width="99%">
            Servicetelefon 01801-MYGOLD (zum Ortstarif)<br />
            &copy; 2000, 2001 MyGold.com - Impetex GmbH<br />
            <a href="mailto:info@mygold.com">info@mygold.com</a>
          </td>
          <td width="1%" class="bgspacer30"><img src="/sitedesign/mygold/images/shim.gif" alt="" width="30" height="85" /></td>
        </tr>
      </table>
    </td>
    <td class="bgspacer20" align="right" valign="bottom">
      <?
         // Store the statistics with a callback image.
         // It will be no overhead with this method for storing stats
         //
         $StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

         if ( $StoreStats == "enabled" )
         {
             // callback for storing the stats
             $imgSrc = $wwwDir . "/stats/store" . $REQUEST_URI . "1x1.gif";
             print( "<img src=\"$imgSrc\" height=\"1\" width=\"1\" border=\"0\" alt=\"\" />" );
         }

      ?>    
    </td>
    <td class="spacer" align="center" valign="middle" bgcolor="#DDDDDD">
      <a href="http://www.campaign.paybox.de/banner.php3?merchantPayboxNo=4900011161914" target="neu"><img src="/sitedesign/mygold/images/paybox_s.gif" alt="" width="36" height="26" hspace="1" border="0" /></a> 
      <a href="http://www.visa.de" target="neu"><img src="/sitedesign/mygold/images/visa_s.gif" alt="" width="42" height="26" hspace="1" border="0" /></a> 
      <a href="http://www.eurocard.de/" target="neu"><img src="/sitedesign/mygold/images/euro_s.gif" alt="" width="37" height="26" hspace="1" border="0" /></a> 
      <img src="/sitedesign/mygold/images/elv_s.gif" alt="" width="26" height="26" hspace="1" border="0" />
    </td>
  </tr>
</table>
</body>
</html>
