<?
switch ( $url_array[2] )
{
    case "product" :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Shopping tags..."/>
        <meta name="keywords" content="product info "/>        
        <?
        break;
    case "search" :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Search for products on zez"/>
        <meta name="keywords" content="fast search and transfer :) "/>        
        <?        
        break;
    default :
        ?>
        <meta name="author" content="eZ systems"/>
        <meta name="copyright" content="eZ systems &copy; 2000"/>
        <meta name="description" content="Page not found, sorry about that"/>
        <meta name="keywords" content="404 sorry.. what can I say."/>
        <?
        break;
}
?>
