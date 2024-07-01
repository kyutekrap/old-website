<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

if (empty($_SESSION['rrm_username']) || empty($_SESSION['rrm_alias'])) {
    header("location: /rrm/login");
}

$item = substr($_GET['id'], 2);

$_TITLE = "";
$_SHORT_DESC = "";
$_BODY = "";
$_CATEGORY = "";

$getItem = "SELECT * FROM rrm_articles WHERE id = '".$item."'";
if ($gotItem = mysqli_query($conn, $getItem)) {
    if ($row = mysqli_fetch_assoc($gotItem)) {
        $_TITLE = $row['title'];
        $_SHORT_DESC = $row['short_desc'];
        $_BODY = $row['body'];
        $_CATEGORY = $row['category'];
    }
}

$category_types = array();

$getCategory = "SELECT category FROM rrm_articles GROUP BY category";
if ($gotCategory = mysqli_query($conn, $getCategory)) {
    while ($row = mysqli_fetch_assoc($gotCategory)) {
        array_push($category_types, $row['category']);
    }
}

if (isset($_POST['update'])) {
    $title = $_POST['name'];
    $short_desc = $_POST['short_desc'];
    $body = $_POST['body'];
    $category = $_POST['category'];
    $category_free = $_POST['category_free'];
    $updated_on = time();
    
    if ($category == "Others") {
        $category = $category_free;
    }
    $category = ucfirst($category.trim());
    
    $update = "UPDATE rrm_articles SET title = '".$title."', short_desc = '".$short_desc."', body = '".$body."', category = '".$category."', updated_on = '".$updated_on."' WHERE id = '".$item."'";
    if (mysqli_query($conn, $update)) {
        header("location: ../?id=".$_GET['id']);
    }
}

if (isset($_POST['publish'])) {
    $deleteQuery = "DELETE FROM rrm_articles WHERE id = ?";

    if ($deleteStmt = mysqli_prepare($conn, $deleteQuery)) {
        mysqli_stmt_bind_param($deleteStmt, "i", $item);
        if (mysqli_stmt_execute($deleteStmt)) {
            header("location: /rrm/knowledge");
        }
        mysqli_stmt_close($deleteStmt);
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Edit Article | RRM</title>
        <link rel="icon" href="../../../RRM.png" />
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
                margin-top: 80px;
                margin-left: 30px;
                margin-right: 30px;
                width: calc(100% - 60px);
            }
            
            .modern-table {
              width: 100%;
              border-collapse: collapse;
              border-radius: 8px;
              box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
              font-family: Arial, sans-serif;
            }
            
            .modern-table th,
            .modern-table td {
              padding: 12px;
              text-align: left;
            }
            
            .modern-table th {
              background-color: #6a1b9a;
              color: white;
              font-weight: 300;
            }
            
            .modern-table th:first-child {
              border-top-left-radius: 8px;
            }
            
            .modern-table th:last-child {
              border-top-right-radius: 8px;
            }

            .modern-table tr:nth-child(even) {
              background-color: #f2f2f2;
            }
            
            .modern-table td a {
                text-decoration: underline;
                color: #8c3fa5;
                cursor: pointer;
            }
            
            .h3 {
                font-size: 1.2rem;
            }
            
            .form-container {
              width: calc(100% - 60px);
              max-width: 600px;
              margin-left: 30px;
              margin-top: 80px;
            }
            
            .modern-form {
              background-color: white;
              padding: 20px;
              border-radius: 8px;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .modern-form h2 {
              margin-top: 0;
              margin-bottom: 20px;
              color: #333;
            }
            
            .form-group {
              margin-bottom: 20px;
            }
            
            label {
              display: block;
              margin-bottom: 5px;
              color: #555;
            }
            
            input[type="text"],
            textarea {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              transition: 0.2s all;
            }
            
            textarea {
                resize: none;
                height: 300px;
            }
            
            input[type="text"]:focus,
            .custom-select select:focus,
            textarea:focus {
                outline: 1px solid #7a2fbc;
            }

            button {
              margin-top: 40px;
              padding: 6px 10px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
              font-size: 1rem;
            }
            button:hover {
              background-color: #8c3fa5;
            }
            
            .custom-select select {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              appearance: none; /* Hide default arrow */
              -webkit-appearance: none;
              -moz-appearance: none;
              background-color: white;
              cursor: pointer;
            }
            
            /* Custom styles for the select */
            .custom-select::before {
              content: '\f078'; /* Unicode for down arrow */
              font-family: 'Font Awesome 5 Free'; /* Ensure Font Awesome is loaded */
              position: absolute;
              right: 10px;
              top: 50%;
              transform: translateY(-50%);
              pointer-events: none;
            }
            
            .custom-select select:focus + ::before {
              color: #6a1b9a; /* Change arrow color on focus */
            }
            
            .publish_btn {
                background: #D3D3D3;
                color: black;
            }
            
            @media screen and (max-width: 500px) {
                .form-container {
                  width: calc(100% - 20px);
                  margin-left: 10px;
                  margin-top: 60px;
                }
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../../../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div class="dropdown">
              <button class="dropbtn">Your Name</button>
              <div class="dropdown-content">
                <a href="#">Settings</a>
                <a href="#">Logout</a>
              </div>
            </div>
        </div>
        
        <div class="form-container">
            <form class="modern-form" method="post">
              <h2>Edit Article</h2>
              <div class="form-group">
                <label for="name">Title</label>
                <input type="text" name="name" required value="<?php echo $_TITLE; ?>" maxlength=30>
              </div>
              <div class="form-group">
                <label for="desc">Short Description</label>
                <input type="text" name="short_desc" required value="<?php echo $_SHORT_DESC; ?>" maxlength=120>
              </div>
              <div class="form-group">
                <label for="desc">Article Body</label>
                <textarea name="body" maxlength="2400"><?php echo $_BODY; ?></textarea>
              </div>
              <div class="form-group">
                <label for="message">Category</label>
                <div class="custom-select">
                    <select name="category" onchange="filter()" id="category">
                        <?php
                        echo '<option value="'.$_CATEGORY.'">'.$_CATEGORY.'</option>';
                        foreach ($category_types as $category) {
                            if ($category == $_CATEGORY) {
                                continue;
                            }
                            echo '<option value="'.$category.'">'.$category.'</option>';
                        }
                        ?>
                      <option value="Others">Others</option>
                    </select>
              </div>
              <div class="form-group" style="margin-top: 10px; display: none;" id="category_free">
                <input type="text" name="category_free" maxlength=30>
              </div>
              
              <button type="submit" name="update">Update</button>

              <button type="submit" class="publish_btn" name="publish">Delete</button>
            </form>
        </div>

        <script>
            function filter() {
                if (document.getElementById("category").value == "Others") {
                    const element = document.getElementById("category_free");
                    element.style.display = "block";
                    element.setAttribute("required", "true");
                } else {
                    const element = document.getElementById("category_free");
                    element.style.display = "none";
                    element.setAttribute("required", "false");
                }
            }
        </script>
    </body>
</html>
