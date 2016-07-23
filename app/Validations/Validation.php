<?php

namespace App\Validations;

abstract class Validation
{
	/**
     * Get rules for basic index request.
     *
     * @param  string  $table
     * @return array
     */
	protected function basicIndexRules($table)
	{
		return [
			'fields' => 'sometimes|fields:'. $table,
			'sort' => 'sometimes|sort:'. $table,
			'where' => 'sometimes|where:'. $table,
			'between' => 'sometimes|between_select:'. $table,
			'limit' => 'sometimes|limit:'. $table,
			'skip' => 'sometimes|skip:'. $table,
		];
	}
}
