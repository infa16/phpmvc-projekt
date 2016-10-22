<article class="article1">

    <h2>Mest aktiva användare</h2>
    <div class="start-user-browser">
        <?php foreach ($users as $user) : ?>
            <div class="start-user-info">
                <a href="<?= $this->url->create('users/id/' . $user->Id) ?>">
                    <img class="gravatar"
                         src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($user->Email)))) ?>?d=identicon"/>
                </a>
                <div class="start-user-name">
                    <a href="<?= $this->url->create('users/id/' . $user->Id) ?>"><?= htmlentities($user->Name) ?></a>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <h2>Populära taggar</h2>
    <?php foreach ($tags as $tag) : ?>
        <div class="start-tag-overview">
            <a class="tag" href="<?= $this->url->create('tags/id/' . $tag->Id) ?>"><?= htmlentities($tag->Tag) ?></a>
            <span class="tag-count">x <?= $tag->TagCount ?></span>
        </div>
    <?php endforeach; ?>

</article>