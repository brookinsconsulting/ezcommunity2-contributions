<h1>Produktvisning</h1>

<hr noshade size="4"/>
/ <a href="/trade/productlist/0/">Hovedkategori</a> / 

<!-- BEGIN path_tpl -->
<a href="/trade/productlist/{category_id}/">{category_name}</a> / 
<!-- END path_tpl -->

<hr noshade size="4"/>


<h2>{title_text}</h2>

<!-- BEGIN main_image_tpl -->

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

<!-- END main_image_tpl -->

<p>
{description_text}
</p>

<table width="100%" cellspacing="0" cellpadding="7">
<tr>

<!-- BEGIN image_tpl -->

<td class="bglight">

<table>
<tr>
	<td valign="top">
	<img src="{image_url}" border="0" alt="{image_caption}" width="{image_width}" height="{image_height}"/>
	</td>
</tr>
<tr>
	<td valign="top">
	<p class="small">
	{image_caption}
	</p>
	</td>
</tr>
</table>
&nbsp;

</td>

<!-- END image_tpl -->

</tr>
</table>
<form action="/trade/cart/add/{product_id}/" method="post">

<!-- BEGIN option_tpl -->

<table width="100%" border="0">
<tr>
	<th colspan="2">
	{option_name}
	</th>
</tr>
<tr>
	<td width="20%">
	<input type="hidden" name="OptionIDArray[]" value="{option_id}" />
	<select name="OptionValueArray[]">

	<!-- BEGIN value_tpl -->
	<option value="{value_id}">{value_name}</option>
	<!-- END value_tpl -->

	{value_list}
	</select>
	</td>

	<td width="80%">
	{option_description}
	</td>
</tr>
</table>

<!-- END option_tpl -->

<input type="submit" name="Cart" value="kjøp" />
<input type="submit" name="WishList" value="Ønskeliste" />
</form>
