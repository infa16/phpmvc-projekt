<article class="article1">
    <h1>Senaste frågorna</h1>

    <?php foreach ($questions as $question) : ?>
        <div class="start-question">
            <div class="start-summary">
                <div class="start-statscontainer">
                    <div class="start-answers"><?= $question->AnswerCount ?> svar</div>
                </div>

                <div class="start-header">
                    <h3>
                        <a class="question-hyperlink"
                           href="<?= $this->url->create('questions/id/' . $question->Id) ?>"><?= htmlentities($question->Title) ?></a>
                    </h3>
                </div>
            </div>

            <div class="start-question-user-info">
                <span class="start-user-action-time">
                    <?= gmdate("M d Y H:i", strtotime($question->CreatedTime)) . ' -' ?>
                </span>
                <a class="start-user-details" href="<?= $this->url->create('users/id/' . $question->UserId) ?>">
                    <?= htmlentities($question->Name) ?>
                </a>
            </div>
        </div>
    <?php endforeach; ?>
</article>
