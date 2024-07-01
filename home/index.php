<?php
session_start();

if (empty($_SESSION['rrm_alias']) || empty($_SESSION['rrm_username'])) {
    header("location: ../login");
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Portal Home | RRM</title>
        <link rel="icon" href="../RRM.png" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
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
                z-index: 3;
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
            
            .container {
                display: flex;
                justify-content: center;
                align-items: center;
                padding-left: 30px; 
                padding-right: 30px;
                width: calc(100% - 60px);
                min-height: 100vh;
            }
            .middle_item {
                display: flex;
                justify-content: center;
                align-items: center;
                max-width: 1000px;
            }
            .items {
                margin-top: 30px;
            }
            .item {
                border-radius: 10px;
                background: white;
                padding: 30px;
                width: calc(33.33% - 100px);
                float: left;
                margin: 20px;
                box-shadow: 0 4px 8px 0 rgba(0,0,0,0.2);
            }
            .item i {
                font-size: 2rem;
            }
            .item_name {
                font-size: 1.2rem;
                font-weight: 600;
                margin-top: 30px;
            }
            .item_desc {
                font-size: 0.8rem;
            }
            
            .h3 {
                font-size: 1.2rem;
                font-weight: 600;
                text-align: center;
            }
            
            a {
                text-decoration: none;
                color: black;
            }
            
            @media screen and (max-width: 768px) {
                .item {
                    width: calc(100% - 100px);
                }
            }
            @media screen and (max-width: 500px) {
                .item {
                    width: calc(100% - 40px);
                    padding: 20px;
                    margin: 20 0 20 0;
                }
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
        
        <div class="container">
            <div class="middle_item">
                <div>
                
                    <div class="h3">Select a menu</div>
                    
                    <div class="items">
                        <div class="item">
                            <i class="fas fa-book"></i>
                            <a href="../knowledge">
                                <div class="item_name">Knowledge Base</div>
                            </a>
                            <br/>
                            <div class="item_desc">Write and respond to articles about relationships</div>
                        </div>
                        
                        <div class="item">
                            <i class="fas fa-folder"></i>
                            <a href="../contact">
                                <div class="item_name">Contact</div>
                            </a>
                            <br/>
                            <div class="item_desc">Manage contact methods to characterize your opportunity</div>
                        </div>
                        
                        <div class="item">
                            <i class="fas fa-search"></i>
                            <a href="../l&o">
                                <div class="item_name">Lead & Opportunity</div>
                            </a>
                            <br/>
                            <div class="item_desc">Track potential lover and contact history as lead and opportunity</div>
                        </div>
                        
                        <div class="item">
                            <i class="fas fa-chart-bar"></i>
                            <a href="../reporting">
                                <div class="item_name">Reporting</div>
                            </a>
                            <br/>
                            <div class="item_desc">Visualize performance of creation of new lead and opportunity</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>
