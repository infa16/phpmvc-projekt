<article class="article1">

    <!-- Question -->

    <div id="question">

        <h1><?= htmlentities($question->Title) ?></h1>
        <div class="post-cell">
            <div class="post-text">
                <p>
                    <?= $question->Content ?>
                </p>
            </div>
            <div class="post-info">
                <div class="tags">
                    <?php foreach ($question->Tags as $tag) : ?>
                        <a class="tag"
                           href="<?= $this->url->create('tags/id/' . $tag->Id) ?>"><?= htmlentities($tag->Tag) ?></a>
                    <?php endforeach; ?>
                </div>
                <div class="user-info">
                    <div class="user-action-time">
                        frågade
                    </div>
                     <span class="relativetime">
                         <?= gmdate("M d Y H:i", strtotime($question->CreatedTime)) ?>
                     </span>
                    <div class="user-gravatar">
                        <img class="avatar"
                             src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($question->Email)))) ?>?d=identicon"/>
                    </div>
                    <div class="user-details"><a
                            href="<?= $this->url->create('users/id/' . $question->UserId) ?>"><?= htmlentities($question->Name) ?></a>
                    </div>
                </div>
            </div>
            <div class="comments">
                <?php foreach ($question->Comments as $comment) : ?>
                    <div class="comment">
                        <span class="comment-text"><?= $comment->Content ?></span> -
                        <a class="comment-user" href="<?= $this->url->create('users/id/' . $comment->UserId) ?>">
                            <?= htmlentities($comment->Name) ?></a>
                        <span class="relativetime"><?= gmdate("M d Y H:i", strtotime($comment->CreatedTime)) ?></span>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Answer -->

    <div id="answers">

        <h2><?= count($answers) ?> svar</h2>

        <?php foreach ($answers as $answer) : ?>
            <div class="post-cell">
                <div class="post-text">
                    <p><?= $answer->Content ?></p>
                </div>
                <div class="post-info">
                    <div class="user-info">
                        <div class="user-action-time">
                            svarade
                        <span class="relativetime">
                            <?= gmdate("M d Y H:i", strtotime($answer->CreatedTime)) ?>
                        </span>
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
                <div class="comments">
                    <?php foreach ($answer->Comments as $comment) : ?>
                        <div class="comment">
                            <span class="comment-text"><?= $comment->Content ?></span> -
                            <a class="comment-user" href="<?= $this->url->create('users/id/' . $comment->UserId) ?>">
                                <?= htmlentities($comment->Name) ?></a>
                            <span
                                class="relativetime"><?= gmdate("M d Y H:i", strtotime($comment->CreatedTime)) ?></span>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>

            <!-- Comments on answer -->


        <?php endforeach; ?>
    </div>

</article>
