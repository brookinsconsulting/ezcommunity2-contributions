<?
// 
// $Id: mailtofriend.php,v 1.2 2001/06/20 17:48:17 br Exp $
//
// Bjørn Reiten <br@ez.no>
// Created on: <18-Jun-2001 16:37:47 br>
// 
// This source file is part of eZ publish, publishing software.
// Copyright (C) 1999-2001 eZ systems as
//
// This program is free software; you can redistribute it and/or
// modify it under the terms of the GNU General Public License
// as published by the Free Software Foundation; either version 2
// of the License, or (at your option) any later version.
//
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with this program; if not, write to the Free Software
// Foundation, Inc., 59 Temple Place - Suite 330, Boston, MA  02111-1307, US
//
include_once( "classes/eztemplate.php" );
include_once( "classes/ezhttptool.php" );

include_once( "ezmail/classes/ezmail.php" );
include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerenderer.php" );


$ini =& INIFile::globalINI();
$Language = $ini->read_var( "eZUserMain", "Language" );
$tpl = new eZTemplate( "ezarticle/user/" . $ini->read_var( "eZUserMain", "TemplateDir" ),
                       "ezarticle/user/" . "intl", $Language, "mailtofriend.php" );
$tpl->setAllStrings();

if ( isset( $Submit ) || isset( $RealName ) ||  isset( $SendTo  ) || isset( $From ) )
{
    $errorArr = array();
    if ( (  trim( $RealName ) == "" ) )
        $errorArr["errormsg_real_name_tpl"] = "errormsg_real_name";
    
    if (! ( eZMail::validate( $SendTo ) ) )
        $errorArr["errormsg_send_to_tpl"] = "errormsg_send_to";

    if (! ( eZMail::validate( $From ) ) )
        $errorArr["errormsg_from_tpl"] = "errormsg_from";

    if ( $errorArr )
        errorMsg( $ArticleID, $tpl, $RealName, $SendTo, $From, $Textarea, $errorArr );
    else
        sendmail( $ArticleID, $tpl, $RealName, $SendTo, $From, $Textarea );
} else
{
    printForm( $ArticleID, $tpl );
}

/*!
  build up the mail to send.
 */
function sendmail ( $article_id, $tpl, $real_name, $to_name, $from_name, $text )
{
    $article = getArticle( $article_id );
    $renderer = new eZArticleRenderer( $article );
    $name = $article->name( );
    $intro = strip_tags( $renderer->renderIntro( ) );
    
    $SiteURL = $GLOBALS["HTTP_HOST"];
    $ServerName = $GLOBALS["SERVER_NAME"];
    
    $tpl->set_file( "mailtofriend_tpl" ,"mailtofriend.tpl" );
    
// Build up the mail to send from the template.
    
    $tpl->set_block( "mailtofriend_tpl", "mail_subject_tpl", "mail_subject" );
    $tpl->set_var( "server_name", $ServerName );
    $mail_subject = $tpl->parse( "mail_subject", "mail_subject_tpl" );
    
    $tpl->set_block( "mailtofriend_tpl", "mail_body_tpl", "mail_body" );
    $tpl->set_var( "server_name", $ServerName );
    $tpl->set_var( "comment", $text );
    $tpl->set_var( "name", $name );
    $tpl->set_var( "intro", $intro );
    $mail_body = $tpl->parse( "mail_body", "mail_body_tpl" );

    $tpl->set_block( "mailtofriend_tpl", "article_url_tpl", "article_url");
    $tpl->set_var( "site_url", $SiteURL );
    $tpl->set_var( "art_id", $article_id );
    $mail_body .= $tpl->parse( "article_url", "article_url_tpl" );

// Send the mail.
    
    $mail = new eZMail();
    $mail->setFromName( $real_name );
    $mail->setTo( $to_name );
    $mail->setFrom( $from_name );
    $mail->setSubject( $mail_subject );
    $mail->setBodyText( $mail_body );
    $mail->send();
    
// print a successfull message to the webpage
    
    $tpl->set_block( "mailtofriend_tpl", "success_tpl", "success" );
    $tpl->set_var( "to_name", $to_name );
    $tpl->set_var( "server_name", $ServerName );
    $tpl->set_var( "user_comment", $text );
    $tpl->set_var( "header_text", $name );
    $tpl->set_var( "intro_text", $intro );
    $tpl->set_var( "site_url", $SiteURL );
    $tpl->set_var( "art_id", $article->id() );
    $tpl->parse( "success", "success_tpl" );
    $tpl->pparse( "output", "success_tpl" );
}



/*!
  Print an error msg if wrong input from the form.
 */
function errorMsg ( $article_id, $tpl, $real_name, $send_to, $from, $textarea, $error )
{
    $tpl->set_file( "mailtofriend_tpl" ,"mailtofriend.tpl" );
    $tpl->set_block( "mailtofriend_tpl", "errormsg_tpl", "errormsg" );
    $tpl->pparse( "output", "errormsg_tpl" );
    while ( list ($target, $handle) = each ( $error ) )
    {
        $tpl->set_block( "mailtofriend_tpl", $target, $handle );
        $tpl->pparse( "output" , $target );
    }
    printForm( $article_id, $tpl,  $real_name, $send_to, $from, $textarea );
}

/*!
  Return an article with the given number.
*/
function getArticle ( $ArticleID )
{
    $article = new eZArticle();
    if ( $article->get( $ArticleID ) )
    {
        return $article;
    } else
    {
        eZHTTPTool::header( "Location: /error/404/" );
        exit();
    }
}

/*!
  print the first page.
 */
function printForm ( $ArticleID, $tpl, $real_name="", $send_to="", $from="", $textarea="" )
{
    $article = getArticle( $ArticleID );
    $renderer = new eZArticleRenderer( $article );
    
    $name = $article->name( );
    $intro = strip_tags( $renderer->renderIntro( ) );
    
    $tpl->set_file( "mailtofriend_tpl" ,"mailtofriend.tpl" );
    $tpl->set_block( "mailtofriend_tpl", "first_page_tpl", "first_page" );
    $tpl->set_var( "Topic", $name );
    $tpl->set_var( "Intro", $intro );
    $tpl->set_var( "real_name", $real_name );
    $tpl->set_var( "send_to", $send_to );
    $tpl->set_var( "from", $from );
    $tpl->set_var( "textarea", $textarea );
    $tpl->pparse( "output", "first_page_tpl" );
}
?>
