<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Homepage</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f0f0f0;
            
        }
        .main-container {
            display: flex;
            max-width: 1200px;
            margin: 0 auto;
            gap: 20px;
            padding: 20px;
        }
        .tabs {
            display: flex;
            background-color: #4ECDC4;
            border-radius: 8px 8px 0 0;
        }

        .tab {
            flex: 1;
            padding: 12px;
            text-align: center;
            color: white;
            cursor: pointer;
            border-right: 1px solid rgba(255,255,255,0.3);
        }

        .tab:last-child {
            border-right: none;
        }

        .tab.active {
            background-color: #44A08D;
             border-radius: 8px 8px 0 0
        }

        .movies-grid {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 30px;
            padding: 30px;
            background-color: white;
            border-radius: 0 0 8px 8px;
        }

        .movie-card {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            transition: transform 0.3s;
            background-color: #f8f8f8;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .movie-card:hover {
            transform: translateY(-5px);
        }

        .movie-poster {
            width: 100%;
            height: 220px;
            background: linear-gradient(45deg, #FF6B35, #F7931E);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 14px;
            text-align: center;
        }

       .movie-info {
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            padding: 10px;
        }

     .movie-buttons {
            display: flex;
            justify-content: flex-end; 
            margin-top: auto;          
        }

        .btn-small {
            font-size: 10px;
            padding: 4px 8px;
            border-radius: 3px;
            border: none;
            cursor: pointer;
            text-decoration: none;
        }

        .btn-buy {
            background-color: #FF6B35;
            color: white;
        }

       

        .movie-name {
            font-size: 15px;
            font-weight: bold;
            color: #333;
        }

        .showing-time {
            font-size: 12px;
            font-weight: bold;
            color: #333;
        }

        @media (max-width: 768px) {
            .main-container {
                flex-direction: column;
            }
            
            .sidebar {
                width: 100%;
            }
            
            .movies-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .banner-section {
                flex-direction: column;
            }
        }
        .tab{
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="main-container">
        <main class="content-area">

            <div class="tabs">
                  <div class="tab active">NOW SHOWING</div>
                  <a href="aboutus.php" class="tab">ABOUT US</a>
            </div>


            <div class="movies-grid">
                <div class="movie-card">
                     <img src="images/1.jpg" alt="Final Destination Bloodlines" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Final Destination Bloodlines</div>
                         <div class="showing-time">June 20, 2025 9:00 AM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=final_destination" class="btn-small btn-buy">Book Now</a>

                        
                        </div>
                    </div>
                </div>

               <div class="movie-card">
                     <img src="images/2.jpg" alt="Lilo & Stitch" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Lilo & Stitch</div>
                         <div class="showing-time">June 20, 2025 12:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=lilo_stitch" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

               <div class="movie-card">
                     <img src="images/3.jpg" alt="Lost in Starlight" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Lost in Starlight</div>
                         <div class="showing-time">June 20, 2025 3:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=lost_in_starlight" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                 <div class="movie-card">
                     <img src="images/4.jpg" alt="From the World of John Wick: Ballerina" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">From the World of John Wick: Ballerina</div>
                         <div class="showing-time">June 20, 2025 6:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=ballerina" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                  <div class="movie-card">
                     <img src="images/5.jpg" alt="Karate Kid: Legends" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Karate Kid: Legends</div>
                         <div class="showing-time">June 21, 2025 9:00 AM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=karate_kid" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                  <div class="movie-card">
                     <img src="images/6.jpg" alt="How to Train Your Dragon" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">How to Train Your Dragon</div>
                         <div class="showing-time">June 21, 2025 12:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=how_to_train" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                     <div class="movie-card">
                     <img src="images/7.jpg" alt="Thunderbolts*" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Thunderbolts*</div>
                         <div class="showing-time">June 21, 2025 3:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=thunderbolts" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                     <div class="movie-card">
                     <img src="images/8.jpg" alt="Fear Street: Prom Queen" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Fear Street: Prom Queen</div>
                         <div class="showing-time">June 21, 2025 6:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=fear_street" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                  <div class="movie-card">
                     <img src="images/9.jpg" alt="Last Bullet" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">Last Bullet</div>
                         <div class="showing-time">June 22, 2025 9:00 AM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=last_bullet" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

                 <div class="movie-card">
                     <img src="images/10.jpg" alt="The Boy, the Mole, the Fox and the Horse" class="movie-poster">
                    <div class="movie-info">
                        <div class="movie-name">The Boy, the Mole, the Fox and the Horse</div>
                         <div class="showing-time">June 22, 2025 1:00 PM</div>
                        <div class="movie-buttons">
                            <a href="register.php?movie=the_boy" class="btn-small btn-buy">Book Now</a>
                        </div> 
                    </div>
                </div>

            </div>
        </main>
    </div>

    

    <script>
        // Tab functionality
        document.querySelectorAll('.tab').forEach(tab => {
            tab.addEventListener('click', function() {
                document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
                this.classList.add('active');
            });
        });

        // Movie card hover effects
        document.querySelectorAll('.movie-card').forEach(card => {
            card.addEventListener('mouseenter', function() {
                this.style.transform = 'translateY(-5px)';
            });
            
            card.addEventListener('mouseleave', function() {
                this.style.transform = 'translateY(0)';
            });
        });

        // Button interactions
        document.querySelectorAll('.btn, .btn-small').forEach(button => {
    button.addEventListener('click', function(e) {
        // Only prevent default if it's NOT an anchor tag
        if (this.tagName !== 'A') {
            e.preventDefault();
        }
        this.style.transform = 'scale(0.95)';
        setTimeout(() => {
            this.style.transform = 'scale(1)';
        }, 100);
    });
});
    </script>
</body>
</html>