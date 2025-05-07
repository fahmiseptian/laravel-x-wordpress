<?php
/**
 * Copyright (с) Cloud Linux GmbH & Cloud Linux Software, Inc 2010-2025 All Rights Reserved
 *
 * Licensed under CLOUD LINUX LICENSE AGREEMENT
 * https://www.cloudlinux.com/legal/
 */

namespace CloudLinux\Imunify\App\Views;

use CloudLinux\Imunify\App\AccessManager;
use CloudLinux\Imunify\App\DataStore;
use CloudLinux\Imunify\App\View;
use CloudLinux\Imunify\App\Model\Feature;
use CloudLinux\Imunify\App\Model\MalwareItem;

/**
 * Dashboard widget view.
 */
class Widget extends View {
	/**
	 * Maximum number of items to show in widget view.
	 */
	const MAX_WIDGET_ITEMS = 5;

	/**
	 * User meta key for storing widget snooze state.
	 *
	 * @var string
	 */
	const WIDGET_SNOOZED_META_KEY = 'imunify_widget_snoozed_until';

	/**
	 * Nonce name for widget snooze action.
	 *
	 * @var string
	 */
	const WIDGET_SNOOZE_NONCE_NAME = 'imunify_widget_snooze_nonce';

	/**
	 * Data store instance.
	 *
	 * @var DataStore
	 */
	public $dataStore;

	/**
	 * Access manager instance.
	 *
	 * @var AccessManager
	 */
	private $accessManager;

	/**
	 * Constructor.
	 *
	 * @param AccessManager $accessManager Access manager instance.
	 * @param DataStore     $dataStore Data store instance.
	 */
	public function __construct( AccessManager $accessManager, DataStore $dataStore ) {
		$this->accessManager = $accessManager;
		$this->dataStore     = $dataStore;
		add_action( 'wp_dashboard_setup', array( $this, 'add' ) );
		add_action( 'wp_ajax_imunify_snooze_widget', array( $this, 'snoozeWidget' ) );
	}

	/**
	 * Get hardcoded features.
	 *
	 * @return Feature[]
	 */
	public function getFeatures() {
		return array(
			Feature::fromArray(
				array(
					'name'   => esc_html__( 'Malware Scanning', 'imunify-security' ),
					'url'    => 'https://imunify360.com/imunify-security-wp-plugin/#malware-scanning',
					'status' => esc_html__( 'Enabled', 'imunify-security' ),
				)
			),
			Feature::fromArray(
				array(
					'name'   => esc_html__( 'Proactive Defence', 'imunify-security' ),
					'url'    => 'https://imunify360.com/imunify-security-wp-plugin/#proactive-defence',
					'status' => esc_html__( 'Enabled', 'imunify-security' ),
				)
			),
		);
	}

	/**
	 * Get malware items for widget display.
	 *
	 * @param array $items All malware items.
	 * @return array
	 */
	private function getWidgetMalwareItems( array $items ) {
		$totalItems = count( $items );
		return array(
			'items'     => array_slice( $items, 0, self::MAX_WIDGET_ITEMS ),
			'show_more' => $totalItems > self::MAX_WIDGET_ITEMS,
		);
	}

	/**
	 * Get malware items grouped by time period for modal display.
	 *
	 * @param array $items All malware items.
	 * @return array
	 */
	private function getModalMalwareSections( array $items ) {
		$gmtOffset       = (int) get_option( 'gmt_offset' ) * HOUR_IN_SECONDS;
		$today           = gmdate( 'Y-m-d', time() + $gmtOffset );
		$yesterday       = gmdate( 'Y-m-d', strtotime( '-1 day' ) + $gmtOffset );
		$startOfWeek     = gmdate( 'Y-m-d', strtotime( 'monday this week' ) + $gmtOffset );
		$startOfLastWeek = gmdate( 'Y-m-d', strtotime( 'monday last week' ) + $gmtOffset );
		$endOfLastWeek   = gmdate( 'Y-m-d', strtotime( 'sunday last week' ) + $gmtOffset );

		$sections = array();

		foreach ( $items as $item ) {
			$date = gmdate( 'Y-m-d', $item->getCleanedAt() + $gmtOffset );

			if ( $date >= $startOfWeek ) {
				if ( $date === $today ) {
					$key = esc_html__( 'Today', 'imunify-security' );
				} elseif ( $date === $yesterday ) {
					$key = esc_html__( 'Yesterday', 'imunify-security' );
				} else {
					$key = wp_date( 'l, F j', strtotime( $date ) );
				}
				$sections[ $key ][] = $item;
			} elseif ( $date >= $startOfLastWeek && $date <= $endOfLastWeek ) {
				$key                = esc_html__( 'Last Week', 'imunify-security' );
				$sections[ $key ][] = $item;
			} else {
				$key                = esc_html__( 'Older', 'imunify-security' );
				$sections[ $key ][] = $item;
			}
		}

		return $sections;
	}

	/**
	 * Add a new dashboard widget.
	 *
	 * @return void
	 */
	public function add() {
		if ( ! $this->willBeRendered() ) {
			return;
		}

		wp_add_dashboard_widget(
			'imunify_security_widget',
			esc_html__( 'Imunify Security', 'imunify-security' ),
			array(
				$this,
				'view',
			),
			null,
			null,
			'normal',
			'high'
		);
	}

	/**
	 * Output the contents of the dashboard widget.
	 */
	public function view() {
		$pluginUrl = plugin_dir_url( IMUNIFY_SECURITY_FILE_PATH );
		if ( ! $this->dataStore->isDataAvailable() ) {
			$this->render(
				'widget-not-installed',
				array(
					'installLink' => 'https://imunify360.com/getting-started-installation/',
					'pluginUrl'   => $pluginUrl,
				)
			);
		} else {
			$scanData = $this->dataStore->getScanData();
			if ( null === $scanData ) {
				// Data is not available, do not render the widget.
				return;
			}

			$malwareItems = $scanData->getMalware();
			$widgetData   = $this->getWidgetMalwareItems( $malwareItems );

			$this->render(
				'widget',
				array(
					'scanData'        => $scanData,
					'pluginUrl'       => $pluginUrl,
					'features'        => $this->getFeatures(),
					'malwareItems'    => $widgetData['items'],
					'totalItemsCount' => count( $malwareItems ),
					'showMoreButton'  => $widgetData['show_more'],
					'modalSections'   => $this->getModalMalwareSections( $malwareItems ),
				)
			);
		}
	}

	/**
	 * Checks if the widget will be rendered.
	 *
	 * @return bool
	 */
	public function willBeRendered() {
		if ( ! $this->accessManager->isUserAdmin() ) {
			return false;
		}

		return ! $this->isSnoozed();
	}

	/**
	 * Checks if the widget is currently snoozed.
	 *
	 * @return bool
	 */
	private function isSnoozed() {
		$user_id       = get_current_user_id();
		$snoozed_until = get_user_meta( $user_id, self::WIDGET_SNOOZED_META_KEY, true );

		return $snoozed_until && time() < $snoozed_until;
	}

	/**
	 * Snoozes the widget for the specified number of weeks.
	 *
	 * @return void
	 */
	public function snoozeWidget() {
		check_ajax_referer( self::WIDGET_SNOOZE_NONCE_NAME, 'nonce' );

		$weeks = filter_input( INPUT_POST, 'weeks', FILTER_VALIDATE_INT );
		if ( ! $weeks || $weeks < 1 || $weeks > 4 ) {
			wp_send_json_error( array( 'message' => 'Invalid snooze duration' ) );
		} else {
			$user_id      = get_current_user_id();
			$snooze_until = strtotime( "+{$weeks} weeks UTC" );
			update_user_meta( $user_id, self::WIDGET_SNOOZED_META_KEY, $snooze_until );
			wp_send_json_success();
		}
	}
}

