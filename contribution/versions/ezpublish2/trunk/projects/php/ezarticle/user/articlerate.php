<?
// 
// $Id: articlerate.php,v 1.1 2001/10/31 12:25:19 bf Exp $
//
// Created on: <31-Oct-2001 09:51:25 bf>
//
// This source file is part of eZ publish, publishing software.
//
// Copyright (C) 1999-2001 eZ Systems.  All rights reserved.
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

include_once( "classes/INIFile.php" );
include_once( "classes/eztemplate.php" );
include_once( "classes/ezlocale.php" );
include_once( "ezmail/classes/ezmail.php" );

include_once( "ezarticle/classes/ezarticle.php" );
include_once( "ezarticle/classes/ezarticlerate.php" );

$ini =& INIFile::globalINI();

$Language = $ini->read_var( "eZArticleMain", "Language" );
$TemplateDir = $ini->read_var( "eZArticleMain", "TemplateDir" );

$t = new eZTemplate( "ezarticle/user/" . $TemplateDir,
                     "ezarticle/user/intl/", $Language, "articlerate.php" );

$t->set_file( "article_rate_page_tpl", "articlerate.tpl"  );
$t->set_block( "article_rate_page_tpl", "rate_error_tpl", "rate_error"  );
$t->set_block( "article_rate_page_tpl", "rate_ok_tpl", "rate_ok"  );

$t->setAllStrings();

$rateOk = false;
if ( is_numeric( $RateValue ) )
{
    
    $t->set_var( "referer_url",  $HTTP_REFERER );
    
    $article = new eZArticle();
    if ( $article->get( $ArticleID ) )
    {
        $rateOk = true; 
        eZArticleRate::addRate( $article, $RateValue );
    }
    
}

if ( $SendFeedback == "true" )
{
    $t->set_var( "referer_url",  $RefererURL );

    // mail template
    $mailTemplate = new eZTemplate( "ezarticle/user/" . $TemplateDir,
                     "ezarticle/user/intl/", $Language, "ratingmail.php" );

    $mailTemplateIni = new INIFile( "ezarticle/user/intl/" . $Language . "/ratingmail.php.ini", false );
    
    $mailTemplate->set_file( "rating_mail_tpl", "ratingmail.tpl"  );
    $mailTemplate->setAllStrings();

    $mailTemplate->set_var( "user_ip", $REMOTE_ADDR );
    $mailTemplate->set_var( "user_comment", $UserComment );
    $mailTemplate->set_var( "referer_url", $RefererURL );

    $body = $mailTemplate->parse( "output", "rating_mail_tpl" );

    $mail = new eZMail();
    $mail->setTo( $ini->read_var( "eZArticleMain", "ArticleRatingCommentReceiver" ) );
    $mail->setSubject( $mailTemplateIni->read_var( "strings", "mail_subject" ) );
    
    $mail->setBody( $body );
    $mail->send();
    
    $rateOk = true;
}

if ( $rateOk == true )
{
    $t->set_var( "rate_error", "" );
    $t->parse( "rate_ok", "rate_ok_tpl" );
}
else
{
    $t->set_var( "rate_ok", "" );
    $t->parse( "rate_error", "rate_error_tpl" );
}


$t->pparse( "output", "article_rate_page_tpl" );

?>
