<div id="LayerContent" class="LayerContent" >
<h1>{intl-rfp_report}</h1>

<!-- BEGIN empty_rfp_header_tpl -->
<br />
<br />
<br />

<table class="list" width="75%" cellspacing="0" cellpadding="0" border="0">
<tr>
        <th>
        {intl-rfp_empty_record_set}
	</th>     
</tr>
</table>
<!-- END empty_rfp_header_tpl -->



<!-- BEGIN most_viewed_rfp_header_tpl -->
<span>	{intl-rfp_name}: </span>

<span align="right">
	<b>{intl-view_count}:</b>
</span>
<!-- END most_viewed_rfp_header_tpl -->


<!-- BEGIN most_viewed_rfp_tpl -->

<table width="100%" cellpadding="2" cellspacing="0" >
<tr>
<td class="{bg_color}">
		   <span class="" style="">
			<a class="subdiv" style="font-size: 14px; font-weight:normal; text-decoration: none;" href="http://ladivaloca.org/index.php{rfp_uri_encoded}">{rfp_name}</a> 
<br />

			 <a class="subdiv" style="font-size: 13px; font-weight:normal; color: #7c7a6e; text-decoration: none;" href="http://ladivaloca.org/index.php{rfp_uri_encoded}">{rfp_uri}</a>

</td>
<td align="right" class="{bg_color}">
		<span>
			  <span style="font-decoration: underline;">
        		    <b>{item_view_count}</b></span>
	
  	             	</span>
		   </span>
</td>
</tr>

<!-- BEGIN viewed_by_user_tpl -->
<tr>
<td class="{user_bg_color}">
			<span>
		 <a class="subdiv" href="http://ladivaloca.org/index.php/rfp/author/view/{rfp_downloaded_user_id}">{rfp_download_user_name}</a>
			</span>

</td>
<td  class="{user_bg_color}" align="right">
		          
                <span> {user_view_count} </span>

</td>
</tr>
<!-- END viewed_by_user_tpl -->
</table>

		<span> &nbsp; </span>

<!-- END most_viewed_rfp_tpl -->

</div>


