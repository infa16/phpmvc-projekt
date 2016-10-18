<?php

namespace Anax\Question;


class QuestionController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
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
     * List question with id
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

        $this->addTags($question);
        $this->addComments($question, 1);

        $this->db
            ->select('a.*, u.Id as UserId, u.Name, u.Email')
            ->from('Answer a')
            ->join('User u', 'u.Id = a.CreatedBy')
            ->where('a.QuestionId = ?')
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
}