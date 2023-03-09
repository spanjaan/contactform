<?php

namespace Spanjaan\ContactForm\Models;

use Model;

/**
 * Model
 */
class ContactForm extends Model
{
    use \Winter\Storm\Database\Traits\Validation;

    /*
     * Validation
     */
    public $rules = [
    ];

    /*
     * Disable timestamps by default.
     * Remove this line if timestamps are defined in the database table.
     */
    public $timestamps = true;

    /**
     * @var string The database table used by the model.
     */
    public $table = 'spanjaan_contactform';

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = [
     'is_new' => 'boolean',
     'is_replied' => 'boolean',
    ];
}
