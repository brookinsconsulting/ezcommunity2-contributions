<div id="LayerContent" class="LayerContentL" style=""> 

<div class="header" style="position:relative;">

	<!-- BEGIN rfp_path_header_tpl -->
		<span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/0/">{intl-top_level}</a>
	<!-- END rfp_path_header_tpl -->

        <!-- BEGIN rfp_path_headers_tpl -->
		<div style="">
		 <span class="body"><a href="/procurement/archive/10/">North Slope Borough</a></span><br />
		 <span class="body">Request for proposals</span>
		 <br /><br />
		</div>
        <!-- END rfp_path_headers_tpl -->

        <!-- BEGIN rfp_path_headers2_tpl -->
                <span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/10/">{intl-north_slope_burough_category}</a>
        <!-- END rfp_path_headers2_tpl -->

	<!-- BEGIN path_item_tpl -->
                <span class="subdiv">/</span>
		<a class="subdiv" href="{www_dir}{index}/procurement/archive/{category_id}/">{category_name}</a>
	<!-- END path_item_tpl -->

	<!-- BEGIN current_image_item_tpl -->
		<img src="{www_dir}{current_image_url}" alt="{current_image_caption}" width="{current_image_width}" height="{current_image_height}" border="0" />
	<!-- END current_image_item_tpl -->

<!-- BEGIN category_list_tpl -->
<!--
	<div style="padding: 10px;">
          <span class="body"><a href="/procurement/archive/10/">North Slope Borough</a></span><br>
          <span class="body">Request for proposals</span>
        </div>
-->

<div style="">
     
<!-- BEGIN category_item_tpl -->
	<!-- BEGIN image_item_tpl -->
	<img src="{www_dir}{image_url}" alt="{image_caption}" width="{image_width}" height="{image_height}" border="0" />
	<!-- END image_item_tpl -->
	<!-- BEGIN no_image_tpl -->
	<!-- END no_image_tpl -->
	
	<span class="body"><a href="{www_dir}{index}/procurement/archive/{category_id}/">{category_name}</a></span><br>
	<span class="body">{category_description}</span>
<!-- END category_item_tpl -->
	
	<br />
	<span><img src="/images/1x1.gif" height="1" width="240" border="0" alt="" /></span>

</div>

</div>

<!-- END category_list_tpl -->

<!-- BEGIN rfp_list_tpl -->
	<div style="">
	<br />
	<span class="subdiv">{intl-rfp_name}</span><br />
	<!-- BEGIN rfp_item_tpl -->

        <!-- BEGIN headline_with_link_tpl -->
	<span class="subdiv"><br /><a href="{www_dir}{index}/procurement/view/{rfp_id}/1/{category_id}/">{rfp_name}</a><br /></span>
        <!-- END headline_with_link_tpl -->
        <!-- BEGIN headline_without_link_tpl -->
        <span class="subdiv"><br /><a href="{www_dir}{index}/procurement/view/{rfp_id}/1/{category_id}/">{rfp_name}</a><br /></span>
        <!-- END headline_without_link_tpl -->
<!-- END rfp_item_tpl -->
	</div>
<!-- END rfp_list_tpl -->


<!-- BEGIN type_list_tpl -->
<div>
	<br />
	<!-- BEGIN type_list_previous_tpl -->
	<span class="body"><a href="{www_dir}{index}/procurement/archive/{category_current_id}/{item_previous_index}">&lt;&lt;&nbsp;{intl-previous}</a></span>
	<!-- END type_list_previous_tpl -->
	<!-- BEGIN type_list_previous_inactive_tpl -->
	<!-- END type_list_previous_inactive_tpl -->
	<!-- BEGIN type_list_item_list_tpl -->
	<!-- BEGIN type_list_item_tpl -->
	<span class="body"><a href="{www_dir}{index}/procurement/archive/{category_current_id}/{item_index}">{type_item_name}</a></span>
	<!-- END type_list_item_tpl -->
	<!-- BEGIN type_list_inactive_item_tpl -->
	<span class="body">{type_item_name}</span>
	<!-- END type_list_inactive_item_tpl -->
	<!-- END type_list_item_list_tpl -->
	<!-- BEGIN type_list_next_tpl -->
	<span class="body">| <a href="{www_dir}{index}/procurement/archive/{category_current_id}/{item_next_index}">{intl-next}</a></span>
	<!-- END type_list_next_tpl -->
	<!-- BEGIN type_list_next_inactive_tpl -->
	<!-- END type_list_next_inactive_tpl -->
</div>
<!-- END type_list_tpl -->


</div>