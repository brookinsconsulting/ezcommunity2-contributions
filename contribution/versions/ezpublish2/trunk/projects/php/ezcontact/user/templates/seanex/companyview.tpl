<table width="100%" border="0">
<tr>
        <td valign="bottom">
        <h1>{name}</h1>
        </td>
        <td rowspan="2" align="right">
        <form action="/contact/search/company" method="post">
        <input type="text" name="SearchText" size="12" />       
        <input type="submit" value="{intl-search}" />
        </form> 
        </td>
</tr>
</table>


<hr noshade="noshade" size="4" />

<!-- <p class="boxtext">{intl-logo}:</p> -->
<!-- BEGIN no_logo_tpl -->
<!-- <p>{intl-no_logo}</p> -->
<!-- END no_logo_tpl -->
<!-- BEGIN logo_view_tpl -->

<img src="{logo_image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />

<!-- END logo_view_tpl -->

<p class="boxtext">{intl-company_no}:</p>
{company_no}:

<!-- BEGIN address_item_tpl -->
<p class="boxtext">{intl-address}:</p>
{street1}<br/>
{street2}<br />
{zip} {place}<br />
<!-- END address_item_tpl -->

<br clear="all" />

<br />



<!-- BEGIN phone_item_tpl -->
<p class="boxtext">{intl-telephone_headline}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <!-- BEGIN phone_line_tpl -->
    <td width="{phone_width}%">
        <span class="boxtext">{phone_type_name}:</span>
        <span>{phone}</span>
    </td>
    <!-- END phone_line_tpl -->
</tr>
</table>
<!-- END phone_item_tpl -->

<!-- BEGIN no_phone_item_tpl -->
<p class="boxtext">{intl-telephone_headline}:</p>
<p>{intl-error_no_phones}</p>
<!-- END no_phone_item_tpl -->

<!-- BEGIN online_item_tpl -->
<p class="boxtext">{intl-online_headline}:</p>
<table width="100%" cellpadding="0" cellspacing="0" border="0">
<tr>
    <!-- BEGIN online_line_tpl -->
    <td width="{online_width}%">
        <span class="boxtext">{online_type_name}:</span>
        <!-- BEGIN email_line_tpl -->
        <a href="{online_url_type}:{online}">{online}</a>
        <!-- END email_line_tpl -->
        <!-- BEGIN url_line_tpl -->
        <a href="{online_url_type}://{online}">{online}</a>
        <!-- END url_line_tpl -->
    </td>
    <!-- END online_line_tpl -->
</tr>
</table>
<!-- END online_item_tpl -->

<!-- BEGIN no_online_item_tpl -->
<p class="boxtext">{intl-online_headline}:</p>
<p>{intl-error_no_onlines}</p>
<!-- END no_online_item_tpl -->







<!-- BEGIN no_image_tpl -->
&nbsp;
<!-- END no_image_tpl -->


<!-- BEGIN image_view_tpl -->
<!--     <p class="boxtext">{intl-company_image}:</p> -->
<img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" align="right" />
<!-- END image_view_tpl -->

<p class="boxtext">{intl-description}:</p>
{description}<br /><br />

