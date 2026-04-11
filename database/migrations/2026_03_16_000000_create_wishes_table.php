<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wishes', function (Blueprint $table) {
            $table->increments('w_id');
            $table->integer('wished_by');
            $table->integer('granted_by')->nullable();
            $table->string('granted_date', 25)->nullable();
            $table->string('wish_title', 100)->nullable();
            $table->string('summary_title', 150)->nullable();
            $table->longText('wish_description')->nullable();
            $table->text('primary_image')->nullable();
            $table->double('expected_cost')->nullable();
            $table->string('expected_date', 25)->nullable();
            $table->string('in_return', 1500)->nullable();
            $table->string('who_can', 150)->nullable();
            $table->tinyInteger('non_pay_option')->default(0);
            $table->string('financial_assistance', 255)->nullable();
            $table->string('financial_assistance_other', 255)->nullable();
            $table->tinyInteger('wish_status')->default(1);
            $table->string('way_of_wish', 100)->nullable();
            $table->text('description_of_way')->nullable();
            $table->tinyInteger('show_mail_status')->default(0);
            $table->string('show_mail', 250)->nullable();
            $table->tinyInteger('show_person_status')->default(0);
            $table->string('show_person_street', 100)->nullable();
            $table->string('show_person_city', 250)->nullable();
            $table->string('show_person_state', 250)->nullable();
            $table->string('show_person_zip', 20)->nullable();
            $table->string('show_person_country', 250)->nullable();
            $table->tinyInteger('show_other_status')->default(0);
            $table->string('show_other_specify', 250)->nullable();
            $table->tinyInteger('i_agree_decide')->default(0);
            $table->tinyInteger('i_agree_decide2')->default(0);
            $table->tinyInteger('process_status')->default(0);
            $table->integer('process_granted_by')->nullable();
            $table->string('process_granted_date', 25)->nullable();
            $table->integer('fulfilled_by')->nullable();
            $table->dateTime('fulfilled_date')->nullable();
            $table->tinyInteger('wish_progress_status')->default(0);
            $table->text('grant_note')->nullable();
            $table->tinyInteger('email_status')->default(0);
            $table->tinyInteger('wish_email_status')->default(0);
            $table->timestamp('date_updated')->useCurrent();
            $table->timestamp('created_at')->nullable();
        });
        //         1️⃣ User creates wish
        // wish_progress_status = 0

        // Shows in Current Wishes

        // 2️⃣ Another user grants wish
        // granted_by = user_id
        // granted_date = NOW()
        // wish_progress_status = 1

        // Shows in In Progress

        // 3️⃣ Creator fulfills wish
        // fulfilled_by = creator_id
        // fulfilled_date = NOW()
        // wish_progress_status = 2

        // Shows in Granted / Fulfilled Wishes
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wishes');
    }
};
