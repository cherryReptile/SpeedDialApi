<?php

use App\Models\Dial;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('dials', 'img_source')) {
            Dial::chunk(100, function ($dials) {
                foreach ($dials as $dial) {
                    $dial->images()->create([
                        'img_source' => $dial->img_source
                    ]);
                }
            });
        }
        Schema::table('dials', function (Blueprint $table) {
            $table->dropColumn('img_source');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('dials', function (Blueprint $table) {
            $table->string('img_source')->nullable();
        });
    }
};
