
<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="this_image_caption" width="{this_image_width}" align="right" border="0">
<!-- END article_image_template -->


<!-- BEGIN article_here_template -->

<h1>{intl-delete_article}</h1>

<hr noshade size="4" />

<!-- END article_here_template -->

<!-- BEGIN article_item_template -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	{this_picture}
	<h2>{this_name}</h2>
	<p>
	{this_description}
	</p>
	<p class="pris">
	{this_price}
	</p>
	</td>
</tr>
</table>
<br />

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<form method="post" action="/{this_path}/{this_id}?delete+this">
	<input class="okbutton" name="form_delete" type="submit" value="{intl-delete}">
	</form>
	</td>
	<td>
	&nbsp;
	</td>
	
<!-- END article_item_template -->




<!-- BEGIN go_to_parent_template -->
	<td>
	<form method="post" action="/{this_path}/{this_canonical_parent_id}">
	<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}"></form>
	</form>
	</td>
</tr>
</table>

<!-- END go_to_parent_template -->



<!-- BEGIN go_to_self_template -->
	<td>
	<form method="post" action="/{this_path}/{this_id}">
	<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
	</form>
	</td>
</tr>
</table>

<!-- END go_to_self_template -->





<!-- These need to be here, even though they are empty! -->

<!-- BEGIN upload_picture_template -->

<!-- END upload_picture_template -->

<!-- BEGIN picture_uploaded_template -->

<!-- END picture_uploaded_template -->
