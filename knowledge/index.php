<?php
session_start();

$conn = mysqli_connect(
  '',
  '',
  '',
  '');

error_reporting(E_ERROR | E_PARSE);

$categories = array();
$articles = array();

date_default_timezone_set('UTC');

if (empty($_GET['category'])) {
    $getData = "SELECT rrm_articles.*, 
               COUNT(rrm_articles_likes.id) AS likes, 
               COUNT(rrm_articles_comments.id) AS comments, 
               rrm_users.alias AS author
            FROM rrm_articles
                LEFT JOIN rrm_articles_likes ON rrm_articles.id = rrm_articles_likes.article
                LEFT JOIN rrm_articles_comments ON rrm_articles.id = rrm_articles_comments.article
                INNER JOIN rrm_users ON rrm_articles.created_by = rrm_users.id
            GROUP BY rrm_articles.id
            ORDER BY rrm_articles.created_on DESC
            ";
} else {
    $getData = "SELECT rrm_articles.*, 
               COUNT(rrm_articles_likes.id) AS likes, 
               COUNT(rrm_articles_comments.id) AS comments, 
               rrm_users.alias AS author
            FROM rrm_articles
                LEFT JOIN rrm_articles_likes ON rrm_articles.id = rrm_articles_likes.article
                LEFT JOIN rrm_articles_comments ON rrm_articles.id = rrm_articles_comments.article
                INNER JOIN rrm_users ON rrm_articles.created_by = rrm_users.id
            WHERE rrm_articles.category = '".$_GET['category']."'
            GROUP BY rrm_articles.id
            ORDER BY rrm_articles.created_on DESC
            ";
}

if ($gotData = mysqli_query($conn, $getData)) {
    while ($row = mysqli_fetch_assoc($gotData)) {
        array_push($articles, array("id"=>$row['article_id'].$row['id'], "category"=>$row['category'], "title"=>$row['title'], "short_desc"=>$row['short_desc'], "author"=>$row['author'], "created_on"=>date("F j, Y", $row['created_on']), "updated_on"=>$row['updated_on'], "likes"=>$row['likes'], "comments"=>$row['comments']));
        if (!in_array($row['category'], $categories)) {
            array_push($categories, $row['category']);
        }
    }
}
?>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Knowledge Base | RRM</title>
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
            
            main {
              display: flex;
              flex-direction: column;
              margin-left: 30px;
              margin-right: 30px;
            }
            
            .sidebar {
              background-color: #6a1b9a;
              color: white;
              padding: 20px;
            }
            
            .category-list {
              list-style: none;
              padding: 0;
            }
            
            .category-list li {
              margin-bottom: 10px;
            }
            .category-list li a {
                color: white;
            }
            
            .articles {
              background-color: white;
              padding: 20px;
              margin: 20 0 20 0;
            }
            
            .article-list {
              list-style: none;
              padding: 0;
            }
            
            .article-list li {
              margin-bottom: 20px;
            }

            .header {
                margin-top: 70px;
                margin-left: 30px;
                margin-right: 30px;
            }
            
            .h3 {
                font-size: 1.2rem;
            }
            
            .new_button {
              margin-top: 20px;
              margin-bottom: 30px;
              padding: 6px 10px;
              border: none;
              border-radius: 4px;
              background-color: #6a1b9a;
              cursor: pointer;
              font-size: 1rem;
            }
            .new_button:hover {
              background-color: #8c3fa5;
            }
            
            .new_button a {
                color: white;
            }
            
            .article-category {
              color: #8c3fa5;
              font-size: 1rem;
              font-weight: 600;
            }
            
            .article-title {
              color: #333333;
              font-size: 1.6rem;
              font-weight: 600;
            }
            
            .article-info {
              color: #666666;
            }
            
            .article-description {
              color: black;
              margin-bottom: 2rem;
            }

            .article-metadata {
              display: flex;
              flex-wrap: wrap;
            }
            
            .metadata-item {
              display: flex;
              align-items: center;
              margin-right: 20px;
              margin-bottom: 10px;
            }
            
            .metadata-icon {
              margin-right: 5px;
              font-size: 16px;
            }
            
            .metadata-text {
              font-size: 14px;
            }
            
            .read-more {
              display: inline-block;
              color: #8c3fa5;
              text-decoration: none;
            }
            
            a {
                text-decoration: none;
            }

            /* Media queries for responsiveness */
            @media screen and (min-width: 768px) {
              main {
                flex-direction: row;
              }
            
              .sidebar {
                flex: 1;
                margin-right: 20px;
              }
            
              .articles {
                flex: 4;
                margin-top: 0;
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
        
        <div class="header">
            <div class="h3">Knowledge Base</div>
            <button class="new_button"><a href="./new">Create New</a></button>
        </div>
        
        <main>
            <aside class="sidebar">
              <h2>Categories</h2>
              <ul class="category-list">
                  <?php
                  foreach ($categories as $category) {
                      echo '<li><a href="/rrm/knowledge?category='.$category.'">'.$category.'</a></li>';
                  }
                  ?>
              </ul>
            </aside>
            
            <section class="articles">
              <h2>Articles</h2>
              
              <ul class="article-list">
                  <?php
                  foreach ($articles as $index=>$value) {
                      echo '<li>
                        <div class="article">
                          <div class="article-header">
                            <div class="article-category">Category: '.$value['category'].'</div>
                            <div class="article-title">'.$value['title'].'</div>
                          </div>
                          <div class="article-info">
                              <p class="article-description">'.$value['short_desc'].'</p>
                              <div class="article-metadata">
                                <div class="metadata-item">
                                  <span class="metadata-icon"><i class="fas fa-user"></i></span>
                                  <span class="metadata-text">Author: '.$value['author'].'</span>
                                </div>
                                <div class="metadata-item">
                                  <span class="metadata-icon"><i class="far fa-calendar-alt"></i></span>
                                  <span class="metadata-text">Created on: '.$value['created_on'].'</span>
                                </div>';
                                if ($value['updated_on'] != "") {
                                    echo '
                                    <div class="metadata-item">
                                      <span class="metadata-icon"><i class="far fa-calendar-check"></i></span>
                                      <span class="metadata-text">Updated on: '.date('F j, Y', $value['updated_on']).'</span>
                                    </div>';
                                }
                                echo'
                                <div class="metadata-item">
                                  <span class="metadata-icon"><i class="fas fa-thumbs-up"></i></span>
                                  <span class="metadata-text">Likes: '.$value['likes'].'</span>
                                </div>
                                <div class="metadata-item">
                                  <span class="metadata-icon"><i class="far fa-comments"></i></span>
                                  <span class="metadata-text">Comments: '.$value['comments'].'</span>
                                </div>
                              </div>
                            </div>
                          <a href="./item?id='.$value['id'].'" class="read-more">Read More</a>
                        </div>
                      </li>';
                  }
                  ?>
                </ul>

            </section>
        </main>

    </body>
</html>
