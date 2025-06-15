<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>About Us</title>
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
        flex-direction: column;
            
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
        text-decoration: none;
    }

    .tab:last-child {
        border-right: none;
    }

    .tab.active {
        background-color: #44A08D;
        border-radius: 8px 8px 0 0
    }

    .content {
        display: flex;
        flex-direction: row;
        gap: 20px;
        flex: 1;
        padding: 20px;
      
    }


    .info-box {
       flex: 1;
       padding: 20px;
       border: 2px solid #ccc;
       border-radius: 10px;
       background-color: #fdfdfd;
       box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    .info-box h2 {
       text-align: center;
       margin-bottom: 20px;
       font-size: 28px;
       color:rgb(2, 74, 60); 
    }

    .info-box p {
       font-size: 15px;
       color: #444;
    }

    .info-box h3 {
        font-size: 20px;
        color: black;
        font-weight: normal;  /* Removes bold */
        font-family: 'Courier New', monospace;
    }

    .member-section {
       flex: 2;
       display: flex;
       flex-direction: column;
    }

    .members-label {
       font-weight: bold;
       font-size: 20px;
       margin-bottom: 15px;
       text-align: center;
    }

    .member-grid {
       display: grid;
       grid-template-columns: repeat(3, 1fr);
       gap: 20px;
    }

    .member-box {
       border-radius: 10px;
       overflow: hidden;
       box-shadow: 0 2px 5px rgba(0,0,0,0.1);
       background-color: #f8f8f8;
       transition: transform 0.3s, box-shadow 0.3s;
       display: flex;
       flex-direction: column;
       padding-bottom: 10px;
    }

    .member-box:hover {
       transform: translateY(-5px);
       box-shadow: 0 6px 12px rgba(0,0,0,0.15);
    }

    .member-box img {
       width: 100%;
       height: 200px;
       object-fit: cover;
    }

    .member-box p {
       padding: 5px 12px;
       font-size: 12px;
       color: #333;
    }

    .member-box p span {
      font-weight: bold;
    }

    @media (max-width: 768px) {
      .content {
        flex-direction: column;
      }
   
      .member-grid {
        grid-template-columns: repeat(2, 1fr);
      }
      
    }

  </style>
</head>
<body>

<div class="main-container">
    <main class="content-area">

  <div class="tabs">
    <a href="homepage.php" class="tab">NOW SHOWING</a>
    <div class="tab active">ABOUT US</div>
  </div>

  <div class="content">
    <div class="info-box">
      <h2>CINEMA TICKETING AND SALES MANAGEMENT SYSTEM</h2>
      <h3>
       The Cinema Ticketing and Sales Management System is a web-based application developed using HTML, CSS, PHP, and HTML. It streamlines movie ticket reservation, seat selection, and payment monitoring, reducing manual work and improving data accuracy. The system uses a MySQL database for data storage and management, allowing customers to view schedules, select seats, and check out easily.
  </h3>
    </div>

    <div class="member-section">
      <div class="members-label">MEMBERS</div>
      <div class="member-grid">

        <div class="member-box">
          <img src="images/m1.jpg" alt="Member 1">
          <p><span>Name:</span> Reginald O. Agustin </p>
          <p><span>Contact no:</span> 09498291275</p>
          <p><span>Email:</span> reginaldagustin.bsinfotech@gmail.com </p>
        </div>

        <div class="member-box">
          <img src="images/m2.jfif" alt="Member 2">
          <p><span>Name:</span> John Lyod D. Badiana</p>
          <p><span>Contact no:</span> 09517867812</p>
          <p><span>Email:</span> badiana.jl.bsinfotech@gmail.com</p>
        </div>

        <div class="member-box">
          <img src="images/m3.jpg" alt="Member 3">
          <p><span>Name:</span> Leoncio III A. Barcelona</p>
          <p><span>Contact no:</span> 09398030860</p>
          <p><span>Email:</span> barcelona.l.bsinfotech@gmail.com</p>
        </div>

        <div class="member-box">
          <img src="images/m4.jpg" alt="Member 4">
          <p><span>Name:</span> Rochell D.G Concepcion</p>
          <p><span>Contact no:</span> 09691596821</p>
          <p><span>Email:</span> concepcion.r.bsinfotech@gmail.com</p>
        </div>

        <div class="member-box">
          <img src="images/m5.jfif" alt="Member 5">
          <p><span>Name:</span> Lhaira Grace N. Zornosa</p>
          <p><span>Contact no:</span> 09684863343 </p>
          <p><span>Email:</span> lhairazornosa8@gmail.com</p>
        </div>

        <div class="member-box">
          <img src="images/m6.jfif" alt="Member 6">
       <p><span>Name:</span> Ashley B. Naty </p>
          <p><span>Contact no:</span> 09919884161 </p>
          <p><span>Email:</span> naty.ashley.bsinfotech@gmail.com </p>
        </div>

      </div>
    </div>
  </div>
</div>
</body>
</html>