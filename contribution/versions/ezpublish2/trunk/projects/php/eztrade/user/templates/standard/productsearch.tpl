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

<!-- BEGIN previous_tpl -->
<a href="/forum/search/?Offset={prev_offset}&URLQueryString={url_query_string}">
{intl-prev}
</a>
<!-- END previous_tpl -->

&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

<!-- BEGIN next_tpl -->
<a href="/forum/search/?Offset={next_offset}&URLQueryString={url_query_string}">
{intl-next}
</a>
<!-- END next_tpl -->
