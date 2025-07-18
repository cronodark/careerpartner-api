<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\talent\AchievementController;
use App\Http\Controllers\talent\EducationHistoryController;
use App\Http\Controllers\talent\ExperienceController;
use App\Http\Controllers\talent\InterestController;
use App\Http\Controllers\talent\InternshipController;
use App\Http\Controllers\talent\LearningPathController;
use App\Http\Controllers\talent\ProjectController;
use App\Http\Controllers\talent\SkillController;
use App\Http\Controllers\talent\TalentController;
use App\Http\Controllers\talent\VolunteerController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/register', [AuthController::class, 'register'])->name('register');
Route::middleware('auth:sanctum')->group(function () {
    Route::middleware('role:talent')->group(function () {
        Route::get('/talent', [TalentController::class, 'index'])->name('talent.index');
        Route::put('/talent', [TalentController::class, 'update'])->name('talent.update');
        Route::delete('/talent', [TalentController::class, 'destroy'])->name('talent.destroy');
        Route::post('/talent/user', [TalentController::class, 'userUpdate'])->name('talent.user.update');
        Route::post('/talent/profile-generation', [TalentController::class, 'aiGenerateProfile'])->name('talent.profile.generation');
        Route::get('/talent/interests', [InterestController::class, 'index'])->name('talent.interests');
        Route::post('/talent/interests', [InterestController::class, 'store'])->name('talent.interests.store');
        Route::delete('/talent/interests/{id}', [InterestController::class, 'destroy'])->name('talent.interests.destroy');
        Route::get('/talent/skills', [SkillController::class, 'index'])->name('talent.skills');
        Route::post('/talent/skills', [SkillController::class, 'store'])->name('talent.skills.store');
        Route::delete('/talent/skills/{id}', [SkillController::class, 'destroy'])->name('talent.skills.destroy');
        Route::get('/talent/education', [EducationHistoryController::class, 'index'])->name('talent.education');
        Route::post('/talent/education', [EducationHistoryController::class, 'store'])->name('talent.education.store');
        Route::put('/talent/education/{id}', [EducationHistoryController::class, 'update'])->name('talent.education.update');
        Route::delete('/talent/education/{id}', [EducationHistoryController::class, 'destroy'])->name('talent.education.destroy');
        Route::get('/talent/achievements', [AchievementController::class, 'index'])->name('talent.achievements');
        Route::post('/talent/achievements', [AchievementController::class, 'store'])->name('talent.achievements.store');
        Route::put('/talent/achievements/{id}', [AchievementController::class, 'update'])->name('talent.achievements.update');
        Route::delete('/talent/achievements/{id}', [AchievementController::class, 'destroy'])->name('talent.achievements.destroy');
        Route::get('/talent/projects', [ProjectController::class, 'index'])->name('talent.projects');
        Route::post('/talent/projects', [ProjectController::class, 'store'])->name('talent.projects.store');
        Route::post('/talent/projects/{id}', [ProjectController::class, 'update'])->name('talent.projects.update');
        Route::delete('/talent/projects/{id}', [ProjectController::class, 'destroy'])->name('talent.projects.destroy');
        Route::get('/talent/experience', [ExperienceController::class, 'index'])->name('talent.experience');
        Route::post('/talent/experience', [ExperienceController::class, 'store'])->name('talent.experience.store');
        Route::delete('/talent/experience/{id}', [ExperienceController::class, 'destroy'])->name('talent.experience.destroy');
        Route::get('/talent/internships', [InternshipController::class, 'index'])->name('talent.internships');
        Route::get('/talent/internships/{id}', [InternshipController::class, 'show'])->name('talent.internships.show');
        Route::get('/talent/volunteers', [VolunteerController::class, 'index'])->name('talent.volunteers');
        Route::get('/talent/volunteers/{id}', [VolunteerController::class, 'show'])->name('talent.volunteers.show');
        Route::get('/talent/learning-paths', [LearningPathController::class, 'index'])->name('talent.learning-paths');
        Route::post('/talent/learning-path-generation', [LearningPathController::class, 'generate'])->name('talent.learning-path.generate');
    });
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
