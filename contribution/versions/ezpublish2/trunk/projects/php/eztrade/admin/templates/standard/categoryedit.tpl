<form method="post" action="/trade/categoryedit/{action_value}/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{head_line}</h1>

<hr noshade="noshade" size="4" />

<p class="boxtext">{intl-name}:</p>
<input class="box" type="text" size="40" name="Name" value="{name_value}"/>
<br /><br />

<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
	<td width="50%">

	<p class="boxtext">{intl-category}:</p>
	<select name="ParentID">
	<option value="0">{intl-top_level}</option>
	<!-- BEGIN value_tpl -->
	<option value="{option_value}" {selected}>{option_level}{option_name}</option>
	<!-- END value_tpl -->
	</select>
	
	</td>
  	<td width="50%">
	<p class="boxtext">{intl-sort_mode}:</p>
	<select name="SortMode">

	<option {1_selected} value="1">{intl-publishing_date}</option>
	<option {2_selected} value="2">{intl-alphabetic_asc}</option>
	<option {3_selected} value="3">{intl-alphabetic_desc}</option>
	<option {4_selected} value="4">{intl-absolute_placement}</option>

	</select>

	</td>
</tr>
</table>


<p class="boxtext">{intl-description}:</p>
<textarea class="box" rows="5" cols="40" name="Description">{description_value}</textarea>
<br /><br />

<p class="boxtext">{intl-th_type_current_image}:</p>

<!-- BEGIN image_item_tpl -->
<img src="{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" />
<div><input type="checkbox" name="DeleteImage"><span class="p">{intl-delete_image}</span><div /><br />
<!-- END image_item_tpl -->

<input class="box" size="40" name="ImageFile" type="file" />
<br /><br />
<input class="stdbutton" type="submit" name="Browse" value="{intl-browse}" />
<br /><br />


<hr noshade="noshade" size="4" />

<input type="hidden" name="CategoryID" value="{category_id}" />
<input class="okbutton" type="submit" value="OK" />
<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />

</form>
