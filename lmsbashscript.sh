#!/bin/bash

# Generate models without migration files
# php artisan make:model RechargeSupplyTransaction -f
# php artisan make:model RechargeSupplyHistory -f
# php artisan make:model ServicesBetaUser -f
# php artisan make:model RechargeSimDetail -f

#make seeder
# php artisan make:seeder RechargeSupplyTransactionSeeder
# php artisan make:seeder RechargeSupplyHistorySeeder
# php artisan make:seeder ServicesBetaUserSeeder
# php artisan make:seeder RechargeSimDetailSeeder

# Run seeders   
# php artisan db:seed --class=ServicesBetaUserSeeder
# php artisan db:seed --class=RechargeSimDetailSeeder
# php artisan db:seed --class=RechargeSupplyTransactionSeeder
# php artisan db:seed --class=RechargeSupplyHistorySeeder

#make Controller
# php artisan make:controller CheckStatusApiController
# php artisan make:controller CheckBalanceApiController
# php artisan make:controller RechargeApiController
# php artisan make:controller TransactionApiController

#make Controller
# php artisan make:request AddSupplyRequest
# php artisan make:request DashboardRequest
# php artisan make:request TranscationReportRequest
# php artisan make:request SimDetailListRequest

#make event 
# php artisan make:event AddLapuProcessed
# php artisan make:event CheckBalanceProcessed
# php artisan make:event RechargeProcessed
# php artisan make:event CheckStatusProcessed

#make listener
# php artisan make:listener HandleAddLapuResponse
# php artisan make:listener HandleCheckBalanceResponse
# php artisan make:listener HandleRechargeResponse
# php artisan make:listener 


#!/bin/bash

# Create controllers for the API routes
php artisan make:controller RegisterController
php artisan make:controller LoginController
php artisan make:controller ForgotPasswordController
php artisan make:controller ResetPasswordController
php artisan make:controller DashboardController
php artisan make:controller LoanDetailsController
php artisan make:controller LoanViewDetailsController
php artisan make:controller TxnDetailsController
php artisan make:controller ProfileDetailsController
php artisan make:controller BankDetailsController
php artisan make:controller LoanApplicationController
php artisan make:controller EmiRepaymentController
php artisan make:controller UserDetailsController