
<div id="LayerContent" class="LayerContent" style="">
<h1>{intl-head_line}</h1>

<table width="90%">
<td>
<tr>

<span style="width:90%;">{intl-author_info}</span>
<br />
<br />

<div class="list" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
                   <span class="" style="">
			<span class="subdiv"><b><a href="{www_dir}{index}/rfp/author/list/name">{intl-author}</a>:</b></span>

                        <span class="" style="padding-right: 5px; position:absolute; right:0px;">
                          <span class="subdiv"><b><a href="{www_dir}{index}/rfp/author/list/count">{intl-count}</a>:</b></span>
                        </span>
                   </span>
                </div>

<!-- BEGIN author_item_tpl -->
		<div class="{td_class}" style="position:relative; left:0px; right:0px; padding: 3px; z-index:1">
		   <span class="{td_class}" style="font-size: 12px">
			<a href="{www_dir}{index}/rfp/author/view/{author_id}/">{author_name}</a></span>

		        <span class="{td_class}" style="font-size: 10px; padding-right: 5px; position:absolute; right:0px;"> 
			  {rfp_count}
  	             	</span>
		   </span>
		</div>
<!-- END author_item_tpl -->

</div>

</td>
</tr>
</table>