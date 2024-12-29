<?php

namespace App\Repository;


interface DashboardRepository {

    public function getuserloanswidget($userId);
    public function getnextpaymentwidget($userId);

}