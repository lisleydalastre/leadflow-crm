<?php
// Copia este archivo como config.php y completa tus datos
return [
    'db' => [
        'host'    => 'localhost',
        'name'    => 'leadflow_crm',
        'user'    => 'TU_USUARIO_DB',
        'pass'    => 'TU_PASSWORD_DB',
        'charset' => 'utf8mb4',
    ],
    'app' => [
        'name'     => 'LeadFlow CRM',
        'url'      => 'http://localhost/leadflow-crm/public',
        'timezone' => 'America/Bogota',
        'debug'    => false,
    ],
    'session' => [
        'lifetime' => 7200,
        'name'     => 'leadflow_session',
    ],
];
