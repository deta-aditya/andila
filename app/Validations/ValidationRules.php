<?php

namespace App\Validations;

use DB;

class ValidationRules
{
	/**
     * Rules definition for 'fields'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateFields($attribute, $value, $parameters, $validator)
	{
		$fields = explode(',', $value);

		foreach ($fields as $f) {
			if ($this->schemaHasNoColumn($parameters[0], $f)) {
				return false;
			}
		}

		return true;
	}

	/**
     * Rules definition for 'sort'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateSort($attribute, $value, $parameters, $validator)
	{
		$sorts = explode(',', $value);

		foreach ($sorts as $o) {
			list($field, $direction) = explode(':', $o);

			if ($this->schemaHasNoColumn($parameters[0], $field)) {
				return false;
			}

			if ($direction !== 'asc' && $direction !== 'desc') {
				return false;
			}
		}

		return true;
	}

	/**
     * Rules definition for 'where'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateWhere($attribute, $value, $parameters, $validator)
	{
		$wheres = explode(',', $value);

		foreach ($wheres as $w) {
			list($field, $operator, $value, $boolean) = array_pad(explode(':', $w, 4), 4, null);

			if ($this->schemaHasNoColumn($parameters[0], $field)) {
				return false;
			}

			if ($operator !== '=' && $operator !== '<' && $operator !== '>' && $operator !== '<=' && $operator !== '>=' && $operator !== '<>') {
				return false;
			}

			if (! is_null($boolean) && ($boolean !== 'and' && $boolean !== 'or')) {
				return false;
			}
		}

		return true;
	}

	/**
     * Rules definition for 'between_select'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateBetween($attribute, $value, $parameters, $validator)
	{
		$betweens = explode(',', $value);

		foreach ($betweens as $b) {
			list($field, $value1, $value2, $boolean, $not) = array_pad(explode(':', $b, 5), 5, null);

			if ($this->schemaHasNoColumn($parameters[0], $field)) {
				return false;
			}

			if (! is_null($boolean) && ($boolean !== 'and' && $boolean !== 'or')) {
				return false;
			}

			if (! is_null($not) && ($not != 1 && $not != 0)) {
				return false;
			}
		}

		return true;
	}

	/**
     * Rules definition for 'limit'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateLimit($attribute, $value, $parameters, $validator)
	{
		return $this->validForLimitOrSkip((int)$value, 1, 999);
	}

	/**
     * Rules definition for 'skip'.
     * This function is called at App\Providers\ValidationExtensionServiceProvider.
     *
     * @return bool
     */
	public function validateSkip($attribute, $value, $parameters, $validator)
	{
		return $this->validForLimitOrSkip((int)$value, 0, 999);
	}

	/**
     * Shortcut to check whether the requested value is valid for limitting and skipping
     *
     * @param  int  $value
     * @param  int  $min
     * @param  int  $max
     * @return bool
     */
	protected function validForLimitOrSkip($value, $min, $max)
	{
		return $value >= $min && $value <= $max;
	}

	/**
     * Shortcut to check whether the requested schema doesn't have the specified column
     *
     * @param  string  $schema
     * @param  string  $column
     * @return bool
     */
	protected function schemaHasNoColumn($schema, $column)
	{
		return ! $this->getAndilaSchemaBuilder()->hasColumn($schema, $column);
	}

	/**
     * Shortcut to get the Schema Builder of 'andila' connection.
     *
     * @return Schema ?
     */
	protected function getAndilaSchemaBuilder()
	{
		return DB::connection('andila')->getSchemaBuilder();
	}
}
