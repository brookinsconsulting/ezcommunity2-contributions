<form method="post" action="/site/design/update/" enctype="multipart/form-data">
<input type="hidden" name="max_file_size" value="3000000">

<h1>{intl-headline}</h1>

<hr noshade size="4"/>

<br />

<p class="boxtext">{intl-logo}</p>
<!-- BEGIN image_view_tpl -->
<table width="100%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <img src="{image_src}" width="{image_width}" height="{image_height}" border="0" alt="{image_alt}" />
    <input type="hidden" name="ImageID" value="{image_id}">
    </td>
</tr>
</table>
<!-- END image_view_tpl -->

<!-- BEGIN image_insert_tpl -->
<p>Ingen bilde er lagt til.</p>
<!-- END image_insert_tpl -->

<input size="40" name="logo" type="file" />

<br />
<br />

<table width="30%" cellspacing="0" cellpadding="0" border="0">
<tr>
    <td>
    <p class="boxtext">{intl-color1}</p>
    </td>
</tr>
<tr>
    <td>
    R: <input size="4" type="text" name="Color1_R" value="{color1_r}"> <br /><br />
    </td>

    <td>
    G: <input size="4" type="text" name="Color1_G" value="{color1_g}"> <br /><br />
    </td>

    <td>
    B: <input size="4" type="text" name="Color1_B" value="{color1_b}"> <br /><br />
    </td>

    <td bgcolor="{color1}">
    &nbsp;
    </td>

</tr>

<tr>
    <td>
    <p class="boxtext">{intl-color2}</p>
    </td>
</tr>

<tr>
    <td>
    R: <input size="4" type="text" name="Color2_R" value="{color2_r}"> <br /><br />
    </td>

    <td>
    G: <input size="4" type="text" name="Color2_G" value="{color2_g}"> <br /><br />
    </td>

    <td>
    B: <input size="4" type="text" name="Color2_B" value="{color2_b}"> <br /><br />
    </td>

    <td bgcolor="{color2}">
    &nbsp;
    </td>
</tr>

<tr>
    <td>
    <p class="boxtext">{intl-menytext}</p>
    </td>
</tr>

<tr>
    <td>
    R: <input size="4" type="text" name="Color3_R" value="{color3_r}"> <br /><br />
    </td>

    <td>
    G: <input size="4" type="text" name="Color3_G" value="{color3_g}"> <br /><br />
    </td>

    <td>
    B: <input size="4" type="text" name="Color3_B" value="{color3_b}"> <br /><br />
    </td>

    <td bgcolor="{color3}">
    &nbsp;
    </td>
</tr>
</table>


<br />

<hr noshade size="4"/>

<input class="okbutton" type="submit" value="OK" />
</form>

