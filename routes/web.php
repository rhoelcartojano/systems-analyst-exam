<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

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

Route::view('/',  'welcome');

  // Authentication Routes
  Route::get('admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login');
  Route::post('admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.log');
  Route::post('admin/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('logout');

  // Registration Routes
  Route::get('admin/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('admin.register');
  Route::post('admin/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register']);

  // Password Reset Routes
  Route::get('password/reset', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showLinkRequestForm']);
  Route::post('password/email', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'sendResetLinkEmail']);
  Route::get('password/reset/{token}', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'showResetForm']);
  Route::post('password/reset', [App\Http\Controllers\Admin\Auth\ResetPasswordController::class, 'reset']);

  // Employee Authentication Routes
  Route::get('employee/login', [App\Http\Controllers\Employee\Auth\LoginController::class, 'showLogin'])->name('employee.login');
  Route::post('employee/log', [App\Http\Controllers\Employee\Auth\LoginController::class, 'login'])->name('employee.log');
  Route::post('employee/logout', [App\Http\Controllers\Employee\Auth\LoginController::class, 'logout'])->name('employee.logout');

  Route::group(['middleware' => 'employee_auth'], function () {
    Route::get('/employee/home', [App\Http\Controllers\Employee\HomeController::class, 'index'])->name('employee.employee_home');
  });

  Route::group(['middleware' => 'auth'], function () {
    // Admin Routes
    Route::get('/admin/home', [App\Http\Controllers\Admin\HomeController::class, 'index'])->name('admin.admin_home');
    Route::resource('department', App\Http\Controllers\Admin\DepartmentController::class); // CRUD

    Route::resource('employee', App\Http\Controllers\Admin\EmployeeController::class); // CRUD
    Route::get('/export-employee-reports', [App\Http\Controllers\Admin\EmployeeController::class, 'exportCsv']);
  });
