<?php

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->query("SELECT * FROM `party_details`");
$party_details = $sql->fetchAll();

$res = array();

for($i=0;$i<count($party_details);$i++)
{
  $sql = $pdo->prepare("SELECT `balance` FROM `party_ledger` WHERE `party_id` = ?");
  $sql->execute([$party_details[$i]['party_id']]);
  $balance = $sql->fetch()['balance'];

  $tmp = ["party_id" => $party_details[$i]['party_id'], "party_name" => $party_details[$i]['party_name'], "party_contact_person" => $party_details[$i]['party_contact_person'], "party_contact_no" => $party_details[$i]['party_contact_no'], "party_city" => $party_details[$i]['party_city'], "balance" => $balance];

  array_push($res, $tmp);
}

?>

<!-- Card -->
<a href="#" onclick="add_new_party();" type=button class="btn btn-primary mb-3 ml-1">Add a New Party</a>
<div class="card">
  <div class="card-header">
    <h3 class="card-title">Party Ledger</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="party_details_tbl" class="table table-bordered">
      <thead>                  
        <tr>
          <th>Party Name</th> 
          <th>Balance Amount</th>
          <th>Action</th>
        </tr>
      </thead>
      <tbody>
        <?php

                for($i=0;$i<count($res);$i++)
                {
                    echo '<tr>';   
                    echo '<td>'.$res[$i]['party_name'].'</td>'; 
                    echo '<td>'.$res[$i]['balance'].'</td>';
                    echo '<td><a href="#" onclick=\'record_payment("'.$res[$i]['party_id'].'","'.$res[$i]['party_name'].'");\' type=button class="btn btn-primary">New Payment</a><a href="#" onclick=\'show_details("'.$res[$i]['party_name'].'","'.$res[$i]['party_contact_person'].'","'.$res[$i]['party_contact_no'].'","'.$res[$i]['party_city'].'");\' type=button class="btn btn-primary ml-3">Show Details</a></td>';
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

$(function () {
    $("#party_details_tbl").DataTable({
      "responsive": true,
      "autoWidth": false,
    });
  });


function add_new_party()
{
  (async () => {

      const { value: formValues } = await Swal.fire({
        title: 'New Party',
        html:
          'Party Name' +
          '<input type="text" id="party_name" style="display: flex" class="swal2-input">' +
          'Party Contact Person' +
          '<input type="text" id="party_contact_p" style="display: flex" class="swal2-input">' +
          'Party Contact No.' +
          '<input type="number" id="party_contact_n" style="display: flex" class="swal2-input">' +
          'Party City' +
          '<input type="text" id="party_city" style="display: flex" class="swal2-input">',
        focusConfirm: false,
        preConfirm: () => {
          return [
            document.getElementById('party_name').value,
            document.getElementById('party_contact_p').value,
            document.getElementById('party_contact_n').value,
            document.getElementById('party_city').value
          ]
        }
      })

      if (formValues) {
        fetch("/modules/Ledger/add_new_party.php" , {
          method: 'post',
          headers: {
            "Content-type": "application/x-www-form-urlencoded; charset=UTF-8"
          },
          body: 'name='+formValues[0]+'&cp='+formValues[1]+'&cn='+formValues[2]+'&city='+formValues[3]
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

function record_payment(id,name)
{
  Swal.fire({
    title: 'Record Payment for ' + name,
    input: 'number',
    showCancelButton: true,
    confirmButtonText: 'Record Payment',
    showLoaderOnConfirm: true,
    preConfirm: (amount) => {
      return fetch('/modules/Ledger/record_payment.php?party='+id+'&amount='+amount)
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
  }).then((result) => {
    Swal.fire({
        title: result.value.status,
        icon: 'success'
      })
  })
}

function show_details(name,contact_p,contact_n,city)
{
  var det = "<table style='margin:auto;text-align:left;padding-right:10rem'>";
  det += "<tr><td>Name:</td><td>"+name+"</td></tr>";
  det += "<tr><td>Contact Person:</td><td>"+contact_p+"</td></tr>";
  det += "<tr><td>Contact Name:</td><td>"+contact_n+"</td></tr>";
  det += "<tr><td>City:</td><td>"+city+"</td></tr>";
  det += "</table>";
  Swal.fire({
    html:det,
    icon:'info'
  });
}

</script>