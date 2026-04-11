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
        Schema::create('donations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('created_by');
            $table->integer('accepted_by')->nullable();
            $table->dateTime('accepted_at')->nullable();
            $table->integer('completed_by')->nullable();
            $table->dateTime('completed_at')->nullable();
            $table->tinyInteger('status')->default(0);

            $table->string('title', 100)->nullable();
            $table->string('summary_title', 150)->nullable();
            $table->longText('description')->nullable();
            $table->text('image')->nullable();

            $table->double('expected_cost')->nullable()->default(0);
            $table->date('expected_date')->nullable();

            $table->string('in_return', 1500)->nullable();
            $table->string('who_can', 150)->nullable();

            $table->tinyInteger('non_pay_option')->default(0);
            $table->string('financial_assistance', 255)->nullable();
            $table->string('financial_assistance_other', 255)->nullable();

            $table->string('way_of_donation', 100)->nullable();
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
            $table->dateTime('process_granted_date')->nullable();

            $table->tinyInteger('email_status')->default(0);
            $table->tinyInteger('donation_email_status')->default(0);

            $table->timestamp('date_updated')->useCurrent();
            $table->string('delivery_type', 100)->nullable();
            $table->string('delivery_other', 100)->nullable();
            $table->tinyInteger('acknowledgement')->default(0);
            $table->timestamp('created_at')->nullable();
        });

//         Use status with 4 states:

// 0 = draft
// 1 = published
// 2 = in_progress (accepted)
// 3 = completed
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('donations');
    }
};
