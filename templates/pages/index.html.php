<header>
    <h1>Kanji Shiritori</h1>
    <p>Ecrivez un mot commençant par le dernier kanji du mot précédemment saisi</p>
    <p>Exemple : 日<span class="example_kanji">本</span> &#62; <span class="example_kanji">本</span>気</p>
</header>
<main class="container">
    <section class="form-section" id="form-section">
        <div class="alert" id="alert-section">
        <?php if($_SESSION['error']) :?>
            <div class="error" id="alert">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']) ?>
            </div>
        <?php endif; ?>
        <?php if($_SESSION['success']) :?>
            <div class="success" id="success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']) ?>
            </div>
        <?php endif; ?>
        </div>
        <form action="index.php?controller=word&task=insert" method="POST" id="post-form" autocomplete="off" class="play">
            <input type="text" name="input" id="input" autofocus>
            <input type="submit" name="submit" id="submit" value="Envoyer">
        </form>
    </section>
    <section class="string-section" id="string-section">
    <?php if($data) :?>
        <section class="string" id="string">
            <?php foreach ($data as $kanji) echo "<p>" . $kanji->word . " &#62; </p>"?>
        </section>
        <section>
            <form action="index.php?controller=word&task=reset" method="post" id="reset-form" class="reset">
                <input type="hidden" name="hiddenreset">
                <input type="submit" name="reset" id="reset" value="Reset">
            </form>
        </section>
    <?php else: ?>
        <p class="start">Envoyez un mot pour commencer un nouveau shiritori.</p>
    <?php endif; ?>
    </section>
</main>
<footer>
    <p class="help">Besoin d'aide ? Trouvez un mot sur <a href="https://jisho.org/" target="_blank">Jisho.org</a> !</p>
    <p><a href="https://www.kanpai.fr/apprendre-japonais/comment-ecrire-clavier-japonais" target="_blank">Écrire en japonais</a> (kanpai.fr)</p>
    <p><em>Become the kanji master !</em></p>
</footer>

<script src="js/app.js"></script>
<script>
    window.addEventListener('load', function(){
        if(string && string.scrollLeftMax !== 0){
            string.scrollLeft = document.getElementsByClassName('string')[0].scrollLeftMax
        }
    }, false)
</script>

