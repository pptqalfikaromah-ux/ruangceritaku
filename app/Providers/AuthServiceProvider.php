<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        \App\Models\Student::class => \App\Policies\StudentPolicy::class,
        \App\Models\Classroom::class => \App\Policies\ClassroomPolicy::class,
        \App\Models\AcademicYear::class => \App\Policies\AcademicYearPolicy::class,
        \App\Models\CounselorAssignment::class => \App\Policies\CounselorAssignmentPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        //
    }
}
