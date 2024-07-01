<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RRM Portal</title>
        <link rel="icon" href="RRM.png" />
        
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
        
        <style>
            * {
                margin: 0;
                padding: 0;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                overflow-x: hidden;
                scroll-behavior: smooth;
            }
            .first_page {
                width: calc(100vw - 60px);
                min-height: 80vh;
                justify-content: center;
                align-items: center;
                display: flex;
                padding-left: 30px;
                padding-right: 30px;
                background-image: url('banner.png');
                background-size: cover;
                background-position: center;
            }
            .middle_content {
                
            }
            .h1 {
                font-size: 2.6rem;
                font-weight: 600;
            }
            .h3 {
                font-size: 1.4rem;
                width: 50%;
            }
            .big_btn {
                padding: 10px 20px;
                font-size: 1.2rem;
                text-decoration: none;
                color: white;
                border: none;
                border-radius: 5px;
                background-color: #6a1b9a;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }

            .big_btn:hover {
                background-color: #4a148c;
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
    
            .login-btn {
                padding: 8px 20px;
                color: white;
                font-size: 0.8rem;
                border: none;
                border-radius: 5px;
                background-color: #6a1b9a;
                cursor: pointer;
                transition: background-color 0.3s ease;
            }
    
            .login-btn:hover {
                background-color: #4a148c;
            }
            
            .second_page {
                width: calc(100vw - 60px);
                min-height: 80vh;
                justify-content: center;
                align-items: center;
                display: flex;
                padding: 30px;
            }
            .features-container {
                float: left;
                margin-top: 30px;
                width: 60%;
                margin-left: 20%;
            }
            .feature {
                width: calc(33.33% - 100px);
                margin: 20px;
                background-color: #fff;
                border-radius: 8px;
                box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
                aspect-ratio: 4/5;
                padding-left: 30px;
                padding-right: 30px;
                position: relative;
                float: left;
            }
            .feature p {
                color: #333;
            }
            .feature_img {
                align-items: center;
                display: flex;
                width: 100%;
                height: 70%;
            }
            .feature_img i {
                font-size: 4rem;
                color: #6a1b9a;
            }
            .feature_txtbox {
                position: absolute;
                bottom: 40;
                left: 30;
                right: 30;
            }
            
            a {
                color: white;
                text-decoration: none;
            }
    
            /* Responsive styles */
            @media screen and (max-width: 768px) {
                .feature {
                    width: calc(100% - 60px);
                    margin: 30 0 0 0;
                }
                .features-container {
                    width: 80%;
                    margin-left: 10%;
                    margin-top: 10px;
                }
            }
    
            /* Responsive styles */
            @media screen and (max-width: 600px) {
                .logo img {
                    width: 30px;
                }

                .login-btn {
                    padding: 6px 15px;
                }
                
                .second_page {
                    padding-left: 10px;
                    padding-right: 10px;
                    width: calc(100% - 20px);
                }
            }
            
            footer {
                background-color: #333;
                color: #fff;
                text-align: center;
                padding: 20px 0 20px 0;
                position: relative;
                font-size: 12px;
            }
            .footer-content {
                display: flex;
                justify-content: center;
                align-items: center;
                flex-wrap: wrap;
            }
            .contact-details {
                margin: 5px;
            }
        </style>
    </head>
    <body>
        <div class="top-bar">
            <div class="logo">
                <img src="RRM.png" alt="Site Logo">
                <h2>RRM</h2>
            </div>
            
            <div>
                <button class="login-btn"><a href="./login">Client Login</a></button>
            </div>
        </div>
        
        <section class="first_page">
            <div class="middle_content">
                <div class="h1">Welcome to RRM Portal!</div>
                <br/>
                <div class="h3">RRM (Romantic Relationship Management) is a web-based management tool for revolutionizing love life through discovering leads and opportunities.</div>
                <br/>
                <div>
                    <button class="big_btn"><a href="./login">Client Login</a></button>
                </div>
            </div>
        </section>
        
        <section class="second_page">
            <div class="middle_content" style="width: 100%;">
                <div class="h1" style="text-align: center;">Features Introduction 101</div>
                <div class="features-container">
                    <article class="feature">
                        <div class="feature_img">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="feature_txtbox">
                            <h2>Knowledge</h2>
                            <br/>
                            <p>Freely upload and respond to articles in the knowledge base</p>
                        </div>
                    </article>
            
                    <article class="feature">
                        <div class="feature_img">
                            <i class="fas fa-sync"></i>
                        </div>
                        <div class="feature_txtbox" class="feature_txtbox">
                            <h2>Lifecycle</h2>
                            <br/>
                            <p>Track lifecycle stages of potential loved ones</p>
                        </div>
                    </article>
            
                    <article class="feature">
                        <div class="feature_img">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <div class="feature_txtbox">
                            <h2>KPI</h2>
                            <br/>
                            <p>Visualize performance of creation of new target lover and contact</p>
                        </div>
                    </article>
                </div>
            </div>
        </section>
        
        <footer>
            <div class="footer-content">
                <div class="contact-details">
                    <p>Email: katepark@dongkye.tech</p>
                </div>
            </div>
            <div class="copyright">
                <p>&copy; 2024 Dong Kye Technology. All rights reserved.</p>
            </div>
        </footer>
    </body>
</html>