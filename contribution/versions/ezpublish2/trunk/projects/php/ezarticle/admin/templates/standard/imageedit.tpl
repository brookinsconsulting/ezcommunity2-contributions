<form method="post" action="/article/articleedit/imageedit/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>Bildeopplasting: {article_name}</h1>

<hr noshade="noshade" size="4" />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<p class="boxtext">Tittel:</p>
	<input type="text" size="40" name="Name" value="{name_value}"/>

	<p class="boxtext">Bildetekst:</p>
	<input type="text" size="40" name="Caption" value="{caption_value}"/>

	<p class="boxtext">Bilde:</p> {image_file_name}
	<input size="40" name="userfile" type="file" />
	</td>

	<td>
	<img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
	</td>
</tr>
</table>

<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td>
	<input type="hidden" name="ArticleID" value="{article_id}" />
	<input type="hidden" name="ImageID" value="{image_id}" />
	<input type="hidden" name="Action" value="{action_value}" />
	<input class="okbutton" type="submit" value="OK" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>

	<form method="post" action="/article/articleedit/imagelist/{article_id}/">
	<input class="okbutton" type="submit" value="Avbryt" />
	</form>

	</td>

</tr>
</table>



