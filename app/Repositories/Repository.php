<?php

namespace App\Repositories;

use Validator;
use Carbon\Carbon;
use Illuminate\Validation\Validator as OriginalValidator;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;

abstract class Repository
{
    /**
     * The secret access key.
     *
     * @var string
     */
    protected $ACCESS_SECRET_KEY = 'secret-poison';

    /**
     * The secret access initialization vector.
     *
     * @var string
     */
    protected $ACCESS_SECRET_IV = 'mr-chu';

    /**
     * The secret access version identity.
     *
     * @var string
     */
    protected $ACCESS_SECRET_VER = 'shashasha';

    /**
     * The secret method type.
     *
     * @var string
     */
    protected $ACCESS_SECRET_METHOD = 'AES-256-CBC';

    /**
     * The secret hash type.
     *
     * @var string
     */
    protected $ACCESS_SECRET_HASH = 'sha256';

    /**
     * The secret expiry scalar in minutes.
     *
     * @var int
     */
    protected $ACCESS_SECRET_EXPIRY = 999;

    /**
     * Validate incoming request with love and passion :*.
     * BTW this is only an alternative of the buggy Controller's $this->validate() method.
     *
     * @param  array  $inputs
     * @param  string|array  $rules
     * @param  array  $additional
     * @param  array  $messages
     * @param  array  $customAttributes
     * @return Validator
     */
    public function valid($inputs, $rules, $additional = [], $messages = [], $customAttributes = [])
    {
        // Get rules from the validation class 
        // and create the validator instance via Validator facade
        $rulesReal = is_array($rules) ? $rules : $this->validation->{'rules'. ucfirst($rules)}();
        $validator = Validator::make($inputs, $rulesReal, $messages, $customAttributes);

        return $this->getEnhancedValidator($validator, $rules, $additional);
    }

    /**
     * Call the function for enhancing the validator.
     *
     * @param  Validator  $validator
     * @param  array|string  $rules
     * @param  array  $additional
     * @return Validator
     */
    protected function getEnhancedValidator(OriginalValidator $validator, $rules, $additional = [])
    {
        if (is_array($rules) || ! count($additional)) {
            return $validator;
        }

        return $this->validation->{'after'. ucfirst($rules)}($validator, $additional);
    }

    /**
     * Send a request to create activity.
     *
     * @param  string  $text
     * @param  string  $type
     * @return void
     */
    protected function sendActivity()
    {

    }

    /**
     * Perform a standard SELECT query.
     *
     * @param  Builder  $query
     * @param  array  $params
     * @param  bool  $direct
     * @return Builder|array
     */
    protected function select(Builder $query, $params, $direct = true)
    {
        /*
         * Select fields
         * Format: fields=field_name,field_name,..
         */
        if ( array_has($params, 'fields') ) {
            $query->select(explode(',', $params['fields']));
        }

        /*
         * Sort fields
         * Format: sort=field_name:direction,field_name:direction,..
         */
        if ( array_has($params, 'sort') ) {
            $sorts = explode(',', $params['sort']);

            foreach ($sorts as $sort) {
                list($field, $direction) = explode(':', $sort);
                $query->orderBy($field, $direction);
            }
        }

        /*
         * Filter selection with basic conditions
         * Format: where=field_name:operator:value:boolean,field_name:operator:value:boolean,..
         * The 'boolean' attribute is optional
         */
        if ( array_has($params, 'where') ) {
            $conditions = explode(',', $params['where']);

            foreach ($conditions as $condition) {
                list($field, $operator, $value, $boolean) = array_pad(explode(':', $condition, 4), 4, null);
                $boolean = ! is_null($boolean) ? $boolean : 'and';

                $query->where($field, $operator, $value, $boolean);
            }
        }

        /*
         * Filter selection with ranged conditions
         * Format: between=field_name:value1:value2:boolean:not,field_name:value1:value2:boolean:not,..
         * The 'boolean' and 'not' attribute is optional
         */
        if ( array_has($params, 'between') ) {
            $conditions = explode(',', $params['between']);

            foreach ($conditions as $condition) {
                list($field, $value1, $value2, $boolean, $not) = array_pad(explode(':', $b, 5), 5, null);
                $boolean = ! is_null($boolean) ? $boolean : 'and';
                $not = ! is_null($not) ? (bool)$not : false;

                $query->whereBetween($field. $value1, $value2, $boolean, $not);
            }
        }

        /*
         * Determine selection limit
         * Format: limit=value
         * By default query will take 25 data
         */
        $query->take(25);

        if ( array_has($params, 'limit') ) {
            $query->take($params['limit']);
        }

        /*
         * Determine selection skip
         * Format: skip=value
         * By default query will skip 0 data
         */
        $query->skip(0);

        if ( array_has($params, 'skip') ) {
            $query->skip($params['skip']);
        }

        return $direct
            ? $this->extractQuery($query, $params)
            : $query;
    }

    /**
     * Extract query result into a JSON friendly array.
     *
     * @param  Builder  $query
     * @param  array  $params
     * @return array
     */
    protected function extractQuery(Builder $query, $params)
    {
        return array_has($params, 'count')
            ? ['count' => $query->count()]
            : ['results' => $query->get(), 'count' => $query->count()];
    }

    /**
     * Extract resource model into a JSON friendly array.
     *
     * @param  mixed  $model
     * @param  string  $slug
     * @return array
     */
    protected function extractResource($model, $slug)
    {
        return ['model' => $model, 'resource' => $this->resource($model->id, $slug)];
    }

    /**
     * Create a URI of slug-specific resource.
     *
     * @param  int  $id
     * @param  string  $slug
     * @return array
     */
    protected function resource($id, $slug)
    {
        return url($slug .'/'. $id);
    }

    /**
     * Encrypt a string using simple encrypter.
     *
     * @param  string  $string
     * @return string
     */
    protected function encrypt($string)
    {
        return base64_encode(openssl_encrypt($string, $this->ACCESS_SECRET_METHOD, $this->loadSecretKey(), 0, $this->loadSecretIV()));
    }

    /**
     * Decrypt a string using simple decrypter.
     *
     * @param  string  $string
     * @return string
     */
    protected function decrypt($string)
    {
        return openssl_decrypt(base64_decode($string), $this->ACCESS_SECRET_METHOD, $this->loadSecretKey(), 0, $this->loadSecretIV());
    }

    /**
     * Check whether the requested timestamp is expired or not.
     *
     * @param  string  $timestamp
     * @return bool
     */
    protected function checkTimestampExpiry($timestamp)
    {
        $now = Carbon::now();
        $req = Carbon::createFromFormat('U', $timestamp);

        return $req->diffInMinutes($now) >= $this->ACCESS_SECRET_EXPIRY;
    }

    /**
     * Load a secret key for encrypter/decrypter.
     *
     * @return string
     */
    private function loadSecretKey()
    {
        return hash($this->ACCESS_SECRET_HASH, $this->ACCESS_SECRET_KEY);
    }

    /**
     * Load a initialization vector for encrypter/decrypter.
     *
     * @return string
     */
    private function loadSecretIV()
    {
        return substr(hash($this->ACCESS_SECRET_HASH, $this->ACCESS_SECRET_IV), 0, 16);
    }
}
