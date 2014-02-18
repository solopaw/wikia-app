<?php
/**
 * Class definition for Wikia\Search\QueryService\Select\Dismax\InterWiki
 */
namespace Wikia\Search\QueryService\Select\Dismax;
use \Solarium_Query_Select, \Wikia\Search\Utilities;
/**
 * This class is responsible for performing interwiki search queries.
 * Presently, that not only means not restricting search by a single wiki,
 * but also grouping by hostname.
 * @author relwell
 * @package Search
 * @subpackage QueryService
 */
class InterWiki extends AbstractDismax
{
	/**
	 * Used for tracking
	 * @var string
	 */
	protected $searchType = 'inter';
	
	/**
	 * Because this service uses a different core, we need different requested fields.
	 * @var array
	 */
	protected $requestedFields = [
				'id',
				'headline_txt',
				'wam_i',
				'description',
				'sitename_txt',
				'url',
				'articles_i',
				'videos_i',
				'images_i',
				'image_s',
				'hot_b',
				'promoted_b',
				'new_b',
				'official_b',
				'hub_s',
				'lang_s'
			];
	
	/** 
	 * Boost functions used in interwiki search
	 * @var array
	 */
	protected $boostFunctions = array(
		'wam_i^100',
	);
	
	/**
	 * Uses the xwiki core, where a wiki is a document.
	 * @var string
	 */
	protected $core = 'xwiki';
	
	/**
	 * Default time allowed for a query.
	 * @var int
	 */
	protected $timeAllowed = 1000;
	
	/**
	 * Reuses AbstractSelect's extractWikiMatch as the primary match method
	 * @see \Wikia\Search\QueryService\Select\AbstractSelect::extractMatch()
	 * @return Wikia\Search\Match\Wiki
	 */
	public function extractMatch() {
		return $this->extractWikiMatch();
	}
	
	/**
	 * Registers grouping, query parameters, and filters.
	 * @param Solarium_Query_Select $query
	 * @return InterWiki
	 */
	protected function registerNonDismaxComponents( Solarium_Query_Select $query ) {
		return $this->registerFilterQueries( $query );
	}
	

	/**
	 * Registers a filter query for documents matching the wiki ID of a match, if available.
	 * @see \Wikia\Search\QueryService\Select\AbstractSelect::registerFilterQueryForMatch()
	 * @return Wikia\Search\QueryService\Select\InterWiki
	 */
	protected function registerFilterQueryForMatch() {
		$config = $this->getConfig();
		if ( $config->hasWikiMatch() ) {
			$noPtt = Utilities::valueForField( 'id', $config->getWikiMatch()->getId(), array( 'negate' => true ) );
			$config->setFilterQuery( $noPtt, 'wikiptt' );
		}
		return $this;
	}
	
	
	/**
	 * Handles initial configuration when invoking search.
	 * @return Wikia\Search\QueryService\Select\InterWiki
	 */
	protected function prepareRequest() {
		$config = $this->getConfig();
		if ( $config->getPage() > 1 ) {
			$config->setStart( ( $config->getPage() - 1 ) * $config->getLength() );
		}
		return $this;
	}
	
	/**
	 * Builds the string used with filter queries based on search config
	 * @return string
	 */
	protected function getFilterQueryString() {
		$wid = $this->getService()->getWikiId();
		$filterQueries = [ 'articles_i:[50 TO *]', "-id:{$wid}" ];
		if( $this->getConfig()->getCommercialUse() ) {
			$filterQueries[] = "-( commercial_use_allowed_b:false )";
		}
		//removed hub query from filter (PLA-1166)
		return implode( ' AND ', $filterQueries );
	}
	
	/**
	 * Builds the necessary query clauses based on values set in the searchconfig object
	 * @return string
	 */
	protected function getQueryClausesString()
	{
		$widQueries = array();
		$excludedWikiIds = $this->service->getGlobalWithDefault( 'CrossWikiaSearchExcludedWikis', [] );
		$excludedWikiIds[] = $this->service->getWikiId();
		foreach ( $excludedWikiIds as $excludedWikiId ) {
			$widQueries[] = Utilities::valueForField( 'wid',  $excludedWikiId, array( 'negate' => true ) );
		}
		$queryClauses = [];
		$langs = $this->config->getLanguageCode();
		if ( is_array( $langs ) ) {
			$langsQuery = [];
			foreach( $langs as $lang ) {
				$langsQuery[] = 'lang_s:'.$lang;
			}
			if ( !empty( $langsQuery ) ) {
				$queryClauses = [ sprintf( '(%s)', implode( ' OR ', $langsQuery ) ) ];
			}
		} else {
			$queryClauses = [ 'lang_s:'.$langs ];
		}
		$queryClauses= array(
				'lang_s:'.$this->config->getLanguageCode()
		);

		$queryClauses = $this->generateHubQuery( $queryClauses );
		return implode( ' AND ', $queryClauses );
	}

	protected function generateHubQuery( $queryArray ) {
		$hub = $this->getConfig()->getHub();

		$hub_q = '';
		if ( !empty( $hub ) ) {
			if ( !is_array( $hub ) ) {
				$hub = [ $hub ];
			}

			foreach ( $hub as $item ) {
				if ( !$item ) {
					continue;
				}
				$hub_q .= ( $hub_q ? ' OR ' : '' ) . Utilities::valueForField( 'hub_s', $item );
			}

			if ( $hub_q ) {
				$queryArray[] =   ' ( ' . $hub_q . ' ) ';
			}
		}
		return $queryArray;
	}
}
