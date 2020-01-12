<?php
require_once "JishoAPI.php";

// Connexion BDD
$bdd = new PDO('mysql:host=127.0.0.1;dbname=shiritori', 'root', 'root', [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
]);

// Récupération des données d'origine
try {
    $response = $bdd->query("SELECT * FROM list");
    $data = $response->fetchAll();
    $response->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}

// Envoi formulaire
$pattern = "/[a-zA-Z0-9０-９あ-んア-ンー。、？！＜＞： 「」（）｛｝≪≫〈〉《》【】『』〔〕［］・\n\r\t\s\(\)　]/u";

$lastEntry = $bdd->query("SELECT id, word FROM list ORDER BY id DESC LIMIT 1")->fetch();
$lastEntrySplit = preg_split("//u", $lastEntry->word, -1, PREG_SPLIT_NO_EMPTY);
$lastEntryLastChar = end($lastEntrySplit);

if(isset($_POST['submit'])){
    if(isset($_POST['input']) && !empty($_POST['input'])){
        $input = trim(htmlentities($_POST['input']));
        $inputSplit = preg_split("//u", $input, -1, PREG_SPLIT_NO_EMPTY);
        $inputFirstChar = reset($inputSplit);

        if(preg_match($pattern, $input))
        {
            $error = "ブー！Le mot doit être écrit en kanji.";
        }
        elseif(mb_strlen($input) < 2 || mb_strlen($input) > 2)
        {
            $error = "ブー！$input comprend ". mb_strlen($input). " kanjis. Le mot doit faire 2 kanjis.";
        }elseif(!empty($data) && $input === $lastEntry->word)
        {
            $error = "ブー！Le mot $input est identique au mot précédemment saisi ($lastEntry->word).";
        }
        elseif(!empty($data) && $lastEntryLastChar !== $inputFirstChar)
        {
            $error = "ブー！Le premier kanji de $input ($inputFirstChar) ne correspond pas au dernier kanji de $lastEntry->word ($lastEntryLastChar).";
        }else{
            // Recherche du mot dans le dictionnaire
            $jishoApi = new JishoAPI();
            $result = $jishoApi->getJisho($input);

            if ($result === true){
                $req = $bdd->prepare("INSERT INTO list(word) VALUES (:input)");
                $req->execute(['input' => $input]);
                $req->closeCursor();
                $success = "$input a bien été ajouté.";
            }else{
                $error = "ブー！Le mot $input n'existe pas.";
            }

        }
    }else{
        $error = "Le champ est vide.";
    }
}

if(isset($_POST['reset'])){
    $req = $bdd->exec('TRUNCATE TABLE list');
    $success = "Le shiritori a bien été supprimé.";
}

// Récupération des données mise à jour
try {
    $response = $bdd->query("SELECT * FROM list");
    $data = $response->fetchAll();
    $response->closeCursor();
} catch (PDOException $e) {
    echo $e->getMessage();
}
?>
<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kanji shiritori</title>
    <link rel="stylesheet" href="Public/stylesheet.css">
</head>
<body>
    <header>
        <h1>Kanji Shiritori</h1>
        <p>Ecrivez un mot commençant par le dernier kanji du mot précédemment saisi</p>
        <p>Exemple : 日<span class="example_kanji">本</span> &#62; <span class="example_kanji">本</span>気</p>
    </header>
    <main class="container">
        <section class="form">
            <?php if($error) :?>
            <section class="alert error" id="alert">
                <?= $error ?>
            </section>
            <?php endif; ?>
            <?php if($success) :?>
                <section class="alert success" id="success">
                    <?= $success ?>
                </section>
            <?php endif; ?>
            <form action="" method="POST" id="form" autocomplete="off" class="play">
                <input type="text" name="input">
                <input type="submit" name="submit" id="submit" value="Envoyer">
            </form>
        </section>
        <?php if($data) :?>
        <section class="string">
            <?php foreach ($data as $kanji) echo "<p>" . $kanji->word . " &#62; </p>"?>
        </section>
        <section>
            <form action="" method="post" class="reset">
                <input type="submit" name="reset" value="Reset">
            </form>
        </section>
        <?php else: ?>
        <section>
            <p class="start">Envoyez un mot pour commencer un nouveau shiritori.</p>
        </section>
        <?php endif; ?>
    </main>
    <footer>
        <p class="help">Besoin d'aide ? Trouvez un mot sur <a href="https://jisho.org/" target="_blank">Jisho.org</a> !</p>
        <p><em>Become the kanji master !</em></p>
    </footer>
</body>
</html>