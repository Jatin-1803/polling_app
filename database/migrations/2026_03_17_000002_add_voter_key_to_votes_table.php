<?php

use App\Models\Vote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            if (!Schema::hasColumn('votes', 'voter_key')) {
                $table->string('voter_key', 191)->default('')->after('ip_address');
            }
        });

        // Backfill existing rows (if any) so we can enforce uniqueness in app logic.
        Vote::query()
            ->where('voter_key', '')
            ->orderBy('id')
            ->chunkById(200, function ($votes) {
                foreach ($votes as $vote) {
                    $vote->voter_key = $vote->user_id
                        ? ('user:' . $vote->user_id)
                        : ('ip:' . ($vote->ip_address ?: 'unknown'));
                    $vote->save();
                }
            });

        Schema::table('votes', function (Blueprint $table) {
            $table->unique(['poll_id', 'voter_key'], 'votes_poll_voter_key_unique');
        });
    }

    public function down(): void
    {
        Schema::table('votes', function (Blueprint $table) {
            $table->dropUnique('votes_poll_voter_key_unique');
            if (Schema::hasColumn('votes', 'voter_key')) {
                $table->dropColumn('voter_key');
            }
        });
    }
};

