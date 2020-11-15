<?php

$res = '';

$pdo = new PDO($dsn, $user, $pass, $options);
$sql = $pdo->query("SELECT * FROM `manpower`");
$res = $sql->fetchAll();

?>

<div class="card">
  <div class="card-header">
    <h3 class="card-title">Man Powers</h3>
  </div>
  <!-- /.card-header -->
  <div class="card-body">
    <table id="mp-table" class="table table-bordered">
      <thead>                  
        <tr>
          <th>Man Power Name</th>
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
                echo '<td>'.$res[$i]['mp_name'].'</td>'; 
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
  $('#mp-table').DataTable({
    "responsive": true,
    "autoWidth": false,
  });
});

</script>