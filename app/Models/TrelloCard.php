<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrelloCard extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'card_id', 'name', 'shortLink', 'due_date', 'list_id',
        'prior_customer', 'email_address', 'phone_number', 'status', 'synergy_id', 'venue', 'lead_source', 'date_opened', 'date_closed', 'opportunity', 'confidence', 'final_billing', 'lead_type', 'tour_date'
    ];
}
