<h1>{intl-cutover}</h1>

<hr size="4" noshade="noshade" />

<!-- BEGIN error_tpl -->
<h3 class="error">{intl-error_text}: {error_text}</h3>
<h3 class="error">{intl-error_code}: {error_code}</h3>
<!-- END error_tpl -->

<!-- BEGIN success_tpl -->
<p>{intl-success}</p>
<!-- END success_tpl -->

<form method="post"  action="/cc/cutover/">

<input type="hidden" name="Action" value="Cutover" />

<input type="submit" value="{intl-start_cutover}" />

</form>