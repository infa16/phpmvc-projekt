<article class="article1">

    <div class="page-description-tags">
        <h1 class="tag-header">Taggar</h1>
        <p>En tagg är ett nyckelord eller en etikett som sätter din fråga i samma kategori som andra liknande frågor.
            Använd rätt taggar för att andra lättare ska hitta och kunna svara på dina frågor.
        </p>
    </div>

    <div class="tags-list">
        <?php foreach ($tags as $tag) : ?>
        <div class="tag-overview">
            <a class="tag" href="<?= $this->url->create('tags/id/' . $tag->Id)?>"><?= htmlentities($tag->Tag) ?></a>
            <span class="tag-count">x <?= $tag->TagCount ?></span>
            <div class="tag-extract"><?= htmlentities($tag->Description) ?></div>
        </div>
        <?php endforeach; ?>
    </div>

</article>