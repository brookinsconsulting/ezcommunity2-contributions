<form action="/article/topiclist" method="post">

<h1>{intl-topic_edit}</h1>

<hr size="4" noshade="noshade" />
<br />
<!-- BEGIN topic_list_tpl -->

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<th>
	{intl-topic_name}:
	</th>
	<th>
	{intl-topic_description}:
	</th>
</tr>
<!-- BEGIN topic_item_tpl -->
<tr>
	<td>
	<input type="hidden" name="IDArray[]" value="{id}" />
	<input type="text" size="30" name="Name[]" value="{topic_name}" />
	</td>
	<td>
	<input type="text" size="30" name="Description[]" value="{topic_description}" />
	</td>
	<td>
	<input type="checkbox" name="DeleteIDArray[]" value="{id}" />
	</td>
</tr>

<!-- END topic_item_tpl -->
</tr>
</table>
<br />
<!-- END topic_list_tpl -->

<hr size="4" noshade="noshade" />

<input class="stdbutton" type="submit" name="NewTopic" value="{intl-new}" />
<input class="stdbutton" type="submit" name="DeleteTopic" value="{intl-delete_selected}" />

<hr size="4" noshade="noshade" />

<input class="okbutton" type="submit" name="Store" value="{intl-store}" />


</form>
