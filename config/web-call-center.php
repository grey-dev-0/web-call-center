<?php
return [
    // Prefix of database tables included in this package.
    'tables_prefix' => 'wcc',

    // The model class of the customer who'd initiate calls to the call center.
    'customer_model' => \GreyZero\WebCallCenter\Models\Customer::class,

    // The organization that provides customer support and / or call center agents.
    'organization_model' => \GreyZero\WebCallCenter\Models\Organization::class,

    // The model class of the agent who'd receive calls from customers.
    'agent_model' => \GreyZero\WebCallCenter\Models\Agent::class,

    // The foreign key that joins the entity representing the agent to an organization.
    'organization_foreign_key' => 'organization_id',

    // The default datetime format used by the package.
    'datetime_format' => 'd/m/Y h:i:s A',

    // The primary keys to be set as incremental for the tables that will be created by the package.
    // Only for entities that were not changed from their default values above e.g. "customer_model".
    // Set any of them to false if you're going to define the value of each model's id manually.
    'incremental_primary_keys' => [
        'customers' => true,
        'organizations' => true,
        'agents' => true
    ]
];
