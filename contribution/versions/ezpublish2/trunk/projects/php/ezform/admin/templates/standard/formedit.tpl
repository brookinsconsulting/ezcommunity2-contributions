<form action="{www_dir}{index}/form/form/{action_value}/{form_id}" method="post">

<h1>{intl-form_edit}</h1>

<hr noshade="noshade" size="4" />

<!-- BEGIN error_list_tpl -->
<h2 class="error">{intl-error}</h2>
<!-- BEGIN error_item_tpl -->
<div class="error">{error_message}.</div>
<!-- END error_item_tpl -->
<hr noshade="noshade" size="4" />
<br />
<!-- END error_list_tpl -->

<!-- BEGIN no_types_item_tpl -->
<h2 class="error">{intl-error_types}</h2>
<div class="error">{intl-no_types_1}.</div>
<div class="error">{intl-no_types_2}. {intl-no_types_3}.</div>
<hr noshade="noshade" size="4" />
<br />
<!-- END no_types_item_tpl -->


<!-- BEGIN form_item_tpl -->
<p class="boxtext">{intl-form_name}:</p>
<input type="text" class="box" size="40" name="formName" value="{form_name}" />
<br /><br />

<table width="100%" cellpaddning="0" cellspacing="0" border="0">
<tr>
    <td>
	<p class="boxtext">{intl-form_receiver}:</p>
    <input type="text" class="halfbox" size="20" name="formReceiver" value="{form_receiver}" />
	</td>
    <td>
	<p class="boxtext">{intl-form_cc}:</p>
    <input type="text" class="halfbox" size="20" name="formCC" value="{form_cc}" />
	</td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-form_completed_page}:</p>
    <input type="text" class="halfbox" size="20" name="formCompletedPage" value="{form_completed_page}" />
	</td>
    <td>
	<p class="boxtext">{intl-form_instruction_page}:</p>
    <input type="text" class="halfbox" size="20" name="formInstructionPage" value="{form_instruction_page}" />
	</td>
</tr>
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
    <td>
	<p class="boxtext">{intl-form_sender}:</p>
    <input type="text" class="halfbox" size="20" name="formSender" value="{form_sender}" />
	</td>
    <td valign="bottom">
   	<input type="checkbox" {checked} name="formSendAsUser" value="{form_send_as_user}" />&nbsp;<span class="boxtext">{intl-form_send_as_user}</span>
	</td>
</tr>
</table>
<input type="hidden" name="FormID" value="{form_id}" />
<!-- END form_item_tpl -->
<br/>

{page_list}

<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" name="NewPage" value="{intl-add_page}" />
<input class="stdbutton" type="submit" name="DeleteSelectedPages" value="{intl-delete_selected_pages}" />
<br/>


<hr noshade="noshade" size="4" />

<table cellspacing="0" cellpadding="0" border="0">
<tr>
	<td valign="top">
	<input class="okbutton" type="submit" name="OK" value="{intl-ok}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Preview" value="{intl-preview}" />
	</td>
	<td>&nbsp;</td>
	<td>
	<input class="okbutton" type="submit" name="Cancel" value="{intl-cancel}" />
	</td>
</tr>
</table>

</form>
