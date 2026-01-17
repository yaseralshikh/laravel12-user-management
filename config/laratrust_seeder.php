<?php

return [
    /**
     * Control if the seeder should create a user per role while seeding the data.
     */
    'create_users' => false,

    /**
     * Control if all the laratrust tables should be truncated before running the seeder.
     */
    'truncate_tables' => true,

    'roles_structure' => [
        'superadmin' => [
            'users' => 'c,r,u,d',
            'schools' => 'c,r,u,d',
            'sectors' => 'c,r,u,d',
            'academic_years' => 'c,r,u,d',
            'programs' => 'c,r,u,d',
            'program_cycles' => 'c,r,u,d',
            'visits' => 'c,r,u,d',
            'work_events' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
        'admin' => [
            'users' => 'c,r,u,d',
            'schools' => 'c,r,u,d',
            'profile' => 'r,u',
        ],
        'supervisor' => [
            'users' => 'c,r,u,d',
            'schools' => 'c,r,u,d',            
            'profile' => 'r,u',
        ],
        'principal' => [
            'profile' => 'r,u',
        ],
        'coordinator' => [
            'profile' => 'r,u',
        ],
    ],

    'permissions_map' => [
        'c' => 'create',
        'r' => 'read',
        'u' => 'update',
        'd' => 'delete',
    ],
];
