<?php

use App\Models\Employee;
use App\Models\Feedback;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\MenuController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\PengaturanController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\QuestionOptionController;
use App\Http\Controllers\Customer\FeedbackFormController;
use App\Http\Controllers\Admin\QuestionCategoryController;
use App\Http\Controllers\Customer\FeedbackAnswerController;

// Route::get('/', function () {
//     return view('welcome');
// });

// == Login & Logout ==
Route::get('/admin/login', [AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('/admin/login', [AuthController::class, 'login'])->name('admin.login.submit');
Route::post('/admin/logout', [AuthController::class, 'logout'])->name('admin.logout');

Route::get('/login', function () {
    return redirect()->route('admin.login');
})->name('login');


// == Semua route admin hanya bisa diakses setelah login ==
Route::middleware('auth:admin')->group(function () {


    // Admin Dashboard
    Route::get('/admin/dashboard', [DashboardController::class, 'index'])->name('dashboard.main');

    // Menus & Categories
    Route::resource('menus', MenuController::class);
    Route::resource('menu-categories', MenuCategoryController::class);

    // Pengaturan
    Route::get('/admin/pengaturan', [PengaturanController::class, 'edit'])->name('admin.pengaturan.edit');
    Route::put('/admin/pengaturan', [PengaturanController::class, 'update'])->name('admin.pengaturan.update');

    // Employees
    Route::resource('employees', EmployeeController::class);
    Route::get('employees/{employee}/details', [EmployeeController::class, 'showDetails'])->name('employees.details');
    Route::get('employees/{employee}/details/edit', [EmployeeController::class, 'editDetails'])->name('employees.editDetails');
    Route::put('employees/{employee}/details', [EmployeeController::class, 'updateDetails'])->name('employees.updateDetails');

    // Customers
    Route::resource('customers', CustomerController::class);
    Route::get('/customers/{id}/feedbacks', [CustomerController::class, 'showFeedbacks'])->name('customers.feedbacks');
    Route::get('customers/{id}/feedbackDetail', [CustomerController::class, 'feedbackDetail'])->name('customers.feedbackDetail');
    Route::get('customers-export', [CustomerController::class, 'export'])->name('customers.export');

    // Question Categories
    Route::resource('question-categories', QuestionCategoryController::class);
    Route::patch('/question-categories/{category}/toggle', [QuestionCategoryController::class, 'toggle'])->name('question-categories.toggle');

    // Questions
    Route::get('/questions/category/{category}', [QuestionController::class, 'index'])->name('questions.index');
    Route::get('/questions/category/{category}/create', [QuestionController::class, 'create'])->name('questions.create');
    Route::post('/questions/category/{category}', [QuestionController::class, 'store'])->name('questions.store');
    Route::get('/questions/{id}/edit', [QuestionController::class, 'edit'])->name('questions.edit');
    Route::put('/questions/{id}', [QuestionController::class, 'update'])->name('questions.update');
    Route::delete('/questions/{id}', [QuestionController::class, 'destroy'])->name('questions.destroy');

    // Question Options
    Route::prefix('questions/{question}/options')->group(function () {
        Route::get('/', [QuestionOptionController::class, 'index'])->name('question-options.index');
        Route::get('/create', [QuestionOptionController::class, 'create'])->name('question-options.create');
        Route::post('/', [QuestionOptionController::class, 'store'])->name('question-options.store');
        Route::get('/{option}/edit', [QuestionOptionController::class, 'edit'])->name('question-options.edit');
        Route::put('/{option}', [QuestionOptionController::class, 'update'])->name('question-options.update');
        Route::delete('/{option}', [QuestionOptionController::class, 'destroy'])->name('question-options.destroy');
    });

    // Feedback Answers
    Route::get('feedback-answers', [FeedbackAnswerController::class, 'index'])->name('feedback-answers.index');
    Route::get('feedback-answers/{id}', [FeedbackAnswerController::class, 'show'])->name('feedback-answers.show');
    Route::get('feedback-answers-export', [FeedbackAnswerController::class, 'export'])->name('feedback-answers.export');
});


// == Halaman Customer ==

// Pilihan Kategori feedback
Route::get('/feedback', [FeedbackFormController::class, 'showCategories'])->name('feedback.categories');
Route::get('/feedback/success', function () {
    return view('feedback_forms.thankyou');
})->name('feedback.success');
Route::get('/feedback/{category}', [FeedbackFormController::class, 'showQuestions'])->name('feedback.questions');
Route::post('/feedback/{category}/submit', [FeedbackFormController::class, 'submit'])->name('feedback.submit');
