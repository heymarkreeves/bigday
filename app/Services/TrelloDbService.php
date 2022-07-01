<?php

namespace App\Services;

use App\Models\TrelloBoard;
use App\Models\TrelloCard;
use App\Models\TrelloCardTrelloLabel;
use App\Models\TrelloCardTrelloMember;
use App\Models\TrelloCustomField;
use App\Models\TrelloCustomFieldOption;
use App\Models\TrelloLabel;
use App\Models\TrelloList;
use App\Models\TrelloMember;

class TrelloDbService
{
    public function __construct() {}

    public function upsertTrelloBoard($apiData = []) {
        //
    }

    public function upsertTrelloCard($apiData = []) {
        // upsert labels found on card
        // upsert card, return ID
        // create card label relations
        // create card member relations
    }

    public function clearTrelloCardTrelloLabels() {
        TrelloCardTrelloLabel::truncate();
    }

    public function clearTrelloCardTrelloMembers() {
        TrelloCardTrelloMember::truncate();
    }

    public function insertTrelloCardTrelloLabel($cardId = 0, $trelloLabelId = '') {
        //
    }

    public function insertTrelloCardTrelloMember($cardId = 0, $trelloMemberId = '') {
        //
    }

    public function upsertTrelloCustomField($apiData = []) {
        $customField = TrelloCustomField::updateOrCreate(
            ['custom_field_id' => $apiData['id']],
            ['name' => $apiData['name'], 'type' => $apiData['type']]
        );

        if ($apiData['type'] === 'list' && isset($apiData['options'])) {
            foreach($apiData['options'] as $option) {
                $customFieldOption = TrelloCustomFieldOption::updateOrCreate(
                    ['custom_field_option_id' => $option['id'], 'custom_field_id' => $option['idCustomField']],
                    ['value_text' => $option['value'], 'color' => $option['color']]
                );
            }
        }

        return true;
    }

    public function upsertTrelloCustomFieldOption($apiData = []) {
        //
    }

    public function upsertTrelloLabel($apiData = []) {
        $list = TrelloMember::updateOrCreate(
            ['label_id' => $apiData['id']],
            ['name' => $apiData['name'], 'color' => $apiData['color']]
        );

        return true;
    }

    public function upsertTrelloList($apiData = []) {
        $list = TrelloList::updateOrCreate(
            ['list_id' => $apiData['id']],
            ['name' => $apiData['name'], 'pos' => $apiData['pos']]
        );

        return true;
    }

    public function upsertTrelloMember($apiData = []) {
        $list = TrelloMember::updateOrCreate(
            ['member_id' => $apiData['id']],
            ['full_name' => $apiData['fullName'], 'username' => $apiData['username']]
        );

        return true;
    }

    public function skip() {
        return true;
    }
}