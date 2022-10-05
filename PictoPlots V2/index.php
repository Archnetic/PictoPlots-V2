<?php
//Start a session to hold important 
session_start();
$Check = $MovieName = $movieNum = "";
if(isset($_POST['check'])){
  $movieValue = $_POST["movieGroup"];
  if($movieValue != $_SESSION['nameOfMovie']){
    $Check = "<div class='alert alert-danger'>Your selection is incorrect. Please try again!</div>";
    echo($MovieName);
    loadData($_SESSION['movieNumber']);
  }
  else{
    session_destroy();
    echo '<script>alert("Congratulations! You are correct. The page will refresh to play again!")</script>';
    header("Refresh:0");
  }
}
else{
  $movieNum = rand(0, 9);
  loadData($movieNum);
}

function loadData($movieNum){
  /*  
  Generate a Random Number and use that number to choose a movie.
  Using the movies data grab the JSON data from the API and decode it.
  */
  switch($movieNum){
    case 0:
      $MovieName = "The Dark Knight Rises";
      $MovieYear = "2012";
      break;
    case 1:
      $MovieName = "Captain America: The First Avenger";
      $MovieYear = "2011";
      break;
    case 2:
      $MovieName = "Frozen";
      $MovieYear = "2013";
      break;
    case 3:
      $MovieName = "Harry Potter and the Sorcerer's Stone";
      $MovieYear = "2001";
      break;
    case 4:
      $MovieName = "Iron Man";
      $MovieYear = "2008";
      break;
    case 5:
      $MovieName = "The Men Who Stare at Goats";
      $MovieYear = "2009";
      break;
    case 6:
      $MovieName = "The Princess Bride";
      $MovieYear = "1987";
      break;
    case 7:
      $MovieName = "Spider-Man";
      $MovieYear = "2002";
      break;
    case 8:
      $MovieName = "Star Trek";
      $MovieYear = "2009";
      break;
    case 9:
      $MovieName = "Star Wars: Episode I - The Phantom Menace";
      $MovieYear = "1999";
      break;
  }

  $json = file_get_contents("http://www.omdbapi.com/?apikey=14c8c5bf&t=$MovieName&y=$MovieYear&plot=full&r=json");
  $decoded = json_decode($json);
  $plot = $decoded->Plot;

  /* 
  Create a list of Key words for my selected movies. (Batman, Harry Potter, The Princess Bride, Captain America, Iron Man, Spider Man, Star Wars, Men who stare)
  Consume the plot and compare to the keywords.
  Get Images based on on the keywords of the plot and display them
  */

  $keywords = array('Crimes', 'City', 'Police', 'Bane\'s', 'Destroy', 'Boy', 'Wizard', 'School', 'Witchcraft', 'Friends', 'America', 'War', 'Military', 'Secret', 'Project', 'Courage', 'Experiment', 'Weak', 'Nazi', 'Hydra', 'Propaganda', 'Optimist', 'Mountain', 'Reindeer', 'Sister', 'Ice', 'Kingdom', 'Winter', 'Trolls', 'Snowman', 'Magic', 'Snow', 'Isolated', 'Book', 'Princess', 'Bride', 'Grandson', 'Romance', 'Love', 'Farm', 'Buttercup', 'Sea', 'Killed', 'Pirate', 'Kidnapped', 'Bandits', 'Giant', 'Swordsman', 'Chased', 'Marry', 'Story', 'Reporter', 'Marriage', 'Agent', 'Psychic', 'Military', 'Missing', 'End', 'Soldier', 'Superhero', 'Nerdy', 'High-Schooler', 'Bullied', 'Jocks', 'Neighborhood', 'Laboratory', 'Radioactive', 'Spider', 'Bites', 'Physique', 'Vision', 'Webs', 'Millionaire', 'Drug', 'Green', 'Goblin', 'Billionaire', 'Genius', 'Playboy', 'Inventor', 'Weapons', 'Presentation', 'Iraqi', 'Enemy', 'Humvee', 'Survives', 'Chest', 'Battery', 'Heart', 'Iron', 'Suit', 'Technology', 'Cave', 'Birth', 'Dies', 'Starship', 'Mining', 'Vulcan', 'Ambassador', 'Troublemaker', 'Captain', 'Commander', 'Emergency', 'Cadets', 'Adventure', 'Frontier', 'Legend', 'Evil', 'Peaceful', 'World', 'Jedi', 'Confront', 'Leaders', 'Droids', 'Escape', 'Queen', 'Land', 'Boy', 'Trade', 'Future', 'Hiding', 'Sith' );
  $delimiter = ' ';
  $plotWords = explode($delimiter, $plot);

  foreach($plotWords as $plotWord){
    foreach($keywords as $keyword){
      if(strtolower($keyword) == strtolower($plotWord)){
        $pictureJson = file_get_contents("https://pixabay.com/api/?key=17228303-ec297062a3db99e52d960db51&q=$keyword&image_type=illustration");
        $decodedJsonPicture = json_decode($pictureJson, true);
        $picture = $decodedJsonPicture['hits']['0']['previewURL'];
        echo("<img src='$picture' alt='$keyword' title='$keyword' class='rounded border border-dark spacer movieImages'>");
      }
    }
  }
  $_SESSION['nameOfMovie'] = $MovieName;
  $_SESSION['movieNumber'] = $movieNum;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-u1OknCvxWvY5kfmNBILK2hRnQC3Pr17a+RTT6rIHI7NnikvbZlHgTPOOmMi466C8" crossorigin="anonymous"></script>
    <script src="./js/formControl.js"></script>
    <link href="css/pictoplots.css" rel="stylesheet">
    <title>PictoPlots V2</title>
</head>
<body onload="loadQuizInformation()">
  <div class="main border border-dark rounded">
    <form id="movieForm" name="movieSelections" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
      <h5>Please select the movie whose plot is being described from the pictures above.</h5>
      <?php echo $Check; ?>
      <div class="options">
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie1" onclick="checkRadioGroup()" value="The Dark Knight Rises">
          <label class="form-check-label" for="Movie1">The Dark Knight Rises</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie2" onclick="checkRadioGroup()" value="Captain America: The First Avenger">
          <label class="form-check-label" for="Movie2">Captain America: The First Avenger</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie3" onclick="checkRadioGroup()" value="Frozen">
          <label class="form-check-label" for="Movie3">Frozen</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie4" onclick="checkRadioGroup()" value="Harry Potter and the Sorcerer's Stone">
          <label class="form-check-label" for="Movie4">Harry Potter and the Sorcerer's Stone</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie5" onclick="checkRadioGroup() " value="Iron Man">
          <label class="form-check-label" for="Movie5">Iron Man</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie6" onclick="checkRadioGroup()" value="The Men Who Stare at Goats">
          <label class="form-check-label" for="Movie6">The Men Who Stare at Goats</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie7" onclick="checkRadioGroup()" value="The Princess Bride">
          <label class="form-check-label" for="Movie7">The Princess Bride</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie8" onclick="checkRadioGroup()" value="Spider-Man">
          <label class="form-check-label" for="Movie8">Spider-Man</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie9" onclick="checkRadioGroup()" value="Star Trek">
          <label class="form-check-label" for="Movie9">Star Trek</label>
        </div>
        <div class="form-check">
          <input class="form-check-input" type="radio" name="movieGroup" id="Movie10" onclick="checkRadioGroup()" value="Star Wars: Episode I - The Phantom Menace">
          <label class="form-check-label" for="Movie10">Star Wars: Episode I - The Phantom Menace</label>
        </div>
      </div>
      <button class="btn btn-primary" class="btn btn-primary" type="submit" id="submitForm" disabled="true" value="check" name="check">Submit</button>
    </form>
  </div>
</body>
</html>