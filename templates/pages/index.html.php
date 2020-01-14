<header>
    <h1>Kanji Shiritori</h1>
    <p>Ecrivez un mot commençant par le dernier kanji du mot précédemment saisi</p>
    <p>Exemple : 日<span class="example_kanji">本</span> &#62; <span class="example_kanji">本</span>気</p>
</header>
<main class="container">
    <section class="form">
        <?php if($_SESSION['error']) :?>
            <section class="alert error" id="alert">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']) ?>
            </section>
        <?php endif; ?>
        <?php if($_SESSION['success']) :?>
            <section class="alert success" id="success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']) ?>
            </section>
        <?php endif; ?>
        <form action="index.php?controller=word&task=insert" method="POST" id="form" autocomplete="off" class="play">
            <input type="text" name="input" autofocus>
            <input type="submit" name="submit" id="submit" value="Envoyer">
        </form>
    </section>
    <?php if($data) :?>
        <section class="string">
            <?php foreach ($data as $kanji) echo "<p>" . $kanji->word . " &#62; </p>"?>
        </section>
        <section>
            <form action="index.php?controller=word&task=reset" method="post" class="reset">
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
    <p><a href="https://www.kanpai.fr/apprendre-japonais/comment-ecrire-clavier-japonais" target="_blank">Écrire en japonais</a> (kanpai.fr)</p>
    <p><em>Become the kanji master !</em></p>
</footer>

<script>
    window.addEventListener('load', function(){
        if(document.getElementsByClassName('string')[0].scrollLeftMax !== 0){
            document.getElementsByClassName('string')[0].scrollLeft = document.getElementsByClassName('string')[0].scrollLeftMax
        }
    }, false)
</script>

