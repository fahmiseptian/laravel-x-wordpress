<?php
/**
 * Copyright (с) Cloud Linux GmbH & Cloud Linux Software, Inc 2010-2025 All Rights Reserved
 *
 * Licensed under CLOUD LINUX LICENSE AGREEMENT
 * https://www.cloudlinux.com/legal/
 */

namespace CloudLinux\Imunify\App;

/**
 * Access manager.
 */
class AccessManager {
	/**
	 * Checks if current user has admin capabilities
	 *
	 * @return bool
	 */
	public function isUserAdmin() {
		return current_user_can( 'manage_options' );
	}
}
