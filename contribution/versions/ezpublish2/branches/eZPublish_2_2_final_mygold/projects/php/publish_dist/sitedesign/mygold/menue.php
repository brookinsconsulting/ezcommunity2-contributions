<!--Menue Start -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
    <tr>
         <th>Shop by Product</th>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr>
        <td>
		<?
	            $CategoryID = 2;
        	    include( "eztrade/user/categorylist.php" );
        	?>
        </td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img width="1" height="2" alt="" src="/sitedesign/mygold/images/shim.gif" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
    <tr align="center"> 
	<th>Shop by Material</th>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td>
		<?
	           $CategoryID = 1;
	           include( "eztrade/user/categorylist.php" );
    		 ?>
        </td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img width="1" height="2" alt="" src="/sitedesign/mygold/images/shim.gif" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
    <tr align="center"> 
	<th>Gutschein</th>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td> 
	    <table cellpadding="0" cellspacing="0" border="0" width="1">
	        <tr>
		    <td class="spacer" width="1">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="gutschein_arrow" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/trade/vouchermain/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('gutschein_arrow','','/sitedesign/mygold/images/menu_arrow_o.gif',0)"><nobr>Geschenk-Gutschein</nobr></a>
		    </td>
	        </tr>
	    </table>
	</td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img width="1" height="2" alt="" src="/sitedesign/mygold/images/shim.gif" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>    
    <tr align="center"> 
	<th>Suche</th>
    </tr>
    <tr> 
	<td> 
	  <form action="/trade/search/" method="post">
	  <table width="100%">
	    <tr>
		  <td>
			<table width="100%" border="0" cellspacing="0" cellpadding="0" align="center">
		  	  <tr>
		  		<td class="spacer">&nbsp;</td>
		  		<td class="spacer" width="5%">&nbsp;</td>
		  	  </tr>
			  <tr> 
				<td align="center"> 
				  <input type="text" style="width: 95%" name="Query" size="5" />
				</td>
				<td>
				  <input class="okbutton" type="submit" name="search" value="&nbsp;OK&nbsp;" />
				</td>
			  </tr>
			  <tr> 
				<td colspan="2" align="center"><a class="small" href="/trade/extendedsearch/">Erweiterte Suche</a></td>
			  </tr>
			</table>
		  </td>
	    </tr>
	  </table>
	  </form>
	</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img width="1" height="2" alt="" src="/sitedesign/mygold/images/shim.gif" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
    <tr align="center"> 
	<th>Firma</th>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td> 
	    <table cellpadding="0" cellspacing="0" border="0" width="1">
	        <tr>
		    <td class="spacer" width="1">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="Filialen" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articlestatic/11/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Filialen','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Filialen</a>
		    </td>
	        </tr>
		<tr>
	    	    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="kontakt_arrow" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/feedback/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('kontakt_arrow','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Kontakt</a>
		    </td>
	        </tr>
		<tr>
	    	    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="stellen_arrow" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/schmuck/stellenangebote/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('stellen_arrow','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Stellenangebote</a>
		    </td>
	        </tr>		
	    </table>
	</td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img width="1" height="2" alt="" src="/sitedesign/mygold/images/shim.gif" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
    <tr align="center"> 
	<th>Info</th>
    </tr>
    <tr>
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td> 
	    <table cellpadding="0" cellspacing="0" border="0" width="1">
		<tr>
		    <td class="spacer" width="1">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="News" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/archive/10/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('News','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">News</a>
		    </td>
		</tr>
		<tr>
		    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="Garantie" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articlestatic/10/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Garantie','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Garantie</a>
		    </td>
		</tr>		
		<tr>
		    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="AGB" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articlestatic/17/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('AGB','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">AGB</a>
		    </td>
		</tr>
		<tr>
		    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="Sicherheit" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articlestatic/12/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Sicherheit','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Sicherheit</a>
		    </td>
		</tr>
		<tr>
		    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="Sitemap" alt="" />
		    </td>
		    <td>
			<a class="nav" href="http://<? echo $HTTP_HOST; ?>/sitemap/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('Sitemap','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Sitemap</a>
		    </td>
		</tr>
	    </table>
	</td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img src="/sitedesign/mygold/images/shim.gif" width="1" height="2" alt="" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
    <tr align="center"> 
	<th>Hilfe</th>
    </tr>
    <tr>
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td> 
	    <table cellpadding="0" cellspacing="0" border="0" width="1">
		<tr>
		    <td class="spacer" width="1">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="ring_hilfe_arrow" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articleuncached/22/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('ring_hilfe_arrow','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Ringgröße</a>
		    </td>
		</tr>
		<tr>
		    <td class="spacer">
			<img src="/sitedesign/mygold/images/menu_arrow.gif" width="15" height="16" name="shop_hilfe_arrow" alt="" />
		    </td>
		    <td>
			<a class="nav" href="/article/articlestatic/23/" onmouseout="MM_swapImgRestore()" onmouseover="MM_swapImage('shop_hilfe_arrow','','/sitedesign/mygold/images/menu_arrow_o.gif',0)">Einkaufsanleitung</a>
		    </td>
		</tr>		
	    </table>	
	</td>
    </tr>
    <tr> 
	<td class="spacer2">&nbsp;</td>
    </tr>
    <tr> 
	<td class="bgspacer" ><img src="/sitedesign/mygold/images/shim.gif" width="1" height="2" alt="" /></td>
    </tr>
    <tr> 
	<td class="spacer5">&nbsp;</td>
    </tr>
</table>
<!--Menue End -->
