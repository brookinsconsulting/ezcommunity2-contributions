
<!-- BEGIN current_category_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<tr>
   <td>
<a href="/imagecatalogue/image/list/{category_id}/"><img src="/ezimagemanager/user/{image_dir}/folder.png" alt="" width="32" height="32" />{category_name}</a><br />
   </td>
   <td>
   <p>
   {current_category_description}
   </p>
   </td>
</tr>
</table>

<!-- END current_category_tpl -->

<hr noshade="noshade" size="4" />

<img src="/imagecatalogue/user/{image_dir}/path-arrow.gif" height="10" width="15" border="0" alt="">
<a class="path" href="/imagecatalogue/image/list/0/">{intl-image_root}</a>

<!-- BEGIN path_item_tpl -->
<img src="/imagecatalogue/user/{image_dir}/path-slash.gif" height="10" width="20" border="0" alt="">
<a class="path" href="/imagecatalogue/image/list/{category_id}/">{category_name}</a>
<!-- END path_item_tpl -->

<hr noshade="noshade" size="4" />

<!-- BEGIN category_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >

<!-- BEGIN category_tpl -->
<tr>
        <!-- BEGIN category_read_tpl -->
	<td>
	<a href="/imagecatalogue/image/list/{category_id}/"><img src="/imagecatalogue/user/{image_dir}/folder.png" alt="" width="32" height="32" />{category_name}</a><br />
	</td>
        <!-- END category_read_tpl -->
        <!-- BEGIN category_write_tpl -->
	<td>
	<a href="/imagecatalogue/category/edit/{category_id}/">edit</a>
	<a href="/imagecatalogue/category/delete/{category_id}/">delete</a>
	</td>
        <!-- END category_write_tpl -->
</tr>
<!-- END category_tpl -->

</table>
<!-- END category_list_tpl -->

<form method="post" action="/imagecatalogue/image/new/" enctype="multipart/form-data">
<!-- BEGIN image_list_tpl -->
<table width="100%" border="0" cellspacing="0" cellpadding="4" >
<!-- BEGIN image_tpl -->
{begin_tr}
	<!-- BEGIN read_tpl -->
	<td>
	<a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/image/list/{main_category_id}/"><img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a><br />
	<p class="pictext">{image_caption}</p>
	</td>
	<!-- END read_tpl -->
	<!-- BEGIN write_tpl -->

	<!-- END write_tpl -->
{end_tr}

<!-- END image_tpl -->
<!-- BEGIN detail_view_tpl -->
<tr>
	<!-- BEGIN detail_read_tpl -->
	<td>
	<a href="/imagecatalogue/imageview/{image_id}/?RefererURL=/imagecatalogue/image/list/{main_category_id}/"><img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" /></a>
	</td>
	<td class="{td_class}">
	{image_size}Kb
	</td>
	<td class="{td_class}" width="1%">
	<a href="/imagecatalogue/download/{image_id}/{original_image_name}/">download<br />{original_image_name}</a><br />
	</td>
	<!-- END detail_read_tpl -->
	<!-- BEGIN detail_write_tpl -->
	<td class="{td_class}" width="1%">
	<a href="/imagecatalogue/image/edit/{image_id}/">edit</a><br />
	</td>
	<td class="{td_class}" width="1%">
	<input type="checkbox" name="ImageArrayID[]" value="{image_id}">
	</td>
	<!-- END detail_write_tpl -->
</tr>
<!-- END detail_view_tpl -->
</table>
<!-- END image_list_tpl -->
<table cellspacing="0" cellpadding="4" border="0">
<tr>
        <td>
	<input type="submit" name="NewImage" value="{intl-new_image}">
	</td>
        <td>
	<input type="submit" name="NewCategory" value="{intl-new_category}">
	<input type="hidden" name="CategoryID" value="{main_category_id}">
	</td>
        <td>
	<input type="submit" name="Delete" value="{intl-delete}">
	</td>
</tr>
</form>
<form method="post" action="/imagecatalogue/image/list/{main_category_id}/" enctype="multipart/form-data">
<input type="hidden" name="Detail" value="{is_detail_view}">
<tr>
        <!-- BEGIN normal_view_button -->
        <td>
	<input type="submit" name="NormalView" value="{intl-normal_view}">
	</td>
        <!-- END normal_view_button -->
        <!-- BEGIN detail_view_button -->
        <td>
	<input type="submit" name="DetailView" value="{intl-detail_view}">
	</td>
        <!-- END detail_view_button -->

</tr>
</form>
</table>

