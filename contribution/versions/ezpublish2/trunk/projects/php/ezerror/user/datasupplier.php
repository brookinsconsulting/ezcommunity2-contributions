<?
$ini =& INIFile::globalINI();
$GlobalSectionID = $ini->read_var( "eZErrorMain", "DefaultSection" );

include( "ezerror/admin/datasupplier.php" );
?>
