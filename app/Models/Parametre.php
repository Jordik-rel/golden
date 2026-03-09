<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Parametre extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'email_notification_seuil_alerte',
        'email_notification_seuil_min',
        'email_notification_rapport_journalier',
        'email_notification_rapport_inventaire',
        'nombre_jour_retro_production'
    ];
}
