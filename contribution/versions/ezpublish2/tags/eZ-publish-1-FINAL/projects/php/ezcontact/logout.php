<?
require "ezphputils.php";
require "ezcontact/dbsettings.php";

require "classes/ezuser.php";
require "classes/ezsession.php";

$session = new eZSession();
$session->get( $AuthenticatedSession );
$session->delete();

print "<html><head>";
$url = "../index.php?page=" . $DOCUMENTROOT . "loginedit.php";
print "<meta HTTP-EQUIV=\"refresh\" CONTENT=\"0;url=$url\">";
print "<link rel=\"stylesheet\" href=\"ez.css\">";
print "</head><body bgcolor=#000000></body></html>";   

?>
