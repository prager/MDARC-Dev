<section id="learn" class="p-5">
    <div class="container">
        <div class="row align-items-center justify-content-between py-5">
          <div class="col">
            <h3>Search Result</h3>
            <table class="table table-hover table-bordered">
              <thead>
                <tr>
                  <th>Name</th>
                  <th>Current Year</th>
                  <th>Mem Type</th>
                  <th>Callsign</th>
                  <th>License</th>
                  <th>Mem Since</th>
                  <th>Email</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($mems as $key => $mem) {?>
                <tr>
                  <td>
                    <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#showMem<?php echo $mem['id']; ?>"><?php echo $mem['lname'] . ', ' . $mem['fname']; ?></a>
                  </td>
                  <?php include 'modal_show_mem.php'; ?>
                  <td><?php echo $mem['cur_year']; ?></td>
                   <td>
                     <?php echo $mem['mem_type']; ?>
                     <!-- Add modal call with family members -->
                   </td>
                   <td><?php echo $mem['callsign']; ?></td>
                   <td><?php echo $mem['license']; ?></td>
                   <td><?php echo $mem['mem_since']; ?></td>
                   <td><?php
                   if(strlen($mem['email']) > 30) {
                     echo substr($mem['email'], 0, 30) . '...';
                   }
                   else {
                     echo $mem['email'];
                   }?>
                 </td>
                </tr>
              <?php }?>
              </tbody>
            </table>
          </div>
      </div>
</section>