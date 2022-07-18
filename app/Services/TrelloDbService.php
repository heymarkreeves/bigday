<?php
namespace App\Services;

use App\Helpers\Formatter;
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

    private function setCustomFieldValue(&$cardData, $customField) {
        $config = config('trello');

        $dbField = $config['card_fields'][$customField['idCustomField']];
        if (!isset($dbField))
            return false;

        switch ($dbField['type']) {
            case 'lookup':
                $value = TrelloCustomFieldOption::where('custom_field_option_id', $customField['idValue'])->first();
                if (isset($value))
                    $cardData[$dbField['db_field']] = $value->value_text;
                break;
            case 'string':
                $cardData[$dbField['db_field']] = $customField['value']['text'];
                break;
            case 'dollars':
                $cardData[$dbField['db_field']] = (int)(100 * (float)$customField['value']['number']);
                break;
            case 'number':
                $cardData[$dbField['db_field']] = (int)$customField['value']['number'];
                break;
            case 'boolean':
                $cardData[$dbField['db_field']] = $customField['value']['checked'] == 'true';
                break;
            case 'datetime':
                $d = new \DateTime($customField['value']['date']);
                $cardData[$dbField['db_field']] = $d->format('c');
                break;
            case 'phone':
                $formatter = new Formatter();
                $cardData[$dbField['db_field']] = $formatter->rawPhoneNumber($customField['value']['text']);
                break;
            default:
                break;
        }

        return true;
    }

    public function upsertTrelloCard($apiData = []) {
        $d = new \DateTime($apiData['due']);

        $cardData = [];
        // Loop through custom fields in apiData
        foreach($apiData['customFields'] as $customField) {
            $this->setCustomFieldValue($cardData, $customField);
        }
        $cardData['name'] = $apiData['name'];
        $cardData['shortLink'] = $apiData['shortLink'];
        $cardData['due_date'] = $d->format('c');
        $cardData['list_id'] = $apiData['idList'];

        $card = TrelloCard::updateOrCreate(
            ['card_id' => $apiData['id']],
            $cardData
            // [
            //     'name' => $apiData['name'],
            //     'shortLink' => $apiData['shortLink'],
            //     'due_date' => $d->format('c'),
            //     'list_id' => $apiData['idList']
            // ]
        );
        foreach($apiData['labels'] as $cardLabel) {
            $label = TrelloLabel::updateOrCreate(
                ['label_id' => $cardLabel['id']],
                ['name' => $cardLabel['name'], 'color' => $cardLabel['color']]
            );
            $cardLabelRelation = TrelloCardTrelloLabel::updateOrCreate(
                ['trello_card_id' => $card->id, 'trello_label_id' => $label->id],
                ['trello_card_id' => $card->id, 'trello_label_id' => $label->id]
            );
        }
        foreach($apiData['idMembers'] as $cardMemberId) {
            $member = TrelloMember::where('member_id', $cardMemberId)->first();
            $cardMemberRelation = TrelloCardTrelloMember::updateOrCreate(
                ['trello_card_id' => $card->id, 'trello_member_id' => $member->id],
                ['trello_card_id' => $card->id, 'trello_member_id' => $member->id]
            );
        }
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
        //
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