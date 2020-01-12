<?php

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
            <section class="alert" id="alert">
            </section>
            <form action="" method="post" id="form" autocomplete="off" class="play">
                <input type="text" name="input">
                <input type="submit" name="submit" id="submit" value="Envoyer">
            </form>
        </section>
        <section class="string">
            <p>Thread des kanjis ici...</p>
            <p>漢字&#62;</p><p>漢字&#62;</p><p>漢字&#62;</p><p>漢字&#62;</p><p>漢字&#62;</p><p>漢字&#62;</p>
        </section>
        <section>
            <p class="start">Envoyez un mot pour commencer</p>
        </section>
        <section>
            <p class="help">Besoin d'aide ? Trouvez un mot sur <a href="https://jisho.org/" target="_blank">Jisho.org</a> !</p>
            <form action="" method="post" class="reset">
                <input type="submit" name="reset" value="Reset">
            </form>
        </section>
    </main>
    <footer>
        <p><em>Become the kanji master !</em></p>
    </footer>
</body>
</html>