<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class SetFixedFriendsMembershipEndDate extends Migration
{
    public function up()
    {
        DB::table('friend_member_submissions')
            ->whereNotNull('membership_number')
            ->update(['membership_end_date' => config('welfare.friends_membership.end_date', '2027-10-31')]);
    }

    public function down()
    {
        // Previous end dates were derived from each approval year and are not restored.
    }
}
