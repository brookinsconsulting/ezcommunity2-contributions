<h1>{intl-messages_awaiting_approval}</h1>

<hr noshade="noshade" size="4" />

<form method="post" action="/forum/unapprovededit/">

<table cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN message_item_tpl -->
<tr>
     <th>{intl-approve}: <input value="Approve" type="radio" name="ActionValueArray[{i}]"></th>
     <th>{intl-discard}: <input value="Discard" type="radio" name="ActionValueArray[{i}]"></th>
     <th>{intl-reject}: <input value="Reject" type="radio" name="ActionValueArray[{i}]"></th>
</tr>
<tr>
     <th>{intl-subject}: {message_topic}</th>
     <th>{intl-subject}: {message_topic}</th>
     <th>{intl-postingtime}: {message_postingtime}</th>
</tr>
<tr>
     <td>
     <b>{intl-reject_header}</b>
     <input type="hidden" name="MessageID[]" value="{message_id}">
     <textarea wrap="soft" rows="3" cols="40" name="RejectReason[]">{reject_reason}</textarea>
     {message_body}
     </td>
</tr>
<tr>
     <td>&nbsp;</td>
</tr>
<!-- END message_item_tpl -->
</table>

<br />
<hr noshade="noshade" size="4" />

<input type="submit" value="{intl-update}">

</form>

