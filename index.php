<?php
// Start session and check authentication
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] !== true) {
    header('Location: gate.php');
    exit;
}

// Check session timeout (30 minutes)
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > 1800)) {
    session_unset();
    session_destroy();
    header('Location: gate.php');
    exit;
}

// Update last activity time
$_SESSION['last_activity'] = time();
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Teacher's Digital Diary</title>
  
  <!-- Security Headers -->
  <meta http-equiv="X-Content-Type-Options" content="nosniff">
  <meta http-equiv="X-Frame-Options" content="DENY">
  
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      font-family: 'Open Sans', sans-serif;
      margin: 0;
      padding: 0;
      background: linear-gradient(135deg, #f5f7fa 0%, #e4e8f0 100%);
      color: #2d3748;
      min-height: 100vh;
    }
    
    .container {
      max-width: 1400px;
      margin: 0 auto;
      padding: 20px;
    }
    
    header {
      background: linear-gradient(135deg, #667eea 0%, #764ba2 80%);
      color: yellow;
      padding: 20px 0;
      text-align: center;
      margin-bottom: 20px;
      box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
      position: relative;
      overflow: hidden;
    }
    
    header::after {
      content: "";
      position: absolute;
      bottom: 0;
      left: 0;
      right: 0;
      height: 2px;
      background: linear-gradient(90deg, #f6d365 0%, #fda085 100%);
    }
    
    .profile-photo {
      position: absolute;
      right: 30px;
      top: 20px;
      height: 80px;
      width: 80px;
      border-radius: 50%;
      border: 3px solid white;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      object-fit: cover;
      z-index: 1;
    }

    h1, h2, h3 {
      margin: 10px 0;
      font-family: 'Poppins', sans-serif;
    }
    
    h1 {
      font-size: 2.8em;
      font-weight: 700;
      text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.2);
      letter-spacing: 1px;
    }
    
    .nav {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
      gap: 10px;
      margin-bottom: 20px;
    }
    
    .nav a {
      padding: 15px;
      background: linear-gradient(135deg, #ff9a9e 0%, #fad0c4 100%);
      color: #2d3748;
      text-decoration: none;
      border-radius: 10px;
      text-align: center;
      font-weight: 600;
      font-family: 'Poppins', sans-serif;
      transition: all 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.1);
      border: none;
      font-size: 1.05em;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    
    .nav a:hover {
      transform: translateY(-5px) scale(1.02);
      box-shadow: 0 15px 20px rgba(0, 0, 0, 0.15);
      background: linear-gradient(135deg, #fad0c4 0%, #ff9a9e 100%);
      color: #1a202c;
    }
    
    .editable {
      padding: 20px;
      border: 3px dashed #a78bfa;
      margin: 20px 0;
      min-height: 50px;
      background: white;
      border-radius: 10px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.08);
      transition: all 0.3s ease;
      background: linear-gradient(to bottom right, #ffffff, #f8f9fa);
    }
    
    .editable:hover {
      border-color: #8b5cf6;
      box-shadow: 0 15px 35px rgba(0, 0, 0, 0.12);
    }
    
    .editable h3 {
      color: #6b46c1;
      margin-top: 0;
      font-family: 'Poppins', sans-serif;
      font-weight: 700;
      font-size: 1.8em;
      border-bottom: 3px solid #e9d8fd;
      padding-bottom: 5px;
      margin-bottom: 15px;
      text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .editable p {
      font-size: 1.1em;
      line-height: 1.5;
      color: #4a5568;
      font-weight: 600;
    }
    
    footer {
      text-align: center;
      margin-top: 30px;
      padding: 25px;
      color: #718096;
      font-size: 1em;
      font-weight: 600;
      background: linear-gradient(to right, #edf2f7, #e2e8f0);
      border-radius: 10px;
      box-shadow: 0 -5px 15px rgba(0, 0, 0, 0.03);
    }
    
    @media (max-width: 768px) {
      .nav {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 15px;
      }
      
      h1 {
        font-size: 2.2em;
      }
      
      .nav a {
        padding: 12px;
        font-size: 0.95em;
      }

      .profile-photo {
        height: 60px;
        width: 60px;
        right: 20px;
        top: 15px;
      }
    }

    /* Logout button styling */
    .logout-btn {
      position: fixed;
      bottom: 20px;
      right: 20px;
      background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
      color: white;
      border: none;
      padding: 12px 20px;
      border-radius: 50px;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
      font-family: 'Poppins', sans-serif;
      font-weight: 600;
      z-index: 100;
      transition: all 0.3s ease;
    }
    
    .logout-btn:hover {
      transform: translateY(-3px);
      box-shadow: 0 6px 20px rgba(0, 0, 0, 0.3);
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <img src="kbjoshi.png" class="profile-photo" alt="Teacher's Photo">
      <h1>TEACHER'S DIGITAL DIARY</h1>
      <h2>K B JOSHI PGT MATHEMATICS</h2>
      <h3>PM SHRI KENDRIYA VIDYALAYA RANIKHET</h3>
    </div>
  </header>
  
  <div class="container">
    <!-- NAVIGATION -->
    <div class="nav">
      <a href="profile.php">PROFILE</a>
      <a href="timetable.php">Time Table</a>
      <a href="syllabus.php">Syllabus</a>
      <a href="lessonplan.php">LESSON PLAN</a>
      <a href="codeofconduct.php">CODE OF CONDUCT</a>
      <a href="lincoln.php">Lincoln's Letter</a>
      <a href="activities.php">Activities</a>
      <a href="ncertbooks.php">NCERT BOOKS</a>
      <a href="homework.php">Holiday HW</a>
      <a href="practice.php">Practice Papers</a>
      <a href="exams.php">Examinations</a>
      <a href="ioqm.php">Math Olympiad (IOQM)</a>
      <a href="meetings.php">Subject Committee Meetings</a>
      <a href="3dshapes.php">3D Shapes</a>
      <a href="geogebra.php">GeoGebra</a>
      <a href="robocompass.php">RoboCompass</a>
      <a href="paper-folding.php">Paper Folding</a>
      <a href="ppts.php">PPTs</a>
      <a href="students.php">Students Profile</a>
      <a href="monitoring.php">Monitoring</a>
      <a href="board.php">Board Questions</a>
      <a href="studymaterial.php">STUDY MATERIALS</a>
      <a href="ptm.php">PTMs</a>
      <a href="learners.php">LEARNERS LIST</a>
    </div>

    <div class="editable" contenteditable="true">
      <h3>WELCOME TO MY TEACHING HUB!</h3>
      <p>This is the command center for all teaching activities. Click any colorful button above to access different sections of digital workspace.</p>
      <p><strong>PRO TIP:</strong> "The best preparation for tomorrow is doing your best today."</p>
    </div>
    
    <footer>
      <strong>TEACHER'S DIGITAL DIARY</strong> &copy; <?php echo date('Y'); ?> | Empowering Educators with Creative Tools
    </footer>
  </div>

  <!-- Log