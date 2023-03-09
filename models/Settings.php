<?php

namespace Spanjaan\ContactForm\Models;

use Model;

class Settings extends Model
{
    public $implement = ['System.Behaviors.SettingsModel'];

    // A unique code
    public $settingsCode = 'spanjaan_contacform_settings';

    // Reference to field configuration
    public $settingsFields = 'fields.yaml';
}
