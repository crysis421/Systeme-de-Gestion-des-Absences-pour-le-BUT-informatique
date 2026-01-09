<?php

namespace test;

require "../Model/AbsenceModel.php";

use AbsenceModel;
use PHPUnit\Framework\TestCase;


class AbsenceModelTest extends TestCase
{
    private AbsenceModel $model; //Cette variable va contenir l'instance de la classe AbsenceModel
    protected function setUp(): void //Le setUp se produit avant chaque test
    {
        $this->model = new AbsenceModel();
    }

    protected function tearDown(): void//Le tearDown se produit a la fin de chaque test
    {
        $this->model = null;
    }

    //Nos TEST :
    public function testmodifMDP()
    {
        $this->assertEquals("Le mot de passe a bien été modifié",$this->model->ModifierMDP("kilian.stievenard2@uphf.fr","1234"));
    }

    public function testgetUser(){
        $this->assertEquals('kilian.stievenard2',$this->model->getUser(1));
    }

    public function testgetAllMatiere(){
        $this->assertTrue(sizeof($this->model->getMatieres())>1);
    }


}
