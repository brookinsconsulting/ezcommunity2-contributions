<tr>
	<td class="{td_class}">
	{image_name}
	</td>
	<td class="{td_class}">
	<img src="{image_url}" width="{image_width}" height="{image_height}" border="2" />
	</td>
	<td class="{td_class}">
	<input type="radio" {main_image_checked} name="MainImageID" value="{image_id}" />
	</td>
	<td class="{td_class}">
	<input type="radio" {thumbnail_image_checked} name="ThumbnailImageID" value="{image_id}" />
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/imageedit/edit/{image_id}/{product_id}/">[ Rediger ]</a>
	</td>
	<td class="{td_class}">
	<a href="/trade/productedit/imageedit/delete/{image_id}/{product_id}/">[ Slett ]</a>
	</td>	
</tr>