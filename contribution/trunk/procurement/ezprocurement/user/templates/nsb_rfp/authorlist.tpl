<div id="LayerContent" class="LayerContent" style="">
<h1>{intl-head_line}</h1>

<table width="90%">
<tr>
<td>
    <span style="width:90%;">{intl-author_info}</span>
	<br />
	<br />
		<div class="list" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
                   <span class="" style="">
			<span class="subdiv"><b>{intl-author}:</b></span>

                        <span class="" style="padding-right: 5px; position:absolute; right:0px;">
                          <span class="subdiv"><b>{intl-count}:</b></span>
                        </span>
                   </span>
                </div>

<!-- BEGIN author_item_tpl -->
		<div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
		   <span class="{td_class}" style="font-size: 12px">
			<!-- BEGIN company_item_tpl -->
				<a style="text-decoration: none; color: #000000;"  href="{www_dir}{index}/procurement/company/view/{planholder_company_id}/">{planholder_company_name}</a></span> : 
			<!-- END company_item_tpl -->

			<a style="text-decoration: none; color: #000000;"  href="{www_dir}{index}/procurement/holder/view/{author_id}/">{author_name}</a></span>

		        <span class="{td_class}" style="font-size: 10px; padding-right: 5px; position:absolute; right:0px;"> 
			  {rfp_count}
  	             	</span>
		   </span>
		</div>
<!-- END author_item_tpl -->

</td>
</tr>
</table>

<br /></br />
<br /></br />
<br /></br />
<br /></br />
<br /></br />
<br /></br />
<br /></br />

</div>

