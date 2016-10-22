<?php

namespace Anax;

class StartController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;

    public function indexAction()
    {
        $this->di->views
            ->add('start/start', [
                'questions' => $this->getRecentQuestions()
            ], 'main')
            ->add('start/sidebar', [
                'users' => $this->getActiveUsers(),
                'tags' => $this->getPopularTags()
            ], 'sidebar');
    }

    private function getRecentQuestions()
    {
        $this->db
            ->select('distinct q.*, u.Id as UserId, u.Name, u.Email,
              (SELECT count(*)
               FROM wgtotw_Answer a
               WHERE a.QuestionId = q.Id) AS AnswerCount')
            ->from('Question q')
            ->join('User u', 'u.Id = q.CreatedBy')
            ->join('QuestionTagLink l', 'l.QuestionId=q.Id')
            ->orderby('q.CreatedTime DESC')
            ->limit(5)
            ->execute();
        return $this->db->fetchAll();
    }

    private function getActiveUsers()
    {
        $this->db
            ->select('u.*,
              (select count(*) from wgtotw_Question q where q.CreatedBy=u.Id)+
              (select count(*) from wgtotw_Answer a where a.CreatedBy=u.Id)+
              (select count(*) from wgtotw_Comment c where c.CreatedBy=u.Id) as Score')
            ->from('User u')
            ->orderBy('Score DESC, u.Name')
            ->limit(3)
            ->execute();
        return $this->db->fetchAll();
    }

    private function getPopularTags()
    {
        $this->db
            ->select('count(*) as TagCount, t.*')
            ->from('QuestionTagLink l')
            ->join('Tag t', 't.Id = l.TagId')
            ->groupBy('t.Tag')
            ->orderBy('TagCount DESC')
            ->limit(4)
            ->execute();
        return $this->db->fetchAll();
    }
}
