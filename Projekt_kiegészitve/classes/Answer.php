<?php

class Answer
{
    private $id;
    private $questionId;
    private $text;
    private $isCorrect;

    /**
     * @param $id
     * @param $questionId
     * @param $text
     * @param $isCorrect
     */
    public function __construct($id, $questionId, $text, $isCorrect)
    {
        $this->id = $id;
        $this->questionId = $questionId;
        $this->text = $text;
        $this->isCorrect = $isCorrect;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getQuestionId()
    {
        return $this->questionId;
    }

    public function getText()
    {
        return $this->text;
    }

    public function isCorrect()
    {
        return $this->isCorrect;
    }
}

