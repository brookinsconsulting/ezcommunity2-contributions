
<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="{this_image_caption}" width="{this_image_width}" align="right" border="0">
<!-- END article_image_template -->



<!-- BEGIN article_here_template -->

<h1>{intl-preview_article}</h1>

<hr noshade size="4" />

<br />

<!-- END article_here_template -->

<!-- BEGIN article_item_template -->

{this_picture}
<h2>{this_name}</h2>
<p>
{this_description}
</p>
<p class="pris">
{this_price}
</p>

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<form method="post" action="/{this_path}/{this_id}?edit+this">
	<input class="okbutton" type="submit" value="{intl-edit}">
	<input class="okbutton" name="form_publish" type="submit" value="{intl-publish}">
	</td>
	<td>
	&nbsp;
	</td>
<!-- END article_item_template -->




<!-- BEGIN go_to_parent_template -->
	<td>
	<form action="/{this_path}/{this_canonical_parent_id}">
	<input class="okbutton" type="submit" name="form_abort" value="{intl-abort}">
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


<!-- BEGIN picture_uploaded_template -->
{this_image_name}
<input name="ImageID" type="hidden" value="{this_image_id}">
<!-- END picture_uploaded_template -->



<!-- These need to be here, even though they are empty! -->

<!-- BEGIN upload_picture_template -->

<!-- END upload_picture_template -->
