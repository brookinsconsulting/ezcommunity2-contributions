<form method="post" action="/article/articleuncached/27/">
    Durchmesser:
    <input type="text" name="Durchmesser" value="<? echo $Durchmesser;?>"size="2" maxlength="2" />
    <input class="okbutton" type="submit" name="Send" value="&nbsp;OK&nbsp;" />
</form>

Umfang:
<?

if ( $GLOBALS["Send"] )
{
    echo ( round( $GLOBALS["Durchmesser"] * 3.14 ) );
}
?>
