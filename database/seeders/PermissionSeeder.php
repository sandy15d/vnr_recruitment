<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            //role
            ['name' => 'Create Role', 'group_name' => 'Role'],
            ['name' => 'Read Role', 'group_name' => 'Role'],
            ['name' => 'Update Role', 'group_name' => 'Role'],
            ['name' => 'Delete Role', 'group_name' => 'Role'],

            //User
            ['name' => 'Create User', 'group_name' => 'User'],
            ['name' => 'Read User', 'group_name' => 'User'],
            ['name' => 'Update User', 'group_name' => 'User'],
            ['name' => 'Delete User', 'group_name' => 'User'],

            //Master
            ['name' => 'Create Master', 'group_name' => 'Master'],
            ['name' => 'Read Master', 'group_name' => 'Master'],
            ['name' => 'Update Master', 'group_name' => 'Master'],
            ['name' => 'Delete Master', 'group_name' => 'Master'],
            ['name' => 'Sync Master', 'group_name' => 'Master'],

            //Settings and Configuration
            ['name' => 'Sent Email', 'group_name' => 'Settings and Configuration'],
            ['name' => 'Communication Control', 'group_name' => 'Settings and Configuration'],
            ['name' => 'User Log', 'group_name' => 'Settings and Configuration'],
            ['name' => 'Database Backup', 'group_name' => 'Settings and Configuration'],

            //Reports
            ['name' => 'FIRO B Report', 'group_name' => 'Reports'],
            ['name' => 'Manual Entry Report', 'group_name' => 'Reports'],
            ['name' => 'Recruiter Wise Report', 'group_name' => 'Reports'],
            ['name' => 'MRF Report', 'group_name' => 'Reports'],
            ['name' => 'Activity Report', 'group_name' => 'Reports'],

            //Manpower Requisition Creation
            ['name' => 'New Manpower Requisition', 'group_name' => 'MRF Creation'],
            ['name' => 'Replacement Manpower Requisition', 'group_name' => 'MRF Creation'],
            ['name' => 'SIP Manpower Requisition', 'group_name' => 'MRF Creation'],
            ['name' => 'Campus Manpower Requisition', 'group_name' => 'MRF Creation'],

            //Manpower Requisition Listing
            ['name' => 'New MRF Listing', 'group_name' => 'MRF Listing'],
            ['name' => 'Active MRF Listing', 'group_name' => 'MRF Listing'],
            ['name' => 'Closed MRF Listing', 'group_name' => 'MRF Listing'],

            //Campus Hiring
            ['name' => 'Campus MRF', 'group_name' => 'Campus Hiring'],
            ['name' => 'Campus Applications', 'group_name' => 'Campus Hiring'],
            ['name' => 'Campus Screening Tracker', 'group_name' => 'Campus Hiring'],
            ['name' => 'Campus Hiring Tracker', 'group_name' => 'Campus Hiring'],
            ['name' => 'Campus Hiring Costing', 'group_name' => 'Campus Hiring'],

            //Trainee Hiring
            ['name' => 'Trainee MRF', 'group_name' => 'Trainee Hiring'],
            ['name' => 'Trainee Applications', 'group_name' => 'Trainee Hiring'],
            ['name' => 'Trainee Tracker', 'group_name' => 'Trainee Hiring'],
            ['name' => 'Active Trainee', 'group_name' => 'Trainee Hiring'],
            ['name' => 'Old Trainee', 'group_name' => 'Trainee Hiring'],

            //Application Management
            ['name' => 'Application Pool', 'group_name' => 'Application Management'],
            ['name' => 'Job Response', 'group_name' => 'Application Management'],

            //Tracker
            ['name' => 'Screening Tracker', 'group_name' => 'Tracker'],
            ['name' => 'Interview Tracker', 'group_name' => 'Tracker'],

            //Onboarding
            ['name' => 'Job Offers', 'group_name' => 'Onboarding'],
            ['name' => 'Onboarding Tracker', 'group_name' => 'Onboarding'],

            //Employee
            ['name'=>'My Team','group_name'=>'Employee'],
            ['name'=>'Interview Schedule','group_name'=>'Employee'],
            ['name'=>'Pending Screening','group_name'=>'Employee'],
            ['name'=>'Pending MRF Approval','group_name'=>'Employee'],
            ['name'=>'MRF Creation','group_name'=>'Employee'],

        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], ['group_name' => $permission['group_name']]);
        }
    }
}
