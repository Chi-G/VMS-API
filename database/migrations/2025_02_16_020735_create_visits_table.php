<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVisitsTable extends Migration
{
    public function up()
    {
        Schema::create('visits', function (Blueprint $table) {
            $table->id();
            $table->foreignId('admin_id')->constrained()->onDelete('cascade');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->foreignId('visitor_id')->constrained()->onDelete('cascade');
            $table->enum('visitor_type', ['individual', 'group', 'walk-in', 'VIP'])->default('individual');
            $table->enum('purpose', ['recruitment', 'business meeting', 'repairs/maintenance', 'personal/official', 'other'])->default('business meeting');
            $table->timestamp('check_in');
            $table->timestamp('check_out')->nullable();
            $table->string('host_name')->nullable();
            $table->enum('building', ['building 1', 'building 2', 'building 3', 'building 4'])->default('building 1');
            $table->enum('floor', ['1st floor', '2nd floor', '3rd floor', '4th floor'])->default('1st floor');
            $table->enum('group_size', ['2 to 10', '10 to 20', '20 to 30', '30 above'])->default('2 to 10');
            $table->string('status')->nullable();
            $table->enum('visit_time', ['30mins', '1 to 2hrs', '3 to 4hrs', '4 to 5hrs'])->default('30mins');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visits');
    }
}
