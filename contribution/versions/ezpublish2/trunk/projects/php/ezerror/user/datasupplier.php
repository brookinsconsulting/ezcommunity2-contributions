<?
$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "site", "DefaultSection" );

include( "ezerror/admin/datasupplier.php" );
?>
