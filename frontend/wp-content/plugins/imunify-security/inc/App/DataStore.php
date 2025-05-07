<?php
/**
 * Copyright (с) Cloud Linux GmbH & Cloud Linux Software, Inc 2010-2025 All Rights Reserved
 *
 * Licensed under CLOUD LINUX LICENSE AGREEMENT
 * https://www.cloudlinux.com/legal/
 */

namespace CloudLinux\Imunify\App;

use CloudLinux\Imunify\App\Model\ScanData;

/**
 * Data store implementation that uses PHP files.
 */
class DataStore {

	/**
	 * Data directory name.
	 */
	const DIRECTORY = 'imunify-security';

	/**
	 * Scan data file name.
	 */
	const SCAN_DATA_FILE = 'scan_data.php';

	/**
	 * Scan data.
	 *
	 * @var ScanData|null
	 */
	public $data = null;

	/**
	 * Data directory location. Default is WP_CONTENT_DIR.
	 *
	 * @var string
	 */
	private $dataDirectoryLocation = '';

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->dataDirectoryLocation = WP_CONTENT_DIR;
	}

	/**
	 * Checks if data is available.
	 *
	 * @return bool
	 */
	public function isDataAvailable() {
		$filepath = $this->getDataFilePath( self::SCAN_DATA_FILE );
		return file_exists( $filepath ) && is_readable( $filepath );
	}

	/**
	 * Changes data directory and clears the data to make sure it's reloaded when requested again.
	 *
	 * @param string $directory The new directory.
	 *
	 * @return void
	 */
	public function changeDataDirectory( $directory ) {
		$this->dataDirectoryLocation = $directory;
		$this->data                  = null;
	}

	/**
	 * Get the base directory path for data files
	 *
	 * @return string
	 */
	private function getDataDirectory() {
		return $this->dataDirectoryLocation . DIRECTORY_SEPARATOR . self::DIRECTORY;
	}

	/**
	 * Get the full path to a data file
	 *
	 * @param string $filename The name of the file.
	 *
	 * @return string
	 */
	private function getDataFilePath( $filename ) {
		return trailingslashit( $this->getDataDirectory() ) . $filename;
	}

	/**
	 * Retrieves the scan data.
	 *
	 * If not already loaded, it will load it from the file.
	 *
	 * @return ScanData|null
	 */
	public function getScanData() {
		if ( ! $this->data ) {
			$this->data = $this->load();
		}
		return $this->data;
	}

	/**
	 * Loads data from the file.
	 *
	 * @return ScanData|null
	 */
	private function load() {
		if ( ! $this->isDataAvailable() ) {
			return null;
		}

		$filepath = $this->getDataFilePath( self::SCAN_DATA_FILE );

		/**
		 * PHP is able to catch parsing errors since version 7.0. This is a workaround that allows to catch parsing
		 * errors if supported while keeping compatibility with PHP 5.6 that does not support Throwable.
		 */
		if ( interface_exists( 'Throwable' ) ) {
			try {
				$rawData = include $filepath;
				return $this->processRawDataFromFile( $rawData, $filepath );
			} catch ( \Throwable $t ) {
				$this->processDataLoadingError( $t );
				return null;
			}
		} else {
			try {
				$rawData = include $filepath;
				return $this->processRawDataFromFile( $rawData, $filepath );
			} catch ( \Exception $e ) {
				$this->processDataLoadingError( $e );
				return null;
			}
		}
	}

	/**
	 * Processes the raw data from the file.
	 * This method is used to convert the raw data into a ScanData object.
	 * If the data is not valid, it will log an error and return null.
	 *
	 * @param mixed  $rawData   The raw data from the file.
	 * @param string $filepath The path to the file.
	 *
	 * @return ScanData|null
	 */
	private function processRawDataFromFile( $rawData, $filepath ) {
		if ( ! is_array( $rawData ) ) {
			do_action(
				'imunify_security_set_error',
				E_WARNING,
				'File scan_data.php returned unexpected data',
				__FILE__,
				__LINE__,
				array(
					'file' => $filepath,
					'data' => var_export( $rawData, true ), // phpcs:ignore WordPress.PHP.DevelopmentFunctions.error_log_var_export
				)
			);
			return null;
		}

		return ScanData::fromArray( $rawData );
	}

	/**
	 * Processes the error that occurred while loading the data.
	 *
	 * @param \Throwable|\Exception $e The exception.
	 *
	 * @return void
	 */
	private function processDataLoadingError( $e ) {
		do_action(
			'imunify_security_set_error',
			E_WARNING,
			'scan_data.php file loading failed with error  ' . $e->getMessage(),
			__FILE__,
			__LINE__,
			array(
				'fingerprint' => array( 'scan_data_loading_failed', get_class( $e ) ),
			)
		);
	}
}
