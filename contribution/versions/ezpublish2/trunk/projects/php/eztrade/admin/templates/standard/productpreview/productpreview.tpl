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
	{image_list}
</tr>
</table>

{option_list}

<form action="/trade/productedit/edit/{product_id}/" method="post">

<input type="submit" value="tilbake" />

</form>

