<?php
session_start();
$servername="sql202.infinityfree.com";
$username="if0_43187131";
$password="hHimykmJ2nY";
$dbname="if0_43187131_AuroraEventsMain";
try{
    $conn= new PDO("mysql:host=$servername;dbname=$dbname",$username,$password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SESSION['showconnect'] != 0) {
        echo("Connected successfully<br>");
    }   
}
catch(PDOException $e){
    echo("Connection failed: " . $e->getMessage());
}

try{
  $query = $conn->query("SELECT title 
                         FROM tblEvents");
  $eventsResult = $query->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
  error_log($e->getMessage());
}

/*app handler*/
$submitted = false;
$errors = [];
/*
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $alrApp = false;
    $appsResult = [];
    //. If there are no validation errors, proceed with the Database Insertion
    try {
      $stmt2 = $conn->prepare("SELECT discUser mcIGN
                              FROM tblApps
                              WHERE eventName = :eventName");
      $stmt2->execute([':eventName' => $_POST['eventName']]);
      $appsResult = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
      error_log("Query2 failed: " . $e->getMessage());
      $appsResult = [];
    }
      foreach ($appsResult as $row1) {
        if (($row1['discUser'] == $_POST['discUser']) || ($row1['mcIGN'] == $_POST['mcIGN'])) {
            $alrApp = true;
            break;
        }
    }
    if ($alrApp == false) { 
        try {
            // Prepare the SQL query matching tblApps fields
            $stmt = $conn->prepare("INSERT INTO tblApps
                        (applicationID, eventName, discUser, mcIGN, ageGrp, 
                        mic, stream, abtYou, goals, experience, charIdea, problem, 
                        resourceScen, infoScen, friendScen, accepted)
                        VALUES
                        (NULL, :eventName, :discUser, :mcIGN, :ageGrp, 
                        :mic, :stream, :abtYou, 
                        :goals, :experience, :charIdea, :problem, 
                        :resourceScen, :infoScen, :friendScen, NULL)");

            // Bind values directly from the $_POST collection
            $stmt->bindValue(':eventName', $_POST['eventName']);
            $stmt->bindValue(':discUser', $_POST['discUser']);
            $stmt->bindValue(':mcIGN', $_POST['mcIGN']);
            $stmt->bindValue(':ageGrp', $_POST['ageGrp']);
            $stmt->bindValue(':mic', $_POST['mic']);
            
            // Handle optional field safely: store NULL if it was left empty
            $stream = !empty($_POST['stream']) ? trim($_POST['stream']) : null;
            $stmt->bindValue(':stream', $stream);

            $stmt->bindValue(':abtYou', $_POST['abtYou']);
            $stmt->bindValue(':goals', $_POST['goals']);
            $stmt->bindValue(':experience', $_POST['experience']);
            $stmt->bindValue(':charIdea', $_POST['charIdea']);
            $stmt->bindValue(':problem', $_POST['problem']);
            $stmt->bindValue(':resourceScen', $_POST['resourceScen']);
            $stmt->bindValue(':infoScen', $_POST['infoScen']);
            $stmt->bindValue(':friendScen', $_POST['friendScen']);

            // Execute the query
            $stmt->execute();
            
            // Set flag to show the confirmation UI view
            $submitted = true;

        } catch (PDOException $e) {
            // Log database errors silently so database structure is not leaked to the browser
            error_log("Database insertion failed: " . $e->getMessage());
            $errors[] = 'database_error';
        }
    }
}
*/
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Apply | Aurora Civilisations</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
          rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap"
          rel="stylesheet">
    <link rel="stylesheet" href="sheet.css">
</head>
<body>

<div id="result" class="sticky-top style-override">
  <?php include_once("navbar.php"); ?>
</div>


<main class="application-page">
    <div class="container">
        <div class="section-heading text-center">
            <span class="eyebrow">
                <i class="bi bi-stars me-1"></i> YOUR JOURNEY BEGINS
            </span>
            <h1>Event <span class="gradient-text">Application</span></h1>
            <p>Tell us about yourself and the role you want to play.</p>
        </div>
    </div>

  <div class="application-shell">
    <?php if ($submitted): ?>
      <div class="p-4 p-md-5 text-center">
        <div class="display-4 mb-3" style="color:var(--pale-gold)"><i class="bi bi-check-circle-fill"></i></div>
        <h2 class="fw-bold">Application complete!</h2>
        <p class="text-light-emphasis">Your answers passed the demo form checks. Connect this page to a database or application system to actually deliver your application to the event team.</p>
        <a class="btn btn-gold rounded-pill px-4 mt-2" href="home">Return home <i class="bi bi-arrow-right ms-1"></i></a>
      </div>
    <?php else: ?>
      <?php if (!empty($errors)): ?>
        <div class="alert alert-warning m-3 mb-0" role="alert">Please complete every required question before submitting.</div>
      <?php endif; ?>
      <div class="progress-area">
        <div class="step-track" aria-label="Application sections">
          <button type="button" class="step-button active" data-step-button="0" aria-current="step">
            <div class="step-top"><span class="step-number">1</span><span class="step-label">Personal</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="1" disabled>
            <div class="step-top"><span class="step-number">2</span><span class="step-label">Technical</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="2" disabled>
            <div class="step-top"><span class="step-number">3</span><span class="step-label">Experience</span></div>
          </button>
          <button type="button" class="step-button" data-step-button="3" disabled>
            <div class="step-top"><span class="step-number">4</span><span class="step-label">Scenarios</span></div>
          </button>
        </div>
        <div class="progress" role="progressbar" aria-label="Application progress" aria-valuemin="0" aria-valuemax="100" aria-valuenow="25">
          <div class="progress-bar" id="progressBar" style="width:25%"></div>
        </div>
      </div>

      <form id="applicationForm" class="form-content" method="post" action="apply" novalidate>
        <section class="form-step" data-step="0">
          <div class="section-kicker">Section 01 / 04</div>
          <h2 class="section-title">Personal questions</h2>
          <p class="section-intro">First, give us the details we need to identify you in Discord and in-game.</p>

          <div class="question-card">
            <label class="form-label1" for="eventName">Event Name <span class="required-star">*</span></label>
            <select class="form-select" id="eventName" name="eventName" required>
              <option value="" disabled selected>Choose an event...</option>
              <?php
              if (isset($eventsResult) && !empty($eventsResult)) {
                  foreach ($eventsResult as $row) {
                      $eventName = htmlspecialchars($row['title'], ENT_QUOTES, 'UTF-8');
                      echo "<option value=\"$eventName\">$eventName</option>";
                  }
              } else {
                  echo '<option value="" disabled>No upcoming events found</option>';
              }
              ?>
            </select>
            <div class="invalid-feedback">Choose which event you'd like to apply for.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="discUser">Discord username <span class="required-star">*</span></label>
            <input class="form-control" id="discUser" name="discUser" type="text" maxlength="80" placeholder="e.g. username" required>
            <div class="invalid-feedback">Enter your Discord username.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="mcIGN">Minecraft IGN <span class="required-star">*</span></label>
            <input class="form-control" id="mcIGN" name="mcIGN" type="text" maxlength="32" placeholder="Your Minecraft username" required>
            <div class="invalid-feedback">Enter your Minecraft username.</div>
          </div>
          <div class="question-card">
            <div class="question-label">Age group <span class="required-star">*</span></div>
            <div class="row g-2">
              <?php foreach (['under12' => 'Under 12', '12-14' => '12-14', '15-17' => '15-17', '18plus' => '18 and Over'] as $value => $label): ?>
                <div class="col-6 col-md-3">
                  <div class="form-check h-100">
                    <input class="form-check-input" type="radio" name="ageGrp" id="age_<?php echo htmlspecialchars($value, ENT_QUOTES); ?>" value="<?php echo htmlspecialchars($value, ENT_QUOTES); ?>" required>
                    <label class="form-check-label" for="age_<?php echo htmlspecialchars($value, ENT_QUOTES); ?>"><?php echo $label; ?></label>
                  </div>
                </div>
              <?php endforeach; ?>
            </div>  
            <div class="form-text mt-2">Choose the age range that applies to you.</div>
            <div class="invalid-feedback d-block" id="ageFeedback" hidden>Select an age group to continue.</div>
          </div>
          <div class="form-actions">
            <span class="privacy-note align-self-center"><i class="bi bi-shield-lock me-1"></i>Your answers will only be visible to the event team.</span>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="1" aria-hidden="true">
          <div class="section-kicker">Section 02 / 04</div>
          <h2 class="section-title">Technical questions</h2>
          <p class="section-intro">Let us know what equipment you have and whether you plan to share the event.</p>

          <div class="question-card">
            <div class="question-label">Do you have a working microphone? <span class="required-star">*</span></div>
            <div class="d-flex flex-wrap gap-2">
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="mic" id="mic_yes" value="1" required>
                <label class="form-check-label" for="mic_yes">Yes</label>
              </div>
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="mic" id="mic_no" value="0" required>
                <label class="form-check-label" for="mic_no">No</label>
              </div>
            </div>
            <div class="invalid-feedback d-block" data-radio-feedback="microphone" hidden>Choose yes or no.</div>
          </div>
          <div class="question-card">
            <div class="question-label">Will you be streaming or recording this event? <span class="required-star">*</span></div>
            <div class="d-flex flex-wrap gap-2 mb-3">
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="streamyn" id="stream_yes" value="1" required>
                <label class="form-check-label" for="stream_yes">Yes</label>
              </div>
              <div class="form-check flex-grow-1">
                <input class="form-check-input" type="radio" name="streamyn" id="stream_no" value="0" required>
                <label class="form-check-label" for="stream_no">No</label>
              </div>
            </div>
            <label class="form-label1" for="streaming_platform">If yes, where will you stream or upload it?</label>
            <input class="form-control" id="streaming_platform" name="stream" type="text" maxlength="200" placeholder="e.g. Twitch, YouTube, or a channel link">
            <div class="form-text">If you are not streaming or recording, you can leave this blank.</div>
            <div class="invalid-feedback d-block" data-radio-feedback="streaming" hidden>Choose yes or no.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="2" aria-hidden="true">
          <div class="section-kicker">Section 03 / 04</div>
          <h2 class="section-title">Player experience</h2>
          <p class="section-intro">There are no perfect answers. We want to understand your approach to roleplay, storytelling, and playing with others.</p>

          <div class="question-card">
            <label class="form-label1" for="about_you">Tell us a little about yourself. <span class="required-star">*</span></label>
            <textarea class="form-control" id="about_you" name="abtYou" rows="3" minlength="10" maxlength="2000" placeholder="What should the event team know about you?" required></textarea>
            <div class="form-text">A few sentences is a good start.</div><div class="invalid-feedback">Please write a little about yourself.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="event_goals">What are your goals for this event? <span class="required-star">*</span></label>
            <textarea class="form-control" id="event_goals" name="goals" rows="3" minlength="10" maxlength="2000" placeholder="What would make this event memorable for you?" required></textarea>
            <div class="invalid-feedback">Tell us what you hope to get out of the event.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="past_experience">Describe your past experiences with Minecraft events, roleplay events, and storytelling. Please be specific with your answer. <span class="required-star">*</span></label>
            <textarea class="form-control" id="past_experience" name="experience" rows="5" minlength="20" maxlength="4000" placeholder="Mention events you have taken part in, roles you played, stories you helped create, or what you learned. If you are new, explain what interests you." required></textarea>
            <div class="invalid-feedback">Please give a specific answer, or explain if you are new to these events.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="character_idea">Describe a character you might make for this event. Please be specific with your answer. <span class="required-star">*</span></label>
            <textarea class="form-control" id="character_idea" name="charIdea" rows="5" minlength="20" maxlength="4000" placeholder="Consider their background, personality, motivations, strengths, flaws, and how they might develop." required></textarea>
            <div class="invalid-feedback">Describe your character idea in more detail.</div>
          </div>
          <div class="question-card">
            <label class="form-label1" for="problem_solving">Tell us about a time when something went wrong for you. How did you deal with that? <span class="required-star">*</span></label>
            <textarea class="form-control" id="problem_solving" name="problem" rows="4" minlength="15" maxlength="3000" placeholder="What happened, what did you do, and what did you learn?" required></textarea>
            <div class="invalid-feedback">Describe the situation and how you responded.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="button" class="btn btn-primary rounded-pill px-4 next-btn">Continue <i class="bi bi-arrow-right ms-1"></i></button>
          </div>
        </section>

        <section class="form-step d-none-step" data-step="3" aria-hidden="true">
          <div class="section-kicker">Section 04 / 04</div>
          <h2 class="section-title">Scenario questions</h2>
          <p class="section-intro">Think through each situation carefully. We are interested in your reasoning, priorities, and how you would handle difficult choices in a shared story.</p>

          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 01</div>
            <label class="form-label1" for="resource_scenario">You desperately need a certain resource, but the area you occupy has none of it. Another group has an abundance of it, and they have refused all trade offers. What do you do? <span class="required-star">*</span></label>
            <textarea class="form-control" id="resource_scenario" name="resourceScen" rows="5" minlength="20" maxlength="4000" placeholder="Explain your options, risks, and why you would choose your approach." required></textarea>
            <div class="invalid-feedback">Explain how you would approach this situation.</div>
          </div>
          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 02</div>
            <label class="form-label1" for="information_scenario">You come across some information that could ensure safety for your group, but revealing it to anyone would also ensure the safety of your enemies. No one else knows this information. What do you do? What are your priorities? <span class="required-star">*</span></label>
            <textarea class="form-control" id="information_scenario" name="infoScen" rows="5" minlength="20" maxlength="4000" placeholder="Who would you tell, if anyone? How would you weigh competing risks?" required></textarea>
            <div class="invalid-feedback">Explain your decision and your priorities.</div>
          </div>
          <div class="question-card">
            <div class="scenario-number mb-2">SCENARIO 03</div>
            <label class="form-label1" for="friend_scenario">You overhear a dangerous group of players talking about murdering a friend of yours. Later in the session, you get a chance to have a one-on-one with this person. You know that telling them they are in danger could potentially put your own life at risk; however, keeping this information to yourself would put them at the mercy of the group. What do you do? <span class="required-star">*</span></label>
            <textarea class="form-control" id="friend_scenario" name="friendScen" rows="5" minlength="20" maxlength="4000" placeholder="Consider trust, safety, consequences, and how you would handle the situation in-character." required></textarea>
            <div class="invalid-feedback">Explain what you would do and why.</div>
          </div>
          <div class="question-card">
            <div class="form-check">
              <input class="form-check-input" type="checkbox" id="confirm_truth" name="confirm_truth" value="yes" required>
              <label class="form-check-label" for="confirm_truth">I confirm that these answers are my own and that I will follow the event rules. <span class="required-star">*</span></label>
            </div>
            <div class="invalid-feedback d-block" id="confirmFeedback" hidden>Please confirm before submitting.</div>
          </div>
          <div class="form-actions">
            <button type="button" class="btn btn-outline-light rounded-pill px-4 back-btn"><i class="bi bi-arrow-left me-1"></i> Back</button>
            <button type="submit" class="btn btn-gold rounded-pill px-4">Submit application <i class="bi bi-stars ms-1"></i></button>
          </div>
        </section>
      </form>
    <?php endif; ?>
  </div>
</main>

<footer class="site-footer">
    <div class="container text-center">
        <p class="mb-0">AURORA CIVILIZATIONS · Build. Explore. Belong.</p>
    </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="script.js"></script>
</body>
</html> 