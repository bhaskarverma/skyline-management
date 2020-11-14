<?php

$res = '';

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->query("SELECT * FROM `employees`");
$res = $sql->fetchAll();

?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Employees</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="employee-table" class="table table-bordered">
      <thead>                  
        <tr>
          <th>Employee Name</th>
          <th>Date of Joining</th>
          <th>Brought By</th>
          <th>Role</th>
        </tr>
      </thead>
      <tbody>
        <?php

            for($i=0;$i<count($res);$i++)
            {
                echo '<tr>';   
                echo '<td>'.$res[$i]['emp_name'].'</td>'; 
                echo '<td>'.$res[$i]['date_of_joining'].'</td>';
                echo '<td>'.$res[$i]['brought_by'].'</td>'; 
                echo '<td>'.$res[$i]['role'].'</td>';
                echo '</tr>';
            }
        ?>
      </tbody>
    </table>
  </div>
  <!-- /.card-body -->
</div>

<script>

$(function () {
  $('#employee-table').DataTable({
    "responsive": true,
    "autoWidth": false,
  });
});

</script>