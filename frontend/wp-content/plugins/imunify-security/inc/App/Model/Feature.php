<?php
/**
 * Copyright (с) Cloud Linux GmbH & Cloud Linux Software, Inc 2010-2025 All Rights Reserved
 *
 * Licensed under CLOUD LINUX LICENSE AGREEMENT
 * https://www.cloudlinux.com/legal/
 */

namespace CloudLinux\Imunify\App\Model;

/**
 * Feature model.
 */
class Feature {
	/**
	 * Name.
	 *
	 * @var string
	 */
	private $name;

	/**
	 * URL.
	 *
	 * @var string
	 */
	private $url;

	/**
	 * Status.
	 *
	 * @var string
	 */
	private $status;

	/**
	 * Get the name.
	 *
	 * @return string
	 */
	public function getName() {
		return $this->name;
	}

	/**
	 * Get the URL.
	 *
	 * @return string
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Get the status.
	 *
	 * @return string
	 */
	public function getStatus() {
		return $this->status;
	}

	/**
	 * Convert to array
	 *
	 * @return array
	 */
	public function toArray() {
		return array(
			'name'   => $this->name,
			'url'    => $this->url,
			'status' => $this->status,
		);
	}

	/**
	 * Create from array
	 *
	 * @param array $data Data.
	 *
	 * @return self
	 */
	public static function fromArray( $data ) {
		$result         = new self();
		$result->name   = isset( $data['name'] ) ? $data['name'] : '';
		$result->url    = isset( $data['url'] ) ? $data['url'] : '';
		$result->status = isset( $data['status'] ) ? $data['status'] : '';

		return $result;
	}
}
