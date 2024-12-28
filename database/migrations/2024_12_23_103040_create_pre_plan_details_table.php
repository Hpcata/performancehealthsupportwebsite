    <?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    return new class extends Migration
    {
        /**
         * Run the migrations.
         *
         * @return void
         */
        public function up()
        {
            Schema::create('pre_plan_details', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_pre_plan_id')->nullable(); // User ID (nullable if not provided)
                $table->string('form_name'); // Name of the form
                $table->string('form_slug'); // Unique index for question (e.g., section_key format)
                $table->text('question'); // The text of the question
                $table->json('answer')->nullable(); // The corresponding answer stored as JSON
                $table->timestamps();

                // Add optional foreign key constraint (if users table exists)
                $table->foreign('user_pre_plan_id')->references('id')->on('user_pre_plans')->onDelete('cascade');
            
            });
        }

        /**
         * Reverse the migrations.
         *
         * @return void
         */
        public function down()
        {
            Schema::dropIfExists('pre_plan_details');
        }
    };
