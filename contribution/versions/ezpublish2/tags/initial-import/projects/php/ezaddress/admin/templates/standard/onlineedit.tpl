<!-- BEGIN list_page -->
<form method="post" action="{form_path}/{action_value}/{item_id}/" enctype="multipart/form-data">

<!-- BEGIN type_edit_tpl -->
<h1>{intl-edit_headline}</h1>

<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN line_item_tpl -->
<table>
<tr>
	<td valign="top">
	<p class="boxtext">{intl-name}:</p>
	<input type="text" size="40" name="ItemName" value="{item_name}"/><br>
	</td>

	<td rowspan="2">
	<p class="boxtext">{intl-options}:</p>
	<input type="checkbox" name="PrefixLink" value="checked" {prefix_link_checked} />
	<span class="boxtext">{intl-prefix_link}</span><br />
	<input type="checkbox" name="PrefixVisual" value="checked" {prefix_visual_checked} />
	<span class="boxtext">{intl-prefix_visual}</span><br />
	</td>

</tr>
<tr>
	<td valign="top">
	<p class="boxtext">{intl-prefix}:</p>
	<input type="text" size="40" name="Prefix" value="{prefix}"/>
	</td>
</tr>
</table>

<input type="hidden" name="ItemID" value="{item_id}">
<input type="hidden" name="BackUrl" value="{back_url}">

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
<tr>
    <td>
	<input class="okbutton" type="submit" value="{intl-ok}" />
	</form>
	</td>
	<td>&nbsp;</td>
	<td>
	<form method="post" action="{item_back_command}/">
	<input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
</tr>
</table>

<!-- END line_item_tpl -->

<!-- BEGIN no_line_item_tpl -->
<p>{intl-no_such_item}!</p>
<!-- END no_line_item_tpl -->

<!-- END type_edit_tpl -->

<!-- BEGIN type_confirm_tpl -->
<h1>{intl-list_confirm_headline}</h1>
<hr noshade="noshade" size="4" />

<br />

<!-- BEGIN errors_tpl -->
<h3 class="error">{intl-error_headline}</h3>
<ul>

<!-- BEGIN error_no_confirm_item_tpl -->
<li>{intl-error_no_confirm}
<!-- END error_no_confirm_item_tpl -->

<!-- BEGIN error_count_change_item_tpl -->
<li>{intl-error_count_change}
<!-- END error_count_change_item_tpl -->

</ul>
<!-- END errors_tpl -->

<p>{intl-confirm_text}</p>
<p class="boxtext">{intl-confirm_item}:</p>
{confirm_item}
<p class="boxtext">{intl-confirm_count}:</p>
{item_count}
<br />

<input type="hidden" name="TypeCount" value="{item_count}">

<hr noshade="noshade" size="4" />

<table cellpadding="0" cellspacing="0" border="0">
    <tr>
        <td>
	    <input class="okbutton" type="submit" value="{intl-confirm_delete}">
	</form>
	</td>

	<td>
	<form method="post" action="{item_back_command}/">
	    <input class="okbutton" type="submit" name="Back" value="{intl-back}">
	</form>
	</td>
    </tr>
</table>

<!-- END type_confirm_tpl -->

<!-- END list_page -->
