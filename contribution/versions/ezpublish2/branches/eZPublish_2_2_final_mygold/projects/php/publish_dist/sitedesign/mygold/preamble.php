<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="de">
<head>
<title>MyGold.com Shop - Gold, Schmuck und Geschenke zu fairen Preisen</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1"/>
 
<?
$ForceSSL = $ini->read_var( "eZTradeMain", "ForceSSL" );

/*
// if we are entering right page, and IE 40 bit browser
if( $url_array[1] == "trade" && $url_array[2] == "checkout" && $SERVER_PORT != "443" && $ForceSSL == "enabled"
&& (  strpos( $GLOBALS["HTTP_USER_AGENT"], "MSIE" ) || strpos( $GLOBALS["HTTP_USER_AGENT"], "Internet Explorer" )  ) )
{
    $localURL = $GLOBALS["HTTP_HOST"];
    print("<meta http-equiv=\"REFRESH\" content=\"15; URL=https://$localURL/trade/checkout\">" );
    $SSLWARNING = "on";
}


if( ( $url_array[1] == "trade" ) &&
    ( $url_array[2] == "checkout" ) &&
    ( $SERVER_PORT != "443" ) &&
    ( $ForceSSL == "enabled" ) )
//    ( $session->variable( "SSLWarning" ) == "" ) )
{
    $session->setVariable( "SSLWarning", "enabled" );
    $SSLWARNING = "on";
}
*/

?>

<link rel="stylesheet" type="text/css" href="/sitedesign/mygold/style.css" />
<script language="JavaScript" type="text/javascript">
<!-- --> <![CDATA[ /> <!--
function MM_swapImgRestore() { //v3.0
  var i,x,a=document.MM_sr; for(i=0;a&&i<a.length&&(x=a[i])&&x.oSrc;i++) x.src=x.oSrc;
}

function MM_preloadImages() { //v3.0
  var d=document; if(d.images){ if(!d.MM_p) d.MM_p=new Array();
    var i,j=d.MM_p.length,a=MM_preloadImages.arguments; for(i=0; i<a.length; i++)
    if (a[i].indexOf("#")!=0){ d.MM_p[j]=new Image; d.MM_p[j++].src=a[i];}}
}

function MM_findObj(n, d) { //v4.0
  var p,i,x;  if(!d) d=document; if((p=n.indexOf("?"))>0&&parent.frames.length) {
    d=parent.frames[n.substring(p+1)].document; n=n.substring(0,p);}
  if(!(x=d[n])&&d.all) x=d.all[n]; for (i=0;!x&&i<d.forms.length;i++) x=d.forms[i][n];
  for(i=0;!x&&d.layers&&i<d.layers.length;i++) x=MM_findObj(n,d.layers[i].document);
  if(!x && document.getElementById) x=document.getElementById(n); return x;
}

function MM_swapImage() { //v3.0
  var i,j=0,x,a=MM_swapImage.arguments; document.MM_sr=new Array; for(i=0;i<(a.length-2);i+=3)
   if ((x=MM_findObj(a[i]))!=null){document.MM_sr[j++]=x; if(!x.oSrc) x.oSrc=x.src; x.src=a[i+2];}
}
// --> <! ]]>
</script>
