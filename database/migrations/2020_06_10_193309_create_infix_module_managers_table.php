<?php

use App\InfixModuleManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

use Illuminate\Support\Facades\Log;

class CreateInfixModuleManagersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('infix_module_managers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name', 200)->nullable();
            $table->string('email', 200)->nullable();
            $table->string('notes', 255)->nullable();
            $table->string('version', 200)->nullable();
            $table->string('update_url', 200)->nullable();
            $table->string('purchase_code', 200)->nullable();
            $table->string('installed_domain', 200)->nullable();
            $table->date('activated_date', 200)->nullable();
            $table->timestamps();
        });

        try {
            // RolePermission
            $dataPath = 'Modules/RolePermission/RolePermission.json';
            $name = 'RolePermission';
            $strJsonFileContents = file_get_contents($dataPath);
            $array = json_decode($strJsonFileContents, true);

            $version = $array[$name]['versions'][0];
            $url = $array[$name]['url'][0];
            $notes = $array[$name]['notes'][0];

            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = $notes;
            $s->version = $version;
            $s->update_url = $url;
            $s->purchase_code = time();
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();


            // TemplateSettings
            $dataPath = 'Modules/TemplateSettings/TemplateSettings.json';
            $name = 'TemplateSettings';
            $strJsonFileContents = file_get_contents($dataPath);
            $array = json_decode($strJsonFileContents, true);

            $version = $array[$name]['versions'][0];
            $url = $array[$name]['url'][0];
            $notes = $array[$name]['notes'][0];

            $s = new InfixModuleManager();
            $s->name = $name;
            $s->email = 'support@spondonit.com';
            $s->notes = $notes;
            $s->version = $version;
            $s->update_url = $url;
            $s->purchase_code = time();
            $s->installed_domain = url('/');
            $s->activated_date = date('Y-m-d');
            $s->save();
        } catch (Exception $e) {
            Log::info($e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('infix_module_managers');
    }
}
