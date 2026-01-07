<?php
declare(strict_types=1);

namespace test;

require "../Model/AbsenceModel.php";

use AbsenceModel;
use PHPUnit\Framework\TestCase;


class AbsenceModelTest extends TestCase
{
    private AbsenceModel $model;
    protected function setUp(): void
    {
        $this->model = new AbsenceModel();
    }

    protected function tearDown(): void
    {
        $this->model = null;
    }

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
