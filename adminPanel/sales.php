<?php 
$currentSubPage="sales";
include "admin.php"; ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<div class="card mt-5">
    <div class="card-body">
    <canvas id="myChart" style="width:100%;max-width:100%;max-height: 500px"></canvas>
    <?php 
    echo "
    <script>
    const ctx = document.getElementById('myChart');

    new Chart(ctx, {
      type: 'bar',
      data: {
        labels: ['Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange','Red', 'Blue', 'Yellow', 'Green', 'Purple', 'Orange'],
        datasets: [{
          label: '# of Votes',
          data: [12, 19, 3, 5, 2, 3,12, 19, 3, 5, 2, 3],
          borderWidth: 1
        }]
      },
      options: {
        scales: {
          y: {
            beginAtZero: true
          }
        }
      }
    });
    </script>
    
    ";
    
    ?>
    
    </div>
</div>

<?php include "adminFooter.php"; ?>