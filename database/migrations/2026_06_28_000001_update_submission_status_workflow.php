<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateSubmissionStatusWorkflow extends Migration
{
    /**
     * @return void
     */
    public function up()
    {
        $existingStatusTables = [
            'ordinary_member_submissions',
            'friend_member_submissions',
            'partner_submissions',
            'community_aid_submissions',
            'mfls_scholarship_submissions',
        ];

        foreach ($existingStatusTables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                $this->setStringDefault($table, 'status', 'received');
            }
        }

        $newStatusTables = [
            'feedback_submissions',
            'mentor_submissions',
            'volunteer_submissions',
            'contact_submissions',
        ];

        foreach ($newStatusTables as $tableName) {
            if (!Schema::hasTable($tableName) || Schema::hasColumn($tableName, 'status')) {
                continue;
            }

            Schema::table($tableName, function (Blueprint $table) {
                $table->string('status')->default('received');
            });
        }

        $this->migrateLegacyStatuses();
    }

    /**
     * @return void
     */
    public function down()
    {
        $allTables = [
            'ordinary_member_submissions',
            'friend_member_submissions',
            'partner_submissions',
            'community_aid_submissions',
            'mfls_scholarship_submissions',
            'feedback_submissions',
            'mentor_submissions',
            'volunteer_submissions',
            'contact_submissions',
        ];

        foreach ($allTables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'status')) {
                continue;
            }

            DB::table($table)->where('status', 'received')->update(['status' => 'pending']);
            DB::table($table)->where('status', 'reviewing')->update(['status' => 'under_review']);

            if (in_array($table, ['feedback_submissions', 'mentor_submissions', 'volunteer_submissions', 'contact_submissions'], true)) {
                Schema::table($table, function (Blueprint $table) {
                    $table->dropColumn('status');
                });
                continue;
            }

            $this->setStringDefault($table, 'status', 'pending');
        }
    }

    private function migrateLegacyStatuses(): void
    {
        $tables = [
            'ordinary_member_submissions',
            'friend_member_submissions',
            'partner_submissions',
            'community_aid_submissions',
            'mfls_scholarship_submissions',
            'feedback_submissions',
            'mentor_submissions',
            'volunteer_submissions',
            'contact_submissions',
        ];

        foreach ($tables as $table) {
            if (!Schema::hasTable($table) || !Schema::hasColumn($table, 'status')) {
                continue;
            }

            DB::table($table)->where('status', 'pending')->update(['status' => 'received']);
            DB::table($table)->where('status', 'under_review')->update(['status' => 'reviewing']);
        }
    }

    private function setStringDefault(string $table, string $column, string $default): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'mysql') {
            DB::statement(sprintf(
                'ALTER TABLE `%s` MODIFY `%s` VARCHAR(255) NOT NULL DEFAULT %s',
                $table,
                $column,
                DB::getPdo()->quote($default)
            ));
            return;
        }

        if ($driver === 'sqlite') {
            // SQLite cannot alter column defaults in place; data migration covers existing rows.
            return;
        }
    }
}
