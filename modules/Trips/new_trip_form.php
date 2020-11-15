<?php

$trip_id = $_GET['trip_id'];
$pdo = new PDO($dsn, $user, $pass, $options);

if($trip_id != "new")
{
  $sql = $pdo->prepare("SELECT round_trip_id FROM trip_round_trip_xref WHERE trip_id = ?");
  $sql->execute([$trip_id]);
  $round_trip_id = $sql->fetch()['round_trip_id'];
}
else
{
  $round_trip_id = "new";
}

$sql = $pdo->prepare("SELECT * FROM `manpower` WHERE `role` = 'DRIVER'");
$sql->execute();
$drivers = $sql->fetchAll();

$sql = $pdo->prepare("SELECT `vehicle_no` FROM `vehicles`");
$sql->execute();
$vehicles = $sql->fetchAll();

$sql = $pdo->prepare("SELECT `party_name`, `party_id` FROM `party_details`");
$sql->execute();
$party_details = $sql->fetchAll();

?>

 <!-- Main content -->
<!-- general form elements -->
<div class="card card-primary">
  <div class="card-header">
    <h3 class="card-title">
      <?php

          if($trip_id == "new")
          {
            echo 'Start a New Trip';
          }
          else
          {
            echo 'Continue Trip';
          }

          ?>
    </h3>
  </div>
  <!-- /.card-header -->
  <!-- form start -->
  <form role="form" method="post" action="/modules/Trips/add_new_trip.php">
    <div class="card-body">
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="trip_from">Trip From</label>
            <input type="text" class="form-control" name="trip_from" id="trip_from" placeholder="Trip From">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="trip_to">Trip To</label>
            <input type="text" class="form-control" name="trip_to" id="trip_to" placeholder="Trip To">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="material">Material</label>
            <input type="text" class="form-control" name="material" id="material" placeholder="Material">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="vehicle">Vehicle</label>
            <select class="form-control" id="vehicle" name="vehicle" data-placeholder="Select a Vehicle" style="width: 100%;">
            <option></option>
            <?php
                  for($i=0; $i<count($vehicles); $i++)
                  {
                    echo '<option value="'.$vehicles[$i]['vehicle_no'].'">'.$vehicles[$i]['vehicle_no'].'</option>';
                  }
            ?>
            </select>
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="driver">Driver</label>
              <select name="driver" class="form-control" id="driver" data-placeholder="Select a Driver" style="width: 100%;">
                  <option></option>
                  <?php
                  for($i=0; $i<count($drivers); $i++)
                  {
                      echo '<option value="'.$drivers[$i]['emp_name'].'">'.$drivers[$i]['emp_name'].'</option>';
                  }
                  ?>
              </select>
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="quantity">Material Quantity</label>
            <input type="number" class="form-control" id="quantity" name="quantity" placeholder="Material Quantity">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="rate">Rate</label>
            <input type="number" class="form-control" id="rate" name="rate" placeholder="Rate">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="fuel_money">Fuel (Money)</label>
            <input type="number" class="form-control" id="fuel_money" name="fuel_money" placeholder="Fuel (Money)">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="fuel_ltr">Fuel (Ltr)</label>
            <input type="number" class="form-control" id="fuel_ltr" name="fuel_ltr" placeholder="Fuel (Ltr)">
          </div>
        </div>
        <div class="col-lg-6">
          <div class="form-group">
            <label for="fuel_filled_by">Fuel Filled By</label>
              <select name="fuel_filled_by" class="form-control" id="fuel_filled_by" data-placeholder="Fuel Filled By" style="width: 100%;">
                  <option></option>
                  <option value="self">Self</option>
                  <option value="party">Paying Party</option>
              </select>
          </div>
        </div>
      </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="booking_party">Booking Party</label>
                    <select name="booking_party" class="form-control" id="booking_party" data-placeholder="Select a Booking Party" style="width: 100%;">
                        <option></option>
                        <?php
                        for($i=0; $i<count($party_details); $i++)
                        {
                            echo '<option value="'.$party_details[$i]['party_id'].'">'.$party_details[$i]['party_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="paying_party">Paying Party</label>
                    <select name="paying_party" class="form-control" id="paying_party" data-placeholder="Select a Paying Party" style="width: 100%;">
                        <option></option>
                        <?php
                        for($i=0; $i<count($party_details); $i++)
                        {
                            echo '<option value="'.$party_details[$i]['party_id'].'">'.$party_details[$i]['party_name'].'</option>';
                        }
                        ?>
                    </select>
                </div>
            </div>
        </div>
      <div class="row">
        <div class="col-lg-6">
          <div class="form-group">
            <label for="km_start">KM (Start)</label>
            <input type="number" class="form-control" id="km_start" name="km_start" placeholder="KM (Start)">
            <?php
                    echo '<input type="hidden" name="round_trip_id" value="'.$round_trip_id.'">';
             ?>
          </div>
        </div>
          <div class="col-lg-6">
              <div class="form-group">
                  <label for="trip_type">Trip Type</label>
                  <select name="trip_type" class="form-control" id="trip_type" data-placeholder="Select a Trip Type" style="width: 100%;">
                      <option></option>
                      <option value="line">Line</option>
                      <option value="local">Local</option>
                  </select>
              </div>
          </div>
      </div>
        <div class="row">
            <div class="col-lg-6">
                <div class="form-group">
                    <label for="freight">Freight</label>
                    <input type="number" class="form-control" id="freight" name="freight" placeholder="Freight">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                    <label for="trip_advance">Trip Advance</label>
                    <input type="number" class="form-control" id="trip_advance" name="trip_advance" placeholder="Trip Advance">
                </div>
            </div>
            <div class="col-lg-3">
                <div class="form-group">
                  <label for="fuel_filled_by">Advance By</label>
                    <select name="advance_by" class="form-control" id="advance_by" data-placeholder="Advance By" style="width: 100%;">
                        <option></option>
                        <option value="self">Self</option>
                        <option value="party">Paying Party</option>
                    </select>
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
  $(function () {
    $('#vehicle').select2();
    $('#driver').select2();
    $('#trip_type').select2();
    $('#booking_party').select2();
    $('#paying_party').select2();
    $('#fuel_filled_by').select2();
    $('#advance_by').select2();
  })

  //Calculation of Total Freight Start
 $("#rate").on("keyup", function() {
            var rate = $("#rate").val();
            var quantity = $("#quantity").val();

            if(rate == "")
            {
              rate = 0;
            }

            if(quantity == "")
            {
              quantity = 0;
            }

            if(!$.isNumeric(rate))
            {
              alert("Please Enter Only Integers as Rate");
              $("#rate").val("");
              return;
            }

            if(!$.isNumeric(quantity))
            {
              alert("Please Enter Only Integers as Material Quantity");
              $("#quantity").val("");
              return;
            }

            $("#freight").val(rate * quantity);            
          });

 $("#quantity").on("keyup", function() {
            var rate = $("#rate").val();
            var quantity = $("#quantity").val();

            if(rate == "")
            {
              rate = 0;
            }

            if(quantity == "")
            {
              quantity = 0;
            }

            if(!$.isNumeric(rate))
            {
              alert("Please Enter Only Integers as Rate");
              $("#rate").val("");
              return;
            }

            if(!$.isNumeric(quantity))
            {
              alert("Please Enter Only Integers as Material Quantity");
              $("#quantity").val("");
              return;
            }

            $("#freight").val(rate * quantity);
          });
 //Calculation of Total Freight End
</script>