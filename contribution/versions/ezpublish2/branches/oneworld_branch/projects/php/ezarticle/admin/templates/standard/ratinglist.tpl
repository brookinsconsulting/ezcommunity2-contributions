<h1>{intl-article_ratings}</h1>

<hr size="4" noshade="noshade" />

<!-- BEGIN article_rate_list_tpl -->

<table width="100%" >
<tr>
	<th>
	{intl-article_name}
	</th>
	<th>
	<a href="/article/rating/avgrate/">{intl-avg_rate}</a>
	</th>
	<th>
	<a href="/article/rating/maxrate/">{intl-max_rate}</a>
	</th>
	<th>
	<a href="/article/rating/minrate/">{intl-min_rate}</a>
	</th>
	<th>
	<a href="/article/rating/ratecount/">{intl-rate_count}</a>
	</th>
</tr>

<!-- BEGIN article_rate_item_tpl -->
<tr>
	<td class="{td_class}">
	{article_name}
	</td>
	<td class="{td_class}">
	{avg_rate}
	</td>
	<td class="{td_class}">
	{max_rate}
	</td>
	<td class="{td_class}">
	{min_rate}
	</td>
	<td class="{td_class}">
	{rate_count}
	</td>
</tr>
<!-- END article_rate_item_tpl -->

</table>
<!-- END article_rate_list_tpl -->


<!-- BEGIN type_list_tpl -->
<br />
<table cellpadding="0" cellspacing="0" border="0">
<tr>
	<!-- BEGIN type_list_previous_tpl -->
	<td>
	<a class="path" href="{www_dir}{index}/article/rating/parent/{item_previous_index}/{sort_mode}/">&lt;&lt;&nbsp;{intl-previous}</a>&nbsp;
	</td>
	<!-- END type_list_previous_tpl -->

	<!-- BEGIN type_list_previous_inactive_tpl -->
	<td>
	&nbsp;
	</td>
	<!-- END type_list_previous_inactive_tpl -->

	<!-- BEGIN type_list_item_list_tpl -->

	<!-- BEGIN type_list_item_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/article/rating/parent/{item_index}/{sort_mode}/">{type_item_name}</a>&nbsp;
	</td>
	<!-- END type_list_item_tpl -->

	<!-- BEGIN type_list_inactive_item_tpl -->
	<td>
	|&nbsp;&lt;&nbsp;{type_item_name}&nbsp;&gt;&nbsp;
	</td>
	<!-- END type_list_inactive_item_tpl -->

	<!-- END type_list_item_list_tpl -->

	<!-- BEGIN type_list_next_tpl -->
	<td>
	|&nbsp;<a class="path" href="{www_dir}{index}/article/rating/parent/{item_next_index}/{sort_mode}/">{intl-next}&nbsp;&gt;&gt;</a>
	</td>
	<!-- END type_list_next_tpl -->

	<!-- BEGIN type_list_next_inactive_tpl -->
	<td>
	|&nbsp;
	</td>
	<!-- END type_list_next_inactive_tpl -->

</tr>
</table>
<!-- END type_list_tpl -->
