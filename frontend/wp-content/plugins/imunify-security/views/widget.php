<?php
/**
 * Widget view.
 *
 * @var array $data Template data.
 */

if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! is_array( $data ) || ! array_key_exists( 'scanData', $data ) || ! array_key_exists( 'pluginUrl', $data ) || ! array_key_exists( 'features', $data ) ) {
	return;
}

/**
 * Template data.
 *
 * @var \CloudLinux\Imunify\App\Model\ScanData $scanData
 * @var \CloudLinux\Imunify\App\Model\Feature[] $features
 * @var \CloudLinux\Imunify\App\Model\MalwareItem[] $malwareItems
 * @var int $totalItemsCount
 * @var bool $showMoreButton
 * @var array<string, \CloudLinux\Imunify\App\Model\MalwareItem[]> $modalSections
 */
$scanData        = $data['scanData'];
$pluginUrl       = $data['pluginUrl'];
$features        = $data['features'];
$malwareItems    = $data['malwareItems'];
$totalItemsCount = $data['totalItemsCount'];
$showMoreButton  = $data['showMoreButton'];
$modalSections   = $data['modalSections'];

$lastScanTime = $scanData->getLastScanTimestamp();
$nextScanTime = $scanData->getNextScanTimestamp();

use CloudLinux\Imunify\App\Helpers\DateTimeFormatter;
use CloudLinux\Imunify\App\Helpers\PathFormatter;
?>
<div class="imunify-security__widget">
	<div class="imunify-security__overview">
		<div class="imunify-security__overview-main">
			<div class="imunify-security__status">
				<div class="imunify-security__status-icon">
					<img src="<?php echo esc_url( $pluginUrl . 'assets/images/shield-check.svg' ); ?>" alt="Protected status" width="80" height="80">
				</div>
				<div class="imunify-security__status-title"><?php esc_html_e( 'Protected', 'imunify-security' ); ?></div>
			</div>
		</div>

		<div class="imunify-security__overview-details">
			<div class="imunify-security__overview-rows">
				<?php foreach ( $features as $feature ) : ?>
				<div class="imunify-security__overview-row imunify-security__overview-row--feature">
					<span class="imunify-security__overview-label">
						<a href="<?php echo esc_url( $feature->getUrl() ); ?>" target="_blank">
							<?php echo esc_html( $feature->getName() ); ?>
						</a>
					</span>
					<span class="imunify-security__overview-value imunify-security__overview-value--success">
						<?php echo esc_html( $feature->getStatus() ); ?>
					</span>
				</div>
				<?php endforeach; ?>
				<div class="imunify-security__overview-row imunify-security__overview-row--scan imunify-security__overview-row--separator">
					<span class="imunify-security__overview-label"><?php esc_html_e( 'Last scan:', 'imunify-security' ); ?></span>
					<span class="imunify-security__overview-value">
					<?php
					if ( $lastScanTime > 0 ) {
						echo esc_html( DateTimeFormatter::formatScanTime( $lastScanTime ) );
					} else {
						esc_html_e( 'never', 'imunify-security' );
					}
					?>
					</span>
				</div>
				<div class="imunify-security__overview-row imunify-security__overview-row--scan">
					<span class="imunify-security__overview-label"><?php esc_html_e( 'Next scan:', 'imunify-security' ); ?></span>
					<span class="imunify-security__overview-value">
					<?php
					if ( $nextScanTime > 0 ) {
						echo esc_html( DateTimeFormatter::formatScanTime( $nextScanTime ) );
					} else {
						esc_html_e( 'not scheduled', 'imunify-security' );
					}
					?>
					</span>
				</div>
			</div>
			<?php if ( empty( $malwareItems ) ) : ?>
			<div class="imunify-security__no-malware">
				<?php esc_html_e( 'No malware found', 'imunify-security' ); ?>
			</div>
			<?php endif; ?>
		</div>
	</div>

	<?php if ( ! empty( $malwareItems ) ) : ?>
	<div class="imunify-security__malware">
		<div class="imunify-security__malware-list">
			<div class="imunify-security__malware-row imunify-security__malware-header">
				<strong>
					<?php
					/* translators: %d: number of malware items cleaned */
					echo esc_html( sprintf( _n( '%d malware cleaned', '%d malware cleaned', $totalItemsCount, 'imunify-security' ), $totalItemsCount ) );
					?>
				</strong>
			</div>
			<?php foreach ( $malwareItems as $malware ) : ?>
			<div class="imunify-security__malware-row">
				<span class="imunify-security__malware-path"><?php echo esc_html( PathFormatter::formatLongPath( $malware->getPath() ) ); ?></span>
				<span class="imunify-security__malware-signature"><?php echo esc_html( $malware->getSignature() ); ?></span>
				<span class="imunify-security__malware-detected"><?php echo esc_html( DateTimeFormatter::formatDetectionDate( $malware->getCleanedAt() ) ); ?></span>
			</div>
			<?php endforeach; ?>
		</div>
		<div class="imunify-security__malware-actions">
			<?php if ( $showMoreButton ) : ?>
			<a href="#" class="imunify-security__action-link js-show-more"><?php esc_html_e( 'Show more results', 'imunify-security' ); ?></a>
			<span class="imunify-security__action-separator">|</span>
			<?php endif; ?>
			<a href="#" class="imunify-security__action-link js-hide-notifications"><?php esc_html_e( 'Hide notifications', 'imunify-security' ); ?></a>
		</div>
	</div>

	<!-- Malware Details Modal -->
	<div class="imunify-security__modal" style="display: none;">
		<div class="imunify-security__modal-content">
			<div class="imunify-security__modal-header">
				<div class="modal-title"><?php esc_html_e( 'Imunify Security - Malware Cleanup Details', 'imunify-security' ); ?></div>
				<button type="button" class="imunify-security__modal-close" aria-label="<?php esc_attr_e( 'Close', 'imunify-security' ); ?>">
					<span class="dashicons dashicons-no-alt"></span>
				</button>
			</div>
			<div class="imunify-security__modal-body">
				<div class="notice notice-info inline">
					<p>
						<span class="dashicons dashicons-info"></span>
						<?php esc_html_e( 'For additional management options, open Imunify360 plugin page in your control panel.', 'imunify-security' ); ?>
					</p>
				</div>
				<?php foreach ( $modalSections as $sectionName => $sectionItems ) : ?>
					<table class="widefat">
						<thead>
							<tr>
								<th colspan="3"><?php echo esc_html( $sectionName ); ?></th>
							</tr>
						</thead>
						<tbody>
							<?php foreach ( $sectionItems as $malware ) : ?>
								<tr>
									<td class="imunify-security__modal-path"><?php echo esc_html( $malware->getPath() ); ?></td>
									<td class="imunify-security__modal-signature"><?php echo esc_html( $malware->getSignature() ); ?></td>
									<td class="imunify-security__modal-time">
										<?php echo esc_html( DateTimeFormatter::formatTimestamp( $malware->getCleanedAt() ) ); ?>
									</td>
								</tr>
							<?php endforeach; ?>
						</tbody>
					</table>
				<?php endforeach; ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
</div>
<div class="imunify-security__snooze-panel" style="display: none;">
	<form class="imunify-security__snooze-form">
		<label for="imunify-snooze-weeks"><?php esc_html_e( 'Snooze for:', 'imunify-security' ); ?></label>
		<select id="imunify-snooze-weeks" name="weeks">
			<?php for ( $i = 1; $i <= 4; $i++ ) : ?>
				<option value="<?php echo esc_attr( $i ); ?>">
					<?php
					/* translators: %d: number of weeks */
					echo esc_html( sprintf( _n( '%d week', '%d weeks', $i, 'imunify-security' ), $i ) );
					?>
				</option>
			<?php endfor; ?>
		</select>
		<button type="submit" class="button"><?php esc_html_e( 'Snooze', 'imunify-security' ); ?></button>
	</form>
</div>
