<h1>{intl-article_rate}</h1>
<hr size="4" noshade="noshade" />


<!-- BEGIN rate_error_tpl -->
<h2>{intl-error_rating_resource}</h2>
<!-- END rate_error_tpl -->

<!-- BEGIN rate_ok_tpl -->

<h2>{intl-thanks_for_feedback}</h2>

<a href="{referer_url}">{intl-click_here_go_back}</a>

<!-- END rate_ok_tpl -->

<p>
{intl-feedback}
</p>

<form method="post" action="/article/rate/">
<textarea class="box" name="UserComment" cols="40" rows="5" wrap="soft"></textarea>
<br />
<input type="submit" value="{intl-send_feedback}" />

<input type="hidden" name="RefererURL" value="{referer_url}" />
<input type="hidden" name="SendFeedback" value="true" />

</form>
