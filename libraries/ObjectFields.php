<?php

namespace BestProject;

use Exception;
use FieldsHelper;

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
	 * Fields context (usually com_content.article)
	 *
	 * @var string
	 * @since 1.5.0
	 */
	private $context;


	/**
	 * Create object fields instance.
	 *
	 * @param   array   $fields   Item fields array.
	 * @param   string  $context  Fields context.
	 *
	 * @since 1.5.0
	 */
	public function __construct(array &$fields, string $context)
	{
		$this->fields  = $fields;
		$this->context = $context;
	}

	/**
	 * Get selected field from item and convert its JSON contents to array.
	 *
	 * @param   string  $name  Name of a field.
	 *
	 * @return array
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function getArrayFromJSON(string $name): array
	{
		if (!$this->has($name))
		{
			throw new Exception('This item does not have a field called "' . $name . '"".', 500);
		}

		return (array) json_decode($this->get($name)->rawvalue);
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
			throw new Exception('This item does not have a field called "' . $name . '"".', 500);
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

		$layout = $field->params->get('layout', 'render');

		return FieldsHelper::render(
			$this->context,
			'field.' . $layout,
			array(
				'item'    => '',
				'context' => $this->context,
				'field'   => $field
			)
		);
	}

	/**
	 * Get the value of a field.
	 *
	 * @param   string  $name  Name of a field.
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function getValue(string $name): string
	{
		$field = $this->get($name);

		return $field->value;
	}

}