<h1>Sitemap</h1>
<hr noshade="noshade" size="1" />
Hier finden Sie eine &Uuml;bersicht &uuml;ber unser umfangreiches Sortiment an:
<h1 style="border: 0; font-size: 12px; font-weight: normal; color: #000000">Gold, Schmuck, Goldschmuck und Geschenken.</h1>
<table cellpadding="5" border="0">
  <tr>
    <td>
      <table bgcolor="#dddddd"  border="0" cellpadding="0" cellspacing="0" align="left">
        <tr>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="97%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
	</tr>
<?php

include_once( "eztrade/classes/ezproductcategory.php" );

$category = new eZProductCategory();
$categoryArray = $category->getTree( );

$shim   =  '<td>&nbsp;</td>';
$i = 0;
foreach ( $categoryArray as $catItem )
{
    $id = $catItem[0]->id();
    $name = $catItem[0]->name();

    if ( $catItem[1] == 1 AND $name != "Gutscheine")
    {
	$indent = '';
        $indent2 = '<th colspan="4"><a class="SiteMapLnkOne" href="/trade/productlist/' . $id . '/">' . $name . '</a></th>';
	if ( $i != 0 )
	    $preline = '<tr><td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td></tr>';
	$i++;
    }
    elseif  ( $catItem[1] == 2 )
    {
        $indent  =  $shim;
        $indent2 = '<td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/productlist/'.$id.'/">' . $name . '</a></td>';
    }
    elseif  ( $catItem[1] == 3 )
    {
        $indent  = $shim . $shim;
        $indent2 = '<td class="StMpLvThree">- <a href="/trade/productlist/' . $id . '/">' . $name . '</a></td>';
    }

    if ( $catItem[1] == 1 AND $name != "Gutscheine")
    {
        print( $preline );
	print( "<tr>$indent2</tr>\n" );
	print( "<tr><td colspan=\"4\"><img src=\"/sitedesign/mygold/images/shim.gif\" width=\"1\" height=\"3\" alt=\"\" /></td></tr>\n" );
    }
    elseif ( $name == "Gutscheine" )
    {
	print("");
    }
    else
    {
	print( "<tr>$indent $indent2 <td>&nbsp;&nbsp;</td></tr>\n" );
    }
}


?>

	<tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
	</tr>
	<tr>
	  <th colspan="4">Gutscheine</th>
	</tr>
	<tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
	</tr>
	<tr>
	  <td>&nbsp;</td> <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/schmuck/gutschein/">Gutschein</a></td> <td>&nbsp;&nbsp;</td>
	</tr>
	<tr>
	  <td>&nbsp;</td><td>&nbsp;</td> <td class="StMpLvThree">- <a href="/schmuck/gutschein/email/">E-Mail Gutschein</a></td><td>&nbsp;&nbsp;</td>
	</tr>
	<tr>
	  <td>&nbsp;</td><td>&nbsp;</td> <td class="StMpLvThree">- <a href="/schmuck/gutschein/brief/">Brief Gutschein</a></td><td>&nbsp;&nbsp;</td>	  
	</tr>
	<tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
	</tr>
      </table>
    </td>
    <td valign="top">
      <table bgcolor="#dddddd" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="100%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
          <th width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></th>
       </tr>
       <tr>
         <th colspan="3">Firma</th>
         <th>&nbsp;</th>
       </tr>
       <tr>
         <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articlestatic/11/">Filialen</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/feedback/">Kontakt</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/schmuck/stellenangebote/">Stellenangebote</a></td>
         <td>&nbsp;</td>
       </tr>       
       <tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
       </tr>
       <tr>
         <th colspan="3">Info</th>
         <th>&nbsp;</th>
       </tr>
       <tr>
         <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articlestatic/10/">Garantie</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articlestatic/17/">AGB</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articlestatic/12/">Sicherheit</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
       </tr>       
       <tr>
         <th colspan="3">Hilfe</th>
         <th>&nbsp;</th>
       </tr>
       <tr>
         <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articleuncached/22/">Ringgr&ouml;&szlig;e</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/article/articlestatic/23/">Einkaufsanleitung - Übersicht</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
         <td class="StMpLvThree">- <a href="/article/articlestatic/24/">Auswahl</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
         <td class="StMpLvThree">- <a href="/article/articlestatic/25/">Warenkorb / Wunschzettel</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
         <td class="StMpLvThree">- <a href="/article/articlestatic/26/">Kasse</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td colspan="2">&nbsp;</td>
         <td class="StMpLvThree">- <a href="/article/articlestatic/27/">Anmeldung</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
       </tr>
       <tr>
         <th colspan="3">Anmeldung</th>
         <th>&nbsp;</th>
       </tr>
       <tr>
         <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/user/login/">Login</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/user/user/new/">Registrierung</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
       </tr>
       <tr>
         <th colspan="3">Benutzer</th>
         <th>&nbsp;</th>
       </tr>
       <tr>
         <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="1" height="3" alt="" /></td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/cart/">Ihr Warenkorb</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/wishlist/">Ihr Wunschzettel</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/sendwishlist/">Wunschzettel senden</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/findwishlist/">Wunschzettel finden</a></td>
         <td>&nbsp;</td>
       </tr>
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/voucherview/">Gutschein abfragen</a></td>
         <td>&nbsp;</td>
       </tr>       
       <tr>
         <td>&nbsp;</td>
         <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="/trade/orderlist/">Ihre Bestellungen</a></td>
         <td>&nbsp;</td>
       </tr>       
       <tr>
	  <td colspan="4"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="5" alt="" /></td>
       </tr>
     </table>
   </td>
  </tr>
</table>