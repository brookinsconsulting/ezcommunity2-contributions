<h1>Produktvisning</h1>

<hr noshade>

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
<!-- END image_tpl -->

</td>

</tr>
</table>

<!-- BEGIN option_tpl -->
<table width="100%" border="0">
<tr>
	<th colspan="2">
	{option_name}
	</th>
</tr>
<tr>
	<td width="20%">
	<select>
	<!-- BEGIN value_tpl -->
	<option value="{value_id}">{value_name}</option>
	<!-- END value_tpl -->
	</select>
	</td>

	<td width="80%">
	{option_description}
	</td>
</tr>
</table>

<!-- END option_tpl -->

<form action="/trade/productedit/edit/{product_id}/" method="post">

<input type="submit" value="tilbake" />

</form>

