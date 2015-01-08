<?php

$dir = dirname( __FILE__ );

//i18n
$wgExtensionMessagesFiles['IndexingPipeline'] = $dir . '/IndexingPipeline.i18n.php';

$wgAutoloadClasses['PipelineConnectionBase'] =  $dir . '/PipelineConnectionBase.class.php';
$wgAutoloadClasses['PipelineEventProducer'] =  $dir . '/PipelineEventProducer.class.php';

$wgHooks['ArticleSaveComplete'][] = 'PipelineEventProducer::onArticleSaveComplete';
$wgHooks['NewRevisionFromEditComplete'][] = 'PipelineEventProducer::onNewRevisionFromEditComplete';
$wgHooks['ArticleDeleteComplete'][] = 'PipelineEventProducer::onArticleDeleteComplete';
$wgHooks['ArticleUndelete'][] = 'PipelineEventProducer::onArticleUndelete';
$wgHooks['TitleMoveComplete'][] = 'PipelineEventProducer::onTitleMoveComplete';

