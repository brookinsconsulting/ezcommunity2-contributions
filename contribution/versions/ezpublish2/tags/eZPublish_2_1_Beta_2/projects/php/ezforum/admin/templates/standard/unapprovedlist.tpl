<form metdod="post" action="/forum/unapprovededit/">

<h1>{intl-messages_awaiting_approval}</h1>


<!-- BEGIN message_item_tpl -->
<hr noshade="noshade" size="4" />
<br />
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
</tr>
<tr>
    <td width="50%">
        <p class="boxtext">{intl-subject}:</p>
        {message_topic}
    </td>
	<td>
	<p class="boxtext">{intl-username}:</p>
	{message_user}
	</td>
    <td>
        <p class="boxtext">{intl-postingtime}:</p>
        {message_postingtime}
    </td>
</tr>
</table>

<p class="boxtext">{intl-original_message}:</p>
<table width="100%" cellspacing="0" cellpadding="4" border="0">
<tr>
    <td class="bglight">
        {message_body}
    </td>
</tr>
</table>
 
<p class="boxtext">{intl-action}:</p>
<span class="p">{intl-approve}:</span> <input value="Approve" type="radio" name="ActionValueArray[{i}]" />
<span class="p">{intl-discard}:</span> <input value="Discard" type="radio" name="ActionValueArray[{i}]" />
<span class="p">{intl-reject}:</span> <input value="Reject" type="radio" name="ActionValueArray[{i}]" />

<p class="boxtext">{intl-reject_reason}:</p>
<input type="hidden" name="MessageID[]" value="{message_id}" />
<textarea wrap="soft" rows="3" cols="40" name="RejectReason[]">{reject_message}</textarea>


<!-- END message_item_tpl -->
<br /><br />

<hr noshade="noshade" size="4" />


<input class="stdbutton" type="submit" value="{intl-update}">

</form>

