<?php
/**
 * Dumps the Forum data for the site selected by setting the SERVER_ID variable
 *
 * @ingroup Maintenance
 */

error_reporting(E_ALL);

require_once( __DIR__ . '/../../../../../maintenance/Maintenance.php' );
include_once( __DIR__ . '/../DumpUtils.php' );
include_once( __DIR__ . '/../ForumDumper.php' );
include_once( __DIR__ . '/../FollowsFinder.php' );
include_once( __DIR__ . '/../WallHistoryFinder.php' );

class DumpForumBatch extends Maintenance {
	private $dumper;
	private $fh;
	private $outputName;

	public function __construct() {
		parent::__construct();
		$this->mDescription = 'Dumps forum page ids';
		$this->addArg( 'pageids', "Output file for page ids", $required = true );
		$this->addArg( 'out', "Output file for INSERTS", $required = true );
		$this->addArg( 'minIndex', "Min index", $required = true );
		$this->addArg( 'maxIndex', "Max index", $required = true );
	}

	public function execute() {

		$pageIdsName = $this->hasOption( 'pageids' ) ? $this->getArg( 0 ) : "php://stdout";
		$this->outputName = $this->hasOption( 'out' ) ? $this->getArg( 1 ) : "php://stdout";
		$minIndex = $this->hasOption( 'minIndex' ) ? $this->getArg( 2 ) : -1;
		$maxIndex = $this->hasOption( 'maxIndex' ) ? $this->getArg( 3 ) : -1;

		$pageIds = [];

		for ($x = $minIndex; $x <= $maxIndex; $x++) {
			$spl = new SplFileObject( $pageIdsName );
			$spl->seek( $x );
			$pageIds[] = $spl->current();
		}

		$this->dumper = new Discussions\ForumDumper();

		$this->dumpPages( $pageIds, $minIndex );
		$this->output("Pages dumped!");
		sleep(3);

		$this->dumpRevisions();
		$this->output("Revisions dumped!");
		sleep(3);

		$this->dumpVotes();
		$this->output("Votes dumped!");
		sleep(3);

		$this->dumpFollows();
		$this->output("Follows dumped!");
		sleep(3);

		$this->dumpWallHistory();
		$this->output("History dumped!");
		sleep(3);

		$this->dumpTopics();
		$this->output("Topics dumped!");
	}

	private function dumpPages( array $pageIds, int $minIndex ) {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getPages( $this->fh, $pageIds, $minIndex );
		fclose( $this->fh );
	}

	private function dumpRevisions() {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getRevisions( $this->fh );
		fclose( $this->fh );
	}

	private function dumpVotes() {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getVotes( $this->fh );
		fclose( $this->fh );
	}

	private function dumpFollows() {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getFollows( $this->fh );
		fclose( $this->fh );
	}

	private function dumpWallHistory() {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getWallHistory( $this->fh );
		fclose( $this->fh );
	}

	private function dumpTopics() {

		$this->fh = fopen( $this->outputName, 'a' );
		$this->dumper->getTopics( $this->fh );
		fclose( $this->fh );
	}
}

$maintClass = DumpForumBatch::class;
require_once( RUN_MAINTENANCE_IF_MAIN );
