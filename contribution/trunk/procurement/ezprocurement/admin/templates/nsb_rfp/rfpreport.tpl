<div id="LayerContent" class="LayerContent" >
<h1>{intl-rfp_report}</h1>

<!-- BEGIN stat_deleted_tpl -->
<div style="font-size: 12px; font-weight: bold; color: orange;">{intl-stat_deleted}</div><br />
<!-- END stat_deleted_tpl -->

<!-- BEGIN all_deleted_tpl -->
<div style="font-size: 12px; font-weight: bold; color: orange;">{intl-all_deleted}</div><br />
<!-- END all_deleted_tpl -->

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
			<a class="subdiv" style="font-size: 14px; font-weight:normal; text-decoration: none;" href="{rfp_link}">{rfp_name}</a>
<br />

			 <a class="subdiv" style="font-size: 13px; font-weight:normal; color: #7c7a6e; text-decoration: none;" href="{rfp_uri_encoded}">{rfp_uri}</a>

</td>
<td align="right" class="{bg_color}">
		<span>
			  <span style="font-decoration: underline;">
        		    <b>{item_view_count}</b></span>
	
  	             	</span>
		   </span>
</td>
<!-- BEGIN clean_by_id_tpl -->
<td width="20%">
<a href="/procurement/report/clean/{rfp_id}" style="padding-left: 4px; font-size: 10px;" >Remove</a>
</td>
<!-- END clean_by_id_tpl -->
</tr>

<!-- BEGIN viewed_by_user_tpl -->
<tr>
<td class="{user_bg_color}">
			<span>
	<!-- BEGIN not_anonymous_user_tpl -->
    <a class="subdiv" href="/procurement/author/view/{rfp_downloaded_user_id}">{rfp_download_user_name}</a>
	<!-- END not_anonymous_user_tpl -->

	<!-- BEGIN anonymous_user_tpl -->
	{rfp_download_user_name}
	<!-- END anonymous_user_tpl -->
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

<!-- BEGIN clear_all_stats_tpl -->  
<br /><br />
<a href="/procurement/report/clean" style="margin-right: 12px;">{intl-clear_all_stats}</a> 

<!-- BEGIN stats_depricated_tpl -->
| <a href="/procurement/insertstats/10" style="margin-left: 12px; margin-right: 12px;">Insert 10 Stats</a> | <a href="/procurement/report/clean/user/1" style="margin-right: 12px; margin-left: 12px;">{intl-clear_stats_by_user}</a>
<!-- END stats_depricated_tpl -->

<!-- END clear_all_stats_tpl -->

</form>
</div>


