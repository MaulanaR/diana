<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ALL MENUS
        $idmenu_menus = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 0,
            'menu_nama' => 'Menus',
            'menu_uri' => 'menus',
            'menu_target' => null,
            'menu_icon' => 'fas fa-allergies',
            'order_num' => 1,
        ]);

        $idmenu_group = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 0,
            'menu_nama' => 'Group',
            'menu_uri' => 'group',
            'menu_target' => null,
            'menu_icon' => 'fas fa-align-justify',
            'order_num' => 2,
        ]);

        $idmenu_users = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 0,
            'menu_nama' => 'Users',
            'menu_uri' => 'users',
            'menu_target' => null,
            'menu_icon' => 'fas fa-allergies',
            'order_num' => 3,
        ]);

        $idmenu_internship = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 0,
            'menu_nama' => 'Interships',
            'menu_uri' => '#',
            'menu_target' => null,
            'menu_icon' => 'fas fa-align-justify',
            'order_num' => 1,
        ]);

        $idmenu_internship_locations = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 56,
            'menu_nama' => 'Internship Locations',
            'menu_uri' => 'internship_locations',
            'menu_target' => null,
            'menu_icon' => 'fas fa-info-circle',
            'order_num' => 1,
        ]);

        $idmenu_internship_periods = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 56,
            'menu_nama' => 'Internship Periods',
            'menu_uri' => 'internship_periods',
            'menu_target' => null,
            'menu_icon' => 'fas fa-award',
            'order_num' => 0,
        ]);

        $idmenu_internship_students = DB::table('alus_mg')->insertGetId([
            'menu_parent' => 56,
            'menu_nama' => 'Interships Students',
            'menu_uri' => 'internship_students',
            'menu_target' => null,
            'menu_icon' => 'fas fa-align-justify',
            'order_num' => 3,
        ]);



        //ROLE ADMIN
        $idgrup = DB::table('alus_g')->insertGetId(
            ['name' => 'admin', 'description' => 'Admin Website'],
        );

        $id_user = DB::table('users')->insertGetId([
            'name' => 'Administrator',
            'email' => 'admin@admin.com',
            'password' => bcrypt('adminadmin'),
            'picture' => 'avatar.png',
        ]);

        DB::table('alus_ug')->insert([
            'user_id' => $id_user,
            'group_id' => $idgrup,
        ]);

        DB::table('alus_mga')->insert(
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_group,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_menus,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_users,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_locations,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_periods,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_students,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ]
        );
        //END ADMIN

        //ROLE STUDENT
        $idgrup = DB::table('alus_g')->insertGetId(
            ['name' => 'student', 'description' => 'Students Roles'],
        );
        $id_user = DB::table('users')->insertGetId([
            'name' => 'Student James',
            'email' => 'student@admin.com',
            'password' => bcrypt('adminadmin'),
            'picture' => 'avatar.png',
        ]);

        DB::table('student_details')->insertGetId([
            'id' => $id_user,
            'full_name' => 'Student James',
            'nim' => 7276666,
        ]);

        DB::table('alus_ug')->insert([
            'user_id' => $id_user,
            'group_id' => $idgrup,
        ]);

        DB::table('alus_mga')->insert(
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_group,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_menus,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_users,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_locations,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_periods,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_students,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 0,
            ]
        );
        //END STUDENT

        //ROLE INSTRUCTOR
        $idgrup = DB::table('alus_g')->insertGetId(
            ['name' => 'instructor', 'description' => 'Instructor Rules'],
        );
        $id_user = DB::table('users')->insertGetId([
            'name' => 'Instructor',
            'email' => 'instructor@admin.com',
            'password' => bcrypt('adminadmin'),
            'picture' => 'avatar.png',
        ]);

        DB::table('instructors')->insertGetId([
            'id' => $id_user,
            'name' => 'Instructor',
        ]);

        DB::table('alus_ug')->insert([
            'user_id' => $id_user,
            'group_id' => $idgrup,
        ]);

        DB::table('alus_mga')->insert(
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_group,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_menus,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_users,
                'can_view' => 0,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 1,
                'can_delete' => 1,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_locations,
                'can_view' => 1,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_periods,
                'can_view' => 1,
                'can_edit' => 0,
                'can_add' => 0,
                'can_delete' => 0,
            ],
            [
                'id_group' => $idgrup,
                'id_menu' => $idmenu_internship_students,
                'can_view' => 1,
                'can_edit' => 1,
                'can_add' => 0,
                'can_delete' => 0,
            ]
        );
        //END INSTRUCTOR

    }
}
