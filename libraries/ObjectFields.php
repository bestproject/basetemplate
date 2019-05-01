<?php

namespace BestProject;

use Exception;

/**
 * Class helping with item custom fields.
 *
 * @since 1.5.0
 */
final class ObjectFields
{

	/**
	 * Items fields map.
	 *
	 * @var array
	 * @since 1.5.0
	 */
	private $fields = [];


	/**
	 * Create object fields instance.
	 *
	 * @param   array  $fields  Item fields array.
	 *
	 * @since 1.5.0
	 */
	public function __construct(array &$fields)
	{
		$this->fields = $fields;
	}

	/**
	 * Get selected field from item.
	 *
	 * @param   string  $name  Name of a field.
	 *
	 * @return object
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function get(string $name): object
	{
		if (!$this->has($name))
		{
			throw new Exception('This item does not have a field called "'.$name.'"".', 500);
		}

		return $this->fields[$name];
	}

	/**
	 * Render selected item field.
	 *
	 * @param   string  $name  Name of a field.
	 *
	 * @return object
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function render(string $name): string
	{
		$field = $this->get($name);

		return $field->value;
	}

	/**
	 * Check if object has selected field.
	 *
	 * @param   string  $name  Name of a field.
	 *
	 * @return bool
	 *
	 * @since 1.5.0
	 */
	public function has(string $name): bool
	{
		return key_exists($name, $this->fields);
	}

}