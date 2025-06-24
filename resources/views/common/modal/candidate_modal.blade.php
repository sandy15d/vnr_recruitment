<div class="modal fade" id="HrScreeningModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog">
        <form action="{{ route('update_hrscreening') }}" method="POST" id="ScreeningForm">
            <div class="modal-content">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label for="">HR Screening Date: <font color="#FF0000">*</font></label>
                            <input type="datetime-local" name="HrScreeningDate" id="HrScreeningDate"
                                class="form-control form-control-sm reqinp_scr">
                        </div>
                    </div>
                    <input type="hidden" name="Hr_Screening_JAId" id="Hr_Screening_JAId" value="{{ $JAId }}">
                    <label for="Status" class="mt-2">HR Screening Status</label>
                    <select name="Status" id="Status" class="form-select form-select-sm reqinp_scr">
                        <option value="" disabled selected></option>
                        <option value="Selected">Selected</option>
                        <option value="Rejected">Rejected</option>
                        <option value="Irrelevant">Irrelevant</option>
                    </select>

                    <textarea name="RejectRemark" id="RejectRemark" cols="30" rows="3"
                        class="form-control form-control-sm mt-2 reqinp_scr" placeholder="Please Enter Remark"></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="MoveCandidategModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog ">
        <form action="{{ route('MoveCandidate') }}" method="POST" id="MoveCandidateForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title">Move to Other Company</h6>
                    <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">x</span>
                    </button>
                </div>
                <div class="modal-body">
                    <label for="Status">Move Candidate To:</label>
                    <input type="hidden" name="MoveCandidate_JAId" id="MoveCandidate_JAId">
                    <select name="MoveCompany" id="MoveCompany" class="form-select form-select-sm">
                        <option value="" disabled selected></option>
                        @foreach ($company_list as $key => $value)
                            <option value="{{ $key }}">{{ $value }}</option>
                        @endforeach
                    </select>

                    <label class="mt-3" for="MoveDepartment">Department</label>
                    <select name="MoveDepartment" id="MoveDepartment" class="form-select form-select-sm">

                    </select>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </div>
        </form>
    </div>
</div>

<div id="profile_info" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title border-bot">Profile Information</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="CandidateProfileForm" action="{{ route('Candidate_ProfileData_Save') }}" method="POST">
                    <input type="hidden" name="Pro_JCId" id="Pro_JCId">
                    <div class="form-group">
                        <label>First Name</label>
                        <input type="text" name="FName" id="FName" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label>Middle Name</label>
                        <input class="form-control form-control-sm" type="text" id="MName" name="MName">
                    </div>

                    <div class="form-group">
                        <label>Last Name</label>
                        <input type="text" name="LName" id="LName" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label>Date of Birth</label>
                        <input class="form-control form-control-sm" type="date" id="DOB" name="DOB">
                    </div>

                    <div class="form-group">
                        <label>Mobile</label>
                        <input type="text" name="Mobile" id="Mobile" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label>Email ID</label>
                        <input type="text" name="EMail" id="EMail" class="form-control form-control-sm">
                    </div>

                    <div class="form-group">
                        <label>Candidate Image</label>
                        <input type="file" name="CandidateImage" id="CandidateImage"
                            class="form-control form-control-sm">
                    </div>
                    <div class="form-group">
                        <label>Resume / CV</label>
                        <input type="file" name="Resume" id="Resume" class="form-control form-control-sm"
                            accept=".pdf,.docx">
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="personal_info_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Personal Information</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="CandidatePersonalForm" action="{{ route('Candidate_PersonalData_Save') }}" method="POST">

                    <input type="hidden" name="P_JCId" id="P_JCId">

                    <div class="form-group">
                        <label for="FatherName">Father Name</label><br>
                        <select name="FatherTitle" id="FatherTitle" class="form-select form-select-sm"
                            style="width: 100px; display:initial">
                            <option value="Mr.">Mr.</option>
                            <option value="Late">Late</option>
                        </select>
                        <input type="text" name="FatherName" id="FatherName"
                            class="form-control form-control-sm d-inline" style="width: 333px;">
                    </div>
                    <div class="form-group">
                        <label>Gender</label>
                        <select name="Gender" id="Gender" class="form-select form-select-sm">
                            <option value="">Select</option>
                            <option value="M">Male</option>
                            <option value="F">Female</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Aadhaar No</label>
                        <input class="form-control form-control-sm" type="text" id="Aadhaar" name="Aadhaar">
                    </div>
                    <div class="form-group">
                        <label>Nationality</label>
                        <select name="Nationality" id="Nationality" class="form-select form-select-sm">
                            <option value="">Select</option>
                            @foreach ($country_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>

                    </div>
                    <div class="form-group">
                        <label>Religion</label>
                        <select name="Religion" id="Religion" class="form-select form-select-sm">
                            <option value="Hinduism">Hinduism</option>
                            <option value="Islam">Islam</option>
                            <option value="Christianity">Christianity</option>
                            <option value="Sikhism">Sikhism</option>
                            <option value="Buddhism">Buddhism</option>
                            <option value="Jainism">Jainism</option>
                            <option value="Others">Others</option>
                        </select>
                        <input type="text" name="OtherReligion" id="OtherReligion"
                            class="form-control form-control-sm d-none mt-2" placeholder="Other Religion">
                    </div>
                    <div class="form-group">
                        <label>Marital Status</label>
                        <select name="MaritalStatus" id="MaritalStatus" class="form-select form-select-sm">
                            <option value=""></option>
                            <option value="Single">Single</option>
                            <option value="Married">Married</option>
                            <option value="Divorced">Divorced</option>
                            <option value="Widowed">Widowed</option>
                        </select>
                    </div>
                    <div class="form-group d-none" id="MDate">
                        <label>Marriage Date</label>
                        <input type="date" name="MarriageDate" id="MarriageDate"
                            class="form-select form-select-sm">
                    </div>
                    <div class="form-group d-none" id="Spouse">
                        <label>spouse Name</label>
                        <input class="form-control form-control-sm" type="text" id="SpouseName"
                            name="SpouseName">
                    </div>
                    <div class="form-group">
                        <label>Category</label>
                        <select name="Category" id="Category" class="form-select form-select-sm">
                            <option value="ST">ST</option>
                            <option value="SC">SC</option>
                            <option value="OBC">OBC</option>
                            <option value="General">General</option>
                            <option value="Other">Other</option>
                        </select>
                        <input type="text" name="OtherCategory" id="OtherCategory"
                            class="form-control form-control-sm d-none mt-2" placeholder="Other Category">
                    </div>

                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="emergency_contact_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Emergency Contact</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="EmergencyContactForm" action="{{ route('Candidate_EmergencyContact_Save') }}"
                    method="POST">
                    <input type="hidden" name="Emr_JCId" id="Emr_JCId">
                    <p class="mb-1 fw-bold">Primary Emergency Contact ---------------------------</p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="PrimaryName">Name</label>
                                <input type="text" name="PrimaryName" id="PrimaryName"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="PrimaryRelation">Relationship</label>
                                <input type="text" name="PrimaryRelation" id="PrimaryRelation"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="PrimaryPhone">Phone No</label>
                                <input type="text" name="PrimaryPhone" id="PrimaryPhone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <p class="mb-1 fw-bold mt-2">Secondary Emergency Contact <small class="text-danger"> (optional)
                        </small></p>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="SecondaryName">Name</label>
                                <input type="text" name="SecondaryName" id="SecondaryName"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="SecondaryRelation">Relationship</label>
                                <input type="text" name="SecondaryRelation" id="SecondaryRelation"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="SecondaryPhone">Phone No</label>
                                <input type="text" name="SecondaryPhone" id="SecondaryPhone"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="bank_info_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Bank Information & Other</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="BankInfoForm" action="{{ route('Candidate_BankInfo_Save') }}" method="POST">
                    <input type="hidden" name="Bank_JCId" id="Bank_JCId">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="BankName">Bank Name</label>
                                <input type="text" name="BankName" id="BankName"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="BranchName">Branch</label>
                                <input type="text" name="BranchName" id="BranchName"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="IFSCCode">IFSC Code</label>
                                <input type="text" name="IFSCCode" id="IFSCCode"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="AccountNumber">Account Number</label>
                                <input type="text" name="AccountNumber" id="AccountNumber"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PAN">PAN Number</label>
                                <input type="text" name="PAN" id="PAN"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="UAN">UAN Number</label>
                                <input type="text" name="UAN" id="UAN"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PFNumber">PF Number</label>
                                <input type="text" name="PFNumber" id="PFNumber"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="ESICNumber">ESIC Number</label>
                                <input type="text" name="ESICNumber" id="ESICNumber"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="Passport">Passport</label>
                                <input type="text" name="Passport" id="Passport"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="family_info_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Family Information</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="FamilyInfoForm" action="{{ route('Candidate_Family_Save') }}" method="POST">
                    <input type="hidden" name="Family_JCId" id="Family_JCId">
                    <table class="table table-bordered">
                        <thead class="text-center">
                            <tr>
                                <td style="width: 20%">Relation</td>
                                <td style="width: 20%">Name</td>
                                <td style="width:10%;">DOB</td>
                                <td style="width: 20%">Qualification</td>
                                <td style="width: 20%">Occupation</td>
                                <td style="width: 10%">Delete</td>
                            </tr>
                        </thead>
                        <tbody id="FamilyData">

                        </tbody>
                    </table>
                    <input type="button" value="Add Member" id="addMember" class="btn btn-primary btn-sm">
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="current_address_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Current Address</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="CurrentAddressForm" action="{{ route('Candidate_CurrentAddress_Save') }}" method="POST">
                    <input type="hidden" name="Current_JCId" id="Current_JCId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PreAddress">Address</label>
                                <input type="text" name="PreAddress" id="PreAddress"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PreCity">City</label>
                                <input type="text" name="PreCity" id="PreCity"
                                    class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PreState">State</label>
                                <select name="PreState" id="PreState" class="form-select form-select-sm"
                                    onchange="getLocation(this.value);">
                                    <option value="">Select State</option>
                                    @foreach ($state_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="spinner-border text-primary d-none" role="status" id="PreDistLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="form-group">
                                <label for="PreDistrict">District</label>
                                <select name="PreDistrict" id="PreDistrict" class="form-select form-select-sm">
                                    @foreach ($district_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PrePinCode">Pin Code</label>
                                <input type="text" name="PrePinCode" id="PrePinCode"
                                    class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="permanent_address_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Current Address</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="PermanentAddressForm" action="{{ route('Candidate_PermanentAddress_Save') }}"
                    method="POST">
                    <input type="hidden" name="Permanent_JCId" id="Permanent_JCId">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PermAddress">Address</label>
                                <input type="text" name="PermAddress" id="PermAddress"
                                    class="form-control form-control-sm">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="PermCity">City</label>
                                <input type="text" name="PermCity" id="PermCity"
                                    class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="row mt-3">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PermState">State</label>
                                <select name="PermState" id="PermState" class="form-select form-select-sm"
                                    onchange="getLocation1(this.value);">
                                    <option value="">Select State</option>
                                    @foreach ($state_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>

                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="spinner-border text-primary d-none" role="status" id="PermDistLoader">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <div class="form-group">
                                <label for="PermDistrict">District</label>
                                <select name="PermDistrict" id="PermDistrict" class="form-select form-select-sm">
                                    @foreach ($district_list as $key => $value)
                                        <option value="{{ $key }}">{{ $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="PermPinCode">Pin Code</label>
                                <input type="text" name="PermPinCode" id="PermPinCode"
                                    class="form-control form-control-sm">
                            </div>
                        </div>

                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div id="suitable_modal" class="modal fade" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Suitable For:</h6>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{ route('suitable_candidate') }}" method="POST" id="SuitableForm">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12 mb-3">
                            <input type="hidden" name="SuitableJCId" id="SuitableJCId"
                                value="{{ $Rec->JCId }}">
                            <label for="Irrelevant_Candidate" class="form-label">Irrelevant Candidate <span
                                    class="text-danger">*</span> :</label>
                            <select name="Irrelevant_Candidate" id="Irrelevant_Candidate"
                                class="form-select reqinp_suit">
                                <option value="">Select</option>
                                <option value="N">No</option>
                                <option value="Y">Yes</option>
                            </select>
                        </div>
                        <div class="col-md-12 d-none" id="sui_dep_div">
                            <label class="form-label">Department <span class="text-danger">*</span> :</label>
                            <select name="suitable_department[]" id="suitable_department" multiple
                                class="form-select ">
                                <option></option>
                                @foreach ($department_list as $key => $value)
                                    <option value="{{ $key }}">{{ $value }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="" class="form-label">Remark <span
                                    class="text-danger">*</span>:</label>
                            <textarea name="suitable_remark" id="suitable_remark" class="form-control reqinp_suit"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="OlActionModal" class="modal fade" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">OL action on behalf of Candidate:</h6>
                <button type="button" class="btn btn-close" data-bs-dismiss="modal" aria-label="Close">
                </button>
            </div>
            <form action="{{ route('ol_action_on_behalf_candidate') }}" method="POST" id="responseform">
                @csrf
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <input type="hidden" name="ol_action_jaid" id="ol_action_jaid"
                                value="{{ $JAId }}">
                            <label for="ol_action" class="form-label">Offer Status <span class="text-danger">*</span>
                                :</label>
                            <select name="ol_action" id="ol_action" class="form-select">
                                <option value="">Select</option>
                                <option value="Accepted">Accept</option>
                                <option value="Rejected">Reject</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3 d-none" id="ol_action_div">
                            <label for="ol_action_date" class="form-label">Joining Date </label>
                            <input type="date" name="ol_action_date" id="ol_action_date"
                                class="form-control form-control-sm">
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <label for="" class="form-label">Remark <span
                                    class="text-danger">*</span>:</label>
                            <textarea name="ol_action_remark" id="ol_action_remark" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="ref_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Send for Reference Check</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('send_for_ref_chk') }}" method="POST" id="ref_chk_form">
                    @csrf
                    <div class="form-group mb-2">
                        <input type="hidden" name="ReferenceChkJAId" value="{{ $JAId }}">
                        <label for="RefChkMail">Ref. Person Mail ID <i class="text-danger">*</i></label>
                        <input type="text" name="RefChkMail" id="RefChkMail"
                            class="form-control form-control-sm">
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Send Mail</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="document_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title text-light" id="exampleModalLabel">Document Upload</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered">
                    <thead class="text-center">
                        <th>Document Name</th>
                        <th>Upload Document</th>
                        <th>View</th>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Test Paper</td>
                            <td>
                                <input type="file" name="TestPaper" id="TestPaper"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="TestPaperUpload">Upload
                                </button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->Test_Paper != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->Test_Paper }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Interview Assessment</td>
                            <td>
                                <input type="file" name="IntervAssessment" id="IntervAssessment"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="IntervAssessmentUpload">Upload
                                </button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->IntervAssessment != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->IntervAssessment }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        @if ($Rec->Professional == 'P')
                            <tr>
                                <td style="width: 25%">Offer or appointment letter
                                    (previous company)
                                </td>
                                <td style="width: 60%">
                                    <input type="file" name="OfferLtr" id="OfferLtr"
                                        class="form-control form-control-sm d-inline" style="width: 80%"
                                        accept="application/pdf">
                                    <button class="btn btn-warning btn-sm d-inline" id="OfferLtrUpload">Upload
                                    </button>
                                </td>
                                <td style="width: 10%; text-align:center">
                                    @if ($Docs != null && $Docs->OfferLtr != null)
                                        <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->OfferLtr }}"
                                            target="_blank" class="btn btn-primary btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>

                                <td>Resignation or Relieving Letter (previous
                                    company)
                                </td>
                                <td>
                                    <input type="file" name="RelievingLtr" id="RelievingLtr"
                                        class="form-control form-control-sm d-inline" style="width: 80%"
                                        accept="application/pdf">
                                    <button class="btn btn-warning btn-sm d-inline" id="RelievingLtrUpload">Upload
                                    </button>
                                </td>
                                <td style="width: 10%; text-align:center">
                                    @if ($Docs != null && $Docs->RelievingLtr != null)
                                        <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->RelievingLtr }}"
                                            target="_blank" class="btn btn-primary btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <td>Last drawn salary pay slip (previous company)
                                </td>
                                <td>
                                    <input type="file" name="SalarySlip" id="SalarySlip"
                                        class="form-control form-control-sm d-inline" style="width: 80%"
                                        accept="application/pdf">
                                    <button class="btn btn-warning btn-sm d-inline" id="SalarySlipUpload">Upload
                                    </button>
                                </td>
                                <td style="width: 10%; text-align:center">
                                    @if ($Docs != null && $Docs->SalarySlip != null)
                                        <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->SalarySlip }}"
                                            target="_blank" class="btn btn-primary btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                            <tr>

                                <td>Increment or appraisal letter with revised CTC
                                    details
                                </td>
                                <td>
                                    <input type="file" name="AppraisalLtr" id="AppraisalLtr"
                                        class="form-control form-control-sm d-inline" style="width: 80%"
                                        accept="application/pdf">
                                    <button class="btn btn-warning btn-sm d-inline" id="AppraisalLtrUpload">Upload
                                    </button>
                                </td>
                                <td style="width: 10%; text-align:center">
                                    @if ($Docs != null && $Docs->AppraisalLtr != null)
                                        <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->AppraisalLtr }}"
                                            target="_blank" class="btn btn-primary btn-sm">View</a>
                                    @endif
                                </td>
                            </tr>
                        @endif
                        <tr>

                            <td>COVID Vaccine Certificate (Final Certificate)
                            </td>
                            <td>
                                <input type="file" name="VaccinationCert" id="VaccinationCert"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="VaccinationCertUpload">Upload
                                </button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->VaccinationCert != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->VaccinationCert }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td style="width: 25%">Aadhaar Card</td>
                            <td style="width: 60%">
                                <input type="file" name="AadhaarCard" id="AadhaarCard"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="AadhaarUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->Aadhar != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->Aadhar }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>

                        <tr>

                            <td>Driving License</td>
                            <td>
                                <input type="file" name="DLCard" id="DLCard"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="DLCardUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->DL != null)
                                    <a title="View"
                                        href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->DL }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>PF Nomination Form 2</td>
                            <td>
                                <input type="file" name="PFForm2" id="PFForm2"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="PFForm2Upload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->PF_Form2 != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->PF_Form2 }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>PF Declaration Form 11
                            </td>
                            <td>
                                <input type="file" name="PF_Form11" id="PF_Form11"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="PFForm11Upload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->PF_Form2 != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->PF_Form11 }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>Gratuity Nomination Form
                            </td>
                            <td>
                                <input type="file" name="GratuityForm" id="GratuityForm"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="GratuityUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->Gratutity != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->Gratutity }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>ESIC Declaration Form 1
                            </td>
                            <td>
                                <input type="file" name="ESICForm" id="ESICForm"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="ESICFormUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->ESIC != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->ESIC }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>Family Declaration Form 1(A)
                            </td>
                            <td>
                                <input type="file" name="ESIC_Family" id="ESIC_Family"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="ESIC_FamilyUpload">Upload
                                </button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->ESIC_Family != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->ESIC_Family }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>Health Declaration Form
                            </td>
                            <td>
                                <input type="file" name="Health" id="Health"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="HealthUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->Health != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->Health }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td>Declaration for Compliance to Ethical Financial
                                Dealings
                            </td>
                            <td>
                                <input type="file" name="Ethical" id="Ethical"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="EthicalUpload">Upload</button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->Ethical != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->Ethical }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                        <tr>

                            <td>Blood Group Certificate
                            </td>
                            <td>
                                <input type="file" name="BloodGroup" id="BloodGroup"
                                    class="form-control form-control-sm d-inline" style="width: 80%"
                                    accept="application/pdf">
                                <button class="btn btn-warning btn-sm d-inline" id="BloodGroupUpload">Upload
                                </button>
                            </td>
                            <td style="width: 10%; text-align:center">
                                @if ($Docs != null && $Docs->BloodGroup != null)
                                    <a href="{{ URL::to('/') }}/uploads/Documents/{{ $Docs->BloodGroup }}"
                                        target="_blank" class="btn btn-primary btn-sm">View</a>
                                @endif
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
