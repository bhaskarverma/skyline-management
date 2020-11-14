<?php

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->query("SELECT round_trip_id FROM round_trip WHERE on_road = true");
$all_on_road_round_trips = $sql->fetchAll();

$res_on_road = array();
$res_ready = array();

foreach($all_on_road_round_trips AS $trip)
{
    $sql = $pdo->prepare("SELECT MAX(trip_id) AS tid FROM trip_round_trip_xref WHERE round_trip_id = ? GROUP BY round_trip_id");
    $sql->execute([$trip['round_trip_id']]);
    $trip_id = $sql->fetch()['tid'];

    $sql = $pdo->prepare("SELECT * FROM trips WHERE trip_id = ?");
    $sql->execute([$trip_id]);
    $tmp = $sql->fetch();
    if($tmp['trip_end'] == '0000-00-00')
    {
      array_push($res_on_road,$tmp);
    }
    else
    {
      array_push($res_ready,$tmp);
    }
}

function isBreakdown($vehicle)
{
    global $pdo;
    $sql = $pdo->prepare("SELECT `breakdown` FROM `vehicles` WHERE `vehicle_no` = ?");
    $sql->execute([$vehicle]);
    $res = $sql->fetch();

    if($res['breakdown'])
    {
      return true;
    }
    else
    {
      return false;
    }
}

?>

<!-- Card -->
<a href="<?php echo '/?module=Trips&page=Trip New&trip_id=new' ?>" type="button" class="btn btn-primary mb-3 ml-1">Start New Trip</a>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Trips On-Route</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="trips_all_tbl" class="table table-bordered">
      <thead>                  
        <tr>
          <th>Vehicle No.</th> 
          <th>Driver</th>
          <th>From</th> 
          <th>To</th> 
          <th>Start Date</th>
          <th>Current Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

                for($i=0;$i<count($res_on_road);$i++)
                {
                    echo '<tr>';   
                    echo '<td>'.$res_on_road[$i]['vehicle'].'</td>'; 
                    echo '<td>'.$res_on_road[$i]['driver'].'</td>';
                    echo '<td>'.$res_on_road[$i]['trip_from'].'</td>';
                    echo '<td>'.$res_on_road[$i]['trip_to'].'</td>';
                    echo '<td>'.$res_on_road[$i]['trip_start'].'</td>';
                    if(isBreakdown($res_on_road[$i]['vehicle']))
                    {
                      echo '<td>Breakdown</td>';
                    }
                    else
                    {
                      echo '<td>'.$res_on_road[$i]['current_status'].'</td>';
                    }                
                    echo '<td><a href="#" onclick="add_fuel('.$res_on_road[$i]['trip_id'].')" type="button" class="btn btn-primary">Add Fuel</a>';
                    if(!isBreakdown($res_on_road[$i]['vehicle']))
                    {
                      echo '<a href="#" onclick=\'update_status("'.$res_on_road[$i]['trip_id'].'")\' type="button" class="ml-3 btn btn-primary">Update Status</a><a href="#" onclick=\'endT("'.$res_on_road[$i]['trip_id'].'","'.strtoupper($res_on_road[$i]['trip_to']).'")\' type="button" class="ml-3 btn btn-primary">End Trip</a>';
                    }
                    echo '</td>';
                    echo '</tr>';
                }
                ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
<!-- /.card -->
</div>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Vehicles Ready for Next Trip</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="vehicle_ready_tbl" class="table table-bordered">
      <thead>                  
        <tr>
          <th>Vehicle No.</th> 
          <th>Driver</th>
          <th>From</th> 
          <th>To</th> 
          <th>Start Date</th>
          <th>Current Status</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

                for($i=0;$i<count($res_ready);$i++)
                {
                    echo '<tr>';   
                    echo '<td>'.$res_ready[$i]['vehicle'].'</td>'; 
                    echo '<td>'.$res_ready[$i]['driver'].'</td>';
                    echo '<td>'.$res_ready[$i]['trip_from'].'</td>';
                    echo '<td>'.$res_ready[$i]['trip_to'].'</td>';
                    echo '<td>'.$res_ready[$i]['trip_start'].'</td>';
                    if(isBreakdown($res_ready[$i]['vehicle']))
                    {
                      echo '<td>Breakdown</td>';
                    }
                    else
                    {
                      echo '<td>'.$res_ready[$i]['current_status'].'</td>';
                    }  
                    echo '<td><a href="#" onclick=\'continue_trip("'.$res_ready[$i]['trip_id'].'")\' type="button" class="btn btn-primary">Continue Trip</a></td>';
                    echo '</tr>';
                }
                ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
<!-- /.card -->
</div>
<!-- Scripts -->
<script>

  function continue_trip(trip_id)
  {
    window.location = "/?module=Trips&page=Trip New&trip_id=" + trip_id
  }

  function update_status(trip_id)
  {
    (async () => {

      const { value: formValues } = await Swal.fire({
        title: 'Update Status',
        html:
          'Status' +
            '<select class="form-control swal2-input" id="status_upd">'+
                '<option value=""></option>'+
                '<option value="Empty">Empty</option>'+
                '<option value="Sent for Loading">Sent for Loading</option>'+
                '<option value="Papers Received">Papers Received</option>'+
                '<option value="Diesel and Advance Received">Diesel and Advance Received</option>'+
                '<option value="Departed">Departed</option>'+
                '<option value="Waiting for Diesel / Cash">Waiting for Diesel / Cash</option>'+
                '<option value="Reached Destination">Reached Destination</option>'+
                '<option value="Papers Submitted">Papers Submitted</option>'+
                '<option value="Sample Taken">Sample Taken</option>'+
                '<option value="Waiting for Unload">Waiting for Unload</option>'+
                '<option value="Unloading Started">Unloading Started</option>'+
                '<option value="Unloaded">Unloaded</option>'+
                '<option value="Receiving Received">Receiving Received</option>'+
            '</select>',
        focusConfirm: false,
        preConfirm: () => {
          return [
            document.getElementById('status_upd').value
          ]
        }
      })

      if (formValues) {
        console.log(formValues);
        fetch("/modules/Trips/update_trip_status.php" , {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'status='+formValues[0]+'&trip_id='+trip_id
        })
        .then(
            function(response)
            {
              if(response.status !== 200)
              {
                Swal.fire("Error Connecting to Server.");
                return;
              }

              response.json().then(
                  function(data)
                  {
                    Swal.fire(data.result);
                  }
                )
            }
          )
          .catch(function(err) {
            Swal.fire("Unable to Connect to Server");
          })
      }

      })()
  }

  function add_fuel(trip_id)
  {
    (async () => {

      const { value: formValues } = await Swal.fire({
        title: 'Add Fuel',
        html:
          'Fuel Ltr' +
          '<input type="number" id="fuel_ltr_add" style="display: flex" class="swal2-input">' +
          'Fuel Money' +
          '<input type="number" id="fuel_money_add" style="display: flex" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
          return [
            document.getElementById('fuel_ltr_add').value,
            document.getElementById('fuel_money_add').value
          ]
        }
      })

      if (formValues) {
        console.log(formValues);
        fetch("/modules/Trips/add_fuel_to_trip.php" , {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'fuel_ltr='+formValues[0]+'&fuel_money='+formValues[1]+'&trip_id='+trip_id
        })
        .then(
            function(response)
            {
              if(response.status !== 200)
              {
                Swal.fire("Error Connecting to Server.");
                return;
              }

              response.json().then(
                  function(data)
                  {
                    Swal.fire(data.result);
                  }
                )
            }
          )
          .catch(function(err) {
            Swal.fire("Unable to Connect to Server");
          })
      }

      })()
  }

  function endRTrip(trip_id)
  {
    (async () => {

      const { value: formValues } = await Swal.fire({
        title: 'End Trip',
        html:
          'END KM' +
            '<input type="number" id="end_km" style="display: flex" class="swal2-input" >'+
          'Trip Expense' +
            '<input type="number" id="trip_expense" style="display: flex" class="swal2-input" >'+
          'Receiving Quantity' +
            '<input type="number" id="rec_quantity" style="display: flex" class="swal2-input" >'+
          'Penalty' +
            '<input type="number" id="penalty" style="display: flex" class="swal2-input" >',
        focusConfirm: false,
        preConfirm: () => {
          return [
            document.getElementById('end_km').value,
            document.getElementById('trip_expense').value,
            document.getElementById('rec_quantity').value,
            document.getElementById('penalty').value
          ]
        }
      })

      if (formValues) {
        console.log(formValues);
        fetch("/modules/Trips/end_trip.php" , {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'km_end='+formValues[0]+'&trip_expense='+formValues[1]+'&rec_quantity='+formValues[2]+'&penalty='+formValues[3]+'&trip_id='+trip_id
        })
        .then(
            function(response)
            {
              if(response.status !== 200)
              {
                Swal.fire("Error Connecting to Server.");
                return;
              }

              response.json().then(
                  function(data)
                  {
                    Swal.fire(data.result);
                  }
                )
            }
          )
          .catch(function(err) {
            Swal.fire("Unable to Connect to Server");
          })
      }

      })()
  }

  function endTrip(trip_id)
  {
    (async () => {

      const { value: formValues } = await Swal.fire({
        title: 'End Trip',
        html:
          'Trip Expense' +
            '<input type="number" id="trip_expense" style="display: flex" class="swal2-input" >'+
          'Receiving Quantity' +
            '<input type="number" id="rec_quantity" style="display: flex" class="swal2-input" >'+
          'Penalty' +
            '<input type="number" id="penalty" style="display: flex" class="swal2-input" >',
        focusConfirm: false,
        preConfirm: () => {
          return [
            document.getElementById('trip_expense').value,
            document.getElementById('rec_quantity').value,
            document.getElementById('penalty').value
          ]
        }
      })

      if (formValues) {
        console.log(formValues);
        fetch("/modules/Trips/end_trip.php" , {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'km_end=NA&trip_expense='+formValues[1]+'&rec_quantity='+formValues[2]+'&penalty='+formValues[3]+'&trip_id='+trip_id
        })
        .then(
            function(response)
            {
              if(response.status !== 200)
              {
                Swal.fire("Error Connecting to Server.");
                return;
              }

              response.json().then(
                  function(data)
                  {
                    Swal.fire(data.result);
                  }
                )
            }
          )
          .catch(function(err) {
            Swal.fire("Unable to Connect to Server");
          })
      }

      })()
  }

  function endT(trip_id,trip_to)
  {
    if(trip_to == "RAIPUR")
    {
      endRTrip(trip_id);
    }
    else
    {
      endTrip(trip_to);
    }
  }

  $(function () {
    $("#trips_all_tbl").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });

  $(function () {
    $("#vehicle_ready_tbl").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });

</script>