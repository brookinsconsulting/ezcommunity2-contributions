<h1>Bildeopplasting - {product_name}</h1>

<form method="post" action="/trade/productedit/imageedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">
<input type="hidden" name="docp" value="TRUE">

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	Tittel:
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Name" value="{name_value}"/>
	</td>
</tr>
<tr>
	<td>
	Bildetekst:
	</td>
</tr>
<tr>
	<td>
	<input type="text" size="20" name="Caption" value="{caption_value}"/>
	</td>
</tr>
<tr>
	<td>
	Bilde:
	</td>
</tr>
<tr>
	<td>
	<input name="userfile" type="file" />
	</td>
</tr>
<tr>
	<td>
    <input type="hidden" name="ProductID" value="{product_id}" />
    <input type="hidden" name="ImageID" value="{image_id}" />
    <input type="hidden" name="Action" value="{action_value}" />
    <input type="submit" value="OK" />
	</td>
</tr>
</table>
</form>


