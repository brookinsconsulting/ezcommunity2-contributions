<h1 class="small"><span class="h1bigger">B </span>R U K T E&nbsp;&nbsp; <span class="h1bigger">B </span>Å T E R</h1>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
<tr>
	<td class="tdmini" width="1%" background="/images/gyldenlinje-strekk.gif"><img src="/images/gyldenlinje-venstre.gif" width="8" height="4" border="0" /><br /></td>
    <td class="tdmini" width="98%" background="/images/gyldenlinje-strekk.gif"><img src="/images/1x1.gif" width="1" height="1" border="0" /><br /></td>
    <td class="tdmini" width="1%" background="/images/gyldenlinje-strekk.gif"><img src="/images/gyldenlinje-hoyre.gif" width="8" height="4" border="0" /><br /></td>
</tr>
</table>

<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

<!-- BEGIN category_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>{intl-category}:</th>
	<th>{intl-description}:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/productlist/{category_id}/">{category_name}</a>&nbsp;
	</td>
	<td class="{td_class}">
	{category_description}&nbsp;
	</td>
</tr>
<!-- END category_tpl -->

</table>

<!-- END category_list_tpl -->

<br />

<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="2" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td valign="top">
<!-- BEGIN product_image_tpl -->
	<a href="/trade/productview/{product_id}/{category_id}/">
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
<!-- END product_image_tpl -->
	</td>
	<td valign="top">
	<a href="/trade/productview/{product_id}/{category_id}/">{product_name}</a>

	<p>{product_intro_text}</p>

<!-- BEGIN price_tpl -->
<p class="pris">{product_price}</p>
<!-- END price_tpl -->

     	</td>
</tr>
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

