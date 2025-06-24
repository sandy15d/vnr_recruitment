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

