<?php

require("modules/core/database/db_config.php");

?>

<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">
      Add a New Vehicle
    </h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form role="form" method="post" id="add_new_vehicles_form">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="vehicle_no">Vehicle No.</label>
            <input type="text" class="form-control" name="vehicle_no" id="vehicle_no" placeholder="Vehicle No" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="financer">Financer</label>
            <input type="text" class="form-control" name="financer" id="financer" placeholder="Financer" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="vehicle_make">Make</label>
            <input type="text" class="form-control" name="vehicle_make" id="vehicle_make" name="vehicle_make" placeholder="Make" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="tot_wheels">No. Of Wheels</label>
            <input type="number" class="form-control" id="tot_wheels" name="tot_wheels" placeholder="No. Of Wheels" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="unladen_weight">Unladen Weight</label>
            <input type="number" class="form-control" onkeyup="update_net_weight()" step=".01" id="unladen_weight" name="unladen_weight" placeholder="Unladen Weight" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="vehicle_gvw">GVW</label>
            <input type="number" class="form-control" onkeyup="update_net_weight()" step=".01" id="vehicle_gvw" name="vehicle_gvw" placeholder="GVW" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="net_weight">Net Weight</label>
            <input type="text" class="form-control" id="net_weight" name="net_weight" placeholder="Net Weight" disabled=disabled required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="emi_amount">EMI Amount</label>
            <input type="number" class="form-control" id="emi_amount" step=".01" name="emi_amount" placeholder="EMI Amount" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="emi_date">EMI Date</label>
            <input type="number" min='0' max='31' class="form-control" id="emi_date" name="emi_date" placeholder="EMI Date" required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="bank_name">Bank Name</label>
            <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="Bank Name" required>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="bank_ac_no">Bank A/C No.</label>
            <input type="number" class="form-control" id="bank_ac_no" name="bank_ac_no" placeholder="Bank A/C No." required>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="emi_remaining">Emi Remaining</label>
            <input type="number" class="form-control" id="emi_remaining" name="emi_remaining" placeholder="Emi Remaining" required>
          </div>
        </div>
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
  
    function update_net_weight()
    {
        var gvw = $("#vehicle_gvw").val();
        var unladen = $("#unladen_weight").val();
        $("#net_weight").val(gvw - unladen);
    }

    $("#add_new_vehicles_form").on('submit', function(e) {
        e.preventDefault();
            $(':disabled').each(function(e) {
            $(this).removeAttr('disabled');
            });

        Swal.fire({
          title: 'Confirm Action : Add Vehicle',
          showCancelButton: true,
          confirmButtonText: 'Add Vehicle',
          showLoaderOnConfirm: true,
          preConfirm: function() {
            return fetch("/modules/Vehicles/add_vehicle_process.php", {
                body: new FormData(document.getElementById("add_new_vehicles_form")),
                header: {
                    "Content-Type": "application/x-www-form-urlencoded"
                },
                method: "post" 
            })
              .then(response => {
                if (!response.ok) {
                  throw new Error(response.statusText)
                }
                return response.json()
              })
              .catch(error => {
                Swal.showValidationMessage(
                  `Request failed: ${error}`
                )
              })
          },
          allowOutsideClick: () => !Swal.isLoading()
        })
        .then((result) => {
          Swal.fire(
              'Action : Vehicle Add',
              result.res,
              'success'
            )
        })
    });

</script>