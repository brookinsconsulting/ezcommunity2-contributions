<h1>{intl-messages_awaiting_approval}</h1>

<hr noshade="noshade" size="4" />

<form metdod="post" action="/forum/unapprovededit/">

<table cellspacing="0" cellpadding="4" border="0">
<!-- BEGIN link_item_tpl -->
<tr>
    <td>
        <div class="boxtext">{intl-subject}:</div>
        {message_topic}
    </td>
    <td>
        <div class="boxtext">{intl-postingtime}:</div>
        {message_postingtime}
    </td>
</tr>
<tr>
    <td colspan="2">
        <div class="boxtext">{intl-original_message}:</div>
        {message_body}
    </td>
</tr>
<tr>
    <td colspan="2">
        <div class="boxtext">{intl-action}:</div>
        {intl-approve}: <input value="Approve" type="radio" name="ActionValueArray[{i}]" />
        {intl-discard}: <input value="Discard" type="radio" name="ActionValueArray[{i}]" />
        {intl-reject}: <input value="Reject" type="radio" name="ActionValueArray[{i}]" />
    </td>
</tr>
<tr>
    <td colspan="2">
        <div class="boxtext">{intl-reject_header}:</div>
        <input type="hidden" name="MessageID[]" value="{message_id}" />
        <textarea wrap="soft" rows="3" cols="40" name="RejectReason[]">{reject_reason}</textarea>
    </td>
</tr>
<tr>
    <td colspan="2">
    &nbsp;
    </td>
</tr>
<!-- END link_item_tpl -->
</table>

<br />
<hr noshade="noshade" size="4" />

<input class="stdbutton" type="submit" value="{intl-update}">

</form>

