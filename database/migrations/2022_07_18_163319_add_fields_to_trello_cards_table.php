<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('trello_cards', function (Blueprint $table) {
            $table->boolean('prior_customer')->after('list_id')->nullable();
            $table->string('email_address')->after('prior_customer')->nullable();
            $table->string('phone_number', 25)->after('email_address')->nullable();
            $table->string('status', 25)->after('phone_number')->nullable();
            $table->string('synergy_id', 25)->after('status')->nullable();
            $table->string('venue')->after('synergy_id')->nullable();
            $table->string('lead_source', 100)->after('venue')->nullable();
            $table->datetimeTz('date_opened')->after('lead_source')->nullable();
            $table->datetimeTz('date_closed')->after('date_opened')->nullable();
            $table->integer('opportunity')->after('date_closed')->nullable();
            $table->integer('confidence')->after('opportunity')->nullable();
            $table->integer('final_billing')->after('confidence')->nullable();
            $table->string('lead_type', 100)->after('final_billing')->nullable();
            $table->dateTimeTz('tour_date')->after('lead_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('trello_cards', function (Blueprint $table) {
            $table->dropColumn('prior_customer');
            $table->dropColumn('email_address');
            $table->dropColumn('phone_number');
            $table->dropColumn('status');
            $table->dropColumn('synergy_id');
            $table->dropColumn('venue');
            $table->dropColumn('lead_source');
            $table->dropColumn('date_opened');
            $table->dropColumn('date_closed');
            $table->dropColumn('opportunity');
            $table->dropColumn('confidence');
            $table->dropColumn('final_billing');
            $table->dropColumn('lead_type');
            $table->dropColumn('tour_date');
        });
    }
};
