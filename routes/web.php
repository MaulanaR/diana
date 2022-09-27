<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\Roles;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
//login route
Route::get('/', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('home');
Route::get('/login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\Auth\LoginController::class, 'login']);
Route::get('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout'])->name('logout');
Route::post('/logout', [App\Http\Controllers\Auth\LoginController::class, 'logout']);
Route::get('/backend_login', [App\Http\Controllers\Auth\LoginController::class, 'showLoginForm'])->name('login_offline');
Route::post('/backend_login', [App\Http\Controllers\Auth\LoginController::class, 'login'])->name('login_offline_process');

Route::prefix('app')->middleware(['roles', 'auth', 'PageHitMiddleware'])->group(function () {
    //agar fungsi ajax tidak terkena ROLES ( menu belum didaftarkan, maka buatlah menjadi /ajax/...nama_fungsi.../paramenter/parameter)
    //prefix yang depannya /ajax tidak ada di validasi.
    Route::get('/', [App\Http\Controllers\HomeController::class, 'index'])->name('homelogin');

    Route::get('/ajax/change_role', [App\Http\Controllers\HomeController::class, 'ubah_role'])->name('change_role');
    Route::get('/ajax/get_majors/{id?}', [App\Http\Controllers\HomeController::class, 'get_majors'])->name('get_majors');
    Route::get('/ajax/get_classes/{period_id?}/{major_id?}', [App\Http\Controllers\HomeController::class, 'get_classes'])->name('get_classes');


    Route::get('group', [App\Http\Controllers\GroupController::class, 'index'])->name('group.index');
    Route::get('group/show', [App\Http\Controllers\GroupController::class, 'show'])->name('group.show');
    Route::post('group/permission', [App\Http\Controllers\GroupController::class, 'permission'])->name('group.permission');
    Route::post('group/updatepermission', [App\Http\Controllers\GroupController::class, 'updatepermission'])->name('group.updatepermission');
    Route::post('group/store', [App\Http\Controllers\GroupController::class, 'store'])->name('group.store');
    Route::put('group/update', [App\Http\Controllers\GroupController::class, 'update'])->name('group.update');
    Route::delete('group/destroy', [App\Http\Controllers\GroupController::class, 'destroy'])->name('group.destroy');

    Route::get('menus', [App\Http\Controllers\MenusController::class, 'index'])->name('menus.index');
    Route::get('menus/modal', [App\Http\Controllers\MenusController::class, 'modal'])->name('menus.modal');
    Route::get('menus/show', [App\Http\Controllers\MenusController::class, 'show'])->name('menus.show');
    Route::post('menus/edit', [App\Http\Controllers\MenusController::class, 'edit'])->name('menus.edit');
    Route::post('menus/store', [App\Http\Controllers\MenusController::class, 'store'])->name('menus.store');
    Route::put('menus/update', [App\Http\Controllers\MenusController::class, 'update'])->name('menus.update');
    Route::delete('menus/destroy', [App\Http\Controllers\MenusController::class, 'destroy'])->name('menus.destroy');

    Route::get('users', [App\Http\Controllers\UsersController::class, 'index'])->name('users.index');
    Route::get('users/modal', [App\Http\Controllers\UsersController::class, 'modal'])->name('users.modal');
    Route::get('users/show', [App\Http\Controllers\UsersController::class, 'show'])->name('users.show');
    Route::post('users/edit', [App\Http\Controllers\UsersController::class, 'edit'])->name('users.edit');
    Route::post('users/store', [App\Http\Controllers\UsersController::class, 'store'])->name('users.store');
    Route::post('users/update', [App\Http\Controllers\UsersController::class, 'updatex'])->name('users.update');
    Route::delete('users/destroy', [App\Http\Controllers\UsersController::class, 'destroy'])->name('users.destroy');

    Route::get('internship_periods', [App\Http\Controllers\InternshipPeriodsController::class, 'index'])->name('internship_periods.index');
    Route::get('internship_periods/modal', [App\Http\Controllers\InternshipPeriodsController::class, 'modal'])->name('internship_periods.modal');
    Route::get('internship_periods/show', [App\Http\Controllers\InternshipPeriodsController::class, 'show'])->name('internship_periods.show');
    Route::get('internship_periods/edit/{id?}', [App\Http\Controllers\InternshipPeriodsController::class, 'edit'])->name('internship_periods.edit');
    Route::post('internship_periods/store', [App\Http\Controllers\InternshipPeriodsController::class, 'store'])->name('internship_periods.store');
    Route::post('internship_periods/update', [App\Http\Controllers\InternshipPeriodsController::class, 'update'])->name('internship_periods.update');
    Route::delete('internship_periods/destroy', [App\Http\Controllers\InternshipPeriodsController::class, 'destroy'])->name('internship_periods.destroy');

    Route::get('internship_locations', [App\Http\Controllers\InternshipLocationsController::class, 'index'])->name('internship_locations.index');
    Route::get('internship_locations/modal', [App\Http\Controllers\InternshipLocationsController::class, 'modal'])->name('internship_locations.modal');
    Route::get('internship_locations/show', [App\Http\Controllers\InternshipLocationsController::class, 'show'])->name('internship_locations.show');
    Route::get('internship_locations/edit/{id?}', [App\Http\Controllers\InternshipLocationsController::class, 'edit'])->name('internship_locations.edit');
    Route::post('internship_locations/store', [App\Http\Controllers\InternshipLocationsController::class, 'store'])->name('internship_locations.store');
    Route::post('internship_locations/update', [App\Http\Controllers\InternshipLocationsController::class, 'update'])->name('internship_locations.update');
    Route::delete('internship_locations/destroy', [App\Http\Controllers\InternshipLocationsController::class, 'destroy'])->name('internship_locations.destroy');

    Route::get('internship_students', [App\Http\Controllers\InternshipsStudentsController::class, 'index'])->name('internship_students.index');
    Route::get('internship_students/modal', [App\Http\Controllers\InternshipsStudentsController::class, 'modal'])->name('internship_students.modal');
    Route::get('internship_students/show', [App\Http\Controllers\InternshipsStudentsController::class, 'show'])->name('internship_students.show');
    Route::get('internship_students/edit/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'edit'])->name('internship_students.edit');
    Route::get('internship_students/approval/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'approval'])->name('internship_students.approval');
    Route::get('internship_students/info/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'info'])->name('internship_students.info');
    Route::get('internship_students/instruktur/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'instruktur'])->name('internship_students.instruktur');
    Route::get('internship_students/berkas/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'berkas'])->name('internship_students.berkas');
    Route::get('internship_students/nilai/{id?}', [App\Http\Controllers\InternshipsStudentsController::class, 'nilai'])->name('internship_students.nilai');
    Route::post('internship_students/store', [App\Http\Controllers\InternshipsStudentsController::class, 'store'])->name('internship_students.store');
    Route::post('internship_students/storeStudent', [App\Http\Controllers\InternshipsStudentsController::class, 'storeStudent'])->name('internship_students.storeStudent');
    Route::post('internship_students/update', [App\Http\Controllers\InternshipsStudentsController::class, 'update'])->name('internship_students.update');
    Route::post('internship_students/updateapproval', [App\Http\Controllers\InternshipsStudentsController::class, 'updateapproval'])->name('internship_students.updateapproval');
    Route::post('internship_students/updateinstruktor', [App\Http\Controllers\InternshipsStudentsController::class, 'updateinstruktor'])->name('internship_students.updateinstruktor');
    Route::post('internship_students/updateberkas', [App\Http\Controllers\InternshipsStudentsController::class, 'updateberkas'])->name('internship_students.updateberkas');
    Route::post('internship_students/updateevaluation', [App\Http\Controllers\InternshipsStudentsController::class, 'updateevaluation'])->name('internship_students.updateevaluation');
    Route::delete('internship_students/destroy', [App\Http\Controllers\InternshipsStudentsController::class, 'destroy'])->name('internship_students.destroy');

    Route::get('logbook/{id?}', [App\Http\Controllers\LogbookController::class, 'index'])->name('logbook.index');
    Route::get('logbook/{id?}/modal', [App\Http\Controllers\LogbookController::class, 'modal'])->name('logbook.modal');
    Route::get('logbook/{id?}/show', [App\Http\Controllers\LogbookController::class, 'show'])->name('logbook.show');
    Route::get('logbook/edit/{id?}', [App\Http\Controllers\LogbookController::class, 'edit'])->name('logbook.edit');
    Route::post('logbook/store', [App\Http\Controllers\LogbookController::class, 'store'])->name('logbook.store');
    Route::post('logbook/update', [App\Http\Controllers\LogbookController::class, 'update'])->name('logbook.update');
    Route::delete('logbook/destroy', [App\Http\Controllers\LogbookController::class, 'destroy'])->name('logbook.destroy');

    Route::post('students/update', [App\Http\Controllers\StudentsController::class, 'update'])->name('students.update');
    Route::post('instructors/update', [App\Http\Controllers\InstructorsController::class, 'update'])->name('instructor.update');

    Route::get('academic_periods', [App\Http\Controllers\AcademicPeriodsController::class, 'index'])->name('academic_periods.index');
    Route::get('academic_periods/modal', [App\Http\Controllers\AcademicPeriodsController::class, 'modal'])->name('academic_periods.modal');
    Route::get('academic_periods/show', [App\Http\Controllers\AcademicPeriodsController::class, 'show'])->name('academic_periods.show');
    Route::get('academic_periods/edit/{id?}', [App\Http\Controllers\AcademicPeriodsController::class, 'edit'])->name('academic_periods.edit');
    Route::post('academic_periods/store', [App\Http\Controllers\AcademicPeriodsController::class, 'store'])->name('academic_periods.store');
    Route::post('academic_periods/update', [App\Http\Controllers\AcademicPeriodsController::class, 'update'])->name('academic_periods.update');
    Route::delete('academic_periods/destroy', [App\Http\Controllers\AcademicPeriodsController::class, 'destroy'])->name('academic_periods.destroy');

    Route::get('majors', [App\Http\Controllers\MajorsController::class, 'index'])->name('majors.index');
    Route::get('majors/modal', [App\Http\Controllers\MajorsController::class, 'modal'])->name('majors.modal');
    Route::get('majors/show', [App\Http\Controllers\MajorsController::class, 'show'])->name('majors.show');
    Route::get('majors/edit/{id?}', [App\Http\Controllers\MajorsController::class, 'edit'])->name('majors.edit');
    Route::post('majors/store', [App\Http\Controllers\MajorsController::class, 'store'])->name('majors.store');
    Route::post('majors/update', [App\Http\Controllers\MajorsController::class, 'update'])->name('majors.update');
    Route::delete('majors/destroy', [App\Http\Controllers\MajorsController::class, 'destroy'])->name('majors.destroy');

    Route::get('classes', [App\Http\Controllers\ClassesController::class, 'index'])->name('classes.index');
    Route::get('classes/modal', [App\Http\Controllers\ClassesController::class, 'modal'])->name('classes.modal');
    Route::get('classes/show', [App\Http\Controllers\ClassesController::class, 'show'])->name('classes.show');
    Route::get('classes/edit/{id?}', [App\Http\Controllers\ClassesController::class, 'edit'])->name('classes.edit');
    Route::post('classes/store', [App\Http\Controllers\ClassesController::class, 'store'])->name('classes.store');
    Route::post('classes/update', [App\Http\Controllers\ClassesController::class, 'update'])->name('classes.update');
    Route::delete('classes/destroy', [App\Http\Controllers\ClassesController::class, 'destroy'])->name('classes.destroy');

    Route::get('courses', [App\Http\Controllers\CoursesController::class, 'index'])->name('courses.index');
    Route::get('courses/modal', [App\Http\Controllers\CoursesController::class, 'modal'])->name('courses.modal');
    Route::get('courses/show', [App\Http\Controllers\CoursesController::class, 'show'])->name('courses.show');
    Route::get('courses/edit/{id?}', [App\Http\Controllers\CoursesController::class, 'edit'])->name('courses.edit');
    Route::post('courses/store', [App\Http\Controllers\CoursesController::class, 'store'])->name('courses.store');
    Route::post('courses/update', [App\Http\Controllers\CoursesController::class, 'update'])->name('courses.update');
    Route::delete('courses/destroy', [App\Http\Controllers\CoursesController::class, 'destroy'])->name('courses.destroy');
});
