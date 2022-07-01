<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Trello extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'trello:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch board & cards from Trello';

    /**
     * The Trello provider services.
     */
    protected $trelloApi;
    protected $trelloDb;

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->trelloApi = app('trello-api');
        $this->trelloDb = app('trello-db');

        $board = $this->trelloApi->fetch();
        $this->newLine(1);

        if ($this->trelloApi->hasError) {
            $this->error("Unable to initialize a Trello connection.");
            return 0;
        }

        if (isset($board['board']['error']) && $board['board']['error']) {
            $this->error("An error occurred fetching the board.");
            $this->newLine(1);
        }

        $this->updateData($board['customFields'], 'custom fields', 'upsertTrelloCustomField');

        $this->updateData($board['lists'], 'lists', 'upsertTrelloList');

        $this->updateData($board['members'], 'members', 'upsertTrelloMember');

        $this->trelloDb->clearTrelloCardTrelloLabels();
        $this->trelloDb->clearTrelloCardTrelloMembers();

        $this->updateData($board['cards'], 'cards', 'upsertTrelloCard', true);

        return 0;
    }

    private function updateData($apiData = [], $pluralName = '', $upsertMethod = 'skip', $last = false) {
        if (isset($apiData['error']) && $apiData['error']) {
            $this->error("An error occurred fetching $pluralName.");
            if (!$last)
                $this->newLine(1);
            return false;
        }
        else {
            $number = count($apiData);
            $this->info("$number $pluralName fetched");
            $this->info("Updating $pluralName...");
            $this->newLine(1);

            $bar = $this->output->createProgressBar(count($apiData));
            $bar->start();
            foreach($apiData as $dataItem) {
                $this->trelloDb->$upsertMethod($dataItem);
                $bar->advance();
            }
            $bar->finish();

            if (!$last)
                $this->newLine(2);
            return true;
        }
    }
}
