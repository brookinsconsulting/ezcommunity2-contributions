</head>

<body bgcolor="#FFFFFF" topmargin="0" marginheight="0" leftmargin="0" marginwidth="0"  onload="MM_preloadImages('/images/redigerminimrk.gif','/images/slettminimrk.gif','/images/downloadminimrk.gif','/images/addminimrk.gif')">

<?
// Store the statistics with a callback image.
// It will be no overhead with this method for storing stats
//

$StoreStats = $ini->read_var( "eZStatsMain", "StoreStats" );

if ( $StoreStats == "enabled" )
{
    // callback for storing the stats
    $imgSrc = "/stats/store" . $REQUEST_URI . "1x1.gif";
    print( "<img src=\"$imgSrc\" height=\"0\" width=\"0\" border=\"0\" alt=\"\" />" );    
}

?>

    <!-- Banner -->

    <div align="center">
<!--
        <?
        
        $CategoryID = $ini->read_var( "eZAdMain", "DefaultCategory" );
        $Limit = 1; 
        include( "ezad/user/adlist.php" );

        ?>
-->
    </div>

<table width="600" border="0" cellspacing="0" cellpadding="0">
<tr valign="bottom">
	<td width="160"></td>
	<td width="440">
	
	<table border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td class="sectionblack" nowrap>First section</td>
		<td bgcolor="black" valign="top"><img src="/sitedesign/designsection1/images/sec_highlight.gif" width="14" height="17" border="0" alt="" /></td>
		<td class="section" nowrap><a href="/article/archive/32">2nd section</a></td>
		<td class="orange" valign="top"><img src="/sitedesign/designsection1/images/sec_normal.gif" width="14" height="17" border="0" alt="" /></td>
		<td class="section" nowrap><a href="/article/archive/35">Last section</a></td>
		<td class="orange" valign="top"><img src="/sitedesign/designsection1/images/sec_normal.gif" width="14" height="17" border="0" alt="" /></td>
	</tr>
	</table>

	</td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="Black">
<tr>
	<td><img src="/sitedesign/designsection1/images/spacer.gif" width="160" height="23" border="0" alt="" /></td>
</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="Black">
<tr>
	<td>

	<table width="207" border="0" cellspacing="0" cellpadding="0">
	<tr>
		<td width="188"><img src="/sitedesign/designsection1/images/search.gif" width="188" height="19" border="0" alt="" /></td>
		<td width="19"><img src="/sitedesign/designsection1/images/top1_1.gif" width="19" height="19" border="0" alt="" /></td>
	</tr>
	</table>

	</td>
</tr>
</table>

<table width="100%" border="1" cellspacing="0" cellpadding="0">
<tr valign="top">

	<td width="120" colspan="1" rowspan="2" bgcolor="#ffffff">

	<form>

	<table width="120" border="0" cellspacing="0" cellpadding="0" class="orange">
	<tr valign="top">
            <td width="9"><img src="/sitedesign/designsection1/images/spacer.gif" width="9" height="38" border="0" alt="" /></td>
	    	<td>
	       <input type="text" name="textfield" class="textfield" value="Test" size="10" style="width:80px">
            </td>
            <td width="42"><img src="/sitedesign/designsection1/images/go.gif" width="42" height="28" border="0" alt="" /></td>
            <td width="2"><img src="/sitedesign/designsection1/images/spacer.gif" width="2" height="1" border="0" alt="" /></td>
	</tr>
	<tr>
	<td width="120" bgcolor="#000000" colspan="4">
	
	<img src="/images/1x1.gif" height="5" width="1" border="0" alt="" />

	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	     <td>
	     <?
          $CategoryID = 29;
          include( "ezarticle/user/menubox.php" );
         ?>    
            </td>
	</tr>
	</table>				

	</td>
	
</tr>
</table>
</form>

	</td>
	<td width="100%" rowspan="2">



