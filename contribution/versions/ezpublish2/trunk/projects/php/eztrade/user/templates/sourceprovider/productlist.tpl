<!-- BEGIN path_tpl -->

<!-- END path_tpl -->

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
<tr>
     <td align="right">
	 <form action="/trade/search/" method="post">
	       <input type="text" name="Query">
	       <input type="submit" name="search" value="{intl-search_button}">
         </form>
     </td>
</tr>
</table>
<table width="100%" cellspacing="0" cellpadding="0" border="0">

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>

<!-- BEGIN product_image_tpl -->
    <table align="right">
    <tr>
        <td>
	<a href="/trade/productview/{product_id}/{category_id}/">
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
	</a>
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

<!-- END product_list_tpl -->

