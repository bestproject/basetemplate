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
	private $fields;

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
			throw new \RuntimeException('This item does not have a field called "' . $name . '"".', 500);
		}

		return (array)json_decode($this->get($name)->rawvalue, false, 512, JSON_THROW_ON_ERROR);
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
		return array_key_exists($name, $this->fields) and !empty($this->fields[$name]->value);
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
			throw new \RuntimeException('This item does not have a field called "' . $name . '"".', 500);
		}

		return $this->fields[$name];
	}

	/**
	 * Get the value of a field. If field don't exists or value is empty, return $default value.
	 *
	 * @param   string  $name     Name of a field.
	 * @param   mixed   $default  Default value to return.
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function getValue(string $name, $default = '')
	{
		if ($this->has($name))
		{
			$value = $this->get($name)->value;
		}

		return empty($value) ? $default : $value;
	}

	/**
	 * Get the raw value of a field. If field don't exists or the value is empty, return $default value.
	 *
	 * @param   string  $name     Name of a field.
	 * @param   mixed   $default  Default value to return.
	 *
	 * @return mixed
	 *
	 * @throws Exception
	 * @since 1.5.0
	 */
	public function getRawValue(string $name, $default = '')
	{
		if ($this->has($name))
		{
			$rawvalue = $this->get($name)->rawvalue;
		}

		return empty($rawvalue) ? $default : $rawvalue;
	}

	/**
	 * Render field if it exists.
	 *
	 * @param   string  $name  Field name.
	 *
	 * @return string
	 *
	 * @throws Exception
	 * @since 1.0
	 */
	public function renderIfExists(string $name): string
	{
		$return = '';

		if ($this->has($name))
		{
			$return = $this->render($name);
		}

		return $return;
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
			[
				'item'    => '',
				'context' => $this->context,
				'field'   => $field
			]
		);
	}

}