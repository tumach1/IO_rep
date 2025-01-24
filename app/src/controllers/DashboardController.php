<?php

require_once 'SessionController.php';

class DashboardController extends SessionController
{
    public function call()
    {
        if($this->isReader())
        {
            $this->render("dashboard.php");
        }
        elseif($this->isWorker())
        {
            $this->render("dashboardWorker.php");
        }
        else
        {
            $this->render("Login.php");
        }
    }


}
