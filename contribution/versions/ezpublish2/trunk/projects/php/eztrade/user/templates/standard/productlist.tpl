<h1>Produktoversikt</h1>

<hr noshade="noshade" size="4"/>

<img src="/eztrade/images/path-arrow.gif" height="10" width="15" border="0">
<a class="path" href="/trade/productlist/0/">Hovedkategori</a>

<!-- BEGIN path_tpl -->
<img src="/eztrade/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/trade/productlist/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade="noshade" size="4" />
<!-- BEGIN category_list_tpl -->

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Kategori:</th>
	<th>Beskrivelse:</th>
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

<hr noshade size="4"/>

<!-- END category_list_tpl -->


<!-- BEGIN product_list_tpl -->

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
        </td>
    </tr>
    <tr>
        <td class="pictext">
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
<!-- END product_image_tpl -->

<p>{product_intro_text}</p>

<!-- BEGIN price_tpl -->
<p class="pris">{product_price}</p>
<!-- END price_tpl -->

	</td>
</tr>
<!-- END product_tpl -->

</table>

<!-- END product_list_tpl -->

