<?
/*!
    $Id: forum.php,v 1.1 2000/07/14 12:55:45 lw-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: Created on: <14-Jul-2000 13:41:35 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include('template.inc');
include('src/ezforumforum.php');
  
$forum = new eZforumForum();
$t = new Template(".");

$t->set_file(array("" => "$DOCROOT/admin/templates/",
                   "" => "$DOCROOT/admin/templates/",
                   ) );

if ($add)
{
    if ($forum_id)
    {
        $forum->Id = $forum_id;
    }
    
    $forum->setName($Name);
    $forum->setDescription($Description);
    $forum->setCategoryId($category_id);
        
    if ($Private)
        $forum->setPrivate("Y");
    else
        $forum->setPrivate("N");
    
    if ($Moderated)
        $forum->setModerated("Y");
    else
        $forum->setModerated("N");
    
    $forum->store();
}
  
if ($action == "delete")
{
    $forum->delete($forum_id);
}

if ($action == "modify")
{
    $forum->get($forum_id);
}

  
function addForumBox()
{
    
    global $category_id;
        ?>
        <form action="index.php" method="get">
            <input type="hidden" name="page" value="admin/forum.php">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <table border="0" cellspacing="1" cellpadding="6">
                <tr class="head">
                    <td colspan="5">Legg til forum</td>
                </tr>
                <tr>
                    <td>Navn:</td>  
                    <td>Beskrivelse:</td>
                    <td>Privat:</td>
                    <td>Moderert:</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                    <td><input type="text" name="Name"></td>
                    <td><input type="text" name="Description"></td>
                    <td align="center"><input type="checkbox" name="Private"></td>
                    <td align="center"><input type="checkbox" name="Moderated"></td>
                    <td align="center"><input type="submit" name="add" value="Legg til"></td>
                </tr>
            </table>
        </form>
<?
  }
  
function modifyForumBox($Id)
{
    global $forum;
    global $category_id;
?>
       <form action="index.php" method="get">
            <input type="hidden" name="page" value="admin/forum.php">
            <input type="hidden" name="forum_id" value="<?= $Id ?>">
            <input type="hidden" name="category_id" value="<?= $category_id ?>">
            <table border="0" cellspacing="1" cellpadding="5">
                <tr class="head">
                    <td colspan="5">Endre forum</td>
                </tr>
                <tr>
                    <td>Navn:</td>
                    <td>Beskrivelse:</td>
                    <td>Privat:</td>
                    <td>Moderert:</td>
                    <td>&nbsp;</td>
                </tr>
                
                <tr>
                    <td>
                        <input type="text" name="Name" value="<? echo $forum->name(); ?>">
                    </td>
                    <td>
                        <input type="text" name="Description" value="<? echo $forum->description(); ?>">
                    </td>
                    <td align="center">
                        <input type="checkbox" name="Private" <? if ($forum->private() == "Y") echo " checked" ?>>
                    </td>
                    <td align="center">
                        <input type="checkbox" name="Moderated" <? if ($forum->moderated() == "Y") echo " checked" ?>>
                    </td>
                    <td align="center">
                        <input type="submit" name="add" value="Endre">
                    </td>
                </tr>
            </table>
        </form>
<?
}
  
   
   //
echo "<h2>Forum</h2>";
if ($action == "modify")
{
    modifyForumBox($forum_id); 
}
else
{
    addForumBox();
}

function ForumList()
{
    global $category_id;


    $forum = new eZforumForum();
    
    $forums = $forum->getAllForums($category_id);
        
    echo "<table border=\"0\" cellspacing=\"1\" cellpadding=\"5\">\n";
    echo "<tr><td colspan=\"7\" class=\"head\">Forum</td></tr>";
    echo "<tr class=\"choices\">";
    echo "<td>Navn</td>";
    echo "<td>Beskrivelse</td>";
    echo "<td>Privat</td>";
    echo "<td>Moderert</td>";
    echo "<td colspan=\"2\">&nbsp;</a>

<?>
