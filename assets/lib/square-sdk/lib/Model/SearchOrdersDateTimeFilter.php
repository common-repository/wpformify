<?php
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SquareConnect\Model;

use \ArrayAccess;
/**
 * SearchOrdersDateTimeFilter Class Doc Comment
 *
 * @category Class
 * @package  SquareConnect
 * @author   Square Inc.
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License v2
 * @link     https://squareup.com/developers
 */
class SearchOrdersDateTimeFilter implements ArrayAccess {

	/**
	 * Array of property to type mappings. Used for (de)serialization
	 *
	 * @var string[]
	 */
	static $swaggerTypes = array(
		'created_at' => '\SquareConnect\Model\TimeRange',
		'updated_at' => '\SquareConnect\Model\TimeRange',
		'closed_at'  => '\SquareConnect\Model\TimeRange',
	);

	/**
	 * Array of attributes where the key is the local name, and the value is the original name
	 *
	 * @var string[]
	 */
	static $attributeMap = array(
		'created_at' => 'created_at',
		'updated_at' => 'updated_at',
		'closed_at'  => 'closed_at',
	);

	/**
	 * Array of attributes to setter functions (for deserialization of responses)
	 *
	 * @var string[]
	 */
	static $setters = array(
		'created_at' => 'setCreatedAt',
		'updated_at' => 'setUpdatedAt',
		'closed_at'  => 'setClosedAt',
	);

	/**
	 * Array of attributes to getter functions (for serialization of requests)
	 *
	 * @var string[]
	 */
	static $getters = array(
		'created_at' => 'getCreatedAt',
		'updated_at' => 'getUpdatedAt',
		'closed_at'  => 'getClosedAt',
	);

	/**
	 * $created_at Time range for filtering on the `created_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `CREATED_AT`.
	 *
	 * @var \SquareConnect\Model\TimeRange
	 */
	protected $created_at;
	/**
	 * $updated_at Time range for filtering on the `updated_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `UPDATED_AT`.
	 *
	 * @var \SquareConnect\Model\TimeRange
	 */
	protected $updated_at;
	/**
	 * $closed_at Time range for filtering on the `closed_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `CLOSED_AT`.
	 *
	 * @var \SquareConnect\Model\TimeRange
	 */
	protected $closed_at;

	/**
	 * Constructor
	 *
	 * @param mixed[] $data Associated array of property value initializing the model
	 */
	public function __construct( array $data = null ) {
		if ( $data != null ) {
			if ( isset( $data['created_at'] ) ) {
				$this->created_at = $data['created_at'];
			} else {
				$this->created_at = null;
			}
			if ( isset( $data['updated_at'] ) ) {
				$this->updated_at = $data['updated_at'];
			} else {
				$this->updated_at = null;
			}
			if ( isset( $data['closed_at'] ) ) {
				$this->closed_at = $data['closed_at'];
			} else {
				$this->closed_at = null;
			}
		}
	}
	/**
	 * Gets created_at
	 *
	 * @return \SquareConnect\Model\TimeRange
	 */
	public function getCreatedAt() {
		return $this->created_at;
	}

	/**
	 * Sets created_at
	 *
	 * @param \SquareConnect\Model\TimeRange $created_at Time range for filtering on the `created_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `CREATED_AT`.
	 * @return $this
	 */
	public function setCreatedAt( $created_at ) {
		$this->created_at = $created_at;
		return $this;
	}
	/**
	 * Gets updated_at
	 *
	 * @return \SquareConnect\Model\TimeRange
	 */
	public function getUpdatedAt() {
		return $this->updated_at;
	}

	/**
	 * Sets updated_at
	 *
	 * @param \SquareConnect\Model\TimeRange $updated_at Time range for filtering on the `updated_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `UPDATED_AT`.
	 * @return $this
	 */
	public function setUpdatedAt( $updated_at ) {
		$this->updated_at = $updated_at;
		return $this;
	}
	/**
	 * Gets closed_at
	 *
	 * @return \SquareConnect\Model\TimeRange
	 */
	public function getClosedAt() {
		return $this->closed_at;
	}

	/**
	 * Sets closed_at
	 *
	 * @param \SquareConnect\Model\TimeRange $closed_at Time range for filtering on the `closed_at` timestamp. If you use this value, you must also set the `sort_field` in the OrdersSearchSort object to `CLOSED_AT`.
	 * @return $this
	 */
	public function setClosedAt( $closed_at ) {
		$this->closed_at = $closed_at;
		return $this;
	}
	/**
	 * Returns true if offset exists. False otherwise.
	 *
	 * @param  integer $offset Offset
	 * @return boolean
	 */
	public function offsetExists( $offset ) {
		return isset( $this->$offset );
	}

	/**
	 * Gets offset.
	 *
	 * @param  integer $offset Offset
	 * @return mixed
	 */
	public function offsetGet( $offset ) {
		return $this->$offset;
	}

	/**
	 * Sets value based on offset.
	 *
	 * @param  integer $offset Offset
	 * @param  mixed   $value  Value to be set
	 * @return void
	 */
	public function offsetSet( $offset, $value ) {
		$this->$offset = $value;
	}

	/**
	 * Unsets offset.
	 *
	 * @param  integer $offset Offset
	 * @return void
	 */
	public function offsetUnset( $offset ) {
		unset( $this->$offset );
	}

	/**
	 * Gets the string presentation of the object
	 *
	 * @return string
	 */
	public function __toString() {
		if ( defined( 'JSON_PRETTY_PRINT' ) ) {
			return json_encode( \SquareConnect\ObjectSerializer::sanitizeForSerialization( $this ), JSON_PRETTY_PRINT );
		} else {
			return json_encode( \SquareConnect\ObjectSerializer::sanitizeForSerialization( $this ) );
		}
	}
}