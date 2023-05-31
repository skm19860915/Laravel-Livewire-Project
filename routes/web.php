<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TicketController;
use App\Http\Controllers\ExportsController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\PricingController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\LocationController;
use App\Http\Controllers\LogExcelController;
use App\Http\Controllers\ScheduleController;
use App\Http\Controllers\ClinicInfoController;
use App\Http\Controllers\ReceivableController;
use App\Http\Controllers\ScheduleTypeController;
use App\Http\Controllers\ScheduleBlockController;
use App\Http\Controllers\MarketingSourceController;
use App\Http\Controllers\DailyStatsNotificationController;
use \App\Http\Livewire\CreateEmail;
use \App\Http\Livewire\EmailList;
use \App\Http\Livewire\EditEmail;

Route::redirect('/', '/dashboard');
Auth::routes();

Route::middleware(['auth'])->group(function () {
    Route::middleware('locationRequire', "IsBelongToLocation", "LocationCheck")->group(function () {
        Route::get('/home', [HomeController::class, 'index'])->name('home');
        Route::group(['middleware' => ["can:allExceptMarketingOnly,App\Models\User"]], function () {
            Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');
            //Patients
            Route::get('/patients', [PatientController::class, 'index'])->name('patient.index');
            Route::get('/create/patient', [PatientController::class, 'create'])->name('patient.create');
            Route::post('/create/patient', [PatientController::class, 'store'])->name('patient.store');
            Route::get('/edit/patient/{patient}', [PatientController::class, 'edit'])->name('patient.edit');
            Route::post('/edit/patient/{patient}', [PatientController::class, 'update'])->name('patient.update');
            Route::get('/overview/patient/{patient}', [PatientController::class, 'overview'])->name('patient.overview');
            Route::post('/communication/patient/delete', [PatientController::class, 'deleteEmailCommunication'])->name('patient.email');

            //Schedule
            Route::get('/schedule', [ScheduleController::class, 'index'])->name('schedule.index');
            Route::get('create/appointment', [ScheduleController::class, 'create'])->name('appointment.create');
            Route::get('/new/create/appointment/{appointment}', [ScheduleController::class, 'newcreate'])->name('appointment.new.create');
            Route::post('create/appointment', [ScheduleController::class, 'store'])->name('appointment.store');
            Route::get('edit/appointment/{appointment}', [ScheduleController::class, 'edit'])->name('appointment.edit');
            Route::post('edit/appointment/{appointment}', [ScheduleController::class, 'update'])->name('appointment.update');
            Route::get('cancel/appointment/{appointment}', [ScheduleController::class, 'cancel'])->name('appointment.cancel');

            Route::get('block/create', [ScheduleBlockController::class, 'index'])->name('block.create');
            Route::post('block/store', [ScheduleBlockController::class, 'store'])->name('block.store');
            Route::get('block/edit/{id}', [ScheduleBlockController::class, 'edit'])->name('block.edit');
            Route::post('block/update/{id}', [ScheduleBlockController::class, 'update'])->name('block.update');
            Route::post('block/delete/{id}', [ScheduleBlockController::class, 'destroy'])->name('block.delete');

            //Searchc current patient
            Route::get('/ajax-search-patients', [ScheduleController::class, 'ajaxSearchPatient']);
            Route::post('/get/appts', [ScheduleController::class, 'getAppts'])->name('appts.get');
            Route::post('/get/blocks', [ScheduleController::class, 'getApptBlocks'])->name('blocks.get');

            // Ticket
            Route::get('/tickets', [TicketController::class, 'index'])->name('ticket.index');
            Route::get('/patient/tickets/{patient}', [TicketController::class, 'patientTicket'])->name('patient.tickets');
            Route::match(['post', 'get'], '/filter/tickets', [TicketController::class, 'filter'])->name('ticket.filter');
            Route::get('/edit/ticket/{ticket}', [TicketController::class, 'editTicket'])->name('ticket.editTicket');
            Route::get('/view/ticket/{ticket}', [TicketController::class, 'view'])->name('ticket.view');
            Route::group(['middleware' => 'PatientCheckIn'], function () {
                Route::get('/create/ticket/{appointment?}', [TicketController::class, 'create'])->name('ticket.create');
            });
            Route::post('/create/ticket/{appointment?}', [TicketController::class, 'store'])->name('ticket.store');
            Route::get('/edit/ticket/{ticket}/{appointment}', [TicketController::class, 'edit'])->name('ticket.edit');
            Route::post('/edit/ticket/{ticket}/{appointment}', [TicketController::class, 'update'])->name('ticket.update');
            Route::get('/revisit/ticket/{ticket}', [TicketController::class, 'revisit'])->name('ticket.revisit');
            Route::get('/undorevisit/ticket/{ticket}', [TicketController::class, 'undoRevisit'])->name('ticket.undoRevisit');
            Route::group(['middleware' => "can:converBackToAppt,App\Models\User"], function () {
                Route::get('/delete/ticket/{ticket}', [TicketController::class, 'delete'])->name('ticket.delete');
            });
            // Payment
            Route::group(['middleware' => 'PaymentIncrementsCheck'], function () {
                Route::get('payment/{ticket}',  [PaymentController::class, 'create'])->name("payment.create");
                Route::get('payment/history/{ticket}', [PaymentController::class, 'history'])->name("payment.history");
                Route::post('create/payment/{ticket}', [PaymentController::class, 'store'])->name("payment.store");
            });
            Route::get('refund/payment/{payment}', [PaymentController::class, 'refund'])->name("payment.refund");
            //Receivable
            Route::get('/receivables', [ReceivableController::class, 'index'])->name("receivable.index");
        });

        Route::group(['middleware' => 'can:superadminORadminORmanagerORmarketingOnly, App\Models\User'], function () {
            //Reports
            Route::match(['get', 'post'], '/reports/marketing', [ReportController::class, 'marketing'])->name('report.marketing');
        });
        Route::group(['middleware' => 'can:superadminORadminORmanager, App\Models\User'], function () {
            //Reports
            //   Route::get('/reports/marketing', [App\Http\Controllers\ReportController::class, 'marketing']);
            Route::match(['get', 'post'], '/reports/financial', [ReportController::class, 'financial'])->name('report.financial');
            Route::match(['get', 'post'], '/reports/sales-by-product', [ReportController::class, 'salesByProduct'])->name('report.sales-by-product');
            Route::match(['get', 'post'], '/reports/marketing-trend', [ReportController::class, 'marketingTrend'])->name('report.marketing-trend');
        });

        //Profile
        Route::get('/my-account', [UserController::class, 'profile'])->name('my-account');
        Route::post('/update/account', [UserController::class, 'profile'])->name('update-account');
        //Set Current Location
        Route::get('/set/current-location/{location}', [LocationController::class, 'setCurrentLocation'])->name('set-location');
        Route::group(['middleware' => 'can:superadminORadminORmanagerORmarketingOnly, App\Models\User'], function () {
            Route::prefix('settings')->group(function () {
                //Marketing
                Route::get('/marketing-info', [MarketingSourceController::class, 'index'])->name('marking-source-info');
                Route::get('/create/marketing-source', [MarketingSourceController::class, 'create'])->name('marking-source.create');
                Route::post('/create/marketing-source', [MarketingSourceController::class, 'store'])->name('marking-source.store');
                Route::get('/edit/marketing-source/{marketingSource}', [MarketingSourceController::class, 'edit'])->name('marking-source.edit');
                Route::post('/edit/marketing-source/{marketingSource}', [MarketingSourceController::class, 'update'])->name('marking-source.update');
                Route::get('/toggel/disable/marketing-source/{marketingSource}', [MarketingSourceController::class, 'toggelDisable'])->name('marking-source.toggelDisable');
                // Route::get('/delete/marketing-source/{marketingSource}', [MarketingSourceController::class, 'destroy']);
                //ClinciInfo
                Route::get('/edit/clinic-info', [ClinicInfoController::class, 'index'])->name('clinic-info.index');
                Route::post('/edit/clinic-info/{clinicInfo}', [ClinicInfoController::class, 'update'])->name('clinic-info.update');

                //Email
                Route::get('/emails', EmailList::class)->name('email.list');
                Route::post('/emails', [EmailList::class, 'updateStatus'])->name('email.updateStatus');
                Route::get('/create-email', CreateEmail::class)->name('email.create');
                Route::get('/emails/{id}', EditEmail::class)->name('email.edit');

                //Locations
                Route::group(['middleware' => 'can:manageLocations, App\Models\User'], function () {
                    Route::get('/locations', [LocationController::class, 'index'])->name('location.index');
                    Route::get('/create-location', [LocationController::class, 'create'])->name('location.create');
                    Route::post('/add-location', [LocationController::class, 'store'])->name('location.store');
                    Route::get('/locations/{id}', [LocationController::class, 'edit'])->name('location.edit');
                    Route::post('/update-location', [LocationController::class, 'update'])->name('location.update');
                });

                //Roles
                Route::group(['middleware' => "can:manageRoles, App\Models\User"], function () {
                    Route::get('/roles', [RoleController::class, 'index'])->name('role.index');
                    Route::get('/create/role', [RoleController::class, 'create'])->name('role.create');
                    Route::post('/create/role', [RoleController::class, 'store'])->name('role.store');
                    Route::get('/edit/role/{role}', [RoleController::class, 'edit'])->name('role.edit');
                    Route::post('/edit/role/{role}', [RoleController::class, 'update'])->name('role.update');
                });

                //Users
                Route::group(['middleware' => 'can:manageUsers, App\Models\User'], function () {
                    Route::get('/users', [UserController::class, 'index'])->name('user.index');
                    Route::get('/create-user', [UserController::class, 'create'])->name('user.create');
                    Route::post('/add-user', [UserController::class, 'store'])->name('user.store');
                    Route::group(['middleware' => 'CanEdit'], function () {
                        Route::get('/users/{id}', [UserController::class, 'edit'])->name('user.edit');
                        Route::post('/update-user', [UserController::class, 'update'])->name('user.update');
                        Route::get('/disable/{user}', [UserController::class, 'disable'])->name('user.disable');
                        Route::get('/active/{user}', [UserController::class, 'active'])->name('user.active');
                    });
                });

                //Schedule Type
                Route::group(['middleware' => "can:manageScheduleTypes, App\Models\User"], function () {
                    Route::get("/schedule-types", [ScheduleTypeController::class, 'index'])->name('schedule-type.index');
                    Route::get("/create/schedule-type", [ScheduleTypeController::class, 'create'])->name('schedule-type.create');
                    Route::post("/create/schedule-type", [ScheduleTypeController::class, 'store'])->name('schedule-type.store');
                    Route::get("/edit/schedule-type/{scheduleType}", [ScheduleTypeController::class, 'edit'])->name('schedule-type.edit');
                    Route::post("/edit/schedule-type/{scheduleType}", [ScheduleTypeController::class, 'update'])->name('schedule-type.update');
                });

                Route::group(['middleware' => ['can:managePricing, App\Models\User', 'locationRequire']], function () {
                    /**
                     *  Pricing Routes
                     */
                    Route::get('/pricing', [PricingController::class, 'index'])->name('pricing');

                    /**
                     * Product Routes
                     */
                    Route::post('/create/product', [ProductController::class, 'store'])->name('product.store');
                    Route::post('/delete/product/{product}', [ProductController::class, 'destroy'])->name('product.delete');
                    Route::post('/enable/product/{product}', [ProductController::class, 'enable'])->withTrashed()->name('product.enable');
                    Route::post('/restore/product/{product}', [ProductController::class, 'restore'])->withTrashed()->name('product.restore');
                    Route::post('/edit/product/{product}', [ProductController::class, 'update'])->withTrashed()->name('product.update');
                    /**
                     * Service Routes
                     */
                    Route::post('/create/service', [ServiceController::class, 'store'])->name('service.store');
                    Route::post('/enable/service/{service}', [ServiceController::class, 'destroy'])->name('service.enable');
                    Route::post('/edit/service/{service}', [ServiceController::class, 'update'])->name('service.update');
                });
            });
        });

        //Log
        Route::group(['middleware' => 'can:superadmin, App\Models\User'], function () {
            Route::get('/log/index', [LogExcelController::class, 'index'])->name('log.index');
            Route::get('/create/daily/stats/notification', [DailyStatsNotificationController::class, 'create'])->name('notification.create');
            Route::post('/create/daily/stats/notification', [DailyStatsNotificationController::class, 'store'])->name('notification.store');
        });

        //EXPORTS
        Route::group(['middleware' => 'can:superadmin, App\Models\User'], function () {
            Route::get('/export/patients', [ExportsController::class, 'patients'])->name('exports.patinets');
            Route::get('/export/tickets', [ExportsController::class, 'tickets'])->name('exports.tickets');
            Route::get('/export/schedules', [ExportsController::class, 'schedules'])->name('exports.schedules');
            Route::get('/export/payments', [ExportsController::class, 'payments'])->name('exports.payments');
            //   Route::get('/export/log',[ExportsController::class,'log'])->name('exports.log');
        });

        Route::group(['middleware' => 'can:export, App\Models\User'], function () {
            Route::post('/export/sales-by-product', [ExportsController::class, 'salesByProduct'])->name('exports.sales-by-product');
        });

        Route::group(['middleware' => 'can:manageZingleIntegration, App\Models\User'], function () {
            Route::get('/zingle-integration/{location_id}', [App\Http\Controllers\ZingleIntegrationController::class, 'index']);
            Route::post('/zingle-integration/store', [App\Http\Controllers\ZingleIntegrationController::class, 'store']);
            Route::put('/zingle-integration/update/{zingle_integration}', [App\Http\Controllers\ZingleIntegrationController::class, 'update']);
        });
    });
});
