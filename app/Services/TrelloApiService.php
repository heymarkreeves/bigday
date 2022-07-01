<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class TrelloApiService
{
    protected $config;
    protected $trelloKey, $trelloToken, $trelloBoardSlug;
    public $hasError = false;

    public function __construct($config)
    {
        $this->config = $config;

        if (!isset($this->config['trello_key']) || !isset($this->config['trello_token'])
                || !isset($this->config['board_slug']))
            $this->hasError = true;
        if (!$this->config['trello_key'] || !$this->config['trello_token']
                || !$this->config['board_slug'])
            $this->hasError = true;

        $this->trelloKey = $this->config['trello_key'];
        $this->trelloToken = $this->config['trello_token'];
        $this->trelloBoardSlug = $this->config['board_slug'];
    }

    public function callApi($endpoint = null) {
        if (!$endpoint)
            return false;
        $apiUrl = "https://api.trello.com/1/$endpoint?key=$this->trelloKey&token=$this->trelloToken";
        $response = Http::get($apiUrl);

        $payload = (object)['status' => $response->status()];
        $payload->message = ($payload->status !== 200) ? $response->body() : 'OK';
        $payload->response = $response;
        return $payload;
    }

    public function fetch() {
        $trelloBoard = $this->fetchBoard($this->trelloBoardSlug);

        $board['board'] = $trelloBoard;
        $board['customFields'] = $this->fetchCustomFields($this->trelloBoardSlug);
        $board['lists'] = $this->fetchLists($this->trelloBoardSlug);
        $board['members'] = $this->fetchMembers($this->trelloBoardSlug);
        $board['cards'] = $this->fetchCards($this->trelloBoardSlug);

        return $board;
    }

    private function fetchBoard(string $boardSlug) {
        $payload = $this->callApi("boards/$boardSlug");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);
        else
            return ['error' => false];
    }

    private function fetchCardCustomFieldItems(string $cardSlug = null) {
        if (!$cardSlug)
            return false;
        $payload = $this->callApi("cards/$cardSlug/customFieldItems");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);

        $customFieldItems = [];
        foreach($payload->response->collect() as $customFieldItem) {
            $customFieldItems[] = $customFieldItem;
        }

        return $customFieldItems;
    }

    private function fetchCards(string $boardSlug) {
        $payload = $this->callApi("boards/$boardSlug/cards");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);

        $cards = [];
        foreach ($payload->response->collect() as $card) {
            $cards[] = [
                'id' => $card['id'],
                'desc' => $card['desc'],
                'due' => $card['due'],
                'idList' => $card['idList'],
                'idMembers' => $card['idMembers'],
                'labels' => $card['labels'],
                'idLabels' => $card['idLabels'],
                'name' => $card['name'],
                'pos' => $card['pos'],
                'shortLink' => $card['shortLink'],
                'customFields' => $this->fetchCardCustomFieldItems($card['shortLink']),
            ];
        }

        return $cards;
    }

    private function fetchCustomFields(string $boardSlug) {
        $payload = $this->callApi("boards/$boardSlug/customFields");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);

        $customFields = [];
        foreach ($payload->response->collect() as $customField) {
            $options = [];
            if ($customField['type'] == 'list') {
                foreach ($this->fetchCustomFieldOptions($customField['id']) as $customFieldOption) {
                    $options[] = [
                        'id' => $customFieldOption['_id'],
                        'idCustomField' => $customField['id'],
                        'value' => $customFieldOption['value']['text'],
                        'color' => $customFieldOption['color'],
                    ];
                }
            }
            $customFields[] = [
                'id' => $customField['id'],
                'name' => $customField['name'],
                'type' => $customField['type'],
                'options' => $options,
            ];
        }

        return $customFields;
    }

    private function fetchCustomFieldOptions(string $customFieldId = null) {
        if (!$customFieldId)
            return false;
        $payload = $this->callApi("customFields/$customFieldId/options");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);
        return $payload->response->collect();
    }

    private function fetchLists(string $boardSlug) {
        $payload = $this->callApi("boards/$boardSlug/lists");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);

        $lists = [];
        foreach ($payload->response->collect() as $list) {
            $lists[] = [
                'id' => $list['id'],
                'name' => $list['name'],
                'pos' => $list['pos'],
            ];
        }

        return $lists;
    }

    private function fetchMembers(string $boardSlug) {
        $payload = $this->callApi("boards/$boardSlug/members");
        if ($payload->message !== 'OK')
            return $this->handleError($payload);

        $members = [];
        foreach ($payload->response->collect() as $member) {
            $members[] = [
                'id' => $member['id'],
                'fullName' => $member['fullName'],
                'username' => $member['username'],
            ];
        }

        return $members;
    }

    private function handleError($payload) {
        // TODO: Log the error
        return [
            'error' => true,
            'status' => $payload->status,
            'message' => $payload->message,
        ];
    }
}
