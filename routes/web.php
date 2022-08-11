<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthenticationController;
use App\Http\Controllers\SurgeryController;
use App\Http\Controllers\PharmacyController;
use App\Http\Controllers\RxController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\StaffController;

Route::get('/login', [AuthenticationController::class, 'index'])->name('site.login');
Route::get('/admin/login', [AuthenticationController::class, 'index'])->name('login');
Route::get('/staff-signup', [AuthenticationController::class, 'staffSignup'])->name('staffSignup');
Route::post('/staff-store', [AuthenticationController::class, 'staffStore'])->name('staff.register');
Route::get('/staff-login', [AuthenticationController::class, 'staffLoginView'])->name('staff.login.view');
Route::post('/login', [AuthenticationController::class, 'parentLogin'])->name('custom.login');
Route::post('/staff-login', [AuthenticationController::class, 'staffLogin'])->name('staff.login');
Route::post('/staff-logout', [AuthenticationController::class, 'staffLogout'])->name('staff.logout');
Route::post('/pharmacy-logout', [AuthenticationController::class, 'pharmacyLogout'])->name('pharmacy.logout');
Route::get('ip-auth-verification', [AuthenticationController::class, function () {
  return view('auth_code');
}])->name('auth_code.view');
Route::post('ip-auth-verify', [AuthenticationController::class, 'verifyAuthCode'])->name('verify.auth_code');
Route::post('resend-auth-code', [AuthenticationController::class, 'resendAuthCodeMail'])->name('resend.auth_code');
Route::get('/sign-out', [AuthenticationController::class, 'signout'])->name('signout');
Route::get('/profile-show', [AuthenticationController::class, 'profile'])->name('profile');
Route::post('/reset-password', [AuthenticationController::class, 'resetPassword'])->name('reset.password');
Route::post('/pro-update', [AuthenticationController::class, 'profileUpdate'])->name('pro.update');
//Admin routes
Route::prefix('admin')->middleware(['auth:sanctum', 'verified', 'admin'])->group(function () {
  Route::resources([
    'surgery' => SurgeryController::class,
    'pharmacy' => PharmacyController::class,
    'rx' => RxController::class,
    'patient' => PatientController::class,
    'staff' => StaffController::class,
  ]);

  Route::get('/patients-edit', [RxController::class, 'patientEdit'])->name('patientEdit');

  Route::get('/autocomplete-drugs', [RxController::class, 'autoDrugs'])->name('autocomplete.drugs');
  Route::post('/save-uncollected-rxs', [RxController::class, 'saveUncollectedRx'])->name('create.uncollected.rxes');
  Route::post('/reorder', [RxController::class, 'reorder'])->name('rx.reorder');
  Route::post('/rxes-save-func', [RxController::class, 'rxsSave'])->name('rxs.save.func');
  Route::get('/pharmacy-managed-patient', [PatientController::class, 'pharmacyManaged'])->name('pharmacy.managed');
  Route::post('/load-order', [RxController::class, 'loadOrder'])->name('rx.loadOrder');
  Route::post('/load-order-new', [RxController::class, 'loadOrder_new'])->name('rx.loadOrder_new');
  Route::get('export-pdf', [RxController::class, 'exportPdf'])->name('export-pdf');
  Route::get('/show/{id}', [RxController::class, 'showFax'])->name('showFax');
  Route::get('/faxes', [RxController::class, 'faxes'])->name('faxes');
  Route::get('/load_pharmacy', [PharmacyController::class, 'load_pharmacy'])->name('load_pharmacy');
  Route::get('/load_surgery', [SurgeryController::class, 'load_surgery'])->name('load_surgery');
  Route::get('delete/{id}', [RxController::class, 'delete'])->name('rx.delete');
  Route::get('staff-list', [StaffController::class, 'list'])->name('staff.list');
  Route::get('load_patient/{id?}', [PatientController::class, 'load_patient'])->name('load_patient');
  Route::get('/urgentRX', [RxController::class, 'urgentRX'])->name('urgentRX');
  Route::get('/noturgentRX/{id}', [RxController::class, 'noturgentRX'])->name('noturgentRX');
  Route::get('rx-full-list', [RxController::class, 'withTrashed'])->name('rx.full_list');
  Route::get('/rx-collection', [RxController::class, 'rxCollection'])->name('rx.collection');
  Route::get('/rx-medication-all-collect/{id}', [RxController::class, 'rxAllMedicationCollect'])->name('rx.medication.all.collect');
  Route::get('/rx-collected', [RxController::class, 'rxCollected'])->name('rx.collected');
  Route::get('/load-rx-patient', [RxController::class, 'loadRxPatient'])->name('load.rx.patient');
  Route::get('/rx-popup-medication-collection', [RxController::class, 'rxPopupMedicationCollection'])->name('rx.popup.medication.collection');
  Route::post('/rx-popup-medication-collection', [RxController::class, 'rxPopupMedicationCollectionUpdate'])->name('rx.popup.medication.collection.update');
  
  Route::post('/rx-for-replicate', [RxController::class, 'newPopup'])->name('rx.new.popup');
  Route::post('/save-collected-note', [RxController::class, 'save_collected_note'])->name('save_collected_note');

  Route::get('/order', [PatientController::class, 'order'])->name('order');
  Route::get('/pending-order', [PatientController::class, 'pendingOrder'])->name('pendingOrder');
  Route::get('/completed-order', [PatientController::class, 'completedOrder'])->name('completedOrder');
  //Route::get('/approve/{id}', [PatientController::class, 'approve'])->name('approve');
  Route::get('/branch-login/{id}', [AuthenticationController::class, 'branchLogin'])->name('branch.login');
  Route::get('/urgent_collection', [SurgeryController::class, 'urgent_collection'])->name('urgent_collection');
  Route::get('/surgery-all_collection', [SurgeryController::class, 'all_collection'])->name('surgery.all_collection');
  Route::get('/surgery-rx/{id}', [SurgeryController::class, 'viewSurgeryRx'])->name('surgery.rx');
  Route::get('/remove-surgery/{id}', [SurgeryController::class, 'destroy'])->name('surgery.destroy');
  Route::get('/remove-pharmacy/{id}', [PharmacyController::class, 'destroy'])->name('pharmacy.destroy');
  Route::get('/remove-rx/{id}', [RxController::class, 'destroy'])->name('rx.destroy');
  Route::get('/remove-patient/{id}', [PatientController::class, 'destroy'])->name('patient.destroy');
});


//Pharmacist routes
Route::prefix('pharmacist')->name('pharmacist.')->middleware(['auth:sanctum', 'verified', 'pharmacist'])->group(function () {
  Route::resources([
    'surgery' => 'App\Http\Controllers\Pharmacist\SurgeryController',
    'rx' => 'App\Http\Controllers\Pharmacist\RxController',
    'patient' => 'App\Http\Controllers\Pharmacist\PatientController',
    'staff' => 'App\Http\Controllers\Pharmacist\StaffController',
  ]);

  Route::get('/patients-edits', 'App\Http\Controllers\Pharmacist\RxController@patientEdit')->name('edit.patient');
  Route::post('/store-uncollected-rxes', 'App\Http\Controllers\Pharmacist\RxController@saveUncollectedRx')->name('create.uncollected.rxes');
  Route::post('/profil-show', 'App\Http\Controllers\AuthenticationController@profile')->name('profile');
  Route::post('/load-order', 'App\Http\Controllers\Pharmacist\RxController@loadOrder')->name('loadOrder');
  Route::post('/load-order-new', 'App\Http\Controllers\Pharmacist\RxController@loadOrderNew')->name('loadOrder_new');
  Route::get('export-pdf', 'App\Http\Controllers\Pharmacist\RxController@exportPdf')->name('export-pdf');
  Route::get('/load_surgery', 'App\Http\Controllers\Pharmacist\SurgeryController@load_surgery')->name('load_surgery');
  Route::get('/show/{id}', 'App\Http\Controllers\Pharmacist\RxController@showFax')->name('showFax');
  Route::get('/faxes', 'App\Http\Controllers\Pharmacist\RxController@faxes')->name('faxes');
  Route::get('pharmacist-rx-delete/{id}', 'App\Http\Controllers\Pharmacist\RxController@delete')->name('rx.delete');
  Route::get('pharmacist-urgent_collection', 'App\Http\Controllers\Pharmacist\SurgeryController@urgent_collection')->name('surgery.urgent_collection');
  Route::get('pharmacist-all_collection', 'App\Http\Controllers\Pharmacist\SurgeryController@all_collection')->name('surgery.all_collection');
  Route::get('load_patient/{id?}', 'App\Http\Controllers\Pharmacist\PatientController@load_patient')->name('load_patient');
  Route::get('/changeUrgentRX', 'App\Http\Controllers\Pharmacist\RxController@urgentRX')->name('rx.changeUrgentRX');
  Route::get('/changeNoturgentRX/{id}', 'App\Http\Controllers\Pharmacist\RxController@noturgentRX')->name('rx.changeNoturgentRX');
  Route::get('pharmacist-surgery-collected', 'App\Http\Controllers\Pharmacist\SurgeryController@collected')->name('surgery.collected');
  Route::get('rx-full-list', 'App\Http\Controllers\Pharmacist\RxController@withTrashed')->name('rx.full_list');
  Route::get('/order', 'App\Http\Controllers\Pharmacist\PatientController@order')->name('order');
  Route::get('/pending-order', 'App\Http\Controllers\Pharmacist\PatientController@pendingOrder')->name('pendingOrder');
  Route::get('/completed-order', 'App\Http\Controllers\Pharmacist\PatientController@completedOrder')->name('completedOrder');
  Route::get('/approve/{id}', 'App\Http\Controllers\Pharmacist\PatientController@approve')->name('approve');
  Route::get('/main-branch-login', [AuthenticationController::class, 'mainBranchLogin'])->name('main.branch.login');
  Route::get('/rx-collection', 'App\Http\Controllers\Pharmacist\RxController@rxCollection')->name('rx.collection');
  Route::post('/rx-reorder', 'App\Http\Controllers\Pharmacist\RxController@reorder')->name('rx.reorder');
  Route::get('/rx-collected', 'App\Http\Controllers\Pharmacist\RxController@rxCollected')->name('rx.collected');
  Route::get('/rx-popup-medication-collection', 'App\Http\Controllers\Pharmacist\RxController@rxPopupMedicationCollection')->name('rx.popup.medication.collection');
  Route::post('/rx-popup-medication-collection', 'App\Http\Controllers\Pharmacist\RxController@rxPopupMedicationCollectionUpdate')->name('rx.popup.medication.collection.update');

  Route::post('/rxs-for-replicate', 'App\Http\Controllers\Pharmacist\RxController@newPopup')->name('rx.new.popup');
  Route::post('/save-collected-note', 'App\Http\Controllers\Pharmacist\RxController@save_collected_note')->name('save_collected_note');


  Route::get('/rx-medication-all-collect/{id}', 'App\Http\Controllers\Pharmacist\RxController@rxAllMedicationCollect')->name('rx.medication.all.collect');
  Route::get('/load-rx-patient', 'App\Http\Controllers\Pharmacist\RxController@loadRxPatient')->name('load.rx.patient');
  Route::get('/delete-surgery/{id}', 'App\Http\Controllers\Pharmacist\SurgeryController@remove')->name('surgery.delete');
  Route::get('/remove/{id}', 'App\Http\Controllers\Pharmacist\RxController@destroy')->name('rx.destroy');
  Route::get('/remove/{id}', 'App\Http\Controllers\Pharmacist\PatientController@destroy')->name('patient.destroy');
});
