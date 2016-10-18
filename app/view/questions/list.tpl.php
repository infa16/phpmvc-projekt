<article class="article1">

    <h1><?= $title ?></h1>

    <?php foreach ($questions as $question) : ?>
        <div id="questions">
            <div id="question-summary">

                <div class="statscontainer">
                    <div class="answers"><?= $question->AnswerCount ?> svar</div>
                </div>
                <div class="summary">
                    <h3>
                        <a class="question-hyperlink"
                           href="<?= $this->url->create('questions/id/' . $question->Id) ?>"><?= htmlentities($question->Title) ?></a>
                    </h3>
                    <div class="question-extract">
                        <?= $question->Content ?>
                    </div>

                    <div class="tags">
                        <?php foreach ($question->Tags as $tag) : ?>
                            <a class="tag" href><?= htmlentities($tag->Tag) ?></a>
                        <?php endforeach; ?>
                    </div>

                    <div class="user-info">
                        <div class="user-action-time">
                            frågade
                            <span
                                class="relativetime"><?= gmdate("M d Y H:i", strtotime($question->CreatedTime)) ?></span>
                        </div>
                        <div class="user-gravatar">
                            <img class="avatar"
                                 src="http://www.gravatar.com/avatar/<?= md5(strtolower(trim(htmlentities($question->Email)))) ?>?d=identicon"/>
                        </div>
                        <div class="user-details"><a
                                href="<?= $this->url->create('users/id/' . $question->UserId) ?>"><?= htmlentities($question->Name) ?></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    <?php endforeach; ?>
</article>
