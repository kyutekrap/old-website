<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

if (empty($_SESSION['rrm_alias']) || empty($_SESSION['rrm_username'])) {
    header("location: /rrm/login");
}

date_default_timezone_set('UTC');

$lead = array();

$getLeads = "SELECT DATE_FORMAT(FROM_UNIXTIME(created_on), '%Y-%m') AS month_year, COUNT(*) AS leads 
        FROM rrm_leads
        WHERE created_by = '".$_SESSION['rrm_username']."'
        GROUP BY month_year 
        ORDER BY month_year ASC";
if ($gotLeads = mysqli_query($conn, $getLeads)) {
    while ($row = mysqli_fetch_assoc($gotLeads)) {
        array_push($lead, array('month_year'=>$row['month_year'], 'leads'=>$row['leads']));
    }
}

function preprocessing($data) {
    // Get the current year and month
    $currentYear = date("Y");
    $currentMonth = date("m");
    
    // Convert current year and month to a comparable format
    $currentMonthYear = $currentYear . "-" . $currentMonth;
    
    // Convert the array into an associative array with month_year as keys
    $dataMap = [];
    foreach ($data as $item) {
        $dataMap[$item["month_year"]] = $item["leads"];
    }
    
    // Initialize an array to store the result
    $result = [];
    
    // Iterate through months starting from the first month in the data array
    $startDate = new DateTime(reset($data)["month_year"]);
    $endDate = new DateTime($currentMonthYear);
    
    $currentDate = clone $startDate;
    while ($currentDate <= $endDate) {
        $monthYearStr = $currentDate->format("Y-m");
    
        // If the month_year exists in the original data, add it to the result
        if (array_key_exists($monthYearStr, $dataMap)) {
            $result[] = ["month_year" => $monthYearStr, "leads" => $dataMap[$monthYearStr], 'oppos'=>0];
        } else { // If the month_year is missing, add it with value 0
            $result[] = ["month_year" => $monthYearStr, "leads" => 0, 'oppos'=>0];
        }
    
        // Move to the next month
        $currentDate->modify("+1 month");
    }
    return $result;
}

$lead = preprocessing($lead);

$getOppo = "SELECT DATE_FORMAT(FROM_UNIXTIME(rrm_oppo.created_on), '%Y-%m') AS month_year, COUNT(*) AS oppos
            FROM rrm_oppo
            INNER JOIN rrm_leads ON rrm_oppo.lead = rrm_leads.id
            WHERE rrm_leads.created_by = '".$_SESSION['rrm_username']."'
            GROUP BY month_year 
            ORDER BY month_year ASC
            ";
if ($gotOppo = mysqli_query($conn, $getOppo)) {
    while ($row = mysqli_fetch_assoc($gotOppo)) {
        $fnd = 0;
        foreach ($lead as $k=>$v) {
            if ($v["month_year"] === $row['month_year']) {
                $lead[$k]['oppos'] = $row['oppos'];
                $fnd = 1;
            }
        }
        if ($fnd == 0) {
            array_push($lead, array('month_year'=>$row['month_year'], 'leads'=>0, 'oppos'=>$row['oppos']));
        }
    }
}

$labels = [];
$data1 = [];
$data2 = [];
foreach ($lead as $k=>$item) {
    $labels[] = $item['month_year'];
    $data1[] = $item['leads'];
    $data2[] = $item['oppos'];
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal Home | RRM</title>
        <link rel="icon" href="../RRM.png" />
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        <style>
            body {
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                background-color: #f2f2f2;
                padding: 0;
                margin: 0;
            }
            .top-bar {
                background-color: white;
                padding: 10px 30px;
                display: flex;
                justify-content: space-between;
                align-items: center;
                position: fixed;
                top: 0;
                width: calc(100vw - 60px);
                height: 30px;
            }
    
            .logo {
                display: flex;
                align-items: center;
            }
    
            .logo img {
                width: 40px;
                height: auto;
                margin-right: 10px;
            }
    
            /* Dropdown button */
            .dropbtn {
              background: transparent;
              padding: 12px;
              font-size: 0.8rem;
              border: none;
              cursor: pointer;
            }
            
            /* Dropdown content (hidden by default) */
            .dropdown-content {
              display: none;
              position: absolute;
              background-color: #f9f9f9;
              min-width: 120px;
              box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
              z-index: 1;
            }
            
            /* Links inside the dropdown */
            .dropdown-content a {
              color: black;
              padding: 12px 16px;
              text-decoration: none;
              display: block;
              font-size: 0.8rem;
            }
            
            /* Change color of dropdown links on hover */
            .dropdown-content a:hover {
              background-color: #ddd;
            }
            
            /* Show the dropdown menu on hover */
            .dropdown:hover .dropdown-content {
              display: block;
            }
            
            .parent {
                display: flex;
                justify-content: center;
                align-items: center;
                padding-bottom: 30px;
            }
            .container {
              background-color: white;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
              border-radius: 10px;
              padding: 20px;
              width: 100%;
              max-width: 600px;
              aspect-ratio: 4/2;
            }
            
            .form-container {
              max-width: 600px;
              margin: 70px auto 20px;
              background-color: #fff;
              border-radius: 8px;
              padding: 20px;
              box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            }
        
            .form-group {
              margin-bottom: 20px;
            }
        
            .form-group label {
              display: block;
              margin-bottom: 8px;
            }
        
            .form-group input[type="date"],
            .form-group select {
              width: calc(50% - 5px);
              padding: 10px;
              border-radius: 4px;
              border: 1px solid #ccc;
              margin-bottom: 10px;
              transition: 0.2s all;
            }
        
            .form-group input[type="checkbox"] {
              margin-right: 5px;
              margin-bottom: 5px;
              transition: 0.2s all;
            }
        
            .form-group .run-button {
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
            }
        
            .form-group .run-button:hover {
              background-color: #8c3fa5;
            }
            
            input[type="date"]:focus,
            input[type="checkbox"]:focus,
            select:focus {
                outline: 1px solid #7a2fbc;
            }
            
            .mega-container {
                padding: 0 20 0 20;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn"><?php echo $_SESSION['rrm_alias']; ?></button>
              <div class="dropdown-content">
                <a href="../settings">Settings</a>
                <a href="../logout">Logout</a>
              </div>
            </div>
        </div>
        
        <div class="mega-container">
            <div class="form-container">
                <form id="chartForm">
                  <div class="form-group">
                    <label for="chartType">Chart Type:</label>
                    <select id="chartType" name="chartType">
                      <option value="bar">Bar</option>
                      <option value="line">Line</option>
                    </select>
                  </div>
            
                  <div class="form-group">
                    <button type="submit" class="run-button">Run</button>
                  </div>
                </form>
            </div>
            
            <div class="parent">
                <div class="container">
                <canvas id="lineChart"></canvas>
                </div>
            </div>
        </div>

          <script>
            const chartForm = document.getElementById('chartForm');
            let chart = null;
            const ctx = document.getElementById('lineChart').getContext('2d');
            
             chartForm.addEventListener('submit', function(event) {
              event.preventDefault();
              const chartType = chartForm.chartType.value;

              if (chart) {
                chart.destroy();
              }
        
              if (chartType === 'line') {
                chart = new Chart(ctx, {
                  type: 'line',
                  data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                      {
                        label: 'New Leads',
                        data: <?php echo json_encode($data1); ?>,
                        borderColor: 'rgba(128,0,128, 1)',
                        borderWidth: 1,
                        fill: false
                      },
                      {
                        label: 'Opportunities',
                        data: <?php echo json_encode($data2); ?>,
                        borderColor: 'rgba(238,130,238, 1)',
                        borderWidth: 1,
                        fill: false
                      }
                    ]
                  },
                  options: {
                    responsive: true,
                    maintainAspectRatio: false
                  }
                });
              } else if (chartType === 'bar') {
                  chart = new Chart(ctx, {
                  type: 'bar',
                  data: {
                    labels: <?php echo json_encode($labels); ?>,
                    datasets: [
                      {
                        label: 'New Leads',
                        data: <?php echo json_encode($data1); ?>,
                        backgroundColor: 'rgba(138, 43, 226, 0.5)',
                        borderWidth: 1
                      },
                      {
                        label: 'Opportunities',
                        data: <?php echo json_encode($data2); ?>,
                        backgroundColor: 'rgba(218, 112, 214, 0.5)',
                        borderWidth: 1
                      }
                    ]
                  },
                  options: {
                    responsive: true,
                    maintainAspectRatio: false
                  }
                });
              }
              
             });
          </script>
          
    </body>
</html>
