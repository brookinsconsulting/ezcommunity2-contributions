/* Lets make the keywords faster */
ALTER TABLE eZArticle_ArticleKeyword ADD INDEX (Keyword);
ALTER TABLE eZArticle_ArticleKeyword ADD INDEX (ArticleID);
