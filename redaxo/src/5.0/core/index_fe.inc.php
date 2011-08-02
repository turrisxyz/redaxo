<?php

/**
 *
 * @package redaxo5
 * @version svn:$Id$
 */

// ----- ob caching start für output filter
ob_start();
ob_implicit_flush(0);

// --------------------------- ini settings

// Setzten des arg_separators, falls Sessions verwendet werden,
// um XHTML valide Links zu produzieren
@ini_set('arg_separator.input', '&amp;');
@ini_set('arg_separator.output', '&amp;');

// ----- INCLUDE ADDONS
include_once rex_path::core('packages.inc.php');

if(rex::isSetup())
{
	header('Location:redaxo/');
	exit();
}

$REX['ARTICLE'] = new rex_article;
$REX['ARTICLE']->setCLang(rex_clang::getId());

if(rex::isSetup())
{
	header('Location: redaxo/index.php');
	exit();
}elseif ($REX["ARTICLE"]->setArticleId(rex::getProperty('article_id')))
{
	echo $REX["ARTICLE"]->getArticleTemplate();
}else
{
	echo 'Kein Startartikel selektiert / No starting Article selected. Please click here to enter <a href="redaxo/index.php">redaxo</a>';
}

// ----- caching end für output filter
$CONTENT = ob_get_contents();
ob_end_clean();

// trigger api functions
rex_api_function::handleCall();

// ----- inhalt ausgeben
rex_response::sendArticle($REX['ARTICLE'], $CONTENT, 'frontend');