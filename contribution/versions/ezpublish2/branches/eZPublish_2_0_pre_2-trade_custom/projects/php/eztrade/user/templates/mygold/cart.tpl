<h1>{intl-cart}</h1>
<hr noshade="noshade" size="1" />

<!-- BEGIN empty_cart_tpl -->
<h2>{intl-empty_cart}</h2>
<!-- END empty_cart_tpl -->

<form action="/trade/cart/" method="post">
<!-- BEGIN cart_item_list_tpl -->
  <table width="100%" cellspacing="0" cellpadding="0" border="0">
	<tr> 
	  <th align="left">&nbsp;{intl-picture}:</th>
	  <th align="left">{intl-product_name}:</th>
	  <th align="left">{intl-options}:</th>
	  <th align="left">{intl-qty}:</th>
	  <th align="center">{intl-price}</th>
	  <th>&nbsp;</th>
	</tr>
    <!-- BEGIN cart_item_tpl -->
	<tr> 
	  <td class="{td_class}"> 
		<!-- BEGIN cart_image_tpl -->
		<img src="{product_image_path}" border="0" width="{product_image_width}" height="{product_image_height}" alt="{product_image_caption}"/> 
		<!-- END cart_image_tpl -->
		&nbsp; </td>
	  <td class="{td_class}"><a href="/trade/productview/{product_id}/">{product_name}</a></td>
	  <td class="{td_class}"> 
		<!-- BEGIN cart_item_option_tpl -->
		{option_name}: {option_value}<br>
		<!-- END cart_item_option_tpl -->
		&nbsp;</td>
	  <td class="{td_class}"> 
		<input type="hidden" name="CartIDArray[]" value="{cart_item_id}" />
		<input size="3" type="text" name="CartCountArray[]" value="{cart_item_count}" />
	  </td>
	  <td class="{td_class}" align="right">{product_price}</td>
	  <td class="{td_class}" align="right"><a href="/trade/cart/remove/{cart_item_id}/"  
onMouseOut="MM_swapImgRestore()" onMouseOver="MM_swapImage('ezuser{cart_item_id}-slett','','/eztrade/user/images/slettminimrk.gif',1)"><img name="ezuser{cart_item_id}-slett" border="0" src="/eztrade/user/images/slettmini.gif" width="16" height="16" align="top"></a> 
	  </td>
	</tr>
	<!-- END cart_item_tpl -->
	<tr> 
	  <td class="sum" colspan="3">&nbsp;</td>
	  <td class="sum" align="right">{intl-shipping}:</td>
	  <td class="sum" align="right">{shipping_cost}</td>
	  <td class="sum" align="right">&nbsp;</td>
	</tr>
	<tr> 
	  <td class="sum" colspan="3">&nbsp;</td>
	  <td class="sum" align="right">{intl-total}:</td>
	  <td class="sum" align="right">{cart_sum}</td>
	  <td class="sum" align="right">&nbsp;</td>
	</tr>
  </table>
<!-- END cart_item_list_tpl -->

  <hr noshade size="1" />

<!-- BEGIN cart_checkout_tpl -->
<table border="0">
<tr>
	<td>
	    <input class="okbutton" type="submit" name="DoCheckOut" value="{intl-checkout}" />
	</td>
	<td>
	    <input class="okbutton" type="submit" value="{intl-update}" />
	</td>
</tr>
</table>
<!-- END cart_checkout_tpl -->
<input type="hidden" name="Action" value="Refresh" />

</form>
