<h1>{head_line}</h1>

<form method="post" action="/poll/vote/{poll_id}/">
<table width="100%" cellspacing="0" cellpadding="0" border="0">
{vote_list}
<br>
<input type="hidden" name="PollID" value="{poll_id}" />
<input type="submit" value="Vote" />


</table>
</form>
