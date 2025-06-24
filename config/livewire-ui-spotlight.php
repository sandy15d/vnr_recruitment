<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Shortcuts
    |--------------------------------------------------------------------------
    |
    | Define which shortcuts will activate Spotlight CTRL / CMD + key
    | The default is CTRL/CMD + K or CTRL/CMD + /
    |
    */

    'shortcuts' => [
        'k',
        'slash',
    ],

    /*
    |--------------------------------------------------------------------------
    | Commands
    |--------------------------------------------------------------------------
    |
    | Define which commands you want to make available in Spotlight.
    | Alternatively, you can also register commands in your AppServiceProvider
    | with the Spotlight::registerCommand(Logout::class); method.
    |
    */

    'commands' => [
        App\Spotlight\Logout::class,
        App\Spotlight\SentEmail::class,
        App\Spotlight\UserList::class,
        App\Spotlight\Company::class,
        App\Spotlight\UserLogs::class,
        App\Spotlight\CandidateDetail::class,
        App\Spotlight\CommunicationControl::class,
        App\Spotlight\Country::class,
        App\Spotlight\Department::class,
        App\Spotlight\DepartmentVertical::class,
        App\Spotlight\Designation::class,
        App\Spotlight\District::class,
        App\Spotlight\Education::class,
        App\Spotlight\EducationSpecialization::class,
        App\Spotlight\EducationInstitute::class,
        App\Spotlight\Eligibility::class,
        App\Spotlight\Employee::class,
        App\Spotlight\Grade::class,
        App\Spotlight\Headquarter::class,
        App\Spotlight\PositionCode::class,
        App\Spotlight\State::class,
        App\Spotlight\StateHQ::class,
        App\Spotlight\ReportExcel::class,
        App\Spotlight\FiroBReport::class,
        App\Spotlight\Password::class,
        App\Spotlight\Dashboard::class,
        App\Spotlight\InterviewSchedule::class,
        App\Spotlight\ManpowerRequisition::class,
        App\Spotlight\MyTeam::class,
        App\Spotlight\MrfAllocated::class,
        App\Spotlight\MrfManualEntry::class,
        App\Spotlight\JobResponse::class,
        App\Spotlight\ResumeDataBank::class,
        App\Spotlight\ScreeningTracker::class,
        App\Spotlight\InterviewTracker::class,
        App\Spotlight\JobOffers::class,
        App\Spotlight\CandidateJoining::class,
        App\Spotlight\CampusMRF::class,
        App\Spotlight\CampusApplication::class,
        App\Spotlight\CampusScrTracker::class,
        App\Spotlight\CampusHiringTracker::class,
        App\Spotlight\CampusCosting::class,
        App\Spotlight\TraineeMRF::class,
        App\Spotlight\TraineeApplications::class,
        App\Spotlight\TraineeTracker::class,
        App\Spotlight\ActiveTrainee::class,
        App\Spotlight\OldTrainee::class,
        App\Spotlight\SubjectMaster::class,
        App\Spotlight\QuestionBank::class,


    ],

    /*
    |--------------------------------------------------------------------------
    | Include CSS
    |--------------------------------------------------------------------------
    |
    | Spotlight uses TailwindCSS, if you don't use TailwindCSS you will need
    | to set this parameter to true. This includes the modern-normalize css.
    |
    */
    'include_css' => true,


    /*
    |--------------------------------------------------------------------------
    | Include JS
    |--------------------------------------------------------------------------
    |
    | Spotlight will inject the required Javascript in your blade template.
    | If you want to bundle the required Javascript you can set this to false,
    | call 'npm install fuse.js' or 'yarn add fuse.js',
    | then add `require('vendor/livewire-ui/spotlight/resources/js/spotlight');`
    | to your script bundler like webpack.
    |
    */
    'include_js' => true,

];
