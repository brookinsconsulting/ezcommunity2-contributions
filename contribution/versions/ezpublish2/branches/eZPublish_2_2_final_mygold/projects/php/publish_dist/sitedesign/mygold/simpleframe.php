<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de" lang="de">
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

<link rel="stylesheet" type="text/css" href="<? print $wwwDir; ?>/sitedesign/<? print ($GlobalSiteDesign); ?>/style.css" />

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

// set the content meta information
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

<meta name="author" content="MyGold.com"/>
<meta name="copyright" content="MyGold.com &copy; 2001"/>
<meta name="description" content="MyGold.com - Gold, Schmuck und Geschenke zu fairen Preisen. Hier finden Sie Ringe, Ketten, Ohrringe, Armreifen, Diamanten, Gold, Goldschmuck und mehr"/>
<meta name="keywords" content="Schmuck, Gold, Goldschmuck, Ringe, Armband, Halskette, Fusskette, Armkette, Ohrring, Diamant, Brillant, Topas Amethist, Perlen, Collier, Memoire, Soltär, Blautopas, Rubin, Preise, günstig, Geschenk, Geschenkidee, MyGold, Impetex, Silber, Angebot, Shop, Gutschein, Wunschzettel, Zirkonia, Ketten, Safir, Schmuck, Webshop, Geschenke, Gutschein, Ohrringe, Halsketten, Ringe, Diamanten"/>
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
<table width="96%" align="center" bgcolor="#FFFFFF">
  <tr>
    <td>
      <br />
 
      <?php
        print( $MainContents );
      ?>

      <hr size="1" noshade/>
      <div align="center">&copy; 2000 MyGold - Impetex GmbH http://www.mygold.com</div>
    </td>
  </tr>
</table>
</body>
</html>
