<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrelloCardTrelloMember extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'trello_card_trello_member';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['trello_card_id', 'trello_member_id'];
}
