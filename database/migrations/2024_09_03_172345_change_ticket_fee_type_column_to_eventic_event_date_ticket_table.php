<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::table('eventic_event_date_ticket')->where('ticket_fee', '>', 99999999.99)->update([
            'ticket_fee' => 99999999.99
        ]);

        DB::table('eventic_event_date_ticket')->update([
            'ticket_fee' => DB::raw('ROUND(ticket_fee, 2)')
        ]);

        Schema::table('eventic_event_date_ticket', function (Blueprint $table) {
            $table->decimal('ticket_fee', 10, 2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('eventic_event_date_ticket', function (Blueprint $table) {
            $table->integer('ticket_fee')->change();
        });
    }
};
