<article class="article1">

    <div id="question">
        <!-- Question -->
        <h1><?= htmlentities($question->Title) ?></h1>

        <div class="post-cell">
            <div class="post-text">
                <p><?= $question->Content ?></p>
            </div>
            <div class="post-info">
                <div class="tags">
                    <?php foreach ($question->Tags as $tag) : ?>
                        <a class="tag" href="<?= $this->url->create('tags/id/' . $tag->Id) ?>">
                            <?= htmlentities($tag->Tag) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
                <div class="user-info">
                    <div class="user-action-time">
                        frågade
                        <span>
                         <?= gmdate("M d Y H:i", strtotime($question->CreatedTime)) ?>
                        </span>
                    </div>
                    <div class="user-gravatar">
                        <img class="avatar"
                             src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($question->Email)))) ?>?d=identicon"/>
                    </div>
                    <div class="user-details">
                        <a href="<?= $this->url->create('users/id/' . $question->UserId) ?>">
                            <?= htmlentities($question->Name) ?>
                        </a>
                    </div>
                </div>
            </div>
            <!-- Comments on question -->
            <div class="comments">
                <?php foreach ($question->Comments as $comment) : ?>
                    <div class="comment">
                        <span class="comment-text"><?= $comment->Content ?></span> -
                        <a class="comment-user" href="<?= $this->url->create('users/id/' . $comment->UserId) ?>">
                            <?= htmlentities($comment->Name) ?>
                        </a>
                        <span><?= gmdate("M d Y H:i", strtotime($comment->CreatedTime)) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
        <!-- Om användaren är inloggad ska länken visas -->
        <?php if ($this->di->get('UserSession')->isAuthenticated()) : ?>
            <div class="add-comment">
                <a href="<?= $this->url->create('questions/add-question-comment/' . $question->Id) ?>">
                    <i class="fa fa-comments-o" aria-hidden="true"></i>Kommentera
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div id="answers">
        <h2><?= count($answers) ?> svar</h2>

        <!-- Answer -->
        <?php foreach ($answers as $answer) : ?>
            <div class="post-cell">
                <div class="post-text">
                    <p><?= $answer->Content ?></p>
                </div>
                <div class="post-info">
                    <div class="user-info">
                        <div class="user-action-time">
                            svarade
                            <span><?= gmdate("M d Y H:i", strtotime($answer->CreatedTime)) ?></span>
                        </div>
                        <div class="user-gravatar">
                            <img class="avatar"
                                 src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($answer->Email)))) ?>?d=identicon"/>
                        </div>
                        <div class="user-details">
                            <a href="<?= $this->url->create('users/id/' . $answer->UserId) ?>">
                                <?= htmlentities($answer->Name) ?>
                            </a>
                        </div>
                    </div>
                </div>
                <!-- Comments on answer -->
                <div class="comments">
                    <?php foreach ($answer->Comments as $comment) : ?>
                        <div class="comment">
                            <span class="comment-text"><?= $comment->Content ?></span> -
                            <a class="comment-user" href="<?= $this->url->create('users/id/' . $comment->UserId) ?>">
                                <?= htmlentities($comment->Name) ?>
                            </a>
                            <span><?= gmdate("M d Y H:i", strtotime($comment->CreatedTime)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <!-- Om användaren är inloggad ska länken visas -->
            <?php if ($this->di->get('UserSession')->isAuthenticated()) : ?>
                <div class="add-comment">
                    <a href="<?= $this->url->create('questions/add-answer-comment/' . $answer->Id) ?>">
                        <i class="fa fa-comments-o" aria-hidden="true"></i> Kommentera</a>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
        <!-- Om användaren är inloggad ska länken visas -->
        <?php if ($this->di->get('UserSession')->isAuthenticated()) : ?>
            <a href="<?= $this->url->create('questions/add-answer/' . $question->Id) ?>">
                <div class="add-answer"><i class="fa fa-comments-o" aria-hidden="true"></i> Svara</div>
            </a>
        <?php endif; ?>
    </div>

</article>
