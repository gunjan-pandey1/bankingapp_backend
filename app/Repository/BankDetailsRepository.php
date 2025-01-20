<?php

namespace App\Repository;



interface BankDetailsRepository {

    public function createBankDetails(array $bankDetailsInsertBo);
    public function updateBankDetails(array $bankDetailsUpdateBo);
    public function getBankDetails(array $bankDetailsGetBo);

}