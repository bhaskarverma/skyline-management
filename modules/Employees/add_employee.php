 <!-- Main content -->
        
<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">New Employee</h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form role="form" action="/modules/Employees/add_emp_process.php" method="post">
    <div class="card-body">
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label for="emp_full_name">Name of Employee</label>
            <input type="text" class="form-control" id="emp_full_name" name="emp_full_name" placeholder="Full Name">
          </div>
          <div class="col-md-6">
            <label for="user_full_name">Brought By</label>
            <input type="text" class="form-control" id="brought_by" name="brought_by" placeholder="Brought By">
          </div>
        </div>
      </div>
      <div class="form-group">
        <div class="row">
          <div class="col-md-6">
            <label for="aadhaar_no">Aadhaar No.</label>
            <input type="number" class="form-control" id="aadhaar_no" name="aadhaar_no" placeholder="Aadhaar No.">
          </div>
          <div class="col-md-6">
            <label for="license_no">License No.</label>
            <input type="number" class="form-control" id="license_no" name="license_no" placeholder="License No.">
          </div>
        </div>
      </div>
      <div class="form-group">
      <label>Role</label>
      <select name="role" id="select2-role" data-placeholder="Select a Role" style="width: 100%;">
        <option></option>
        <option value="DRIVER">Driver</option>
        <option value="STAFF">Staff</option>
      </select>
    </div>
    </div>
    <!-- /.card-body -->

    <div class="card-footer">
      <button type="submit" class="btn btn-primary">Submit</button>
    </div>
  </form>
</div>
<!-- /.card -->
<script>
  $(function () {
    $('#select2-role').select2();
  })
</script>