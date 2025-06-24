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

@php
    function checked($answer, $expected)
    {
        return $answer === $expected ? 'checked' : '';
    }

    function showIf($condition)
    {
        return $condition ? '' : 'd-none';
    }

    $AboutAns = $AboutAns ?? null;

    $aim = $AboutAns->AboutAim ?? '';
    $hobbi = $AboutAns->AboutHobbi ?? '';
    $fiveYear = $AboutAns->About5Year ?? '';
    $assets = $AboutAns->AboutAssets ?? '';
    $improvement = $AboutAns->AboutImprovement ?? '';
    $strength = $AboutAns->AboutStrength ?? '';
    $deficiency = $AboutAns->AboutDeficiency ?? '';
    $criminal = $AboutAns->AboutCriminal ?? '';
    $criminalChk = $AboutAns->CriminalChk ?? '';
    $licenseChk = $AboutAns->LicenseChk ?? '';
    $dlNo = $AboutAns->DLNo ?? '';
    $lValidity = $AboutAns->LValidity ?? '';
@endphp

<div class="modal fade" id="about_modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title text-light" id="exampleModalLabel">About Yourself</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="{{ route('SaveAbout') }}" id="about_form" method="POST">
                    @csrf
                    <div class="col-lg-12">

                        @foreach ([
        'Q1. What is your aim in life?' => ['AboutAim', $aim],
        'Q2. What are your hobbies and interest?' => ['AboutHobbi', $hobbi],
        'Q3. Where do you see yourself 5 Years from now?' => ['About5Year', $fiveYear],
        'Q4. What are your greatest personal assets (qualities, skills, abilities)?' => ['AboutAssets', $assets],
        'Q5. What are your areas where you think you need to improve yourself?' => ['AboutImprovement', $improvement],
        'Q6. What are your Strengths?' => ['AboutStrength', $strength],
        'Q7. Any form of physical disability, illness, or deficiency?' => ['AboutDeficiency', $deficiency],
    ] as $question => [$field, $value])
                            <h6>{{ $question }}</h6>
                            <div class="form-group row mb-2">
                                <div class="col-md-12">
                                    <input type="text" name="{{ $field }}" id="{{ $field }}"
                                        class="form-control form-control-sm reqinp_abt" value="{{ $value }}">
                                </div>
                            </div>
                        @endforeach

                        {{-- Q8: Criminal prosecution --}}
                        <h6>Q8. Have you been criminally prosecuted? If so, give details separately.</h6>
                        <div class="text-left">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input crime" type="radio" name="CriminalChk"
                                    id="YesCriminal" value="Y" data-value="Y" {{ checked($criminalChk, 'Y') }}>
                                <label class="form-check-label" for="YesCriminal">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input crime" type="radio" name="CriminalChk"
                                    id="NoCriminal" value="N" data-value="N" {{ checked($criminalChk, 'N') }}>
                                <label class="form-check-label" for="NoCriminal">No</label>
                            </div>
                        </div>

                        <div class="form-group row mb-2 {{ showIf($criminalChk === 'Y') }}" id="crime_div">
                            <div class="col-md-12">
                                <input type="text" name="AboutCriminal" id="AboutCriminal"
                                    class="form-control form-control-sm" value="{{ $criminal }}">
                            </div>
                        </div>

                        {{-- Q9: Driving license --}}
                        <h6>Q9. Do you have a valid driving licence?</h6>
                        <div class="text-left">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dlchk" type="radio" name="LicenseChk"
                                    id="YesLicense" value="Y" data-value="Y"
                                    {{ checked($licenseChk, 'Y') }}>
                                <label class="form-check-label" for="YesLicense">Yes</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input dlchk" type="radio" name="LicenseChk"
                                    id="NoLicense" value="N" data-value="N" {{ checked($licenseChk, 'N') }}>
                                <label class="form-check-label" for="NoLicense">No</label>
                            </div>
                        </div>

                        <div class="form-group row mb-2 {{ showIf($licenseChk === 'Y') }}" id="dl_div">
                            <label class="col-form-label col-md-1">License No:</label>
                            <div class="col-md-2 col-sm-12">
                                <input type="text" class="form-control form-control-sm" id="DLNo"
                                    name="DLNo" value="{{ $dlNo }}">
                            </div>
                            <label class="col-form-label col-md-1">Validity:</label>
                            <div class="col-md-2 col-sm-12">
                                <input type="date" class="form-control form-control-sm" name="LValidity"
                                    id="LValidity" value="{{ $lValidity }}">
                            </div>
                        </div>

                    </div>
                    <div class="submit-section text-center">
                        <button class="btn btn-primary submit-btn">Save Details</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="view_review" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title text-light" id="exampleModalLabel">Offer Letter Review Status</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>S.No.</th>
                            <th>Offer Letter Ref.No
                            </th>
                            <th>Reviwed By</th>
                            <th>Status
                            </th>
                            <th>Reason for Rejection
                            </th>
                        </tr>
                    </thead>
                    <tbody id="viewReviewData">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div id="review_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Send Offer Letter for review</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="{{ route('send_for_review') }}" method="POST" id="reviewForm">
                    @csrf
                    <div class="form-group mb-2">
                        <input type="hidden" name="ReviewJaid" value="{{ $JAId }}">
                        <label for="ReviewCompany">Company</label>
                        <select name="ReviewCompany" id="ReviewCompany" class="form-select form-select-sm"
                            onchange="getEmployee(this.value)">
                            <option value="">Select</option>
                            @foreach ($company_list as $key => $value)
                                <option value="{{ $key }}">{{ $value }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="spinner-border text-primary d-none" role="status" id="EmpLoader"> <span
                            class="visually-hidden">Loading...</span></div>
                    <div class="form-group">
                        <label>Select Employee</label>
                        <select name="review_to[]" id="review_to"
                            class="form-select form-select-sm multiple-select" multiple>

                        </select>
                    </div>
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="HistoryModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header bg-success">
                <h6 class="modal-title text-light" id="exampleModalLabel">Offer Letter History</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <table class="table table-bordered text-center" style="vertical-align: middle;">
                    <thead>
                        <tr>
                            <th>Date Generate</th>
                            <th>Offer Letter Ref.No
                            </th>
                            <th>Offer Letter</th>
                            <th>Reason for Change
                            </th>
                        </tr>
                    </thead>
                    <tbody id="offerHistory">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@php
    $years = range(1980, $Year);
@endphp


<div class="modal fade" id="OfferLtrModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <form action="{{ route('update_offerletter_basic') }}" method="POST" id="offerletterbasicform">
            <input type="hidden" name="Of_JAId" id="Of_JAId">
            <div class="modal-content">
                <div class="modal-header bg-success">
                    <h6 class="modal-title text-light" id="exampleModalLabel">Offer Letter Basic Details</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-bordered" style="vertical-align: middle;">
                        <tbody>
                            <tr>
                                <input type="hidden" name="JCId" id="JCId">
                                <input type="hidden" name="SelectedForC" id="SelectedForC">
                                <input type="hidden" name="SelectedForD" id="SelectedForD">
                            </tr>
                            <tr>
                                <td style="width:150px;">Department</td>
                                <td>
                                    <input type="text" name="SelectedDepartment" id="SelectedDepartment"
                                        disabled
                                        style="background-color: white;border:aliceblue; width: 160px; color:black">
                                </td>
                            </tr>
                            <tr>
                                <td>Sub Department</td>
                                <td>
                                    <select name="SubDepartment" id="SubDepartment"
                                        class="form-select form-select-sm" style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Section</td>
                                <td>
                                    <select name="Section" id="Section" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:150px;">Grade</td>
                                <td>
                                    <select name="Grade" id="Grade" class="form-select form-select-sm"
                                        style="width: 200px;" required>
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:150px;">Designation</td>
                                <td>
                                    <select name="Designation" id="Designation" class="form-select form-select-sm"
                                        style="width: 200px;" required>
                                        <option value="">Select</option>
                                    </select>
                                </td>

                            </tr>
                            <tr>
                                <td>Designation Suffix</td>
                                <td>
                                    <select name="DesigSuffix" id="DesigSuffix" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                        <option value="Department">Department</option>
                                        <option value="SubDepartment">Sub Department</option>
                                        <option value="Section">Section</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:150px;">Vertical</td>
                                <td>
                                    <select name="Vertical" id="Vertical" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td style="width:150px;">MW</td>
                                <td>
                                    <input type="text" name="MW" id="MW"
                                        class="form-control form-control-sm" style="width: 200px;" readonly></input>
                                </td>
                            </tr>

                            <tr>
                                <td>Location(HQ)</td>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px;">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input " type="checkbox"
                                                            id="permanent_chk" name="permanent_chk"
                                                            value="1">
                                                        <label class="form-check-label"
                                                            for="permanent_chk">Permanent
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline d-none"
                                                        id="permanent_div">
                                                        <select name="Of_PermState" id="Of_PermState"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select State</option>
                                                        </select>
                                                        <select name="PermHQ" id="PermHQ"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select HQ</option>
                                                        </select>
                                                        <input type="text" name="Of_PermCity" id="Of_PermCity"
                                                            class="form-control form-control-sm d-inline"
                                                            style="width: 130px;" placeholder="City">
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input " type="checkbox"
                                                            id="temporary_chk" name="temporary_chk"
                                                            value="1">
                                                        <label class="form-check-label"
                                                            for="temporary_chk">Temporary
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline d-none"
                                                        id="temporary_div" style="margin-right:0px;">
                                                        <select name="TempState" id="TempState"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select State</option>

                                                        </select>
                                                        <select name="TempHQ" id="TempHQ"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select HQ</option>

                                                        </select>
                                                        <input type="text" name="TempCity" id="TempCity"
                                                            class="form-control form-control-sm d-inline"
                                                            style="width: 100px;" placeholder="City">

                                                        <select name="TemporaryMonth" id="TemporaryMonth"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 90px;">
                                                            <option value="0">Select Months</option>
                                                            <option value="One">1</option>
                                                            <option value="Two">2</option>
                                                            <option value="Three">3</option>
                                                            <option value="Four">4</option>
                                                            <option value="Five">5</option>
                                                            <option value="Six">6</option>
                                                            <option value="Seven">7</option>
                                                            <option value="Eight">8</option>
                                                            <option value="Nine">9</option>
                                                            <option value="Ten">10</option>
                                                            <option value="Eleven">11</option>
                                                            <option value="Twelve">12</option>
                                                        </select>
                                                    </div>
                                                    <div class="form-check form-check-inline d-none"
                                                        id="temporary_div1"
                                                        style="margin-right:0px; padding-left:125px;">
                                                        <select name="TempState1" id="TempState1"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select State</option>

                                                        </select>
                                                        <select name="TempHQ1" id="TempHQ1"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select HQ</option>

                                                        </select>
                                                        <input type="text" name="TempCity1" id="TempCity1"
                                                            class="form-control form-control-sm d-inline"
                                                            style="width: 100px;" placeholder="City">

                                                        <select name="TemporaryMonth1" id="TemporaryMonth1"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 90px;">
                                                            <option value="0">Select Months</option>
                                                            <option value="One">1</option>
                                                            <option value="Two">2</option>
                                                            <option value="Three">3</option>
                                                            <option value="Four">4</option>
                                                            <option value="Five">5</option>
                                                            <option value="Six">6</option>
                                                            <option value="Seven">7</option>
                                                            <option value="Eight">8</option>
                                                            <option value="Nine">9</option>
                                                            <option value="Ten">10</option>
                                                            <option value="Eleven">11</option>
                                                            <option value="Twelve">12</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>

                                </td>
                            </tr>

                            <tr>
                                <td>Reporting</td>
                                <td>
                                    <div class="form-check form-check-inline scon">
                                        <input class="form-check-input" type="radio" id="RepWithEmp"
                                            value="RepWithEmp" name="repchk"
                                            onclick="$('#rep_with_emp_tr').removeClass('d-none'); $('#rep_without_emp_tr').addClass('d-none');">
                                        <label class="form-check-label" for="RepWithEmp">Reporting Manager &
                                            Desig.</label>
                                    </div>
                                    <div class="form-check form-check-inline scon">
                                        <input class="form-check-input" type="radio" id="RepWithoutEmp"
                                            value="RepWithoutEmp" name="repchk"
                                            onclick="$('#rep_with_emp_tr').addClass('d-none');$('#rep_without_emp_tr').removeClass('d-none');">
                                        <label class="form-check-label" for="RepWithoutEmp">Designation</label>
                                    </div>


                                </td>
                            </tr>

                            <tr id="rep_with_emp_tr">
                                <td></td>
                                <td>
                                    <table class="table borderless" style="margin-bottom: 0px; ">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input " type="checkbox"
                                                            id="administrative_chk" name="administrative_chk"
                                                            value="1">
                                                        <label class="form-check-label"
                                                            for="administrative_chk">Administrative
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline d-none"
                                                        id="administrative_div">
                                                        <select name="AdministrativeDepartment"
                                                            id="AdministrativeDepartment"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 160px;">
                                                            <option value="">Select Department</option>
                                                        </select>
                                                        <select name="AdministrativeEmployee"
                                                            id="AdministrativeEmployee"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 160px;">
                                                            <option value="">Select Employee</option>
                                                        </select>

                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">
                                                        <input class="form-check-input " type="checkbox"
                                                            id="functional_chk" name="functional_chk"
                                                            value="1">
                                                        <label class="form-check-label"
                                                            for="functional_chk">Functional
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline d-none"
                                                        style="padding-left: 43px;" id="functional_div">
                                                        <select name="FunctionalDepartment"
                                                            id="FunctionalDepartment"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 160px;">
                                                            <option value="">Select Department</option>
                                                        </select>
                                                        <select name="FunctionalEmployee" id="FunctionalEmployee"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 160px;">
                                                            <option value="">Select Employee</option>
                                                        </select>

                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>
                            <tr id="rep_without_emp_tr">
                                <td></td>
                                <td>
                                    <select name="DesignationRep" id="DesignationRep"
                                        class="form-select form-select-sm" style="width: 300px;">
                                        <option value="">Select Reporting Designation</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td style="width:150px;">Show Reporting details in OL</td>
                                <td>
                                    <select name="RepLineVisibility" id="RepLineVisibility"
                                        class="form-select form-select-sm" style="width: 200px;" required>
                                        <option value="">Select</option>
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="bu_tr" class="d-none">
                                <td style="width:150px;">BU</td>
                                <td>
                                    <select name="BU" id="BU" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="zone_tr" class="d-none">
                                <td style="width:150px;">Zone</td>
                                <td>
                                    <select name="Zone" id="Zone" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="region_tr" class="d-none">
                                <td style="width:150px;">Region</td>
                                <td>
                                    <select name="Region" id="Region" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            <tr id="territory_tr" class="d-none">
                                <td style="width:150px;">Territory</td>
                                <td>

                                    <select name="Territory" id="Territory" class="form-select form-select-sm"
                                        style="width: 200px;">
                                        <option value="">Select</option>
                                    </select>
                                </td>
                            </tr>
                            {{-- <tr>
                            <td>CTC</td>
                            <td>CTC:Rs. <input type="text" name="CTC" id="CTC"
                                               class="form-control form-control-sm d-inline" style="width: 200px;">
                            </td>
                        </tr> --}}
                            <tr>
                                <td>Gross Monthly Salary</td>
                                <td><input type="number" name="grsM_salary" id="grsM_salary"
                                        class="form-control form-control-sm d-inline" style="width: 200px;">
                                    Minimum Basic Salary: Rs. <input type="number" name="MinBasicSalary"
                                        id="MinBasicSalary" class="form-control form-control-sm d-inline"
                                        style="width: 200px;">
                                </td>
                            </tr>
                            <tr>
                                <td>PF Wage Limit</td>
                                <td>
                                    <select name="PF_Wage_Limit" id="PF_Wage_Limit"
                                        class="form-select form-select-sm" style="width:200px;">
                                        <option value="Actual">Actual Basic Salary</option>
                                        <option value="Ceiling">PF Wage Ceiling</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Vehicle Policy</td>
                                <td>
                                    <select name="vehicle_policy" id="vehicle_policy"
                                        class="form-select form-select-sm" style="width: 200px;" required>
                                        <option value="">Select Policy</option>
                                        <option value="NA">Policy Not Required</option>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Mobile Handset</td>
                                <td>
                                    <select name="mobile_allow" id="mobile_allow"
                                        class="form-select form-select-sm d-inline" style="width:200px;" required>
                                        <option value="">Select</option>
                                        <option value="Y">Yes</option>
                                        <option value="N">No</option>
                                    </select>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input " type="checkbox" id="GPRS"
                                            name="GPRS">
                                        <label class="form-check-label" for="GPRS">GPRS
                                        </label>
                                    </div>
                                </td>
                            </tr>
                            <tr>
                                <td> Communication Allowance</td>
                                <td>
                                    <select name="Communication_Allowance" id="Communication_Allowance"
                                        class="form-select form-select-sm" style="width: 200px" required>
                                        <option value="">Select</option>
                                        <option value="N">No</option>
                                        <option value="Y">Yes</option>
                                        {{-- <option value="prepaid">Prepaid</option>
                                        <option value="postpaid">Postpaid</option>
                                        <option value="both">Both</option> --}}
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>Service Condition</td>
                                <td>
                                    <div class="form-check form-check-inline scon">
                                        <input class="form-check-input" type="radio" id="Training"
                                            value="Training" name="ServiceCond"
                                            onclick="$('#training_tr').removeClass('d-none');">
                                        <label class="form-check-label" for="Training">Training</label>
                                    </div>
                                    <div class="form-check form-check-inline scon">
                                        <input class="form-check-input" type="radio" id="Probation"
                                            value="Probation" name="ServiceCond"
                                            onclick="$('#training_tr').addClass('d-none');">
                                        <label class="form-check-label" for="Probation">Probation</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" id="nopnot"
                                            value="nopnot" name="ServiceCond"
                                            onclick="$('#training_tr').addClass('d-none');">
                                        <label class="form-check-label" for="nopnot">No Probation / No
                                            Training</label>
                                    </div>

                                </td>
                            </tr>

                            <tr id="training_tr" class="d-none">
                                <td></td>
                                <td>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">

                                                        <label>
                                                            Orientation Period:
                                                        </label>
                                                    </div>
                                                    <div class="d-inline" style="padding-left: 112px;">

                                                        <select name="OrientationPeriod" id="OrientationPeriod"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select</option>
                                                            <option value="One">1</option>
                                                            <option value="Two">2</option>
                                                            <option value="Three">3</option>
                                                            <option value="Four">4</option>
                                                            <option value="Five">5</option>
                                                            <option value="Six">6</option>
                                                        </select>
                                                        <span>Months</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">

                                                        <label>
                                                            Stipend during Orientation Period:
                                                        </label>
                                                    </div>
                                                    <div class="d-inline" style="padding-left: 18px;">

                                                        <input type="text" name="Stipend" id="Stipend"
                                                            class="form-control form-control-sm d-inline"
                                                            style="width: 130px;">
                                                        <span>per months</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">

                                                        <label>Designation & Grade <br>After Training Completion
                                                        </label>
                                                    </div>
                                                    <div class="form-check form-check-inline" id="permanent_div"
                                                        style="padding-left: 71px;">
                                                        <select name="AftDesignation" id="AftDesignation"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select Designation</option>
                                                        </select>
                                                        <select name="AftGrade" id="AftGrade"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select Grade</option>
                                                        </select>
                                                    </div>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>Service Bond</td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ServiceBond"
                                            id="ServiceBondYes" value="Yes"
                                            onclick="$('#bond_tr').removeClass('d-none');">
                                        <label class="form-check-label" for="ServiceBondYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="ServiceBond"
                                            id="ServiceBondNo" value="No" checked
                                            onclick="$('#bond_tr').addClass('d-none');">
                                        <label class="form-check-label" for="ServiceBondNo">No</label>
                                    </div>
                                </td>
                            </tr>

                            <tr id="bond_tr" class="d-none">
                                <td></td>
                                <td>
                                    <table class="table table-borderless">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">

                                                        <label>
                                                            Service Bond Duration
                                                        </label>
                                                    </div>
                                                    <div class="d-inline">

                                                        <select name="ServiceBondDuration" id="ServiceBondDuration"
                                                            class="form-select form-select-sm d-inline"
                                                            style="width: 130px;">
                                                            <option value="">Select</option>
                                                            <option value="One">1</option>
                                                            <option value="Two">2</option>
                                                            <option value="Three">3</option>
                                                            <option value="Four">4</option>
                                                            <option value="Five">5</option>
                                                            <option value="Six">6</option>
                                                            <option value="Seven">7</option>
                                                            <option value="Eight">8</option>
                                                            <option value="Nine">9</option>
                                                            <option value="Ten">10</option>
                                                        </select>
                                                        <span>Years</span>
                                                    </div>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <div class="form-check form-check-inline">

                                                        <label>
                                                            Service Bond Refund
                                                        </label>
                                                    </div>
                                                    <div class="d-inline">
                                                        &nbsp;
                                                        <input type="text" name="ServiceBondRefund"
                                                            id="ServiceBondRefund"
                                                            class="form-control form-control-sm d-inline"
                                                            style="width: 130px;" value="50">
                                                        <span>% of CTC</span>
                                                    </div>
                                                </td>
                                            </tr>

                                        </tbody>
                                    </table>
                                </td>
                            </tr>

                            <tr>
                                <td>Pre-Medical Check-up</td>
                                <td>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="MedicalCheckup"
                                            id="MedicalCheckupYes" value="Yes">
                                        <label class="form-check-label" for="MedicalCheckupYes">Yes</label>
                                    </div>
                                    <div class="form-check form-check-inline">
                                        <input class="form-check-input" type="radio" name="MedicalCheckup"
                                            id="MedicalCheckupNo" value="No" checked>
                                        <label class="form-check-label" for="MedicalCheckupNo">No</label>
                                    </div>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    Signing Authority
                                </td>
                                <td>
                                    <select name="SignAuth" id="SignAuth" class="form-select form-select-sm"
                                        style="width: 170px">
                                        <option value=""></option>
                                        <option value="General Manager HR">General Manager HR</option>
                                        <option value="Managing Director">Managing Director</option>
                                        <option value="Director">Director</option>
                                        <option value="Business Head">Business Head</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td>Remarks / Reason for Rivision</td>
                                <td>
                                    <input type="text" name="Remark" id="Remark"
                                        class="form-control form-control-sm">
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger btn-sm" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Save changes</button>
                </div>
            </div>
        </form>
    </div>

</div>

<div id="other_seed_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Relatives or acquaintances is/are working or associated with any other Seed
                    Company</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="OtherSeedForm" action="{{ route('Candidate_Other_Seed_Relation_Save') }}"
                    method="POST">
                    <input type="hidden" name="OtherSeed_JCId" id="OtherSeed_JCId">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <td>Name</td>
                                    <td>Mobile No</td>
                                    <td>Email</td>
                                    <td>Company Name</td>
                                    <td>Designation</td>
                                    <td>Location</td>
                                    <td>Your Relationship <br>with person mentioned
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="OtherSeed">
                                <tr>
                                    <td><input type="text" name="OtherSeedName[]" id="OtherSeedName1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedMobile[]" id="OtherSeedMobile1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedEMail[]" id="OtherSeedEMail1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedCompany[]" id="OtherSeedCompany1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedDesignation[]"
                                            id="OtherSeedDesignation1" class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedLocation[]" id="OtherSeedLocation1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td><input type="text" name="OtherSeedRelation[]" id="OtherSeedRelation1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions"><a href="javascript:;" class="ms-3"
                                                id="removeOtherSeed"><i class="bx bxs-trash text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <input type="button" value="Add Reference" id="addOtherSeed"
                        class="btn btn-primary btn-sm">
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div id="resume_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog  modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Resume</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                @php
                    $resume = $Rec->Resume;
                    $ext = substr($resume, strrpos($resume, '.') + 1);
                @endphp
                @if ($ext == 'pdf' || $ext == 'PDF')
                    <object width="760" height="500"
                        data="{{ URL::to('/') }}/uploads/Resume/{{ $Rec->Resume }}"
                        id="{{ $Rec->JCId }}"></object>
                @else
                    @php
                        $url = html_entity_decode('https://docs.google.com/viewer?embedded=true&url=');
                    @endphp
                    <iframe src="{{ $url }}{{ URL::to('/') }}/uploads/Resume/{{ $Rec->Resume }}"
                        width="100%" height="500" style="border: none;"></iframe>
                @endif

                <div class="row">
                    <div class="col-12" style="float: right">
                        <a href="{{ URL::to('/') }}/uploads/Resume/{{ $Rec->Resume }}">Download</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<div id="language_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered " role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Language Proficiency</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <input type="hidden" name="Language_JCId" id="Language_JCId">
                <div class="table-responsive">
                    <table class="table table-bordered text-center" style="vertical-align: middle">
                        <thead class="text-center">
                            <tr>
                                <td>Language</td>
                                <td>Reading</td>
                                <td>Writing</td>
                                <td>Speaking</td>
                                <td style="width:30px;"></td>
                            </tr>
                        </thead>
                        <tbody id="LanguageData">
                            <tr>
                                <td>
                                    <input type="text" id="Language1" class="form-control form-control-sm"
                                        value="Hindi" readonly>
                                </td>
                                <td>
                                    <input type="checkbox" id="Read1" value="0">
                                </td>
                                <td>
                                    <input type="checkbox" id="Write1" value="0">
                                </td>
                                <td>
                                    <input type="checkbox" id="Speak1" value="0">
                                </td>
                                <td></td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="text" id="Language2" class="form-control form-control-sm"
                                        value="English" readonly>
                                </td>
                                <td>
                                    <input type="checkbox" id="Read2" value="0">
                                </td>
                                <td>
                                    <input type="checkbox" id="Write2" value="0">
                                </td>
                                <td>
                                    <input type="checkbox" id="Speak2" value="0">
                                </td>
                                <td></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <input type="button" value="Add Language" id="addLanguage" class="btn btn-primary btn-sm">
                <div class="submit-section">
                    <button class="btn btn-primary" id="save_language">Submit</button>
                </div>

            </div>
        </div>
    </div>

</div>
<div id="pre_org_ref_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Previous Organization Reference</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="PreOrgRefForm" action="{{ route('Candidate_PreOrgRef_Save') }}" method="POST">
                    <input type="hidden" name="PreOrgRef_JCId" id="PreOrgRef_JCId">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <td>Name</td>
                                    <td>Name of Company</td>
                                    <td>Email Id</td>
                                    <td>Contact No</td>
                                    <td>Designation</td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="PreOrgRefData">
                                <tr>
                                    <td>
                                        <input type="text" name="PreOrgName[]" id="PreOrgName1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="PreOrgCompany[]" id="PreOrgCompany1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="PreOrgEmail[]" id="PreOrgEmail1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="PreOrgContact[]" id="PreOrgContact1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="PreOrgDesignation[]" id="PreOrgDesignation1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <input type="button" value="Add Reference" id="addPreOrgRef"
                        class="btn btn-primary btn-sm">
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div id="vnr_ref_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Acquaintances or relatives working with VNR Group Companies</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="VNRRefForm" action="{{ route('Candidate_VnrRef_Save') }}" method="POST">
                    <input type="hidden" name="Vnr_JCId" id="Vnr_JCId">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <td>Name</td>
                                    <td>Mobile No</td>
                                    <td>Email</td>
                                    <td>VNR Group <br>Company Name</td>
                                    <td>Designation</td>
                                    <td>Location</td>
                                    <td>Your Relationship <br>with person mentioned
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="VNRRefData">
                                <tr>
                                    <td>
                                        <input type="text" name="VnrRefName[]" id="VnrRefName1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefContact[]" id="VnrRefContact1"
                                            class="form-control form-control-sm">
                                    </td>

                                    <td>
                                        <input type="text" name="VnrRefEmail[]" id="VnrRefEmail1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <select name="VnrRefCompany[]" id="VnrRefCompany1"
                                            class="form-select form-select-sm" onchange="GetOtherCompany(1);">
                                            <option value="">Select</option>
                                            <option value="VNR Seeds Pvt. Ltd.">VNR
                                                Seeds Pvt. Ltd.
                                            </option>
                                            <option value="VNR Nursery Pvt. Ltd.">
                                                VNR Nursery Pvt. Ltd.
                                            </option>
                                            <option value="Other">Other</option>
                                        </select>

                                        <input type="text" name="OtherCompany[]" id="OtherCompany1"
                                            class="d-none form-control form-control-sm"
                                            placeholder="Other Company Name">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefDesignation[]" id="VnrRefDesignation1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefLocation[]" id="VnrRefLocation1"
                                            class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefRelWithPerson[]"
                                            id="VnrRefRelWithPerson1" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <div class="d-flex order-actions"><a href="javascript:;" class="ms-3"
                                                id="removeVnrRef"><i class="bx bxs-trash text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <input type="button" value="Add Reference" id="addVnrRef" class="btn btn-primary btn-sm">
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>

<div id="vnr_business_ref_modal" class="modal custom-modal fade" role="dialog" data-bs-backdrop="static"
    data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered modal-xl" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h6 class="modal-title">Acquaintances or relatives associated with VNR as business associates</h6>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="BusinessForm" action="{{ route('Candidate_VnrRef_Business_Save') }}" method="POST">
                    <input type="hidden" name="Business_JCId" id="Business_JCId">
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="text-center">
                                <tr>
                                    <td>Name</td>
                                    <td>Mobile No</td>
                                    <td>Email</td>
                                    <td>Business relation with <br>VNR</td>
                                    <td>Location of Business / <br>acquaintances
                                    </td>

                                    <td>Your Relationship <br>with person mentioned
                                    </td>
                                    <td></td>
                                </tr>
                            </thead>
                            <tbody id="VNR_Business_AcqData">
                                <tr>
                                    <td>
                                        <input type="text" name="VnrRefBusiness_Name[]"
                                            id="VnrRefBusiness_Name1" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefBusiness_Contact[]"
                                            id="VnrRefBusiness_Contact1" class="form-control form-control-sm">
                                    </td>

                                    <td>
                                        <input type="text" name="VnrRefBusiness_Email[]"
                                            id="VnrRefBusiness_Email1" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <select name="VnrRefBusinessRelation[]" id="VnrRefBusinessRelation1"
                                            class="form-select form-select-sm">
                                            <option value="">Select</option>
                                            <option value="Dealer">Dealer</option>
                                            <option value="Distributor">Distributor
                                            </option>
                                            <option value="Retailer">Retailer
                                            </option>
                                            <option value="Organizer">Organizer
                                            </option>
                                            <option value="Vendor">Vendor</option>

                                        </select>
                                    </td>

                                    <td>
                                        <input type="text" name="VnrRefBusiness_Location[]"
                                            id="VnrRefBusiness_Location1" class="form-control form-control-sm">
                                    </td>
                                    <td>
                                        <input type="text" name="VnrRefBusiness_RelWithPerson[]"
                                            id="VnrRefBusiness_RelWithPerson1" class="form-control form-control-sm">
                                    </td>


                                    <td>
                                        <div class="d-flex order-actions"><a href="javascript:;" class="ms-3"
                                                id="removeVnrRef_Business"><i
                                                    class="bx bxs-trash text-danger"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <input type="button" value="Add Reference" id="addVnrRef_Business"
                        class="btn btn-primary btn-sm">
                    <div class="submit-section">
                        <button class="btn btn-primary submit-btn">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</div>