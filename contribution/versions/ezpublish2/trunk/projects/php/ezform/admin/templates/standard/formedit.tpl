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

<h2>{intl-form_data_heading}:</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td valign="middle" width="1%"><input type="checkbox" {check_database_in_DataHandling} name="DataHandlingDatabase" value="database" id="database" /></td>
    <td valign="middle"><label for="database" class="boxtext">{intl-use_database}</label></td>
</tr>
<tr>
    <td valign="middle"><input id="send" type="checkbox" {check_send_in_DataHandling} name="DataHandlingSend" value="send" /></td>
    <td valign="middle"><label for="send" class="boxtext">{intl-use_e_mail}</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
        <table width="100%" cellpadding="0" cellspacing="0" border="0">
        <tr>
            <td width="50%">
	        <label for="{form_receiver}" class="boxtext">{intl-form_receiver}:</label><br />
            <input id="{form_receiver}" type="text" class="halfbox" size="20" name="formReceiver" value="{form_receiver}" />
	        </td>
            <td width="50%">
	        <label for="" class="boxtext">{intl-form_cc}:</label><br />
            <input type="text" class="halfbox" size="20" name="formCC" value="{form_cc}" />
	        </td>
        </tr>
        </table>
    </td>
</tr>
</table>

<h2>{intl-form_sender_heading}:</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td valign="middle" width="1%"><input type="radio" {DataSender_is_user} name="DataSender" value="user" id="user" /></td>
    <td valign="middle"><label class="boxtext" for="user" >{intl-user_is_sender}</label></td>
</tr>
<tr>
    <td valign="middle"><input type="radio" name="DataSender" {DataSender_is_predefined} value="predefined" id="predefined" /></td>
    <td valign="middle"><label for="predefined" class="boxtext">{intl-form_sender}</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
    <input type="text" class="halfbox" size="20" name="formSender" id="formSender" value="{form_sender}" />
    </td>
</tr>
</table>

<h2>{intl-form_instructions_heading}:</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td valign="middle" width="1%"><input type="radio" {hasInstructions-no-checked} name="hasInstructions" value="no" id="hasInstructions-no" /></td>
    <td valign="middle"><label class="boxtext" for="hasInstructions-no">{intl-no}</label></td>
</tr>
<tr>
    <td valign="middle"><input type="radio" name="hasInstructions" {hasInstructions-yes-checked} value="yes" id="hasInstructions-yes" /></td>
    <td valign="middle"><label for="hasInstructions-yes" class="boxtext">{intl-yes}</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="50%" class="boxtext">{intl-form_instruction_page}:</td>
            <td width="50%" class="boxtext">{intl-form_instruction_page_name}:</td>
        </tr>
        <tr>
            <td>
            <input type="text" class="halfbox" size="20" name="formInstructionPage" value="{form_instruction_page}" />
            </td>
            <td>
            <input type="text" class="halfbox" size="20" name="formInstructionPageName" value="{form_instruction_page_name}" />
            </td>
        </tr>
    </table>
    </td>
</tr>
<!-- BEGIN predefined_instructions_item_tpl -->
<tr>
    <td valign="middle"><input type="radio" name="hasInstructions" {hasInstructions-predefinend-checked} value="predefined" id="hasInstructions-predefined" /></td>
    <td valign="middle"><label for="hasInstructions-predefined" class="boxtext">{intl-yes}</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
    <table width="100%" cellspacing="0" cellpadding="0" border="0">
        <tr>
            <td width="50%" class="boxtext">{intl-Predefined}:</td>
            <td width="50%" class="boxtext">{intl-form_instruction_page_name}:</td>
        </tr>
        <tr>
            <td>
	        <span class="boxtext"><a href="{form_predefined_page}">{form_predefined_page}</a></span><br />
            </td>
            <td>
            <input type="text" class="halfbox" size="20" name="formInstructionPageNameB" value="{form_instruction_page_name_b}" />
            </td>
        </tr>
    </table>
    </td>
</tr>
<!-- END predefined_instructions_item_tpl -->

</table>
<h2>{intl-form_completion_heading}:</h2>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <td valign="middle" width="1%"><input type="radio" {hasCompletion-no-checked} name="hasCompletion" value="no" id="hasCompletion-no" /></td>
    <td valign="middle"><label class="boxtext" for="hasCompletion-no">{intl-no}</label></td>
</tr>
<tr>
    <td valign="middle"><input type="radio" name="hasCompletion" {hasCompletion-yes-checked} value="yes" id="hasCompletion-yes" /></td>
    <td valign="middle"><label for="hasCompletion-yes" class="boxtext">{intl-yes}, {intl-form_completed_page}:</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
    <input type="text" class="halfbox" size="20" name="formCompletedPage" value="{form_completed_page}" />
    </td>
</tr>
<!-- BEGIN predefined_completion_item_tpl -->
<tr>
    <td valign="middle"><input type="radio" name="hasCompletion" {hasCompletion-predefinend-checked} value="predefined" id="hasCompletion-predefined" /></td>
    <td valign="middle"><label for="hasCompletion-predefined" class="boxtext">{intl-yes}, {intl-predefined}</label></td>
</tr>
<tr>
    <td>&nbsp;</td>
    <td>
	<span class="boxtext"><a href="{form_predefined_completion_page}">{form_predefined_completion_page}</a></span><br />
    </td>
</tr>
<!-- END predefined_completion_item_tpl -->
</table>

<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
	<td colspan="2"><br /></td>
</tr>
<tr>
    <td>
	</td>
    <td>
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
