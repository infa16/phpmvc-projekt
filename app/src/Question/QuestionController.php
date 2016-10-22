<?php

namespace Anax\Question;

class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;
    use \Anax\MVC\TRedirectHelpers;

    /**
     * Initializes the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->model = new \Anax\Question\QuestionModel();
        $this->model->setDI($this->di);
    }

    public function listAction($title = 'Alla frågor', $tag = null)
    {
        $query = $this->db
            ->select('q.*,
              (SELECT count(*)
               FROM wgtotw_Answer a
               WHERE a.QuestionId = q.Id) AS AnswerCount,
               u.Id as UserId, u.Name, u.Email')
            ->from('Question q')
            ->join('User u', 'u.Id = q.CreatedBy');
        $params = [];
        if ($tag) {
            $query->join('QuestionTagLink l', 'l.QuestionId=q.Id AND l.TagId=?');
            $params[] = $tag;
        }
        $query->execute($params);
        $questions = $this->db->fetchAll();

        foreach ($questions as $question) {
            $question->Content = substr($this->textFilter->doFilter($question->Content, 'markdown'), 0, 200);
            $this->addTags($question);
        }

        $this->di->views->add('questions/list', [
            'title' => $title,
            'questions' => $questions
        ], 'full');
    }

    /**
     * Lists question with id
     *
     * @param null $questionId
     */
    public function idAction($questionId = null)
    {
        $this->db
            ->select('q.*, u.Id as UserId, u.Name, u.Email')
            ->from('Question q')
            ->join('User u', 'u.Id = q.CreatedBy')
            ->where('q.Id = ?')
            ->execute([$questionId]);
        $question = $this->db->fetchOne();

        if (!$question) {
            $this->redirectTo('questions');
            return;
        }

        $this->addTags($question);
        $this->addComments($question, 1);

        $this->db
            ->select('a.*, u.Id as UserId, u.Name, u.Email')
            ->from('Answer a')
            ->join('User u', 'u.Id = a.CreatedBy')
            ->where('a.QuestionId = ?')
            ->orderby('a.Id', 'DESC')
            ->execute([$question->Id]);
        $answers = $this->db->fetchAll();

        foreach ($answers as $answer) {
            $answer->Content = $this->textFilter->doFilter($answer->Content, 'markdown');
            $this->addComments($answer, 2);
        }

        $question->Content = $this->textFilter->doFilter($question->Content, 'markdown');

        $this->theme->setTitle("Fråga");
        $this->di->views->add('questions/question', [
            'question' => $question,
            'answers' => $answers
        ], 'full');
    }

    /**
     * Fetches tag/s to the question
     *
     * @param $question
     */
    private function addTags($question)
    {
        $this->db
            ->select('t.*')
            ->from('QuestionTagLink l')
            ->join('Tag t', 't.Id = l.TagId')
            ->where('l.QuestionId = ?')
            ->execute([$question->Id]);
        $this->db->setFetchModeClass('\Anax\Tag\TagModel');
        $question->Tags = $this->db->fetchAll();
    }

    /**
     * Fetches comment/s to question/answer
     *
     * @param $owner
     * @param $type
     */
    private function addComments($owner, $type)
    {
        $this->db
            ->select('c.*, u.Id as UserId, u.Name, u.Email')
            ->from('Comment c')
            ->join('User u', 'u.id = c.CreatedBy')
            ->where('c.ReferenceType = ?')
            ->andWhere('c.ReferenceId = ?')
            ->execute([$type, $owner->Id]);

        $owner->Comments = $this->db->fetchAll();

        foreach ($owner->Comments as $comment) {
            $comment->Content = $this->textFilter->doFilter($comment->Content, 'markdown');
        }
    }

    /**
     * Adds a new question
     *
     * @return void
     */
    public function addAction()
    {
        $this->di->UserSession->requireSession();

        $form = new QuestionForm($this->di);
        $form->check();

        $this->theme->setTitle("Ny fråga");
        $this->di->views->add('default/page', [
            'title' => "Ställ en fråga",
            'content' => $form->getHTML()
        ]);
    }

    /**
     * Adds comment to question
     *
     * @param null $questionId
     */
    public function addQuestionCommentAction($questionId = null)
    {
        $model = new QuestionModel();
        $model->setDI($this->di);
        if (!$model->find($questionId)) {
            $this->redirectTo('questions');
        }
        $this->addComment($questionId, $questionId, 1);
    }

    /**
     * Adds comment to answer
     *
     * @param null $answerId
     */
    public function addAnswerCommentAction($answerId = null)
    {
        $model = new AnswerModel();
        $model->setDI($this->di);
        if (!$model->find($answerId)) {
            $this->redirectTo('questions');
        }
        $this->addComment($model->QuestionId, $answerId, 2);
    }

    /**
     * Gets form for comment on question/answer
     *
     * @param null $referenceId Question id
     * @param null $type 1 = Question comment, 2 = Answer comment
     */
    private function addComment($questionId, $referenceId, $type)
    {
        $this->di->UserSession->requireSession();

        $form = new CommentForm($questionId, $referenceId, $type);
        $form->setDI($this->di);
        $form->check();

        $this->theme->setTitle("Kommentera");
        $this->di->views->add('default/page', [
            'title' => "Skriv en kommentar",
            'content' => $form->getHTML()
        ]);
    }

    /**
     * Gets form for answer to question
     *
     * @return void
     */
    public function addAnswerAction($questionId = null)
    {
        if (!$questionId) {
            $this->redirectTo('questions');
        }

        $this->di->UserSession->requireSession();

        $form = new AnswerForm($questionId);
        $form->setDI($this->di);
        $form->check();

        $this->theme->setTitle("Svara");
        $this->di->views->add('default/page', [
            'title' => "Skriv ett svar",
            'content' => $form->getHTML()
        ]);
    }
}
