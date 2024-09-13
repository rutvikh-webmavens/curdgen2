<?php

return [
    'userManagement' => [
        'title'          => 'User management',
        'title_singular' => 'User management',
    ],
    'permission' => [
        'title'          => 'Permissions',
        'title_singular' => 'Permission',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'title'             => 'Title',
            'title_helper'      => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated at',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted at',
            'deleted_at_helper' => ' ',
        ],
    ],
    'role' => [
        'title'          => 'Roles',
        'title_singular' => 'Role',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'title'              => 'Title',
            'title_helper'       => ' ',
            'permissions'        => 'Permissions',
            'permissions_helper' => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
        ],
    ],
    'user' => [
        'title'          => 'Users',
        'title_singular' => 'User',
        'fields'         => [
            'id'                           => 'ID',
            'id_helper'                    => ' ',
            'name'                         => 'Name',
            'name_helper'                  => ' ',
            'email'                        => 'Email',
            'email_helper'                 => ' ',
            'email_verified_at'            => 'Email verified at',
            'email_verified_at_helper'     => ' ',
            'password'                     => 'Password',
            'password_helper'              => ' ',
            'roles'                        => 'Roles',
            'roles_helper'                 => ' ',
            'remember_token'               => 'Remember Token',
            'remember_token_helper'        => ' ',
            'created_at'                   => 'Created at',
            'created_at_helper'            => ' ',
            'updated_at'                   => 'Updated at',
            'updated_at_helper'            => ' ',
            'deleted_at'                   => 'Deleted at',
            'deleted_at_helper'            => ' ',
            'approved'                     => 'Approved',
            'approved_helper'              => ' ',
            'team'                         => 'Team',
            'team_helper'                  => ' ',
            'two_factor'                   => 'Two-Factor Auth',
            'two_factor_helper'            => ' ',
            'two_factor_code'              => 'Two-factor code',
            'two_factor_code_helper'       => ' ',
            'two_factor_expires_at'        => 'Two-factor expires at',
            'two_factor_expires_at_helper' => ' ',
        ],
    ],
    'project' => [
        'title'          => 'Project',
        'title_singular' => 'Project',
        'fields'         => [
            'id'                    => 'ID',
            'id_helper'             => ' ',
            'name'                  => 'Name',
            'name_helper'           => ' ',
            'css'                   => 'CSS',
            'css_helper'            => ' ',
            'date_format'           => 'Date Format',
            'date_format_helper'    => ' ',
            'language'              => 'Language',
            'language_helper'       => ' ',
            'model_location'        => 'Model Location',
            'model_location_helper' => ' ',
            'timezone'              => 'Timezone',
            'timezone_helper'       => ' ',
            'created_at'            => 'Created at',
            'created_at_helper'     => ' ',
            'updated_at'            => 'Updated at',
            'updated_at_helper'     => ' ',
            'deleted_at'            => 'Deleted at',
            'deleted_at_helper'     => ' ',
            'team'                  => 'Team',
            'team_helper'           => ' ',
        ],
    ],
    'menu' => [
        'title'          => 'Menu',
        'title_singular' => 'Menu',
        'fields'         => [
            'id'                         => 'ID',
            'id_helper'                  => ' ',
            'project'                    => 'Project',
            'project_helper'             => ' ',
            'created_at'                 => 'Created at',
            'created_at_helper'          => ' ',
            'updated_at'                 => 'Updated at',
            'updated_at_helper'          => ' ',
            'deleted_at'                 => 'Deleted at',
            'deleted_at_helper'          => ' ',
            'model_name'                 => 'Model Name',
            'model_name_helper'          => ' ',
            'team'                       => 'Team',
            'team_helper'                => ' ',
            'title'                      => 'Title',
            'title_helper'               => ' ',
            'parent'                     => 'Parent',
            'parent_helper'              => ' ',
            'sort_order'                 => 'Sort Order',
            'sort_order_helper'          => ' ',
            'soft_delete'                => 'Soft Delete',
            'soft_delete_helper'         => ' ',
            'create'                     => 'Create',
            'create_helper'              => ' ',
            'edit'                       => 'Edit',
            'edit_helper'                => ' ',
            'show'                       => 'Show',
            'show_helper'                => ' ',
            'delete'                     => 'Delete',
            'delete_helper'              => ' ',
            'column_search'              => 'Column Search',
            'column_search_helper'       => ' ',
            'entries_per_page'           => 'Entries Per Page',
            'entries_per_page_helper'    => ' ',
            'order_by_field_name'        => 'Order By Field Name',
            'order_by_field_name_helper' => ' ',
            'order_by_desc'              => 'Order By Desc',
            'order_by_desc_helper'       => ' ',
            'type'                       => 'Type',
            'type_helper'                => ' ',
        ],
    ],
    'team' => [
        'title'          => 'Teams',
        'title_singular' => 'Team',
        'fields'         => [
            'id'                => 'ID',
            'id_helper'         => ' ',
            'created_at'        => 'Created at',
            'created_at_helper' => ' ',
            'updated_at'        => 'Updated At',
            'updated_at_helper' => ' ',
            'deleted_at'        => 'Deleted At',
            'deleted_at_helper' => ' ',
            'name'              => 'Name',
            'name_helper'       => ' ',
            'owner'             => 'Owner',
            'owner_helper'      => ' ',
        ],
    ],
    'table' => [
        'title'          => 'Tables',
        'title_singular' => 'Table',
        'fields'         => [
            'id'                 => 'ID',
            'id_helper'          => ' ',
            'menu'               => 'Menu',
            'menu_helper'        => ' ',
            'field_type'         => 'Field Type',
            'field_type_helper'  => ' ',
            'field_name'         => 'Field Name',
            'field_name_helper'  => ' ',
            'field_title'        => 'Field Title',
            'field_title_helper' => ' ',
            'in_list'            => 'In List',
            'in_list_helper'     => ' ',
            'in_create'          => 'In Create',
            'in_create_helper'   => ' ',
            'in_edit'            => 'In Edit',
            'in_edit_helper'     => ' ',
            'is_required'        => 'Is Required',
            'is_required_helper' => ' ',
            'sort_order'         => 'Sort Order',
            'sort_order_helper'  => ' ',
            'created_at'         => 'Created at',
            'created_at_helper'  => ' ',
            'updated_at'         => 'Updated at',
            'updated_at_helper'  => ' ',
            'deleted_at'         => 'Deleted at',
            'deleted_at_helper'  => ' ',
            'team'               => 'Team',
            'team_helper'        => ' ',
        ],
    ],

];
