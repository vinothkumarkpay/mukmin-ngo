<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddNetworkFieldsToFriendMemberSubmissions extends Migration
{
    public function up()
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->json('network_communities')->nullable()->after('ind_area_of_interest');
            $table->boolean('communications_consent')->default(false)->after('declaration_confirmed');
        });
    }

    public function down()
    {
        Schema::table('friend_member_submissions', function (Blueprint $table) {
            $table->dropColumn(['network_communities', 'communications_consent']);
        });
    }
}
