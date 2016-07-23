<?php

namespace App\Repositories;

use App\Models\Attachment;
use App\Validations\AttachmentValidation;
use App\Repositories\Repository;
use Carbon\Carbon;

class AttachmentRepository extends Repository
{
    /**
     * The attachment validation instance.
     *
     * @var AttachmentValidation
     */
    protected $validation;

    /**
     * Create a new repository instance.
     *
     * @param  AttachmentValidation  $validation
     * @return void
     */
    public function __construct(AttachmentValidation $validation)
    {
        $this->validation = $validation;
    }

    /**
     * Get all of the agents with specified queries.
     *
     * @param  array  $params
     * @return array
     */
    public function index($params)
    {
        $query = $this->select(Attachment::with('user', 'address'), $params, false);

        if ( array_has($params, 'message') ) {
            $query->ofMessage($params['message']);
        }

        if ( array_has($params, 'user') ) {
            $query->ofUser($params['user']);
        }

        return $this->extractQuery($query, $params);
    }

    /**
     * Store a new attachment model.
     *
     * @param  array  $data
     * @return array
     */
    public function single($data)
    {
        $attachment = new Attachment;
        $attachment->uploaded_at = Carbon::now()->toDateTimeString();
        $attachment->save();

        Message::find($data['message_id'])->attachments()->save($attachment);

        return $this->extractResource($attachment, 'attachments');
    }

    /**
     * Store multiple attachment models at once.
     *
     * @param  array  $data
     * @return array
     */
    public function multiple($data)
    {
        $results = [];

        foreach ($data as $d) {            
            $results[] = $this->single($d);
        }

        return $results;
    }
}
