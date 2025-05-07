<?php
/**
 * Copyright (с) Cloud Linux GmbH & Cloud Linux Software, Inc 2010-2025 All Rights Reserved
 *
 * Licensed under CLOUD LINUX LICENSE AGREEMENT
 * https://www.cloudlinux.com/legal/
 */

namespace CloudLinux\Imunify\App\Helpers;

/**
 * Helper class for formatting file paths.
 */
class PathFormatter {
	/**
	 * Maximum length of path before removing leading slash.
	 */
	const MAX_PATH_LENGTH = 30;

	/**
	 * Format a long file path.
	 * For paths longer than MAX_PATH_LENGTH characters, removes the leading slash if present.
	 *
	 * @param string $path The file path to format.
	 *
	 * @return string The formatted path.
	 */
	public static function formatLongPath( $path ) {
		if ( empty( $path ) ) {
			return '';
		}

		if ( strlen( $path ) > self::MAX_PATH_LENGTH && 0 === strpos( $path, '/' ) ) {
			return substr( $path, 1 );
		}

		return $path;
	}
}
