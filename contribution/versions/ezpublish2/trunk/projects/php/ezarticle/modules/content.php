<?
    global $url_array;
    $page = $url_array[3];
    $viewer = $url_array[2];
$str = <<<EOD
<div style="float: right;">
<table cellspacing="0" cellpadding="2" border="0">
<tr>
    <td bgcolor="#bcbcbc">
        <h2>Module Descriptions</h2>
    </td>
</tr>
<tr>
    <td bgcolor="#f0f0f0">
        <a href="/article/$viewer/$page/3/#ezad">eZ ad</a><br />
        <a href="/article/$viewer/$page/3/#ezaddress">eZ address</a><br />
        <a href="/article/$viewer/$page/3/#ezbug">eZ bug</a><br />
        <a href="/article/$viewer/$page/3/#ezcalendar">eZ calendar</a><br />
        <a href="/article/$viewer/$page/3/#ezcontact">eZ contact</a><br />
        <a href="/article/$viewer/$page/3/#ezfilemanager">eZ filemanager</a><br />
        <a href="/article/$viewer/$page/3/#ezforum">eZ forum</a><br />
        <a href="/article/$viewer/$page/3/#ezimagecatalogue">eZ imagecatalogue</a><br />
        <a href="/article/$viewer/$page/3/#ezlink">eZ link</a><br />
        <a href="/article/$viewer/$page/3/#eznewsfeed">eZ newsfeed</a><br />
        <a href="/article/$viewer/$page/3/#ezpoll">eZ poll</a><br />
        <a href="/article/$viewer/$page/3/#ezstats">eZ stats</a><br />
        <a href="/article/$viewer/$page/3/#eztodo">eZ todo</a><br />
        <a href="/article/$viewer/$page/3/#eztrade">eZ trade</a><br />
        <a href="/article/$viewer/$page/3/#ezuser">eZ user</a><br />
    </td>
</tr>
</table>
</div>
EOD;

if( $viewer != "articleprint" )
{
    echo $str;
}
?>
