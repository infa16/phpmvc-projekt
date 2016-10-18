<article class="article1">
    <div id="user">
        <h2><?= htmlentities($user->Name) ?> - aktivitet</h2>

        <div class="view-userbrowser">
            <a href="<?= $this->url->create('users/id/' . $user->Id) ?>">
                <img class="view-gravatar"
                     src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($user->Email)))) ?>?d=identicon"/>
            </a>
            <div class="view-userdetails">
                <div class="view-username">
                    <a href="<?= $this->url->create('users/id/' . $user->Id) ?>"><?= htmlentities($user->Name) ?></a>
                </div>
                <div class="view-useremail">
                    <a href="mailto:<?= $user->Email ?>"><?= htmlentities($user->Email) ?></a>
                </div>
                    <span class="joined-time">
                        Medlem sedan <?= gmdate("M d Y", strtotime($user->CreatedTime)) ?>
                    </span>
            </div>
        </div>

        <div class="view-useractivity">
            <div>
                <h3>Ställda frågor (<?= count($asked) ?>)</h3>
                <ul class="view-userpost">
                    <?php foreach ($asked as $question) : ?>
                        <li>
                            <a class="question-hyperlink"
                               href="<?= $this->url->create('questions/id/' . $question->Id) ?>"><?= htmlentities($question->Title) ?></a>
                            (<?= htmlentities($question->AnswerCount) ?> svar)
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <div>
                <h3>Besvarade frågor (<?= count($answered) ?>)</h3>
                <ul class="view-userpost">
                    <?php foreach ($answered as $question) : ?>
                        <li>
                            <a class="question-hyperlink"
                               href="<?= $this->url->create('questions/id/' . $question->Id) ?>"><?= htmlentities($question->Title) ?></a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>

</article>