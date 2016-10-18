<?php

namespace Anax\Tag;


class TagController implements \Anax\DI\IInjectionAware
{
    use \Anax\DI\TInjectable;


    /**
     * Initialize the controller.
     *
     * @return void
     */
    public function initialize()
    {
        $this->model = new \Anax\Tag\TagModel();
        $this->model->setDI($this->di);

    }


    /**
     * List all tags.
     *
     * @return void
     */
    public function listAction()
    {
        $this->db
            ->select('t.*, 
             (SELECT count(*) 
             FROM wgtotw_QuestionTagLink l 
             WHERE t.Id = l.TagId) AS TagCount')
            ->from('Tag t')
            ->execute();

        $tags = $this->db->fetchAll();

        $this->di->views->add('tags/list', [
            'tags' => $tags
        ], 'full');
    }


    /**
     * List questions with tag-id
     *
     * @param null $tagId
     * @internal param null $userId
     * @internal param int $id of user to display
     */
    public function idAction($tagId = null)
    {
        $tag = $this->model->find($tagId);
        $this->di->QuestionsController->listAction('Frågor med taggen '. $tag->Tag, $tagId);
    }
}