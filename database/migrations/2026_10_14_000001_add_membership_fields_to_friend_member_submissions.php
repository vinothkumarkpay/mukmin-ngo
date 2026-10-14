<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMembershipFieldsToFriendMemberSubmissions extends Migration
{
    public function up()
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->unsignedInteger('membership_number')->nullable()->unique()->after('status');
            $table->date('membership_start_date')->nullable()->after('membership_number');
            $table->date('membership_end_date')->nullable()->after('membership_start_date');
            $table->timestamp('approved_at')->nullable()->after('membership_end_date');
            $table->timestamp('welcome_email_sent_at')->nullable()->after('approved_at');
        });
    }

    public function down()
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->dropUnique(['membership_number']);
            $table->dropColumn([
                'membership_number',
                'membership_start_date',
                'membership_end_date',
                'approved_at',
                'welcome_email_sent_at',
            ]);
        });
    }
}
