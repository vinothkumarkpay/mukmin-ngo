<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateSisterhoodRegistrationsTable extends Migration
{
    public function up()
    {
        Schema::create('sisterhood_registrations', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('salutation');
            $table->string('preferred_name');
            $table->string('nric_passport');
            $table->string('nric_normalized')->index();
            $table->string('email');
            $table->string('contact_number');
            $table->unsignedTinyInteger('age');
            $table->string('profession');
            $table->string('profession_other')->nullable();
            $table->string('education_level');
            $table->string('education_other')->nullable();
            $table->string('unit_number', 50);
            $table->string('address_line_1');
            $table->string('address_line_2')->nullable();
            $table->string('postcode', 10);
            $table->string('state');
            $table->json('areas_of_interest');
            $table->json('looking_forward');
            $table->string('food_preference');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number', 30);
            $table->string('emergency_relationship');
            $table->string('heard_from');
            $table->string('heard_from_other')->nullable();
            $table->json('declarations');
            $table->decimal('amount', 8, 2);
            $table->string('payment_status')->default('pending')->index();
            $table->string('payment_method')->default('KiplePay');
            $table->string('order_id')->unique();
            $table->string('payment_reference')->nullable();
            $table->json('payment_payload')->nullable();
            $table->json('payment_attempts')->nullable();
            $table->timestamp('paid_at')->nullable();
            $table->text('admin_notes')->nullable();
            $table->timestamps();
        });

        $now = now();
        $permissions = [
            [
                'slug' => 'sisterhood.view',
                'label' => 'View Sisterhood Collective registrations',
                'group' => 'Sisterhood Collective',
                'description' => 'View participant registrations and RM30 payment records.',
            ],
            [
                'slug' => 'sisterhood.manage',
                'label' => 'Manage Sisterhood Collective registrations',
                'group' => 'Sisterhood Collective',
                'description' => 'Edit participant details and payment status.',
            ],
        ];

        foreach ($permissions as $permission) {
            DB::table('permissions')->updateOrInsert(
                ['slug' => $permission['slug']],
                $permission + ['created_at' => $now, 'updated_at' => $now]
            );
        }
    }

    public function down()
    {
        Schema::dropIfExists('sisterhood_registrations');

        DB::table('permissions')->whereIn('slug', [
            'sisterhood.view',
            'sisterhood.manage',
        ])->delete();
    }
}
