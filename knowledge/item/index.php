<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

$item = substr($_GET['id'], 2);

$_TITLE = "";
$_CREATED_ON = "";
$_UPDATED_ON = "";
$_AUTHOR = "";

$created_by = "";

date_default_timezone_set('UTC');

$getData = "SELECT rrm_articles.*, rrm_users.alias AS author, rrm_users.username AS created_by FROM rrm_articles INNER JOIN rrm_users ON rrm_articles.created_by = rrm_users.id WHERE rrm_articles.id = '".$item."' LIMIT 1";
if ($gotData = mysqli_query($conn, $getData)) {
    if ($row = mysqli_fetch_assoc($gotData)) {
        $_TITLE = $row['title'];
        $_CREATED_ON = date('F j, Y', $row['created_on']);
        $_UPDATED_ON = $row['updated_on'];
        $_AUTHOR = $row['author'];
        $_BODY = $row['body'];
        $created_by = $row['created_by'];
    }
}

$_LIKES = 0;
$user_liked = false;

$getLikes = "SELECT * FROM rrm_articles_likes WHERE article = '".$item."'";
if ($gotLikes = mysqli_query($conn, $getLikes)) {
    while ($row = mysqli_fetch_assoc($gotLikes)) {
        $_LIKES ++;
        if (!empty($_SESSION['rrm_username']) && $row['username'] == $_SESSION['rrm_username']) {
            $user_liked = true;
        }
    }
}

if (isset($_POST['add_comment'])) {
    if (empty($_SESSION['rrm_username'])) {
        header("location: /RRM/login");
    }
    
    $content = $_POST['content'];
    $created_on = time();
    $username = $_SESSION['rrm_username'];
    
    $insertQuery = "INSERT INTO rrm_articles_comments (`content`, `created_on`, `article`, `username`) VALUES (?, ?, ?, ?)";

    if ($insertStmt = mysqli_prepare($conn, $insertQuery)) {
        mysqli_stmt_bind_param($insertStmt, "ssis", $content, $created_on, $item, $username);
        mysqli_stmt_execute($insertStmt);
        mysqli_stmt_close($insertStmt);
    }
}

$comments = array();

$getComments = "SELECT rrm_articles_comments.*, rrm_users.alias AS author FROM rrm_articles_comments INNER JOIN rrm_users ON rrm_articles_comments.username = rrm_users.username WHERE article = '".$item."' ORDER BY created_on ASC";
if ($gotComments = mysqli_query($conn, $getComments)) {
    while ($row = mysqli_fetch_assoc($gotComments)) {
        array_push($comments, array("content"=>$row['content'], "created_on"=>date('F j, Y', $row['created_on']), "author"=>$row['author'], "username"=>$row['username'], "id"=>$row['id']));
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Article | RRM</title>
        <link rel="icon" href="../../RRM.png" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            
            header {
              padding: 20px;
              text-align: center;
              margin-top: 60px;
            }
            
            h1 {
              margin-top: 0;
              margin-bottom: 10px;
            }
            
            .article-info {
              margin-top: 0;
              font-style: italic;
            }
            
            main.article-content {
              max-width: 800px;
              margin: auto;
              padding: 0 20px;
            }
            
            article {
              background-color: white;
              padding: 20px;
              border-radius: 8px;
              box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            p {
              line-height: 1.6;
              margin-bottom: 15px;
            }

            .article-likes {
              margin-top: 20px;
            }
            
            .article-likes button {
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
              margin-right: 10px;
            }
            
            .article-likes button:hover {
              background-color: #8c3fa5;
            }
            
            .like-count {
              font-weight: bold;
              font-size: 16px;
            }
            
            .comments {
              margin-top: 40px;
            }
            
            .comments h2 {
              margin-bottom: 20px;
              font-size: 1.5em;
              color: #333;
            }
            
            #commentText {
              width: calc(100% - 20px);
              padding: 10px;
              margin-bottom: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              resize: none;
            }
            
            .comments button {
              margin-top: 10px;
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
            }
            
            .comments button:hover {
              background-color: #8c3fa5;
            }
            
            .comment {
              margin-bottom: 20px;
              padding: 15px;
              border-radius: 8px;
              box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
              background-color: #f9f9f9;
            }
            
            .comment .comment-header {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-bottom: 8px;
            }
            
            .comment .comment-creator {
              font-weight: bold;
              color: #6a1b9a;
            }
            
            .comment .comment-date {
              color: #999;
              font-size: 0.8em;
            }
            
            .comment p {
              margin: 0;
              font-size: 0.9em;
              line-height: 1.4;
            }
            
            .comment-stream {
              max-width: 800px;
              margin: 0 auto;
            }
            
            textarea {
              width: 100%;
              padding: 10px;
              border: 1px solid #ccc;
              border-radius: 4px;
              transition: 0.2s all;
              resize: none;
              height: 100px;
            }
            
            textarea:focus {
                outline: 1px solid #7a2fbc;
            }
            
            .article-status {
              display: flex;
              justify-content: space-between;
              align-items: center;
              margin-top: 20px;
            }
            
            .approval-status {
              font-weight: bold;
            }
            
            .update-button {
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              color: white;
              cursor: pointer;
            }
            
            .update-button:hover {
              background-color: #8c3fa5;
            }
            
            .update-button a {
                color: white;
                text-decoration: none;
            }
            
            .del_container {
                display: flex;
                justify-content: flex-end;
            }
            .del_comment {
                background-color: #D3D3D3 !important;
                color: black !important;
            }
            
            .custom-alert {
              display: none;
              position: fixed;
              z-index: 1;
              left: 0;
              top: 0;
              width: 100%;
              height: 100%;
              overflow: auto;
              background-color: rgba(0, 0, 0, 0.4);
            }
            
            .custom-alert-content {
              background-color: #fefefe;
              margin: 15% auto;
              padding: 20px;
              border: 1px solid #888;
              width: 80%;
              max-width: 400px;
              border-radius: 5px;
              box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2);
            }
            
            .close {
              color: #aaaaaa;
              float: right;
              font-size: 28px;
              font-weight: bold;
              cursor: pointer;
            }
            
            .close:hover,
            .close:focus {
              color: #000;
              text-decoration: none;
            }
            
            .delete_btn {
              margin-top: 10px;
              padding: 10px 20px;
              border: none;
              border-radius: 4px;
              background-color: #D3D3D3;
              cursor: pointer;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="../../RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div class="dropdown">
                <?php
                if (empty($_SESSION['rrm_username'] || empty($_SESSION['rrm_alias']))) {
                    echo '
                      <button class="dropbtn">Guest</button>
                      <div class="dropdown-content">
                        <a href="../login">Login</a>
                      </div>';
                } else {
                    echo '
                      <button class="dropbtn">'.$_SESSION['rrm_alias'].'</button>
                      <div class="dropdown-content">
                        <a href="../settings">Settings</a>
                        <a href="../logout">Logout</a>
                      </div>';
                }
                ?>
            </div>
        </div>


      <header>
        <h1><?php echo $_TITLE; ?></h1>
        <span class="article-info">Published on <?php echo $_CREATED_ON; ?> by <?php echo $_AUTHOR; ?></span>
        <br/>
        <?php
        if ($_UPDATED_ON != "") {
            echo '<span class="article-info">Last Updated on '.date('F j, Y', $_UPDATED_ON).'</span>';
        }
        ?>
      </header>
    
      <main class="article-content">
        <article>
            <?php echo $_BODY; ?>
          <div class="article-likes">
                <button onclick="toggleLike(this)">
                    <?php
                    if ($user_liked) {
                        echo '<i id="likeIcon" class="far fa-thumbs-down"></i> <span id="likeText">Unlike</span>';
                    } else {
                        echo '<i id="likeIcon" class="far fa-thumbs-up"></i> <span id="likeText">Like</span>';
                    }
                    ?>
                </button>
            <span class="like-count" id="likeCount"><?php echo $_LIKES; ?></span> likes
          </div>
          
          <?php 
          if ($created_by == $_SESSION['rrm_username']) {
              echo '<div class="article-status">
                  <span class="approval-status"></span>
                  <button class="update-button"><a href="./edit?id='.$_GET['id'].'">Update</a></button>
              </div>';
          }
          ?>
        </article>
        
        <section class="comments">
          <h2>Comments</h2>
          <form id="commentForm" method="post">
            <textarea id="content" placeholder="Add your comment" rows="4" name="content" maxlength=240></textarea>
            <button type="submit" name="add_comment" id="add_comment"><i class="far fa-comment"></i> Add Comment</button>
          </form>
          <div class="comment-stream">
              <?php
              foreach ($comments as $index=>$value) {
                  echo '
                    <div class="comment">
                      <div class="comment-header">
                        <span class="comment-creator">'.$value['author'].'</span>
                        <span class="comment-date">'.$value['created_on'].'</span>
                      </div>
                      <p>'.$value['content'].'</p>';
                  
                  if (!empty($_SESSION['rrm_username']) && $value['username'] == $_SESSION['rrm_username']) {
                      $id = $value['id'];
                      echo '
                        <div class="del_container">
                            <button type="button" class="del_comment" onclick="showCustomAlert('.$id.')">Delete</button>
                        </div>
                      ';
                  }
                  
                  echo '
                    </div>';
              }
              ?>
          </div>
        </section>
        
      </main>
      
    <div id="customAlert" class="custom-alert">
      <div class="custom-alert-content">
        <span class="close" onclick="closeCustomAlert()">&times;</span>
        <p>Delete this record?</p>
        <button onclick="getResponse()" class="delete_btn">Yes</button>
      </div>
    </div>

    <script>
        let likes = document.getElementById("likeCount").innerHTML;
    
        function toggleLike(button) {
            var likeText = button.querySelector('#likeText');
            var likeIcon = button.querySelector('#likeIcon');
            
            if (likeText.innerHTML === 'Like') {
                
                $.ajax({
                    type: 'POST',
                    url: 'like.php',
                    data: {
                        article: <?php echo $item; ?>
                    },
                    success: function(response) {
                        if (response == 200) {
                          likeText.innerHTML = 'Unlike';
                          likeIcon.classList.remove('far', 'fa-thumbs-up');
                          likeIcon.classList.add('fas', 'fa-thumbs-down');
                          
                          likes ++;
                          document.getElementById("likeCount").innerHTML = likes;
                        } else if (response == 300) {
                            window.location.href = "/rrm/login";
                        }
                    },
                    error: function(xhr, status, error) {
                        //
                    }
                });
                
            } else {
                
                $.ajax({
                    type: 'POST',
                    url: 'unlike.php',
                    data: {
                        article: <?php echo $item; ?>
                    },
                    success: function(response) {
                        if (response == 200) {
                          likeText.innerHTML = 'Like';
                          likeIcon.classList.remove('fas', 'fa-thumbs-down');
                          likeIcon.classList.add('far', 'fa-thumbs-up');
                          
                          likes --;
                          document.getElementById("likeCount").innerHTML = likes;
                        }
                    },
                    error: function(xhr, status, error) {
                        //
                    }
                });
                
            }
        }
        
        let id = "";
        
        function showCustomAlert(x) {
          id = x;
          document.getElementById("customAlert").style.display = "block";
        }
        
        function closeCustomAlert() {
          document.getElementById("customAlert").style.display = "none";
        }
        
        function getResponse() {
            $.ajax({
                type: 'POST',
                url: 'delete_comment.php',
                data: {
                    id: id
                },
                success: function(response) {
                    if (response == 200) {
                        location.reload();
                    }
                },
                error: function(xhr, status, error) {
                    //
                }
            });
        }
    </script>
    </body>
</html>
