<h1>Produktvisning</h1>

<hr noshade size="4"/>
/ <a href="/trade/productlist/0/">Hovedkategori</a> / {category_path}
<hr noshade size="4"/>


<h2>{title_text}</h2>

<p>
<table align="right">
<tr>
	<td>
	<img src="{main_image_uri}" border="0" width="{main_image_width}" height="{main_image_height}" />
	</td>
</tr>
<tr>
	<td>
	{main_image_caption}
	</td>
</tr>
</table>
{intro_text}
</p>

<p>
{description_text}
</p>

<table width="100%" cellspacing="0" cellpadding="7">
<tr>
	{image_list}
</tr>
</table>
<form action="/trade/cart/add/{product_id}/" method="post">
{option_list}

<input type="submit" name="Cart" value="kjøp" />
<input type="submit" name="WishList" value="Ønskeliste" />
</form>
