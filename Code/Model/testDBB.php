<?php

namespace Model;


use AbsenceModel;

require_once "AbsenceModel.php";

class testDBB
{
    private AbsenceModel $absence;

    public function __construct()
    {
        $this->absence = new AbsenceModel();
    }

    public function getAbsence(): array
    {
        return $this->absence->getAllAbsence();
    }

}