<?php


use App\Http\Controllers\CaseStudyAnswerController;
use App\Http\Controllers\CaseStudyQuestionController;
use App\Http\Controllers\CoreAPIController;
use App\Http\Controllers\Master\TestController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\JobController;
use App\Http\Controllers\Hod\HodController;
use App\Http\Controllers\Hod\MrfController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Hod\MyTeamController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\GradeController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CountryController;
use App\Http\Controllers\Common\CampusController;
use App\Http\Controllers\Common\CommonController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\EmployeeController;
use App\Http\Controllers\Common\TrackerController;
use App\Http\Controllers\Common\TraineeController;
use App\Http\Controllers\Admin\EducationController;
use App\Http\Controllers\Admin\InstituteController;
use App\Http\Controllers\Common\OfferLtrController;
use App\Http\Controllers\Admin\DepartmentController;
use App\Http\Controllers\Admin\EduSpecialController;
use App\Http\Controllers\Admin\DesignationController;
use App\Http\Controllers\Admin\HeadquarterController;
use App\Http\Controllers\Admin\ResumeSourcController;
use App\Http\Controllers\Common\ManualEntryController;
use App\Http\Controllers\Admin\CommunicationController;
use App\Http\Controllers\CKEditorController;
use App\Http\Controllers\Recruiter\RecruiterController;
use App\Http\Controllers\Common\AboutCandidateController;
use App\Http\Controllers\Common\JobApplicationController;
use App\Http\Controllers\Common\PositionCodeController;
use App\Http\Controllers\DepartmentVertical;
use App\Http\Controllers\ElgController;
use App\Http\Controllers\ImportController;
use App\Http\Controllers\JobPostAPI;
use App\Http\Controllers\ProcessToEss;
use App\Http\Controllers\Recruiter\MrfAllocatedController;
use App\Http\Controllers\Report\CandidateVsJobController;
use App\Http\Controllers\Report\Reports;
use App\Http\Controllers\TestModule\QuestionBankController;
use App\Http\Controllers\TestModule\SubjectMasterController;
use App\Http\Controllers\TestModule\TestCandidateController;
use App\Http\Controllers\TestModule\ExamController;
use App\Http\Controllers\Admin\MinimumWageController;
use App\Http\Controllers\Admin\RegionController;
use App\Http\Controllers\S3FileUploadController;

Route::get('/', function () {
    return view('auth.login');
});

Route::middleware(['middleware' => 'PreventBackHistory'])->group(function () {
    Auth::routes();
});


Route::group(['prefix' => 'jobportal'], function () {
    Route::view('jobs', 'jobportal.jobs');
    Route::get('job_apply_form', [JobController::class, 'job_apply_form'])->name('job_apply_form');
    Route::get('jobapply', [JobController::class, 'job_apply_form_manual']);
    Route::post('job_apply', [JobController::class, 'job_apply'])->name('job_apply');
    Route::post('job_apply_manual', [JobController::class, 'job_apply_manual'])->name('job_apply_manual');
    Route::get('campus_apply_form', [JobController::class, 'campus_apply_form'])->name('campus_apply_form');
    Route::post('campus_apply', [JobController::class, 'campus_apply'])->name('campus_apply');

    Route::get('apply_form', [JobController::class, 'apply_form'])->name('apply_form');
    Route::post('apply_without_post', [JobController::class, 'apply_without_post'])->name('apply_without_post');

    Route::get('trainee_apply_form', [JobController::class, 'trainee_apply_form'])->name('trainee_apply_form');
    Route::post('trainee_apply', [JobController::class, 'trainee_apply'])->name('trainee_apply');


    Route::get('verification', [JobController::class, 'verification'])->name('verification');
    Route::post('otpverify', [JobController::class, 'otpverify'])->name('otpverify');
    Route::get('confirmation', [JobController::class, 'confirmation'])->name('confirmation');
    Route::get('campus_placement_registration', [JobController::class, 'campus_placement_registration'])->name('campus_placement_registration');
    Route::get('firo_b', [JobController::class, 'firo_b'])->name('firo_b');
    Route::get('firo_b_instruction', [JobController::class, 'firo_b_instruction'])->name('firo_b_instruction');
    Route::get('firob_test', [JobController::class, 'firob_test'])->name('firob_test');
    Route::post('firob_save_answer', [JobController::class, 'firob_save_answer'])->name('firob_save_answer');
    Route::post('firob_submit_exam', [JobController::class, 'firob_submit_exam'])->name('firob_submit_exam');
    Route::get('firob_result', [JobController::class, 'firob_result'])->name('firob_result');
    Route::get('firob_result_summery', [JobController::class, 'firob_result_summery'])->name('firob_result_summery');
    Route::post('deleteFirob', [JobController::class, 'deleteFirob'])->name('deleteFirob');
});

Route::post('setTheme', [CommonController::class, 'setTheme'])->name('setTheme');
Route::get('getDepartment', [CommonController::class, 'getDepartment'])->name('getDepartment');
Route::get('getDesignation', [CommonController::class, 'getDesignation'])->name('getDesignation');
Route::get('getGrade', [CommonController::class, 'getGrade'])->name('getGrade');
Route::get('getReportingManager', [CommonController::class, 'getReportingManager'])->name('getReportingManager');
Route::get('getResignedEmployee', [CommonController::class, 'getResignedEmployee'])->name('getResignedEmployee');
Route::get('getResignedEmpDetail', [CommonController::class, 'getResignedEmpDetail'])->name('getResignedEmpDetail');
Route::get('getState', [CommonController::class, 'getState'])->name('getState');
Route::get('getState1', [CommonController::class, 'getState1'])->name('getState1');
Route::get('getDistrict', [CommonController::class, 'getDistrict'])->name('getDistrict');
Route::get('getHq', [CommonController::class, 'getHq'])->name('getHq');
Route::get('getEducation', [CommonController::class, 'getEducation'])->name('getEducation');
Route::get('getCollege', [CommonController::class, 'getCollege'])->name('getCollege');
Route::get('getCollege1', [CommonController::class, 'getCollege1'])->name('getCollege1');
Route::get('getSpecialization', [CommonController::class, 'getSpecialization'])->name('getSpecialization');
Route::get('getAllDistrict', [CommonController::class, 'getAllDistrict'])->name('getAllDistrict');
Route::get('getAllSP', [CommonController::class, 'getAllSP'])->name('getAllSP');
Route::post('getMRFDetails', [CommonController::class, 'getMRFDetails'])->name('getMRFDetails');
Route::post('updateMRF', [CommonController::class, 'updateMRF'])->name('updateMRF');
Route::post('deleteMRF', [CommonController::class, 'deleteMRF'])->name('deleteMRF');
Route::post('notificationMarkRead', [CommonController::class, 'notificationMarkRead'])->name('notificationMarkRead');
Route::post('markAllRead', [CommonController::class, 'markAllRead'])->name('markAllRead');
Route::get('getMRFByDepartment', [CommonController::class, 'getMRFByDepartment'])->name('getMRFByDepartment');
Route::post('sendMailToCandidate', [CommonController::class, 'sendMailToCandidate'])->name('sendMailToCandidate');
Route::get('getEmpByCompany', [CommonController::class, 'getEmpByCompany'])->name('getEmpByCompany');
Route::get('change-password', [CommonController::class, 'changePassword'])->name('change-password');
Route::get('one_time_pwd_change', [CommonController::class, 'one_time_pwd_change'])->name('one_time_pwd_change');
Route::post('passwordChange', [CommonController::class, 'passwordChange'])->name('passwordChange');
Route::get('upcoming_interview', [CommonController::class, 'upcoming_interview'])->name('upcoming_interview');
Route::post('getInstituteListByJobPost', [CommonController::class, 'getInstituteListByJobPost'])->name('getInstituteListByJobPost');
Route::get('candidate_detail', [AboutCandidateController::class, 'candidate_detail'])->name('candidate_detail');
Route::get('getCityVillageByState', [CommonController::class, 'getCityVillageByState'])->name('getCityVillageByState');
Route::get('getBUByVertical', [CommonController::class, 'getBUByVertical'])->name('getBUByVertical');
Route::get('getZoneByBU', [CommonController::class, 'getZoneByBU'])->name('getZoneByBU');
Route::get('getRegionByZone', [CommonController::class, 'getRegionByZone'])->name('getRegionByZone');
Route::get('getTerritoryByRegion', [CommonController::class, 'getTerritoryByRegion'])->name('getTerritoryByRegion');

Route::get('interview_form_detail', [AboutCandidateController::class, 'interview_form_detail'])->name('interview_form_detail');
Route::get('joining_form_print', [AboutCandidateController::class, 'joining_form_print'])->name('joining_form_print');

Route::post('Candidate_ProfileData', [AboutCandidateController::class, 'Candidate_ProfileData'])->name('Candidate_ProfileData');
Route::post('Candidate_ProfileData_Save', [AboutCandidateController::class, 'Candidate_ProfileData_Save'])->name('Candidate_ProfileData_Save');

Route::post('Candidate_PersonalData', [AboutCandidateController::class, 'Candidate_PersonalData'])->name('Candidate_PersonalData');
Route::post('Candidate_PersonalData_Save', [AboutCandidateController::class, 'Candidate_PersonalData_Save'])->name('Candidate_PersonalData_Save');

Route::post('Candidate_EmergencyContact', [AboutCandidateController::class, 'Candidate_EmergencyContact'])->name('Candidate_EmergencyContact');
Route::post('Candidate_EmergencyContact_Save', [AboutCandidateController::class, 'Candidate_EmergencyContact_Save'])->name('Candidate_EmergencyContact_Save');

Route::post('Candidate_BankInfo', [AboutCandidateController::class, 'Candidate_BankInfo'])->name('Candidate_BankInfo');
Route::post('Candidate_BankInfo_Save', [AboutCandidateController::class, 'Candidate_BankInfo_Save'])->name('Candidate_BankInfo_Save');

Route::post('Candidate_Family', [AboutCandidateController::class, 'Candidate_Family'])->name('Candidate_Family');
Route::post('Candidate_Family_Save', [AboutCandidateController::class, 'Candidate_Family_Save'])->name('Candidate_Family_Save');

Route::post('Candidate_CurrentAddress', [AboutCandidateController::class, 'Candidate_CurrentAddress'])->name('Candidate_CurrentAddress');
Route::post('Candidate_CurrentAddress_Save', [AboutCandidateController::class, 'Candidate_CurrentAddress_Save'])->name('Candidate_CurrentAddress_Save');

Route::post('Candidate_PermanentAddress', [AboutCandidateController::class, 'Candidate_PermanentAddress'])->name('Candidate_PermanentAddress');
Route::post('Candidate_PermanentAddress_Save', [AboutCandidateController::class, 'Candidate_PermanentAddress_Save'])->name('Candidate_PermanentAddress_Save');

Route::post('Candidate_Education', [AboutCandidateController::class, 'Candidate_Education'])->name('Candidate_Education');
Route::post('Candidate_Education_Save', [AboutCandidateController::class, 'Candidate_Education_Save'])->name('Candidate_Education_Save');

Route::post('Candidate_CurrentEmployement_Save', [AboutCandidateController::class, 'Candidate_CurrentEmployement_Save'])->name('Candidate_CurrentEmployement_Save');

Route::post('Candidate_CurrentSalary_Save', [AboutCandidateController::class, 'Candidate_CurrentSalary_Save'])->name('Candidate_CurrentSalary_Save');

Route::post('Candidate_Experience', [AboutCandidateController::class, 'Candidate_Experience'])->name('Candidate_Experience');
Route::post('Candidate_Experience_Save', [AboutCandidateController::class, 'Candidate_Experience_Save'])->name('Candidate_Experience_Save');

Route::post('Candidate_Training', [AboutCandidateController::class, 'Candidate_Training'])->name('Candidate_Training');
Route::post('Candidate_Training_Save', [AboutCandidateController::class, 'Candidate_Training_Save'])->name('Candidate_Training_Save');

Route::post('Candidate_PreOrgRef', [AboutCandidateController::class, 'Candidate_PreOrgRef'])->name('Candidate_PreOrgRef');
Route::post('Candidate_PreOrgRef_Save', [AboutCandidateController::class, 'Candidate_PreOrgRef_Save'])->name('Candidate_PreOrgRef_Save');

Route::post('Candidate_VnrRef', [AboutCandidateController::class, 'Candidate_VnrRef'])->name('Candidate_VnrRef');
Route::post('Candidate_VnrRef_Save', [AboutCandidateController::class, 'Candidate_VnrRef_Save'])->name('Candidate_VnrRef_Save');


Route::post('Candidate_VnrRef_Business', [AboutCandidateController::class, 'Candidate_VnrRef_Business'])->name('Candidate_VnrRef_Business');
Route::post('Candidate_VnrRef_Business_Save', [AboutCandidateController::class, 'Candidate_VnrRef_Business_Save'])->name('Candidate_VnrRef_Business_Save');

Route::post('Candidate_Other_Seed_Relation', [AboutCandidateController::class, 'Candidate_Other_Seed_Relation'])->name('Candidate_Other_Seed_Relation');
Route::post('Candidate_Other_Seed_Relation_Save', [AboutCandidateController::class, 'Candidate_Other_Seed_Relation_Save'])->name('Candidate_Other_Seed_Relation_Save');

Route::post('Candidate_Strength', [AboutCandidateController::class, 'Candidate_Strength'])->name('Candidate_Strength');
Route::post('Candidate_Strength_Save', [AboutCandidateController::class, 'Candidate_Strength_Save'])->name('Candidate_Strength_Save');

Route::post('Candidate_Language', [AboutCandidateController::class, 'Candidate_Language'])->name('Candidate_Language');
Route::post('Candidate_Language_Save', [AboutCandidateController::class, 'Candidate_Language_Save'])->name('Candidate_Language_Save');

Route::get('appointment_letter', [AboutCandidateController::class, 'appointment_letter'])->name('appointment_letter');
Route::get('appointment_ltr_print', [AboutCandidateController::class, 'appointment_ltr_print'])->name('appointment_ltr_print');

Route::post('appointmentGen', [AboutCandidateController::class, 'appointmentGen'])->name('appointmentGen');
Route::post('appointment_letter_generate', [AboutCandidateController::class, 'appointment_letter_generate'])->name('appointment_letter_generate');

Route::get('service_agreement', [AboutCandidateController::class, 'service_agreement'])->name('service_agreement');
Route::get('service_agreement_print', [AboutCandidateController::class, 'service_agreement_print'])->name('service_agreement_print');
Route::get('service_agreement_print_e_first', [AboutCandidateController::class, 'service_agreement_print_e_first'])->name('service_agreement_print_e_first');
Route::get('service_agreement_print_old_stamp', [AboutCandidateController::class, 'service_agreement_print_old_stamp'])->name('service_agreement_print_old_stamp');
Route::get('service_agreement_print_old_stamp_first', [AboutCandidateController::class, 'service_agreement_print_old_stamp_first'])->name('service_agreement_print_old_stamp_first');
Route::post('service_agreement_generate', [AboutCandidateController::class, 'service_agreement_generate'])->name('service_agreement_generate');

Route::get('service_bond', [AboutCandidateController::class, 'service_bond'])->name('service_bond');
Route::get('service_bond_print', [AboutCandidateController::class, 'service_bond_print'])->name('service_bond_print');
Route::get('service_bond_print_e_first', [AboutCandidateController::class, 'service_bond_print_e_first'])->name('service_bond_print_e_first');
Route::get('service_bond_print_old_stamp', [AboutCandidateController::class, 'service_bond_print_old_stamp'])->name('service_bond_print_old_stamp');
Route::post('service_bond_generate', [AboutCandidateController::class, 'service_bond_generate'])->name('service_bond_generate');

Route::get('conf_agreement', [AboutCandidateController::class, 'conf_agreement'])->name('conf_agreement');
Route::get('conf_agreement_print', [AboutCandidateController::class, 'conf_agreement_print'])->name('conf_agreement_print');
Route::get('conf_agreement_print_e_first', [AboutCandidateController::class, 'conf_agreement_print_e_first'])->name('conf_agreement_print_e_first');
Route::get('conf_agreement_print_old_stamp', [AboutCandidateController::class, 'conf_agreement_print_old_stamp'])->name('conf_agreement_print_old_stamp');
Route::post('conf_agreement_generate', [AboutCandidateController::class, 'conf_agreement_generate'])->name('conf_agreement_generate');

Route::post('send_for_ref_chk', [AboutCandidateController::class, 'send_for_ref_chk'])->name('send_for_ref_chk');
Route::get('reference_check', [AboutCandidateController::class, 'reference_check'])->name('reference_check');
Route::get('view_reference_check', [AboutCandidateController::class, 'view_reference_check'])->name('view_reference_check');
Route::post('reference_chk_response', [AboutCandidateController::class, 'reference_chk_response'])->name('reference_chk_response');
Route::post('VerificationSave', [AboutCandidateController::class, 'VerificationSave'])->name('VerificationSave');
Route::post('TwoWheelRCSave', [AboutCandidateController::class, 'TwoWheelRCSave'])->name('TwoWheelRCSave');
Route::post('FourWheelRCSave', [AboutCandidateController::class, 'FourWheelRCSave'])->name('FourWheelRCSave');
Route::post('JoinedSave', [AboutCandidateController::class, 'JoinedSave'])->name('JoinedSave');
Route::post('AssignPositionCode', [AboutCandidateController::class, 'AssignPositionCode'])->name('AssignPositionCode');
Route::post('changeOffLtrDate', [AboutCandidateController::class, 'changeOffLtrDate'])->name('changeOffLtrDate');
Route::post('changeA_Date', [AboutCandidateController::class, 'changeA_Date'])->name('changeA_Date');
Route::post('changeAgr_Date', [AboutCandidateController::class, 'changeAgr_Date'])->name('changeAgr_Date');
Route::post('changeB_Date', [AboutCandidateController::class, 'changeB_Date'])->name('changeB_Date');
Route::post('changeConf_Date', [AboutCandidateController::class, 'changeConf_Date'])->name('changeConf_Date');
Route::post('CandidateClosure', [AboutCandidateController::class, 'CandidateClosure'])->name('CandidateClosure');
Route::post('get_candidate_suitablity', [AboutCandidateController::class, 'get_candidate_suitablity'])->name('get_candidate_suitablity');


Route::get('process_to_ess_form', [AboutCandidateController::class, 'process_to_ess_form'])->name('process_to_ess_form');
Route::post('open_joining_form', [AboutCandidateController::class, 'open_joining_form'])->name('open_joining_form');
Route::post('disable_offer_letter', [AboutCandidateController::class, 'disable_offer_letter'])->name('disable_offer_letter');
Route::post('enable_offer_letter', [AboutCandidateController::class, 'enable_offer_letter'])->name('enable_offer_letter');
Route::get('get_mw_by_designation', [AboutCandidateController::class, 'get_mw_by_designation'])->name('get_mw_by_designation');
Route::post('ol_action_on_behalf_candidate', [AboutCandidateController::class, 'ol_action_on_behalf_candidate'])->name('ol_action_on_behalf_candidate');
Route::post('processDataToEss', [ProcessToEss::class, 'processDataToEss'])->name('processDataToEss');
Route::get('ImportFromOld', [ProcessToEss::class, 'ImportFromOld'])->name('ImportFromOld');


Route::get('job_response', [JobApplicationController::class, 'job_response'])->name('job_response');
Route::get('job_applications', [JobApplicationController::class, 'job_applications'])->name('job_applications');
Route::get('job_applications_not_viewed', [JobApplicationController::class, 'job_applications_not_viewed'])->name('job_applications_not_viewed');
Route::get('get_duplicate_record', [JobApplicationController::class, 'get_duplicate_record'])->name('get_duplicate_record');
Route::post('delete_duplicate_record', [JobApplicationController::class, 'delete_duplicate_record'])->name('delete_duplicate_record');
Route::post('getJobResponseSummary', [JobApplicationController::class, 'getJobResponseSummary'])->name('getJobResponseSummary');
Route::post('getCandidates', [JobApplicationController::class, 'getCandidates'])->name('getCandidates');
Route::post('update_hrscreening', [JobApplicationController::class, 'update_hrscreening'])->name('update_hrscreening');
Route::post('SendForTechScreening', [JobApplicationController::class, 'SendForTechScreening'])->name('SendForTechScreening');
Route::post('MapCandidateToJob', [JobApplicationController::class, 'MapCandidateToJob'])->name('MapCandidateToJob');
Route::post('MoveCandidate', [JobApplicationController::class, 'MoveCandidate'])->name('MoveCandidate');
Route::post('BlacklistCandidate', [JobApplicationController::class, 'BlacklistCandidate'])->name('BlacklistCandidate');
Route::post('UnBlockCandidate', [JobApplicationController::class, 'UnBlockCandidate'])->name('UnBlockCandidate');
Route::get('job_application_manual_entry_form', [JobApplicationController::class, 'job_application_manual_entry_form'])->name('job_application_manual_entry_form');
Route::post('job_application_manual', [JobApplicationController::class, 'job_application_manual'])->name('job_application_manual');
Route::get('getManualEntryCandidate', [JobApplicationController::class, 'getManualEntryCandidate'])->name('getManualEntryCandidate');
Route::post('getJobResponseCandidateByJPId', [JobApplicationController::class, 'getJobResponseCandidateByJPId'])->name('getJobResponseCandidateByJPId');
Route::post('cropImage', [JobApplicationController::class, 'cropImage'])->name('cropImage');
Route::get('candidate-interview-form', [JobApplicationController::class, 'candidate_interview_form'])->name('candidate-interview-form');
Route::post('SaveInterviewConfirmation', [JobApplicationController::class, 'SaveInterviewConfirmation'])->name('SaveInterviewConfirmation');
Route::post('candidate_interview', [JobApplicationController::class, 'candidate_interview'])->name('candidate_interview');
Route::get('candidate-joining-form', [JobApplicationController::class, 'CandidateJoiningForm'])->name('candidate-joining-form');
Route::get('candidate-vehicle-form', [JobApplicationController::class, 'CandidateVehicleForm'])->name('candidate-vehicle-form');
Route::post('SaveVehicleForm', [JobApplicationController::class, 'SaveVehicleForm'])->name('SaveVehicleForm');
Route::get('onboarding', [JobApplicationController::class, 'onboarding'])->name('onboarding');
Route::post('SavePersonalInfo', [JobApplicationController::class, 'SavePersonalInfo'])->name('SavePersonalInfo');
Route::post('SaveContact', [JobApplicationController::class, 'SaveContact'])->name('SaveContact');
Route::post('SaveEducation', [JobApplicationController::class, 'SaveEducation'])->name('SaveEducation');
Route::post('SaveFamily', [JobApplicationController::class, 'SaveFamily'])->name('SaveFamily');
Route::post('SaveExperience', [JobApplicationController::class, 'SaveExperience'])->name('SaveExperience');
Route::post('SaveAbout', [JobApplicationController::class, 'SaveAbout'])->name('SaveAbout');
Route::post('SaveOther', [JobApplicationController::class, 'SaveOther'])->name('SaveOther');
Route::post('OfferLtrFileUpload', [JobApplicationController::class, 'OfferLtrFileUpload'])->name('OfferLtrFileUpload');
Route::post('RelievingLtrFileUpload', [JobApplicationController::class, 'RelievingLtrFileUpload'])->name('RelievingLtrFileUpload');
Route::post('SalarySlipFileUpload', [JobApplicationController::class, 'SalarySlipFileUpload'])->name('SalarySlipFileUpload');
Route::post('AppraisalLtrFileUpload', [JobApplicationController::class, 'AppraisalLtrFileUpload'])->name('AppraisalLtrFileUpload');
Route::post('VaccinationCertFileUpload', [JobApplicationController::class, 'VaccinationCertFileUpload'])->name('VaccinationCertFileUpload');
Route::post('PFeNominationFileUpload', [JobApplicationController::class, 'PFeNominationFileUpload'])->name('PFeNominationFileUpload');
Route::post('ResignationFileUpload', [JobApplicationController::class, 'ResignationFileUpload'])->name('ResignationFileUpload');
Route::post('ResignationAcceptFileUpload', [JobApplicationController::class, 'ResignationAcceptFileUpload'])->name('ResignationAcceptFileUpload');
Route::post('Epfo_JointFileUpload', [JobApplicationController::class, 'Epfo_JointFileUpload'])->name('Epfo_JointFileUpload');
Route::post('Form16FileUpload', [JobApplicationController::class, 'Form16FileUpload'])->name('Form16FileUpload');
Route::post('CheckDocumentUpload', [JobApplicationController::class, 'CheckDocumentUpload'])->name('CheckDocumentUpload');
Route::post('FinalSubmitInterviewApplicationForm', [JobApplicationController::class, 'FinalSubmitInterviewApplicationForm'])->name('FinalSubmitInterviewApplicationForm');

Route::post('AadhaarUpload', [JobApplicationController::class, 'AadhaarUpload'])->name('AadhaarUpload');
Route::post('PanCardUpload', [JobApplicationController::class, 'PanCardUpload'])->name('PanCardUpload');
Route::post('PassportUpload', [JobApplicationController::class, 'PassportUpload'])->name('PassportUpload');
Route::post('DlUpload', [JobApplicationController::class, 'DlUpload'])->name('DlUpload');
Route::post('PF_Form2Upload', [JobApplicationController::class, 'PF_Form2Upload'])->name('PF_Form2Upload');
Route::post('PF_Form11Upload', [JobApplicationController::class, 'PF_Form11Upload'])->name('PF_Form11Upload');
Route::post('GratuityUpload', [JobApplicationController::class, 'GratuityUpload'])->name('GratuityUpload');
Route::post('ESICUpload', [JobApplicationController::class, 'ESICUpload'])->name('ESICUpload');
Route::post('FamilyUpload', [JobApplicationController::class, 'FamilyUpload'])->name('FamilyUpload');
Route::post('HealthUpload', [JobApplicationController::class, 'HealthUpload'])->name('HealthUpload');
Route::post('EthicalUpload', [JobApplicationController::class, 'EthicalUpload'])->name('EthicalUpload');
Route::post('BloodGroupUpload', [JobApplicationController::class, 'BloodGroupUpload'])->name('BloodGroupUpload');
Route::post('Invst_DeclUpload', [JobApplicationController::class, 'Invst_DeclUpload'])->name('Invst_DeclUpload');
Route::post('BankUpload', [JobApplicationController::class, 'BankUpload'])->name('BankUpload');
Route::post('TestPaperUpload', [JobApplicationController::class, 'TestPaperUpload'])->name('TestPaperUpload');
Route::post('IntervAssessmentUpload', [JobApplicationController::class, 'IntervAssessmentUpload'])->name('IntervAssessmentUpload');
Route::post('CheckDocumentUpload_JoiningForm', [JobApplicationController::class, 'CheckDocumentUpload_JoiningForm'])->name('CheckDocumentUpload_JoiningForm');
Route::post('JoiningFormSubmit', [JobApplicationController::class, 'JoiningFormSubmit'])->name('JoiningFormSubmit');
Route::post('/import-cv', [JobApplicationController::class, 'import'])->name('import.cv');

Route::get('TechnicalScreening', [TrackerController::class, 'TechnicalScreening'])->name('TechnicalScreening');
Route::post('getTechnicalSceeningCandidate', [TrackerController::class, 'getTechnicalSceeningCandidate'])->name('getTechnicalSceeningCandidate');
Route::post('getScreenDetail', [TrackerController::class, 'getScreenDetail'])->name('getScreenDetail');
Route::post('getInterviewDetail', [TrackerController::class, 'getInterviewDetail'])->name('getInterviewDetail');
Route::post('CandidateTechnicalScreening', [TrackerController::class, 'CandidateTechnicalScreening'])->name('CandidateTechnicalScreening');
Route::post('getInterviewTrackerCandidate', [TrackerController::class, 'getInterviewTrackerCandidate'])->name('getInterviewTrackerCandidate');
Route::get('interview_tracker', [TrackerController::class, 'interview_tracker'])->name('interview_tracker');
Route::post('first_round_interview', [TrackerController::class, 'first_round_interview'])->name('first_round_interview');
Route::post('second_round_interview', [TrackerController::class, 'second_round_interview'])->name('second_round_interview');
Route::post('select_cmp_dpt_for_candidate', [TrackerController::class, 'select_cmp_dpt_for_candidate'])->name('select_cmp_dpt_for_candidate');
Route::post('update_interview_cost', [TrackerController::class, 'update_interview_cost'])->name('update_interview_cost');
Route::post('get_interview_cost', [TrackerController::class, 'get_interview_cost'])->name('get_interview_cost');


Route::get('offer_letter', [OfferLtrController::class, 'offer_letter'])->name('offer_letter');
Route::post('update_offerletter_basic', [OfferLtrController::class, 'update_offerletter_basic'])->name('update_offerletter_basic');
Route::get('get_offerltr_basic_detail', [OfferLtrController::class, 'get_offerltr_basic_detail'])->name('get_offerltr_basic_detail');
Route::get('offer_letter_generate', [OfferLtrController::class, 'offer_letter_generate'])->name('offer_letter_generate');
Route::post('insert_ctc', [OfferLtrController::class, 'insert_ctc'])->name('insert_ctc');
Route::post('insert_ent', [OfferLtrController::class, 'insert_ent'])->name('insert_ent');
Route::post('offer_ltr_gen', [OfferLtrController::class, 'offer_ltr_gen'])->name('offer_ltr_gen');
Route::get('offer_ltr_print', [OfferLtrController::class, 'offer_ltr_print'])->name('offer_ltr_print');
Route::get('candidate-offer-letter', [OfferLtrController::class, 'candidate_offer_letter'])->name('candidate-offer-letter');
Route::get('offerLtrHistory', [OfferLtrController::class, 'offerLtrHistory'])->name('offerLtrHistory');
Route::get('offer_ltr_history', [OfferLtrController::class, 'offer_ltr_history'])->name('offer_ltr_history');
Route::get('getDetailForReview', [OfferLtrController::class, 'getDetailForReview'])->name('getDetailForReview');
Route::post('saveJoinDate', [OfferLtrController::class, 'saveJoinDate'])->name('saveJoinDate');
Route::post('SendOfferLtr', [OfferLtrController::class, 'SendOfferLtr'])->name('SendOfferLtr');
Route::post('SendJoiningForm', [OfferLtrController::class, 'SendJoiningForm'])->name('SendJoiningForm');
Route::post('SendVehicleForm', [JobApplicationController::class, 'SendVehicleForm'])->name('SendVehicleForm');
Route::post('OfferResponse', [OfferLtrController::class, 'OfferResponse'])->name('OfferResponse');
Route::get('offer-letter-response', [OfferLtrController::class, 'OfferLetterResponse'])->name('offer-letter-response');
Route::post('offerReopen', [OfferLtrController::class, 'offerReopen'])->name('offerReopen');
Route::post('send_for_review', [OfferLtrController::class, 'send_for_review'])->name('send_for_review');
Route::get('offer-letter-review', [OfferLtrController::class, 'offer_letter_review'])->name('offer-letter-review');
Route::get('viewReview', [OfferLtrController::class, 'viewReview'])->name('viewReview');
Route::post('ReviewResponse', [OfferLtrController::class, 'ReviewResponse'])->name('ReviewResponse');
Route::post('saveEmpCode', [OfferLtrController::class, 'saveEmpCode'])->name('saveEmpCode');
Route::get('get_designation_by_grade_department', [OfferLtrController::class, 'get_designation_by_grade_department'])->name('get_designation_by_grade_department');
Route::get('candidate_joining', [OfferLtrController::class, 'candidate_joining'])->name('candidate_joining');
Route::post('update_two_wheel', [OfferLtrController::class, 'update_two_wheel'])->name('update_two_wheel');
Route::post('update_four_wheel', [OfferLtrController::class, 'update_four_wheel'])->name('update_four_wheel');

Route::get('recruiter_mrf_entry', [ManualEntryController::class, 'recruiter_mrf_entry'])->name('recruiter_mrf_entry');
Route::post('get_all_manual_mrf_created_by_me', [ManualEntryController::class, 'get_all_manual_mrf_created_by_me'])->name('get_all_manual_mrf_created_by_me');
Route::get('mrf', [ManualEntryController::class, 'mrf'])->name('mrf');
Route::get('new_mrf_manual', [ManualEntryController::class, 'new_mrf_manual'])->name('new_mrf_manual');
Route::get('replacement_mrf_manual', [ManualEntryController::class, 'replacement_mrf_manual'])->name('replacement_mrf_manual');
Route::get('sip_mrf_manual', [ManualEntryController::class, 'sip_mrf_manual'])->name('sip_mrf_manual');
Route::get('campus_mrf_manual', [ManualEntryController::class, 'campus_mrf_manual'])->name('campus_mrf_manual');
Route::post('add_new_mrf_manual', [ManualEntryController::class, 'add_new_mrf_manual'])->name('add_new_mrf_manual');
Route::post('add_sip_mrf_manual', [ManualEntryController::class, 'add_sip_mrf_manual'])->name('add_sip_mrf_manual');
Route::post('add_campus_mrf_manual', [ManualEntryController::class, 'add_campus_mrf_manual'])->name('add_campus_mrf_manual');
Route::post('add_replacement_mrf_manual', [ManualEntryController::class, 'add_replacement_mrf_manual'])->name('add_replacement_mrf_manual');

Route::post('createJobPost_Campus', [CampusController::class, 'createJobPost_Campus'])->name('createJobPost_Campus');
Route::post('getAllCampusAllocatedMrf', [CampusController::class, 'getAllCampusAllocatedMrf'])->name('getAllCampusAllocatedMrf');
Route::get('campus_mrf_allocated', [CampusController::class, 'campus_mrf_allocated'])->name('campus_mrf_allocated');
Route::get('campus_applications', [CampusController::class, 'campus_applications'])->name('campus_applications');
Route::get('campus_screening_tracker', [CampusController::class, 'campus_screening_tracker'])->name('campus_screening_tracker');
Route::get('campus_hiring_tracker', [CampusController::class, 'campus_hiring_tracker'])->name('campus_hiring_tracker');
Route::post('getCampusSummary', [CampusController::class, 'getCampusSummary'])->name('getCampusSummary');
Route::post('getCampusHiringCandidates', [CampusController::class, 'getCampusHiringCandidates'])->name('getCampusHiringCandidates');
Route::post('getCampusScreeningCandidates', [CampusController::class, 'getCampusScreeningCandidates'])->name('getCampusScreeningCandidates');
Route::post('getPostTitle', [CampusController::class, 'getPostTitle'])->name('getPostTitle');
Route::post('getCandidateName', [CampusController::class, 'getCandidateName'])->name('getCandidateName');
Route::post('ChngGDResult', [CampusController::class, 'ChngGDResult'])->name('ChngGDResult');
Route::post('ChngScreenStatus', [CampusController::class, 'ChngScreenStatus'])->name('ChngScreenStatus');
Route::post('getCampusCandidates', [CampusController::class, 'getCampusCandidates'])->name('getCampusCandidates');
Route::post('SavePlacementDate', [CampusController::class, 'SavePlacementDate'])->name('SavePlacementDate');
Route::post('SaveTestScore', [CampusController::class, 'SaveTestScore'])->name('SaveTestScore');
Route::post('SendForScreening', [CampusController::class, 'SendForScreening'])->name('SendForScreening');
Route::post('SetAllCampusDate', [CampusController::class, 'SetAllCampusDate'])->name('SetAllCampusDate');
Route::post('SetAllCampusTechScrStatus', [CampusController::class, 'SetAllCampusTechScrStatus'])->name('SetAllCampusTechScrStatus');
Route::post('SaveFirstInterview_Campus', [CampusController::class, 'SaveFirstInterview_Campus'])->name('SaveFirstInterview_Campus');
Route::post('SaveSecondInterview_Campus', [CampusController::class, 'SaveSecondInterview_Campus'])->name('SaveSecondInterview_Campus');
Route::post('Save_Cmp_Dpt_Campus', [CampusController::class, 'Save_Cmp_Dpt_Campus'])->name('Save_Cmp_Dpt_Campus');
Route::get('campus_hiring_costing', [CampusController::class, 'campus_hiring_costing'])->name('campus_hiring_costing');
Route::post('getCampusCosting', [CampusController::class, 'getCampusCosting'])->name('getCampusCosting');
Route::post('updateCosting', [CampusController::class, 'updateCosting'])->name('updateCosting');
Route::post('getCostingDetail', [CampusController::class, 'getCostingDetail'])->name('getCostingDetail');
Route::post('deleteCampusCandidate', [CampusController::class, 'deleteCampusCandidate'])->name('deleteCampusCandidate');


Route::get('trainee_mrf_allocated', [TraineeController::class, 'trainee_mrf_allocated'])->name('trainee_mrf_allocated');
Route::post('getAllTraineeAllocatedMrf', [TraineeController::class, 'getAllTraineeAllocatedMrf'])->name('getAllTraineeAllocatedMrf');
Route::get('trainee_applications', [TraineeController::class, 'trainee_applications'])->name('trainee_applications');
Route::post('getTraineeSummary', [TraineeController::class, 'getTraineeSummary'])->name('getTraineeSummary');
Route::post('getTraieeCandidates', [TraineeController::class, 'getTraieeCandidates'])->name('getTraieeCandidates');
Route::post('SendTraineeForScreening', [TraineeController::class, 'SendTraineeForScreening'])->name('SendTraineeForScreening');
Route::get('trainee_screening_tracker', [TraineeController::class, 'trainee_screening_tracker'])->name('trainee_screening_tracker');
Route::post('getTraineeScreeningCandidates', [TraineeController::class, 'getTraineeScreeningCandidates'])->name('getTraineeScreeningCandidates');
Route::post('ChngTraineeScreenStatus', [TraineeController::class, 'ChngTraineeScreenStatus'])->name('ChngTraineeScreenStatus');
Route::post('getTraineeName', [TraineeController::class, 'getTraineeName'])->name('getTraineeName');
Route::post('SaveTraineeInterview', [TraineeController::class, 'SaveTraineeInterview'])->name('SaveTraineeInterview');
Route::get('active_trainee', [TraineeController::class, 'active_trainee'])->name('active_trainee');
Route::post('get_active_trainee', [TraineeController::class, 'get_active_trainee'])->name('get_active_trainee');
Route::get('old_trainee', [TraineeController::class, 'old_trainee'])->name('old_trainee');
Route::post('get_old_trainee', [TraineeController::class, 'get_old_trainee'])->name('get_old_trainee');
Route::post('getTraineeDetail', [TraineeController::class, 'getTraineeDetail'])->name('getTraineeDetail');
Route::post('save_trainee_detail', [TraineeController::class, 'save_trainee_detail'])->name('save_trainee_detail');
Route::post('get_expense_list', [TraineeController::class, 'get_expense_list'])->name('get_expense_list');
Route::post('add_expense', [TraineeController::class, 'add_expense'])->name('add_expense');
Route::post('map_trainee_to_job', [TraineeController::class, 'map_trainee_to_job'])->name('map_trainee_to_job');
Route::get('trainee_detail', [TraineeController::class, 'trainee_detail'])->name('trainee_detail');
Route::post('SetAllTraineeInterviewDetails', [TraineeController::class, 'SetAllTraineeInterviewDetails'])->name('SetAllTraineeInterviewDetails');
Route::post('deleteTraineeCandidate', [TraineeController::class, 'deleteTraineeCandidate'])->name('deleteTraineeCandidate');
Route::get('getInterviewDetailsTrainee', [TraineeController::class, 'getInterviewDetailsTrainee'])->name('getInterviewDetailsTrainee');
Route::post('SendFirobToTrainee', [TraineeController::class, 'SendFirobToTrainee'])->name('SendFirobToTrainee');
Route::post('import_trainee_expense', [TraineeController::class, 'import_trainee_expense'])->name('import_trainee_expense');
Route::post('deleteStipend', [TraineeController::class, 'deleteStipend'])->name('deleteStipend');
Route::group(['prefix' => 'admin', 'middleware' => ['isAdmin', 'auth', 'PreventBackHistory']], function () {
    Route::get('dashboard', [AdminController::class, 'index'])->name('admin.dashboard');
    Route::get('mrf', [AdminController::class, 'mrf'])->name('admin.mrf');
    Route::get('active_mrf', [AdminController::class, 'active_mrf'])->name('admin.active_mrf');
    Route::get('closedmrf', [AdminController::class, 'closedmrf'])->name('admin.closedmrf');
    Route::post('getNewMrf', [AdminController::class, 'getNewMrf'])->name('getNewMrf');
    Route::post('getActiveMrf', [AdminController::class, 'getActiveMrf'])->name('getActiveMrf');
    Route::post('getCloseMrf', [AdminController::class, 'getCloseMrf'])->name('getCloseMrf');
    Route::post('updateMRFStatus', [AdminController::class, 'updateMRFStatus'])->name('updateMRFStatus');
    Route::post('allocateMRF', [AdminController::class, 'allocateMRF'])->name('allocateMRF');
    Route::post('getTaskList', [AdminController::class, 'getTaskList'])->name('getTaskList');
    Route::post('getRecruiterName', [AdminController::class, 'getRecruiterName'])->name('getRecruiterName');

    Route::get('userlogs', [AdminController::class, 'userlogs'])->name('admin.userlogs');
    Route::post('getAllLogs', [AdminController::class, 'getAllLogs'])->name('getAllLogs');
    Route::post('changeMRFStatusOnBehalfReporting', [AdminController::class, 'changeMRFStatusOnBehalfReporting'])->name('changeMRFStatusOnBehalfReporting');
    Route::post('changeMRFStatusOnBehalfHod', [AdminController::class, 'changeMRFStatusOnBehalfHod'])->name('changeMRFStatusOnBehalfHod');
    Route::post('changeMRFStatusOnBehalfManagement', [AdminController::class, 'changeMRFStatusOnBehalfManagement'])->name('changeMRFStatusOnBehalfManagement');

    // ! ======================Master Company ========================//

    Route::view('company', 'admin.company');
    Route::get('getAllCompanyData', [CompanyController::class, 'getAllCompanyData'])->name('getAllCompanyData');
    // ! =====================================================================//

    // ?==========================Master Country ==============================//
    Route::view('country', 'admin.country');
    Route::get('getAllCountryData', [CountryController::class, 'getAllCountryData'])->name('getAllCountryData');
    // ?=========================================================================================//

    // ?==========================Master State ==============================//
    Route::get('state', [StateController::class, 'state'])->name('admin.state');
    Route::post('addState', [StateController::class, 'addState'])->name('addState');
    Route::get('getAllStateData', [StateController::class, 'getAllStateData'])->name('getAllStateData');
    Route::post('getStateDetails', [StateController::class, 'getStateDetails'])->name('getStateDetails');
    Route::post('editState', [StateController::class, 'editState'])->name('editState');
    Route::post('deleteState', [StateController::class, 'deleteState'])->name('deleteState');
    Route::post('syncState', [StateController::class, 'syncState'])->name('syncState');
    // ?=========================================================================================//

    // ?==========================Master State (General Purpose)==============================//
    Route::get('gen_states', [StateController::class, 'gen_states'])->name('admin.gen_states');
    Route::get('getAllStateData_General', [StateController::class, 'getAllStateData_General'])->name('getAllStateData_General');
    Route::post('addState_general', [StateController::class, 'addState_general'])->name('addState_general');
    Route::post('editState_general', [StateController::class, 'editState_general'])->name('editState_general');
    Route::post('getStateDetails_general', [StateController::class, 'getStateDetails_general'])->name('getStateDetails_general');
    Route::post('deleteState_general', [StateController::class, 'deleteState_general'])->name('deleteState_general');


    // ?=========================================================================================//

    //*====================================Master Employee ==================================//
    Route::get('employee', [EmployeeController::class, 'employee'])->name('admin.employee');
    Route::get('getAllEmployeeData', [EmployeeController::class, 'getAllEmployeeData'])->name('getAllEmployeeData');
    Route::post('syncEmployee', [EmployeeController::class, 'syncEmployee'])->name('syncEmployee');
    //*======================================================================================//

    //*====================================Master Headquarter ==================================//
    Route::get('headquarter', [HeadquarterController::class, 'headquarter'])->name('admin.headquarter');
    Route::get('getAllHeadquarter', [HeadquarterController::class, 'getAllHeadquarter'])->name('getAllHeadquarter');
    Route::post('syncHeadquarter', [HeadquarterController::class, 'syncHeadquarter'])->name('syncHeadquarter');
    //*==========================================================================================//

    //*====================================Master Department ==================================//
    Route::get('department', [DepartmentController::class, 'department'])->name('admin.department');
    Route::get('getAllDepartment', [DepartmentController::class, 'getAllDepartment'])->name('getAllDepartment');


    Route::post('getSubDepartmentByDepartment', [DepartmentController::class, 'getSubDepartmentByDepartment'])->name('getSubDepartmentByDepartment');
    //*========================================================================================//

    //*====================================Master Designation ==================================//
    // Route::get('designation', [DesignationController::class, 'designation'])->name('admin.designation');
    Route::view('designation', 'admin.designation');
    Route::get('getAllDesignation', [DesignationController::class, 'getAllDesignation'])->name('getAllDesignation');
    Route::post('syncDesignation', [DesignationController::class, 'syncDesignation'])->name('syncDesignation');
    //*=================================================================================================//

    //*====================================Master Grade ==================================//
    Route::get('grade', [GradeController::class, 'grade'])->name('admin.grade');
    Route::get('getAllGrade', [GradeController::class, 'getAllGrade'])->name('getAllGrade');
    Route::post('syncGrade', [GradeController::class, 'syncGrade'])->name('syncGrade');
    //*=================================================================================================//

    // ?==========================Master District ==============================//
    Route::get('district', [DistrictController::class, 'district'])->name('admin.district');
    Route::post('addDistrict', [DistrictController::class, 'addDistrict'])->name('addDistrict');
    Route::get('getDistrictList', [DistrictController::class, 'getDistrictList'])->name('getDistrictList');
    Route::post('getDistrictDetails', [DistrictController::class, 'getDistrictDetails'])->name('getDistrictDetails');
    Route::post('editDistrict', [DistrictController::class, 'editDistrict'])->name('editDistrict');
    Route::post('deleteDistrict', [DistrictController::class, 'deleteDistrict'])->name('deleteDistrict');
    // ?=========================================================================================//

    // ?==========================Master Education ==============================//
    Route::get('education', [EducationController::class, 'education'])->name('admin.education');
    Route::post('addEducation', [EducationController::class, 'addEducation'])->name('addEducation');
    Route::get('getAllEducation', [EducationController::class, 'getAllEducation'])->name('getAllEducation');
    Route::post('getEducationDetails', [EducationController::class, 'getEducationDetails'])->name('getEducationDetails');
    Route::post('editEducation', [EducationController::class, 'editEducation'])->name('editEducation');
    Route::post('deleteEducation', [EducationController::class, 'deleteEducation'])->name('deleteEducation');
    // ?=========================================================================================//

    //** ======================= Education Specialization ===================== */
    Route::get('eduspecialization', [EduSpecialController::class, 'eduspecialization'])->name('admin.eduspecialization');
    Route::post('addEduSpe', [EduSpecialController::class, 'addEduSpe'])->name('addEduSpe');
    Route::get('getAllEduSpe', [EduSpecialController::class, 'getAllEduSpe'])->name('getAllEduSpe');
    Route::post('getEduSpeDetails', [EduSpecialController::class, 'getEduSpeDetails'])->name('getEduSpeDetails');
    Route::post('editEduSpe', [EduSpecialController::class, 'editEduSpe'])->name('editEduSpe');
    Route::post('deleteEduSpe', [EduSpecialController::class, 'deleteEduSpe'])->name('deleteEduSpe');
    //**====================================================================== */

    //? ======================= Education Institute ===================== */
    Route::get('institute', [InstituteController::class, 'institute'])->name('admin.institute');
    Route::post('addInstitute', [InstituteController::class, 'addInstitute'])->name('addInstitute');
    Route::get('getAllInstitute', [InstituteController::class, 'getAllInstitute'])->name('getAllInstitute');
    Route::post('getInstituteDetails', [InstituteController::class, 'getInstituteDetails'])->name('getInstituteDetails');
    Route::post('editInstitute', [InstituteController::class, 'editInstitute'])->name('editInstitute');
    Route::post('deleteInstitute', [InstituteController::class, 'deleteInstitute'])->name('deleteInstitute');

    //?====================================================================== */

    // !======================== Department Vertical=========================== */


    Route::view('department_vertical', 'admin.department_vertical');
    Route::get('getAllVertical', [DepartmentVertical::class, 'getAllVertical'])->name('getAllVertical');
    Route::post('syncVertical', [DepartmentVertical::class, 'syncVertical'])->name('syncVertical');


    // !======================== Department Vertical=========================== */

    Route::get('lodging', [ElgController::class, 'lodging'])->name('admin.lodging');
    Route::get('travel', [ElgController::class, 'travel'])->name('admin.travel');
    Route::post('getAllLodging', [ElgController::class, 'getAllLodging'])->name('getAllLodging');
    Route::post('getAllTravel', [ElgController::class, 'getAllTravel'])->name('getAllTravel');
    Route::post('syncELg', [ElgController::class, 'syncELg'])->name('syncELg');


    //!======================== Region Master =========================== */

    Route::get('region', [RegionController::class, 'region'])->name('admin.region');
    Route::post('syncRegion', [RegionController::class, 'syncRegion'])->name('syncRegion');
    Route::get('getAllRegion', [RegionController::class, 'getAllRegion'])->name('getAllRegion');
    Route::get('getAllZone', [RegionController::class, 'getAllZone'])->name('getAllZone');
    Route::get('hq_wise_region', [RegionController::class, 'hq_wise_region'])->name('hq_wise_region');
    Route::post('syncHqRegion', [RegionController::class, 'syncHqRegion'])->name('syncHqRegion');
    Route::post('getAllRegionHq', [RegionController::class, 'getAllRegionHq'])->name('getAllRegionHq');

    //**=============================Resume Source================================================= */
    Route::get('resumesource', [ResumeSourcController::class, 'resumesource'])->name('admin.resumesource');
    Route::post('addResumeSource', [ResumeSourcController::class, 'addResumeSource'])->name('addResumeSource');
    Route::get('getAllResumeSource', [ResumeSourcController::class, 'getAllResumeSource'])->name('getAllResumeSource');
    Route::post('getResumeSourceDetails', [ResumeSourcController::class, 'getResumeSourceDetails'])->name('getResumeSourceDetails');
    Route::post('editResumeSource', [ResumeSourcController::class, 'editResumeSource'])->name('editResumeSource');
    Route::post('deleteResumeSource', [ResumeSourcController::class, 'deleteResumeSource'])->name('deleteResumeSource');
    //**=========================================================================================== */

    //? ======================= User Master ===================== */
    Route::get('userlist', [UserController::class, 'userlist'])->name('admin.userlist');
    Route::post('addUser', [UserController::class, 'addUser'])->name('addUser');
    Route::post('changeUserStatus', [UserController::class, 'changeUserStatus'])->name('changeUserStatus');
    Route::get('getAllUser', [UserController::class, 'getAllUser'])->name('getAllUser');
    Route::post('cngUserPwd', [UserController::class, 'cngUserPwd'])->name('cngUserPwd');
    Route::post('deleteUser', [UserController::class, 'deleteUser'])->name('deleteUser');
    Route::post('getPermission', [UserController::class, 'getPermission'])->name('getPermission');
    Route::post('setpermission', [UserController::class, 'setpermission'])->name('setpermission');
    Route::get('getEmployee', [UserController::class, 'getEmployee'])->name('getEmployee');
    Route::get('getEmployeeDetail', [UserController::class, 'getEmployeeDetail'])->name('getEmployeeDetail');
    Route::get('user_department_list', [UserController::class, 'user_department_list'])->name('user_department_list');
    Route::post('map_user_department', [UserController::class, 'map_user_department'])->name('map_user_department');

    //======================================Minimum Wage=========================//
    Route::get('minimum_wage', [MinimumWageController::class, 'minimum_wage'])->name('admin.minimum_wage');
    Route::post('syncMW', [MinimumWageController::class, 'syncMW'])->name('syncMW');
    Route::get('getAllMW', [MinimumWageController::class, 'getAllMW'])->name('getAllMW');
    //?====================================================================== */
    //Route::get('communication_control', [CommunicationController::class, 'communication_control'])->name('admin.communication_control');
    Route::view('communication_control', 'admin.communication');
    Route::post('setCommunication', [CommunicationController::class, 'setCommunication'])->name('setCommunication');
});

Route::post('getRegionByVertical', [RegionController::class, 'getRegionByVertical'])->name('getRegionByVertical');
Route::group(['prefix' => 'recruiter', 'middleware' => ['isRecruiter', 'auth', 'PreventBackHistory']], function () {
    Route::get('dashboard', [RecruiterController::class, 'index'])->name('recruiter.dashboard');
    Route::get('mrf_allocated', [MrfAllocatedController::class, 'mrf_allocated'])->name('mrf_allocated');
    Route::post('getAllAllocatedMRF', [MrfAllocatedController::class, 'getAllAllocatedMRF'])->name('getAllAllocatedMRF');

    Route::post('createJobPost', [MrfAllocatedController::class, 'createJobPost'])->name('createJobPost');
    Route::get('get_mrf_position_filled_detail', [MrfAllocatedController::class, 'get_mrf_position_filled_detail'])->name('get_mrf_position_filled_detail');
    Route::post('save_mrf_position_filled', [MrfAllocatedController::class, 'save_mrf_position_filled'])->name('save_mrf_position_filled');
    Route::post('updateJobPost', [MrfAllocatedController::class, 'updateJobPost'])->name('updateJobPost');
});
Route::post('getDetailForJobPost', [MrfAllocatedController::class, 'getDetailForJobPost'])->name('getDetailForJobPost');

Route::post('ChngPostingView', [MrfAllocatedController::class, 'ChngPostingView'])->name('ChngPostingView');
Route::post('close_mrf', [MrfAllocatedController::class, 'close_mrf'])->name('close_mrf');

Route::group(['prefix' => 'hod', 'middleware' => ['isHod', 'auth', 'PreventBackHistory']], function () {
    Route::get('dashboard', [HodController::class, 'index'])->name('hod.dashboard');
    Route::get('mrfbyme', [HodController::class, 'mrfbyme'])->name('mrfbyme');
    Route::get('interviewschedule', [HodController::class, 'interviewschedule'])->name('interviewschedule');
    Route::get('pending_screening', [HodController::class, 'pending_screening'])->name('pending_screening');
    Route::post('show_resume', [HodController::class, 'show_resume'])->name('show_resume');
    Route::post('change_screen_status', [HodController::class, 'change_screen_status'])->name('change_screen_status');
    Route::get('rejected_candidate', [HodController::class, 'hr_screening_rejected_list'])->name('rejected_candidate');
    //**========================My Team =========================================== */
    Route::get('myteam', [MyTeamController::class, 'myteam'])->name('myteam');
    Route::get('my_resigned_team', [MyTeamController::class, 'my_resigned_team'])->name('my_resigned_team');
    Route::post('getAllMyTeamMember', [MyTeamController::class, 'getAllMyTeamMember'])->name('getAllMyTeamMember');
    Route::post('getResignedMember', [MyTeamController::class, 'getResignedMember'])->name('getResignedMember');
    Route::post('getMyTeam', [MyTeamController::class, 'getMyTeam'])->name('getMyTeam');
    Route::get('repmrf', [MyTeamController::class, 'repmrf'])->name('repmrf');
    //!=============================MRF===============================================//
    Route::get('manpowerrequisition', [MrfController::class, 'manpowerrequisition'])->name('manpowerrequisition');
    Route::get('new_mrf', [MrfController::class, 'new_mrf'])->name('new_mrf');
    Route::get('sip_mrf', [MrfController::class, 'sip_mrf'])->name('sip_mrf');
    Route::get('campus_mrf', [MrfController::class, 'campus_mrf'])->name('campus_mrf');
    Route::post('addNewMrf', [MrfController::class, 'addNewMrf'])->name('addNewMrf');
    Route::post('addSipMrf', [MrfController::class, 'addSipMrf'])->name('addSipMrf');
    Route::post('addCampusMrf', [MrfController::class, 'addCampusMrf'])->name('addCampusMrf');
    Route::post('addRepMrf', [MrfController::class, 'addRepMrf'])->name('addRepMrf');
    Route::post('getAllMRFCreatedByMe', [MrfController::class, 'getAllMRFCreatedByMe'])->name('getAllMRFCreatedByMe');
    Route::get('mrf_approval_list', [HodController::class, 'mrf_approval_list'])->name('mrf_approval_list');
    Route::post('approveMRF', [MrfController::class, 'approveMRF'])->name('approveMRF');
    Route::post('rejectMRF', [MrfController::class, 'rejectMRF'])->name('rejectMRF');
    Route::get('resume_databank', [HodController::class, 'resume_databank'])->name('resume_databank');
    Route::get('shared_profile', [HodController::class, 'shared_profile'])->name('shared_profile');
    //!==============================================================================//
});


//=============================REPORTS=====================================================//
Route::get('Firob_Reports', [Reports::class, 'Firob_Reports'])->name('Firob_Reports');
Route::get('candidate_vs_job', [CandidateVsJobController::class, 'index'])->name('candidate_vs_job');
Route::get('reports_download', [Reports::class, 'reports_download'])->name('reports_download');
Route::get('mrfs_report', [Reports::class, 'mrfs_report'])->name('mrfs_report');
Route::post('getMrfReport', [Reports::class, 'getMrfReport'])->name('getMrfReport');
Route::get('hr_screening_report', [Reports::class, 'hr_screening_report'])->name('hr_screening_report');
Route::post('get_hr_screening_report', [Reports::class, 'get_hr_screening_report'])->name('get_hr_screening_report');
Route::get('tech_screening_report', [Reports::class, 'tech_screening_report'])->name('tech_screening_report');
Route::post('get_tech_screening_report', [Reports::class, 'get_tech_screening_report'])->name('get_tech_screening_report');
Route::get('interview_tracker_report', [Reports::class, 'interview_tracker_report'])->name('interview_tracker_report');
Route::post('get_interview_tracker_report', [Reports::class, 'get_interview_tracker_report'])->name('get_interview_tracker_report');
Route::get('job_offer_report', [Reports::class, 'job_offer_report'])->name('job_offer_report');
Route::post('get_job_offer_report', [Reports::class, 'get_job_offer_report'])->name('get_job_offer_report');
Route::get('candidate_joining_report', [Reports::class, 'candidate_joining_report'])->name('candidate_joining_report');
Route::post('get_candidate_joining_report', [Reports::class, 'get_candidate_joining_report'])->name('get_candidate_joining_report');
Route::get('application_source_report', [Reports::class, 'application_source_report'])->name('application_source_report');
Route::post('getApplicationSource', [Reports::class, 'getApplicationSource'])->name('getApplicationSource');
Route::post('getActiveMRFWiesData', [Reports::class, 'getActiveMRFWiesData'])->name('getActiveMRFWiesData');
Route::post('mrf_status_open_days', [Reports::class, 'mrf_status_open_days'])->name('mrf_status_open_days');
Route::get('mrf_report', [Reports::class, 'mrf_report'])->name('mrf_report');
Route::post('get_mrf_report_data', [Reports::class, 'get_mrf_report_data'])->name('get_mrf_report_data');
Route::get('recruiter_report', [Reports::class, 'recruiter_report'])->name('recruiter_report');
Route::post('get_recruiter_wise_data', [Reports::class, 'get_recruiter_wise_data'])->name('get_recruiter_wise_data');

Route::get('manual_entry_report', [Reports::class, 'manual_entry_report'])->name('manual_entry_report');
Route::post('get_manual_entry_data', [Reports::class, 'get_manual_entry_data'])->name('get_manual_entry_data');

Route::get('candidate_wise_trainee_report', [Reports::class, 'candidate_wise_trainee_report'])->name('candidate_wise_trainee_report');
Route::post('get_candidate_wise_trainee_data', [Reports::class, 'get_candidate_wise_trainee_data'])->name('get_candidate_wise_trainee_data');

Route::get('college_wise_trainee_report', [Reports::class, 'college_wise_trainee_report'])->name('college_wise_trainee_report');
Route::post('get_college_wise_trainee_data', [Reports::class, 'get_college_wise_trainee_data'])->name('get_college_wise_trainee_data');

Route::get('active_trainee_report', [Reports::class, 'active_trainee_report'])->name('active_trainee_report');
Route::post('get_active_trainee_data', [Reports::class, 'get_active_trainee_data'])->name('get_active_trainee_data');

Route::get('all_candidate_report', [Reports::class, 'all_candidate_report'])->name('all_candidate_report');

Route::get('mrf_tat', [Reports::class, 'mrf_tat'])->name('mrf_tat');
Route::post('get_mrf_tat_data', [Reports::class, 'get_mrf_tat_data'])->name('get_mrf_tat_data');

Route::post('job_response_data_download', [Reports::class, 'job_response_data_download'])->name('job_response_data_download');
//==========================================================================================

Route::get('Import', [ImportController::class, 'Import'])->name('Import');

Route::get('position_code', [PositionCodeController::class, 'show_position_code'])->name('position_code');
Route::post('show_all_position_code', [PositionCodeController::class, 'show_all_position_code'])->name('show_all_position_code');
Route::post('unused_position_code', [PositionCodeController::class, 'unused_position_code'])->name('unused_position_code');
Route::post('SyncPositionCode', [PositionCodeController::class, 'SyncPositionCode'])->name('SyncPositionCode');
Route::post('add_position_code', [PositionCodeController::class, 'add_position_code'])->name('add_position_code');


//=============================Test Module=====================================================//
Route::resource('subject_master', SubjectMasterController::class);
Route::post('get_sub_dept_map', [SubjectMasterController::class, 'get_sub_dept_map'])->name('get_sub_dept_map');
Route::post('map_sub_with_dpt', [SubjectMasterController::class, 'map_sub_with_dpt'])->name('map_sub_with_dpt');

Route::resource('question_bank', QuestionBankController::class);
Route::post('get_question_bank_questions/{id}', [QuestionBankController::class, 'get_question_bank_questions'])->name('get_question_bank_questions');
Route::post('ckeditor/upload', [CKEditorController::class, 'upload'])->name('ckeditor.image-upload');

Route::resource('test_candidate', TestCandidateController::class);
Route::post('test_candidate.import', [TestCandidateController::class, 'import'])->name('test_candidate.import');
Route::get('candidate_assessment_report', [TestCandidateController::class, 'candidate_assessment_report'])->name('candidate_assessment_report');
Route::get('candidate_assessment_result', [TestCandidateController::class, 'candidate_assessment_result'])->name('candidate_assessment_result');
Route::resource('exam_master', ExamController::class);
Route::post('setExam', [ExamController::class, 'setExam'])->name('setExam');
Route::get('candidate_assessment_login/{id}/{exam_id}', [ExamController::class, 'candidate_assessment_login'])->name('candidate_assessment_login');
Route::get('candidate_assessment_instruction', [ExamController::class, 'candidate_assessment_instruction'])->name('candidate_assessment_instruction');
Route::get('candidate_assessment_select_paper', [ExamController::class, 'candidate_assessment_select_paper'])->name('candidate_assessment_select_paper');
Route::get('candidate_assessment', [ExamController::class, 'candidate_assessment'])->name('candidate_assessment');
Route::post('sendCandidateAssessmentMail', [ExamController::class, 'sendCandidateAssessmentMail'])->name('sendCandidateAssessmentMail');
Route::post('candidate_assessment_save', [ExamController::class, 'candidate_assessment_save'])->name('candidate_assessment_save');
Route::post('candidate_assessment_final_submit', [ExamController::class, 'candidate_assessment_final_submit'])->name('candidate_assessment_final_submit');
Route::post('candidate_assessment_stop', [ExamController::class, 'candidate_assessment_stop'])->name('candidate_assessment_stop');


//single action controller
Route::middleware('cors')->group(function () {
    Route::get('get_job_opening', JobPostAPI::class);
    Route::get('job_detail/{id}', [JobPostAPI::class, 'job_detail'])->name('job_detail');
});

//Save Event
Route::post('save_event', [CommonController::class, 'save_event'])->name('save_event');
// routes/web.php
Route::post('/check-duplicate', [CommonController::class, 'checkDuplicate'])->name('check.duplicate');


//Reminder Interview Mail
Route::get('reminder_interview_mail', [CommonController::class, 'reminder_interview_mail'])->name('reminder_interview_mail');
Route::get('reminder_interview_mail_second_round', [CommonController::class, 'reminder_interview_mail_second_round'])->name('reminder_interview_mail_second_round');
Route::get('duplicate', [CommonController::class, 'duplicate'])->name('duplicate');
Route::get('candidate_association', [CommonController::class, 'candidate_association'])->name('candidate_association');
Route::post('suitable_candidate', [CommonController::class, 'suitable_candidate'])->name('suitable_candidate');
Route::post('sldpt_process', [CommonController::class, 'sldpt_process'])->name('sldpt_process');
Route::post('sldpt_process_from_databank', [CommonController::class, 'sldpt_process_from_databank'])->name('sldpt_process_from_databank');
Route::post('getMRFTAT', [CommonController::class, 'getMRFTAT'])->name('getMRFTAT');
Route::get('download_candidate_data_mrf_wise/{mrfid}', [CommonController::class, 'download_candidate_data_mrf_wise'])->name('download_candidate_data_mrf_wise');


Route::get('download_bulk_cv', [CommonController::class, 'download_bulk_cv'])->name('download_bulk_cv');
Route::get('download_bulk_cv_all', [CommonController::class, 'download_bulk_cv_all'])->name('download_bulk_cv_all');

Route::resource('case-study-question', CaseStudyQuestionController::class);

Route::resource('case-study-answer', CaseStudyAnswerController::class);

Route::resource('core_api', CoreAPIController::class);
Route::get('core_api_sync', [CoreAPIController::class, 'sync'])->name('core_api_sync');
Route::post('importAPISData', [CoreAPIController::class, 'importAPISData'])->name('importAPISData');

Route::get('sync_company', [CompanyController::class, 'sync'])->name('sync_company');
Route::get('sync_country', [CountryController::class, 'sync'])->name('sync_country');
Route::get('sync_state', [StateController::class, 'sync'])->name('sync_state');
Route::get('sync_district', [DistrictController::class, 'sync'])->name('sync_district');
Route::resource('test', TestController::class);
Route::get('core_department_map', [TestController::class, 'core_department_map'])->name('core_department_map');
Route::get('core_designation_map', [TestController::class, 'core_designation_map'])->name('core_designation_map');
Route::get('core_job_map', [TestController::class, 'core_job_map'])->name('core_job_map');
Route::post('mapCoreDepartment', [TestController::class, 'mapCoreDepartment'])->name('mapCoreDepartment');
Route::post('mapCoreDesignation', [TestController::class, 'mapCoreDesignation'])->name('mapCoreDesignation');
Route::post('mapCoreJobPost', [TestController::class, 'mapCoreJobPost'])->name('mapCoreJobPost');
Route::get('sync_data', [TestController::class, 'sync_data'])->name('sync_data');
Route::get('/s3-upload', [S3FileUploadController::class, 'index'])->name('s3.index');
Route::post('/s3-upload', [S3FileUploadController::class, 'upload'])->name('s3.upload');