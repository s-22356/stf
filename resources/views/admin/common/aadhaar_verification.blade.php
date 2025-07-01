<!-- Modal -->
<div class="modal fade" id="aadhaarModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Verify Aadhaar Number</h4>
      </div>
      <div class="modal-body">
        <div class="container">
          <div class="row">
            <div class="col-md-6">
              <label for="aadhar_no" class="form-label"><b class="form_fields">Enter Aadhaar:</b></label>
              <input type="text" name="aadhar_no" class="form-control" id="aadhar_no" placeholder="e.g., 560073539780" required>
              <input type="hidden" name="adhr_refId" class="form-control" id="adhr_refId">
              <input type="hidden" name="adhr_asct_ph" class="form-control" id="adhr_asct_ph">
            </div>
          </div><br>
          <div class="row otp-div">
            <div class="col-md-6">
              <label for="otp" class="form-label"><b class="form_fields">Enter Otp:</b></label>
              <input type="text" name="otp" class="form-control" id="otp" placeholder="e.g., 876648">
            </div>
          </div>                 
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-lg px-5 py-2 auth-verify-aadhar-otp" style="background: #584090;color:white;display: none;" >Verify Otp</button>
        <button type="button" class="btn btn-lg px-5 py-2 auth-aadhar-otp" style="background: #584090;color:white">Get Otp</button>
      </div>
    </div>
  </div>
</div>