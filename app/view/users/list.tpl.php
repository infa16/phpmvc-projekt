<article class="article1">
    <h1>Användare</h1>

    <div class="page-allusers">

        <?php foreach ($users as $user) : ?>
            <div class="user-cell">
                <div class="user-browser">
                    <a href="<?= $this->url->create('users/id/' . $user->Id) ?>">
                        <img class="gravatar"
                             src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($user->Email)))) ?>?d=identicon"/>
                    </a>
                    <div class="userdetails">
                        <div class="user-name">
                            <a href="<?= $this->url->create('users/id/' . $user->Id) ?>"><?= htmlentities($user->Name) ?></a>
                        </div>
                        <span
                            class="joined-time">Medlem sedan <?= gmdate("M d Y", strtotime($user->CreatedTime)) ?></span>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

    </div>
</article>