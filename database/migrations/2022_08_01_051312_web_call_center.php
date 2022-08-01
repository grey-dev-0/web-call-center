<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class WebCallCenter extends Migration{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(){
        if(config('web-call-center.customer_model') == \GreyZero\WebCallCenter\Models\Customer::class)
            Schema::create(config('web-call-center.tables_prefix').'_customers', function(Blueprint $table){
                if(config('web-call-center.incremental_primary_keys.customers'))
                    $table->increments('id');
                else
                    $table->unsignedInteger('id')->primary();
                $table->string('name');
                $table->timestamps();
            });

        if(config('web-call-center.organization_model') == \GreyZero\WebCallCenter\Models\Organization::class)
            Schema::create(config('web-call-center.tables_prefix').'_organizations', function(Blueprint $table){
                if(config('web-call-center.incremental_primary_keys.organizations'))
                    $table->increments('id');
                else
                    $table->unsignedInteger('id')->primary();
                $table->string('name');
                $table->timestamps();
            });

        if(config('web-call-center.agent_model') == \GreyZero\WebCallCenter\Models\Agent::class)
            Schema::create(config('web-call-center.tables_prefix').'_agents', function(Blueprint $table){
                if(config('web-call-center.incremental_primary_keys.agents'))
                    $table->increments('id');
                else
                    $table->unsignedInteger('id')->primary();
                $table->unsignedInteger($organizationForeignKey = config('web-call-center.organization_foreign_key'));
                $organizationModel = config('web-call-center.organization_model');
                $table->foreign($organizationForeignKey)->references('id')
                    ->on((new $organizationModel)->getTable())->onUpdate('cascade')->onDelete('cascade');
                $table->string('name');
                $table->timestamps();
            });

        Schema::create(config('web-call-center.tables_prefix').'_calls', function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('customer_id');
            $table->unsignedInteger('agent_id');
            $customerModel = config('web-call-center.customer_model');
            $agentModel = config('web-call-center.customer_model');
            $table->foreign('customer_id')->references('id')->on((new $customerModel)->getTable())
                ->onUpdate('cascade')->onDelete('cascade');
            $table->foreign('agent_id')->references('id')->on((new $agentModel)->getTable())
                ->onUpdate('cascade')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(){
        Schema::dropIfExists(config('web-call-center.tables_prefix').'_calls');
        Schema::dropIfExists(config('web-call-center.tables_prefix').'_agents');
        Schema::dropIfExists(config('web-call-center.tables_prefix').'_organizations');
        Schema::dropIfExists(config('web-call-center.tables_prefix').'_customers');
    }
}
