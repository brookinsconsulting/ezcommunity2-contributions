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

<h1>BETA DESIGN SCETCH - section test</h1>

    <!-- Banner -->

    <div align="center">
        <?
        
        $CategoryID = $ini->read_var( "eZAdMain", "DefaultCategory" );
        $Limit = 1; 
        include( "ezad/user/adlist.php" );

        ?>
    </div><br />

<br />
<table width="600" border="0" cellspacing="0" cellpadding="0">
	<tr valign="bottom">
		<td width="160"></td>
		<td width="440">
			<table border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td class="sectionblack" nowrap>First section</td>
					<td bgcolor="black" valign="top"><img src="/sitedesign/designsection1/images/sec_highlight.gif" width="14" height="17"></td>
					<td class="section" nowrap><a href="/article/archive/32">2nd section</a></td>
					<td class="orange" valign="top"><img src="/sitedesign/designsection1/images/sec_normal.gif" width="14" height="17"></td>
					<td class="section" nowrap><a href="/article/archive/35">Last section</a></td>
					<td class="orange" valign="top"><img src="/sitedesign/designsection1/images/sec_normal.gif" width="14" height="17"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="Black">
	<tr>
		<td><img src="/sitedesign/designsection1/images/spacer.gif" width="160" height="23"></td>
	</tr>
</table>
<table width="100%" border="0" cellspacing="0" cellpadding="0" bgcolor="Black">
	<tr>
		<td>
			<table width="207" border="0" cellspacing="0" cellpadding="0">
				<tr>
					<td width="188"><img src="/sitedesign/designsection1/images/search.gif" width="188" height="19"></td>
					<td width="19"><img src="/sitedesign/designsection1/images/top1_1.gif" width="19" height="19"></td>
				</tr>
			</table>
		</td>
	</tr>
</table>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr valign="top">
	<td width="207" colspan="1" rowspan="2" bgcolor="#FFFFFF">
	<table width="207" border="0" cellspacing="0" cellpadding="0"  class="orange">
	<form>
	<tr valign="top">
            <td width="9"><img src="/sitedesign/designsection1/images/spacer.gif" width="9" height="38"></td>
	    <td width="137">
	       <input type="text" name="textfield" class="textfield" value="Test" size="10" style="width:130px">
            </td>
            <td width="42"><img src="/sitedesign/designsection1/images/go.gif" width="42" height="28"></td>
            <td width="19"><img src="/sitedesign/designsection1/images/spacer.gif" width="19" height="1"></td>
	</tr>
	<tr>
	<td width="207" bgcolor="Black" colspan="4">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
	    <td bgcolor="#000000" colspan="2"><img src="/sitedesign/designsection1/images/spacer.gif" width="207" height="1"></td>
	</tr>
	<tr>
	     <td>
	     <?
          $CategoryID = 29;
          include( "ezarticle/user/menubox.php" );
         ?>    
            </td>
	    <td bgcolor="#FFFFFF" valign="top"><img src="/sitedesign/designsection1/images/spacer.gif" width="17" height="1"></td>
	</tr>
	</table>				
	</td>
	
	</tr>
	</form>
	</table>
	</td>
	<td width="100%" rowspan="2">



