<h1>Sitemap</h1>
<hr noshade="noshade" size="1" />
<br />

<table cellpadding="5" border="0">
<tr>
<td>
<table bgcolor="#dddddd" width="230" border="0" cellpadding="0" cellspacing="0" align="left">
    <tr>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="100%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
    </tr>
<?php

include_once( "eztrade/classes/ezproductcategory.php" );

$category = new eZProductCategory();
$categoryArray = $category->getTree( );

$shim   =  '<td>&nbsp;</td>';

foreach ( $categoryArray as $catItem )
{
    $id = $catItem[0]->id();
    $name = $catItem[0]->name();
    //$description = $catItem[0]->description();

    if ( $catItem[1] == 1 )
    {
        $indent  = '';
        $indent2 = '<th colspan="3"><a class="SiteMapLnkOne" href="http://' . $HTTP_HOST . '/trade/productlist/' . $id . '/">' . $name . '</a></th>';
    }

    elseif  ( $catItem[1] == 2 )
    {
        $indent  =  $shim;
        $indent2 = '<td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://' . $HTTP_HOST . '/trade/productlist/' . $id . '/">' . $name . '</a></td>';
    }

    elseif  ( $catItem[1] == 3 )
    {
        $indent  = $shim . $shim;
        $indent2 = '<td class="StMpLvThree">- <a href="http://' . $HTTP_HOST . '/trade/productlist/' . $id . '/">' . $name . '</a></td>';
    }

    if ( $indent == '' )
        print( "<tr>$indent $indent2 <th>&nbsp;&nbsp;</th></tr>
                <tr><td colspan=\"4\"><img src=\"/sitedesign/mygold/images/shim.gif\" width=\"1\" height=\"3\" alt=\"\" /></td></tr>" );
    else
        print( "<tr>$indent $indent2 <td>&nbsp;&nbsp;</td></tr>" );
}

?>
</table>
</td><td valign="top">
<table bgcolor="#dddddd" width="230" border="0" cellpadding="0" cellspacing="0">
    <tr>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="100%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
         <td width="1%"><img src="/sitedesign/mygold/images/shim.gif" width="10" height="1" alt="" /></td>
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
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/11/">Filialen</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/feedback/">Kontakt</a></td>
        <td>&nbsp;</td>
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
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/10/">Garantie</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/17/">AGB</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/12/">Sicherheit</a></td>
        <td>&nbsp;</td>
    </tr>
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
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articleuncached/22/">Ringgr&ouml;&szlig;e</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/23/">Einkaufsanleitung - Übersicht</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td class="StMpLvThree">- <a href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/24/">Auswahl</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td class="StMpLvThree">- <a href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/25/">Warenkorb / Wunschzettel</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td class="StMpLvThree">- <a href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/26/">Kasse</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td colspan="2">&nbsp;</td>
        <td class="StMpLvThree">- <a href="http://<?php echo $HTTP_HOST; ?>/article/articlestatic/27/">Anmeldung</a></td>
        <td>&nbsp;</td>
    </tr>
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
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/user/login/">Login</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/user/user/new/">Registrierung</a></td>
        <td>&nbsp;</td>
    </tr>
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
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/trade/cart/">Ihr Warenkorb</a></td>
        <td>&nbsp;</td>
    </tr>
     <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/trade/wishlist/">Ihr Wunschzettel</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/trade/sendwishlist/">Wunschzettel senden</a></td>
        <td>&nbsp;</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td class="StMpLvTwo" colspan="2">- <a class="SiteMap" href="http://<?php echo $HTTP_HOST; ?>/trade/findwishlist/">Wunschzettel finden</a></td>
        <td>&nbsp;</td>
    </tr>
</table>
</td></tr></table>