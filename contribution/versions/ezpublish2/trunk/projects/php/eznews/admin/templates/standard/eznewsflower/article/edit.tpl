<form method="post" action="/{this_path}/{this_id}?edit+this" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-create_page_title}</h1>


<hr noshade size="4" />

<p class="boxtext">{intl-create_title}</p>
<input name="Name" type="text" size="40" value="{this_name}">

<p class="boxtext">{intl-create_select_category}</p>
<select name="ParentID">
    <!-- BEGIN item_template -->
    <option  value="{item_id}" {Selected}>{item_name}</option>
    <!-- END item_template -->
</select>

<p class="boxtext">{intl-create_body}</p>
<textarea name="Story" cols="40" rows="20" wrap="soft">{this_description}</textarea>

<p class="boxtext">{intl-create_price}</p>
<textarea name="Price" cols="40" rows="3" wrap="soft">{this_price}</textarea>


<p class="boxtext">{intl-create_picture}</p>

<!-- BEGIN upload_picture_template -->
<input name="Image" type="file" size="40">
<!-- END upload_picture_template -->

<br />

<!-- BEGIN picture_uploaded_template -->
{intl-create_picture_uploaded}<br>
{intl-create_picture_name} {this_image_name}
<input name="ImageID" type="hidden" value="{this_image_id}">
<!-- END picture_uploaded_template -->

<!-- BEGIN article_image_template -->
<img src="{this_image}" height="{this_image_height}" alt="{this_image_caption}" width="{this_image_width}" align="right" border="0">
<!-- END article_image_template -->

<input type="hidden" name="ItemID" value="{this_id}">

<br /><br />

<hr noshade size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<td>
	<input class="okbutton" type="submit" value="Forhåndsvis" name="form_preview">
	</form>
	</td>
	<td>
	&nbsp;
	</td>
	<td>
	<form method="post" action="/{this_path}/{this_id}" >
	<input class="okbutton" type="submit" value="Avbryt" name="form_abort">
	</form>
	</td>
</tr>
</table>

<!-- These need to be here, even though they are empty! -->

<!-- BEGIN go_to_parent_template -->

<!-- END go_to_parent_template -->

<!-- BEGIN go_to_self_template -->

<!-- END go_to_self_template -->
