<?
/*!
    $Id: forum.php,v 1.15 2000/07/26 07:01:22 bf-cvs Exp $

    Author: Lars Wilhelmsen <lw@ez.no>
    
    Created on: <14-Jul-2000 12:57:16 lw>
    
    Copyright (C) 2000 eZ systems. All rights reserved.
*/
include( "ezforum/dbsettings.php" );
include( "ezphputils.php" );
include( "template.inc" );
include( "$DOCROOT/classes/ezdb.php" );
include( "$DOCROOT/classes/ezuser.php" );
include( "$DOCROOT/classes/ezforummessage.php" );
include( "$DOCROOT/classes/ezsession.php" );

/*
  Denne funksjonen printer ut alle headerene og viser dem som et tre.
 */
function printHeaderTree( $forum_id, $parent_id, $level = 0 )
{
    $level = $level + 1;
    global $msg;
    global $t;
    global $DOCROOT;
    
    $headers = $msg->getHeaders( $forum_id, $parent_id );

    for ($i = 0; $i < count($headers); $i++)
    {
        $Id = $headers[$i]["Id"];
        $Topic  = $headers[$i]["Topic"];
        $User = $headers[$i]["UserId"];
        $PostingTime = $headers[$i]["PostingTimeFormated"];
        
        $nr = $i + 1;
         
        $t->set_var( "id", $Id );
        $t->set_var( "forum_id", $forum_id );


        $replies = eZforumMessage::countReplies( $Id );
            
        $t->set_var( "replies", $replies );

        // legger på kode for å vise "gren" ikon

        $spacer = "";
        $spacer = "<img src=\"". $DOCROOT ."/images/trans.gif\" border=\"0\" height=\"21\" width=\"5\" >";

        if ( ( $replies == 0 ) )
        {
            if ( $level == 1 )
            {            
                $spacer .= "<img src=\"". $DOCROOT ."/images/n.gif\" border=\"0\" height=\"21\" width=\"9\" >";
            }
            else
            {
                // sjekker om vi er på siste element av en gren.
                if ( $i == ( count($headers) -1 ) )
                {
                    $imgtype = "l";
                }
                else
                {
                    $imgtype = "t";                    
                }

                if ( $level > 2 )
                  $spacer .= "<img  src=\"". $DOCROOT ."/images/trans.gif\" height=\"21\" width=\"" . ( ($level-2)*12 ) ."\" border=\"0\">";
                
                $spacer .= "<img  src=\"". $DOCROOT ."/images/" . $imgtype . ".gif\"  height=\"21\" width=\"12\" border=\"0\">";

                $spacer .= "<img  src=\"". $DOCROOT ."/images/c.gif\" border=\"0\" height=\"21\" width=\"9\" >";
                
            }
            
        }
        else
        {
            if ( $level > 1 )
            {
                if ( $level > 2 )
                    $spacer .= "<img   src=\"". $DOCROOT ."/images/trans.gif\" width=\"" . ( ($level-2)*12 ) ."\" border=\"0\">";
                
                $spacer .= "<img  height=\"21\" width=\"12\" src=\"". $DOCROOT ."/images/l.gif\" border=\"0\">";
                $spacer .= "<img  height=\"21\" width=\"9\" src=\"". $DOCROOT ."/images/m.gif\" border=\"0\">";
            }
            else
            {
                $spacer .= "<img  height=\"21\" width=\"9\" src=\"". $DOCROOT ."/images/m.gif\" border=\"0\">";
            }
        }
        

        $t->set_var( "tree_icon", $spacer );                    
        $t->set_var( "topic", "&nbsp;" . $Topic );        
        $t->set_var( "user", $User );
        $t->set_var( "postingtime", $PostingTime );
        $t->set_var( "link",$link );


        if ( ($i % 2) != 0)
            $t->set_var( "color", "#eeeeee");
        else
            $t->set_var( "color", "#bbbbbb");
    
        $messages .= $t->parse( "messages", "elements", true );
        $messages .= printHeaderTree( $forum_id, $Id, $level );
    }

    return $messages;
}

$msg = new eZforumMessage( $forum_id );
$t = new Template(".");

$t->set_file( Array("forum" => "$DOCROOT/templates/forum.tpl",
                    "elements" => "$DOCROOT/templates/forum-elements.tpl",
                    "preview" => "$DOCROOT/templates/forum-preview.tpl",
                    "navigation" => "$DOCROOT/templates/navigation.tpl",
                    "navigation-bottom" => "$DOCROOT/templates/navigation-bottom.tpl"
                   )
            );

$t->set_var( "docroot", $DOCROOT );
$t->set_var( "category_id", $category_id );
$t->set_var( "forum_id", $forum_id );

//navbar setup
if ( $AuthenticatedSession )
{
    $session = new eZSession();
    $session->get( $AuthenticatedSession );
    $UserID = $session->UserID();

    $t->set_var( "user", eZUser::resolveUser( $session->UserID() ) );
}
else
{
    $UserID = 0;
    $t->set_var( "user", "Anonym" );
}
$t->parse( "navigation-bar", "navigation", true);


// new posting
if ( $post )
{
    $msg->newMessage();
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    $msg->store();
}

// reply
if ( $reply )
{
    $msg->newMessage();    
    $msg->setTopic( $Topic );
    $msg->setBody( $Body );
    $msg->setUserId( $UserID );
    $msg->setParent( $parent );
    if ( $notice )
        $msg->enableEmailNotice();
    else
        $msg->disableEmailNotice();
    $msg->store();
}

// preview message
if ( $preview )
{
    $t->set_var( "topic", $Topic );
    $t->set_var( "body", nl2br( $Body ) );
    $t->set_var( "body-clean", $Body );
    $t->set_var( "userid", $UserID );
    $t->pparse( "output", "preview" );
}
else
{
    $messages = printHeaderTree( $forum_id, 0 );
    
//      $headers = $msg->getHeaders( $forum_id );

//      for ($i = 0; $i < count($headers); $i++)
//      {
//          $Id = $headers[$i]["Id"];
//          $Topic  = $headers[$i]["Topic"];
//          $User = $headers[$i]["UserId"];
//          $PostingTime = $headers[$i]["PostingTimeFormated"];
        
//          $j = $i + 1;
         
//          $t->set_var( "id", $Id);
//          $t->set_var( "forum_id", $forum_id);
//          $t->set_var( "topic", $Topic);
//          $t->set_var( "nr", $j );
//          $t->set_var( "user", $User );
//          $t->set_var( "postingtime", $PostingTime );
//          $t->set_var( "link",$link );
         
//          $t->set_var( "replies", eZforumMessage::countReplies( $Id ) );

//          if ( ($i % 2) != 0)
//              $t->set_var( "color", "#eeeeee");
//          else
//              $t->set_var( "color", "#bbbbbb");
    
//  //          $t->parse( "messages", "elements", true);
//          $messages .= $t->parse( "messages", "elements", true);        
//      }

    $t->set_var( "messages", $messages );
    
//      if ( count( $headers ) == 0)
//          $t->set_var( "messages", "<tr><td colspan=\"4\">Ingen meldinger</td></tr>");
     
    $t->set_var( "newmessage", $newmessage);

    $t->set_var( "link1-url", "newmessage.php" );
    $t->set_var( "link1-caption", "Ny Melding" );
    $t->set_var( "link2-url", "search.php" );
    $t->set_var( "link2-caption", "Søk" );

    $t->set_var( "back-url", "category.php");
    $t->parse( "navigation-bar-bottom", "navigation-bottom", true);

    $t->pparse("output","forum");
}

?>

