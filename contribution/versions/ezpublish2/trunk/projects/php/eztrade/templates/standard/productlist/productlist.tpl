<h1>Produktoversikt</h1>

<hr noshade="noshade" size="4"/>

<img src="/ezforum/images/path-arrow.gif" height="10" width="15" border="0">
<a class="path" href="/trade/productlist/0/">Hovedkategori</a>

<!-- BEGIN path_tpl -->
<img src="/ezforum/images/path-slash.gif" height="10" width="20" border="0">
<a class="path" href="/trade/productlist/{category_id}/">{category_name}</a>
<!-- END path_tpl -->

<hr noshade="noshade" size="4" />

<table class="list" width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
	<th>Kategori:</th>
	<th>Beskrivelse:</th>
</tr>

<!-- BEGIN category_tpl -->
<tr>
	<td class="{td_class}">
	<a href="/trade/productlist/{category_id}/">{category_name}</a>
	</td>
	<td class="{td_class}">
	{category_description}
	</td>
</tr>
<!-- END category_tpl -->

</table>

<hr noshade size="4"/>

<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>

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
<p class="pris">{product_price}</p>

	</td>
</tr>
<!-- END product_tpl -->

</table>

