<h1>search</h1>

<form action="/trade/search/" method="post">
<input type="text" name="Query" size="10" />
<input type="submit" value="{intl-search_button}" />
</form>

<!-- BEGIN product_tpl -->
<tr>
	<td>

	<a href="/trade/productview/{product_id}/{category_id}/"><h2>{product_name}</h2></a><br>
    <!-- BEGIN image_tpl -->
    <table align="right">
    <tr>
        <td>
        <img src="{thumbnail_image_uri}" border="0" width="{thumbnail_image_width}" height="{thumbnail_image_height}" />
        </td>
    </tr>
    <tr>
        <td>
        {thumbnail_image_caption}
        </td>
    </tr>
    </table>
    <!-- END image_tpl -->

    {product_intro_text}

<br>
	{product_price}<br>

	</td>
</tr>
<!-- END product_tpl -->

<a href="/trade/search/?Query={query}&Limit={limit}&Offset={prev_offset}"><-previous</a>
<a href="/trade/search/?Query={query}&Limit={limit}&Offset={next_offset}">next-></a>
