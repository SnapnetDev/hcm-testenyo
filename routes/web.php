<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/fetch-users', function () {
    // return phpinfo();
    \Artisan::queue('fetch:users');
    return 0;
});

Route::get('/emp', function () {
    return view('empmgt.partials.info');
});
Route::get('/', function () {
    return redirect(route('home'));;
});

Auth::routes();
//user routes
Route::get('/home', 'HomeController@index')->name('home');
Route::resource('import','ImportController')->middleware(['auth']);

Route::get('users/modal/{user_id}','UserController@modal')->name('users.modal');
Route::get('users/assignrole','UserController@assignRole')->name('users.assignrole');
Route::get('users/alterstatus','UserController@alterStatus')->name('users.alterstatus');
Route::post('users/alterstatus','UserController@alterStatus')->name('users.alterstatus.post');
Route::get('users/assignmanager','UserController@assignManager')->name('users.assignmanager');
Route::get('users/assigngroup','UserController@assignGroup')->name('users.assigngroup');
Route::get('users/search','UserController@search')->name('users.search');
Route::post('users/new','UserController@saveNew')->name('users.savenew');
Route::resource('userprofile','UserProfileController')->middleware(['auth']);
Route::resource('users', 'UserController')->middleware(['permission:manage_user','auth']);
Route::get('users/company/departmentsandbranches/{company_id}','UserController@getCompanyDepartmentsBranches')->name('users.companydepartmentsandbranches')->middleware(['permission:edit_settings','auth']);
Route::get('users/department/jobroles/{department_id}','UserController@getDepartmentJobroles')->middleware(['permission:edit_settings','auth']);
Route::resource('groups', 'UserGroupController',['names'=>['create'=>'groups.create','index'=>'groups','store'=>'groups.save','edit'=>'groups.edit','update'=>'groups.update','show'=>'groups.view','destroy'=>'groups.delete']])->middleware(['auth']);
Route::get('setfy/{year}','HomeController@setfy');
Route::get('setcpny/{company_id}','HomeController@setcpny');
Route::get('/status-change-requests', 'UserController@statusRequests')->name('status.requests')->middleware(['auth']);
Route::get('/status-change-requests', 'UserController@statusRequests')->name('status.requests')->middleware(['auth']);
Route::get('/status-change-approve/{status_request}/{answer}','UserController@approveStatusRequest')->name('approve.request')->middleware(['auth']);
//end user routes
//settings routes
Route::get('/settings', 'GlobalSettingController@index')->name('settings')->middleware(['permission:edit_settings','auth'])->middleware(['permission:edit_settings','auth']);
Route::get('/deviceclockin', 'AttendanceController@saveAttendance')->name('attendance.sync');
Route::get('/settings/companies', 'CompanySettingController@companies')->name('companies')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/companies', 'CompanySettingController@saveCompany')->name('companies.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/companies/{company_id}', 'CompanySettingController@getCompany')->name('companies.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/companies/parent/{company_id}', 'CompanySettingController@changeParentCompany')->name('companies.parent')->middleware(['permission:edit_settings','auth']);

Route::get('/settings/departments/{company_id}', 'CompanySettingController@departments')->name('departments')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/departments', 'CompanySettingController@saveDepartment')->name('departments.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/department/{department_id}', 'CompanySettingController@getDepartment')->name('departments.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/departments/delete/{department_id}', 'CompanySettingController@deleteDepartment')->name('departments.delete')->middleware(['permission:edit_settings','auth']);

Route::get('/settings/branches/{company_id}', 'CompanySettingController@branches')->name('branches')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/branches', 'CompanySettingController@saveBranch')->name('branches.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/branch/{branch_id}', 'CompanySettingController@getBranch')->name('branches.show');

Route::get('/settings/jobs/{company_id}', 'CompanySettingController@jobs')->name('jobs')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/jobs', 'CompanySettingController@saveJob')->name('jobs.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/jobs/{job_id}', 'CompanySettingController@getJob')->name('jobs.show')->middleware(['permission:edit_settings','auth']);

// system settings start
Route::get('/settings/system', 'SystemSettingController@index')->name('systemsettings')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/system/switchhassub', 'SystemSettingController@switchHasSubsidiary')->name('systemsettings.switchhassub')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/system/switchuseparent', 'SystemSettingController@switchUseParentSetting')->name('systemsettings.switchuseparent')->middleware(['permission:edit_settings','auth']);

// system settings end


//employee settings
Route::get('/settings/employee', 'EmployeeSettingController@index')->name('employeesettings')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/grades', 'EmployeeSettingController@saveGrade')->name('grades.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/grades/{grade_id}', 'EmployeeSettingController@getGrade')->name('grades.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/grades/delete/{grade_id}', 'EmployeeSettingController@deleteGrade')->name('grades.delete')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/grade_categories', 'EmployeeSettingController@saveGradeCategory')->name('grade_categories.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/grade_categories/{grade_category_id}', 'EmployeeSettingController@getGradeCategory')->name('grade_categories.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/grade_categories/delete/{grade_category_id}', 'EmployeeSettingController@deleteGradeCategory')->name('grade_categories.delete')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/qualifications', 'EmployeeSettingController@saveQualification')->name('qualifications.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/qualifications/{qualification_id}', 'EmployeeSettingController@getQualification')->name('qualifications.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/qualifications/delete/{qualification_id}', 'EmployeeSettingController@deleteQualification')->name('qualifications.delete')->middleware(['permission:edit_settings','auth']);

//employee settings end
//leave settings
Route::get('/settings/leave', 'LeaveSettingController@index')->name('leavesettings')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/leaves/save_policy', 'LeaveSettingController@savePolicy')->middleware(['permission:edit_settings','auth'])->name('leave_policy.store');

Route::post('/settings/holidays', 'LeaveSettingController@saveHoliday')->name('holidays.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/holiday/{holiday_id}', 'LeaveSettingController@getHoliday')->name('holidays.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/holidays/delete/{holiday_id}', 'LeaveSettingController@deleteHoliday')->name('holidays.delete')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/leaves', 'LeaveSettingController@saveLeave')->name('leaves.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/leave/{leave_id}', 'LeaveSettingController@getLeave')->name('leaves.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/leaves/delete/{leave_id}', 'LeaveSettingController@deleteLeave')->name('leaves.delete')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/leaveperiods', 'LeaveSettingController@saveLeavePeriod')->name('leaveperiods.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/leaveperiods/{leaveperiod_id}', 'LeaveSettingController@getLeavePeriod')->name('leaveperiods.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/leaveperiods/delete/{leaveperiod_id}', 'LeaveSettingController@deleteLeavePeriod')->name('leaveperiods.delete')->middleware(['permission:edit_settings','auth']);
//leave settings end
// attendance settings
Route::get('/settings/attendance', 'AttendanceSettingController@index')->name('attendancesettings')->middleware(['permission:edit_settings','auth']);
Route::post('/settings/working_period', 'AttendanceSettingController@saveWorkingPeriod')->name('working_periods.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/working_period/{working_period_id}', 'AttendanceSettingController@getWorkingPeriod')->name('working_periods.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/working_period/delete/{working_period_id}', 'AttendanceSettingController@deleteWorkingPeriod')->name('working_periods.delete')->middleware(['permission:edit_settings','auth']);
Route::post('/save/attendance/Settings', 'AttendanceSettingController@saveAttendanceSettings')->name('save.attendance.settings')->middleware(['permission:edit_settings','auth']);



Route::post('/settings/project', 'AttendanceSettingController@saveProject')->name('projects.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/project/{project_id}', 'AttendanceSettingController@getProject')->name('projects.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/project/delete/{project_id}', 'AttendanceSettingController@deleteProject')->name('projects.delete')->middleware(['permission:edit_settings','auth']);



Route::post('/settings/employeetype', 'AttendanceSettingController@saveEmployeeType')->name('employeetypes.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/employeetype/{employeetype_id}', 'AttendanceSettingController@getEmployeeType')->name('employeetypes.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/employeetype/delete/{employeetype_id}', 'AttendanceSettingController@deleteEmployeeType')->name('employeetypes.delete')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/costcenter', 'AttendanceSettingController@saveCostCenter')->name('costcenters.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/costcenter/{costcenter_id}', 'AttendanceSettingController@getCostCenter')->name('costcenters.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/costcenter/delete/{costcenter_id}', 'AttendanceSettingController@deleteCostCenter')->name('costcenters.delete')->middleware(['permission:edit_settings','auth']);



Route::post('/settings/allowance', 'AttendanceSettingController@saveAllowance')->name('allowances.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/allowance/{allowance_id}', 'AttendanceSettingController@getAllowance')->name('allowances.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/allowance/delete/{allowance_id}', 'AttendanceSettingController@deleteAllowance')->name('allowances.delete')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/shift', 'LeaveSettingController@saveShift')->name('shifts.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/shift/{shift_id}', 'LeaveSettingController@getShift')->name('shifts.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/shift/delete/{shift_id}', 'LeaveSettingController@deleteShift')->name('shifts.delete')->middleware(['permission:edit_settings','auth']);

Route::get('/settings/employeedesignation', 'EmployeeDesignationSettingController@index')->name('employeedesignationsettings')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/position', 'EmployeeDesignationSettingController@savePosition')->name('positions.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/position/{position_id}', 'EmployeeDesignationSettingController@getPosition')->name('positions.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/positions/delete/{position_id}', 'EmployeeDesignationSettingController@deletePosition')->name('positions.delete')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/location', 'EmployeeDesignationSettingController@saveLocation')->name('locations.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/location/{location_id}', 'EmployeeDesignationSettingController@getLocation')->name('locations.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/locations/delete/{location_id}', 'EmployeeDesignationSettingController@deleteLocation')->name('locations.delete')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/staffcategory', 'EmployeeDesignationSettingController@saveStaffCategory')->name('staffcategories.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/staffcategory/{staffcategory_id}', 'EmployeeDesignationSettingController@getStaffCategory')->name('staffcategories.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/staffcategories/delete/{staffcategory_id}', 'EmployeeDesignationSettingController@deleteStaffCategory')->name('staffcategories.delete')->middleware(['permission:edit_settings','auth']);

Route::post('/settings/holiday', 'LeaveSettingController@saveHoliday')->name('holidays.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/holiday/{holiday_id}', 'LeaveSettingController@getHoliday')->name('holidays.show')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/holiday/delete/{holiday_id}', 'LeaveSettingController@deleteHoliday')->name('holidays.delete')->middleware(['permission:edit_settings','auth']);

Route::get('/attendance/timesheet', 'AttendanceController@getWorkingDays')->name('attendance.timesheet')->middleware(['permission:view_timesheet','auth']);
Route::get('/attendance/getdetails/{attendance_id}', 'AttendanceController@getDetails')->name('attendance.getdetails')->middleware(['permission:view_timesheet','auth']);

Route::get('/timesheets', 'AttendanceController@timesheets')->name('timesheets')->middleware(['permission:view_timesheet','auth']);
Route::get('/timesheets/{timesheet_id}', 'AttendanceController@timesheetDetail')->name('timesheets.show')->middleware(['permission:view_timesheet','auth']);
Route::get('/usertimesheets/{user_id}/', 'AttendanceController@userTimesheetDetail')->name('timesheets.user')->middleware(['permission:view_timesheet','auth']);
Route::get('/generate_timesheet', 'AttendanceController@queueTimesheet')->name('timesheets.queue')->middleware(['permission:view_timesheet','auth']);
Route::get('/timesheet-excel/{timesheet_id}', 'AttendanceController@timesheetExcel')->name('timesheets.excel')->middleware(['permission:view_timesheet','auth']);


//new
//early staff report
Route::get('/late-staff-report', 'AttendanceReportController@lateStaff')->name('late.staff')->middleware(['permission:view_attendance_report','auth']);
Route::get('/staff-timesheet', 'AttendanceReportController@staffTimesheet')->name('staff.timesheet')->middleware(['permission:view_attendance_report','auth']);
Route::get('/staff-timesheet-excel', 'AttendanceReportController@excelstaffTimesheet')->name('staff.timesheet.excel')->middleware(['permission:view_attendance_report','auth']);
Route::get('/attendance/reports', 'AttendanceController@absenceManagement')->name('attendance.absenceManagement')->middleware(['permission:view_attendance_report','auth']);
Route::get('/monthly/attendance/reports', 'AttendanceController@monthlyAttendance')->name('monthly.attendance.report')->middleware(['permission:view_attendance_report','auth']);
Route::get('/user/monthly/attendance/{user}/{date}', 'AttendanceController@UserMonthlyAttendance')->middleware(['permission:view_attendance_report','auth']);
//manual attendance
Route::get('/attendance/manual', 'ManualAttendanceController@manualAttendance')->name('manual.attendance')->middleware(['permission:view_attendance_report','auth']);
Route::post('/attendance/manual', 'ManualAttendanceController@storeManualAttendance')->name('manual.attendance.store')->middleware(['permission:view_attendance_report','auth']);
Route::get('/attendance/manual/excel', 'ManualAttendanceController@manualAttendanceExcelTemplate')->name('manual.attendance.excel.template')->middleware(['permission:view_attendance_report','auth']);
Route::post('/attendance/manual/excel', 'ManualAttendanceController@manualAttendanceExcel')->name('manual.attendance.excel')->middleware(['permission:view_attendance_report','auth']);
Route::get('/attendance/manual/approval/{id}', 'BiometricController@approval')->name('manual.attendance.approval')->middleware(['permission:view_attendance_report','auth']);

//app schedule shift
Route::get('/schedule/shift/app', 'AttendanceController@appScheduleShift')->middleware(['permission:view_attendance_report','auth'])->name('app.schedule.shift');
Route::post('/schedule/shift/app/submit', 'AttendanceController@appScheduleShiftSubmit')->middleware(['permission:view_attendance_report','auth'])->name('app.schedule.shift.submit');


Route::get('/attendance/all-staff', 'AttendanceReportController@allStaff')->name('attendance.all.staff')->middleware(['permission:view_attendance_report','auth']);
Route::get('/attendance/staff/{staff}', 'AttendanceReportController@staffAttendance')->name('attendance.staff')->middleware(['permission:view_attendance_report','auth']);
Route::post('/settings/regions', 'CompanySettingController@saveRegion')->name('region.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/region/{region_id}', 'CompanySettingController@getRegion')->name('region.show');

Route::post('/settings/state', 'CompanySettingController@saveState')->name('state.store')->middleware(['permission:edit_settings','auth']);
Route::get('/settings/state/{state_id}', 'CompanySettingController@getState')->name('state.show');
//above is only admin

//exemptions

Route::get('/attendance/user/exemptions/{user}/{attendance}', 'ExemptionController@userAttendanceExemptions')->name('user.exemptions.attendance')->middleware(['auth']);
Route::post('/attendance/exemption/store', 'ExemptionController@storeExemptions')->name('store.exemption')->middleware(['auth']);
Route::get('/exemption-approvals', 'ExemptionController@exemptionApprovals')->name('exemption.approvals')->middleware(['auth']);
Route::get('/exemption/all', 'ExemptionController@index')->name('exemptions.index')->middleware(['auth']);

Route::post('/exemption/approve', 'ExemptionController@saveApproval')->name('approve.exemption')->middleware(['auth']);
Route::get('/user-exemption', 'ExemptionController@myexemptions')->name('my.exemptions')->middleware(['auth']);


//biometric devices
Route::prefix('bio')->group(function () {
    Route::get('/data', 'BiometricController@data');
    Route::get('/iclock/cdata', 'BiometricController@checkDevice');
    Route::post('/iclock/cdata', 'BiometricController@receiveRecords');
});
Route::get('/enroll-users', 'BiometricController@enrollUsers')->name('enroll-users');
Route::get('/remove-users', 'BiometricController@removeUsers')->name('remove-users');
Route::get('/fetch-users', 'BiometricController@fetchusers')->name('fetch-users');
//new new shift swaps

Route::get('/user_shift_schedules', 'AttendanceController@myShiftSchedule')->name('my.shift.schedules')->middleware(['auth']);


// new shift functions
//Route::get('/attendance/reports', 'AttendanceController@custAbsenceManagement')->name('attendance.absenceManagement')->middleware(['permission:view_timesheet','auth']);
Route::get('/employee_shift_schedules', 'AttendanceController@employeeShiftSchedules')->name('employeeShiftSchedules')->middleware(['permission:view_shift_schedule','auth']);
Route::get('/export_shift_schedule', 'AttendanceReportController@exportShiftSchedules')->name('exportShiftSchedules')->middleware(['auth']);
Route::get('/cust_shift_schedules', 'AttendanceController@employeesSchedule')->name('employeesSchedule')->middleware(['auth']);

Route::get('/shift_template_download', 'AttendanceController@downloadShiftUploadTemplate')->name('downloadShiftUploadTemplate')->middleware(['auth']);
Route::get('/cust_day_hours/{user_id}/{date}', 'AttendanceController@getCustDayHours')->name('getCustDayHours')->middleware(['auth']);
Route::get('/export_timesheets', 'AttendanceController@custRangeTimesheetView')->name('custRangeTimesheetView')->middleware(['auth']);
Route::get('/cust_dts', 'AttendanceController@custDailyTimesheet')->name('custDailyTimesheet')->middleware(['auth']);
Route::get('/cust_dts_json', 'AttendanceController@custDailyTimesheetJSON')->name('custDailyTimesheetJSON');
Route::get('/cust_rts', 'AttendanceController@custRangeTimesheet')->name('custRangeTimesheet')->middleware(['auth']);
Route::post('/import_employee_shift', 'AttendanceController@importUserShifts')->name('importUserShifts')->middleware(['auth']);
// end of new shift funtions
Route::get('/shift_schedules', 'AttendanceController@shift_schedules')->name('shift_schedules')->middleware(['auth']);
Route::get('/shift_schedules/{shift_schedule_id}', 'AttendanceController@shift_schedule_details')->name('shift_schedule.show')->middleware(['permission:view_shift_schedule','auth']);
Route::get('/user_shift_schedules/{user_id}', 'AttendanceController@userShiftSchedule')->name('shift_schedule.user')->middleware(['permission:view_shift_schedule','auth']);
Route::get('/user_shift_schedule_calendar/{user_id}', 'AttendanceController@userShiftScheduleCalendar')->name('shift_schedule.user_calendar')->middleware(['permission:view_shift_schedule','auth']);
Route::get('/user_shift_schedule_details/{id}', 'AttendanceController@userShiftScheduleDetails')->middleware(['permission:view_shift_schedule','auth']);
//Route::get('/user_attendance/{user_id}', 'AttendanceController@myAttendance')->name('attendance.user')->middleware(['auth']);
Route::get('/user_attendance_cal', 'AttendanceController@myAttendanceCal')->name('attendance.user')->middleware(['auth']);
Route::get('/user_attendance', 'AttendanceController@myAttendance')->name('attendance.userr')->middleware(['auth']);
Route::get('/user_attendance_calendar/{user_id}', 'AttendanceController@myAttendanceCalendar')->name('attendance.user_calendar')->middleware(['auth']);
Route::get('/user_attendance_calendar', 'AttendanceController@shiftUploadedCalendar')->name('attendance.all.user_calendar')->middleware(['auth']);
Route::post('/schedule_shifts','AttendanceController@schedule_shift')->name('schedule_shifts')->middleware(['auth']);
Route::post('/swap_shift','AttendanceController@swapShift')->name('swap_shift')->middleware(['auth']);
Route::get('/shift_swap_cancel/{shift_swap_id}','AttendanceController@approveShiftSwaps')->name('shiftSwap.approve')->middleware(['auth']);
Route::get('/shift_swap_approve/{shift_swap_id}','AttendanceController@cancelShiftSwaps')->name('shiftSwap.cancel')->middleware(['auth']);
Route::get('/shift_swap_reject/{shift_swap_id}','AttendanceController@rejectShiftSwaps')->name('shiftSwap.reject')->middleware(['auth']);
Route::get('/myshiftswaps','AttendanceController@myShiftSwaps')->name('myShiftSwaps')->middleware(['auth']);
Route::get('/shiftswaps','AttendanceController@shiftSwaps')->name('shiftSwaps')->middleware(['permission:approve_shift_swap','auth']);
// attendance settings end
// workflow
Route::get('workflows/alter-status','WorkflowController@alterStatus')->name('workflows.alter-status')->middleware(['auth']);
Route::resource('workflows', 'WorkflowController',['names'=>['create'=>'workflows.create','index'=>'workflows','store'=>'workflows.save','edit'=>'workflows.edit','update'=>'workflow.update','show'=>'workflows.view','destroy'=>'workflows.delete']])->middleware('auth');
// workflow end
// payroll setting
Route::resource('payroll', 'PayrollController')->middleware(['auth']);
Route::resource('payrollsettings', 'PayrollSettingController')->middleware(['permission:edit_settings','auth']);
// payroll setting end
// executive view
Route::get('/people_analytics', 'HomeController@executiveView')->name('executive_view')->middleware(['permission:view_hr_reports','auth']);
Route::get('/people_analytics_leave', 'HomeController@executiveViewLeave')->name('executive_view_leave')->middleware(['permission:view_leave_report','auth']);
Route::get('/people_analytics_attendance', 'HomeController@executiveViewAttendance')->name('executive_view_attendance')->middleware(['permission:view_attendance_report','auth']);
Route::get('/executive-report', 'HomeController@executiveViewAttendance')->name('executive.view_attendance')->middleware(['permission:view_attendance_report','auth']);
Route::get('/bi-report', 'ReportController@getReport')->name('bi_report')->middleware(['permission:view_attendance_report','auth']);
// end of executive view
Route::resource('roles', 'RoleController')->middleware(['permission:edit_settings','auth']);
Route::resource('performances', 'PerformanceController')->middleware(['permission:edit_settings','auth']);
Route::resource('performance', 'PerformanceController')->middleware(['permission:edit_settings','auth']);
Route::resource('leave','LeaveController')->middleware(['auth']);
Route::resource('document','DocumentController')->middleware(['auth']);
Route::resource('projects','ProjectController')->middleware(['auth']);
Route::resource('recruits','RecruitController')->middleware(['auth']);
Route::resource('compensation','CompensationController')->middleware(['auth']);
Route::resource('loan','LoanController')->middleware(['auth']);
Route::get('jobs_departments','JobController@departments')->name('job_departments.view')->middleware(['auth']);
Route::get('job_skill_search','JobController@skill_search')->middleware(['auth']);
Route::get('job_search','JobController@job_search')->middleware(['auth']);
Route::get('job_qualification_search','JobController@qualification_search')->middleware(['auth']);
Route::get('joblist/{department_id}', 'JobController@list')->name('job_list.view')->middleware(['auth']);
Route::get('jobs/department/{department_id}','JobController@index')->middleware(['auth']);
Route::get('jobs/create/{department_id}','JobController@create')->middleware(['auth'])->name('jobs.create');
Route::get('jobs/delete/{job_id}','JobController@delete')->middleware(['auth'])->name('jobs.delete');
Route::get('job_search','JobController@job_search')->middleware(['auth']);
Route::resource('jobs', 'JobController',['names'=>['store'=>'jobs.save','edit'=>'jobs.edit','update'=>'jobs.update','show'=>'jobs.view','destroy'=>'jobs.delete']])->except([
    'index', 'create'])->middleware('auth');
Route::get('location/country','HomeController@countries')->middleware(['auth']);
Route::get('location/state/{country_id}','HomeController@states')->middleware(['auth']);
Route::get('location/lga/{state_id}','HomeController@lgas')->middleware(['auth']);

//OAuth
Route::get('/auth/microsoft', 'MicrosoftController@redirectToProvider');
Route::get('/auth/microsoft/callback', 'MicrosoftController@callbackurl');

Route::resource('bscsettings', 'BSCController')->middleware(['auth']);
Route::get('bsc/usersearch', 'BSCEvaluationController@usersearch')->middleware(['auth']);
Route::resource('bsc', 'BSCEvaluationController')->middleware(['auth']);


/*************Payroll Module Start******************/
//fill in the update weekend form
Route::get('/edit-weekend_days', 'PayrollController@fill_weekend_form');
Route::get('/enablepay', 'PayrollController@enablepay');
Route::get('/payroll/endis', 'PayrollController@endis');


//ju
Route::get('tax_payable/{paxble}', 'PayrollController@tax_payable');

Route::get('/mypayslip/{payid}', 'PayrollController@create_payslip');
//update weekend form
Route::post('/update_weekend_days', 'PayrollController@update_weekend_form');

//Holiday Calendar
Route::get('/holiday-calendar', 'PayrollController@holiday_calendar');

//Add Holiday Form
Route::get('/add-holiday', 'PayrollController@add_holiday_form');

//Save Holiday Form
Route::post('/add-holiday', 'PayrollController@add_holiday');

//Edit Holiday form filling DB data
Route::get('/edit-holiday/{id}', array('uses' => 'PayrollController@fill_holiday_form'));

//Updating data
Route::post('/update-holiday', array('uses' => 'PayrollController@update_holiday_form'));

//List of holidays
Route::get('/holiday-list', 'PayrollController@holiday_list');

//Delete Holiday
Route::get('/delete_holiday/{id}', 'PayrollController@delete_holiday');

//Holiday Status Change
Route::post('/holiday_status_change', 'PayrollController@holiday_status_change');

//List of Employees to add or edit basic pay
Route::get('/basicpay-list', 'PayrollController@basicpay_list');

//Updating Payroll of employee by admin
Route::post('/basicpay-update', 'PayrollController@basicpay_update');

//Add Allowance Form
Route::get('/add-allowance', 'PayrollController@add_allowance_form');

//Save Allowance Form
Route::post('/add-allowance', 'PayrollController@add_allowance');

//Edit Allowance form filling DB data
Route::get('/edit-allowance/{id}', array('uses' => 'PayrollController@fill_allowance_form'));

//Updating Allowance
Route::post('/update-allowance', array('uses' => 'PayrollController@update_allowance_form'));

//List of Allowances
Route::get('/allowance-list', 'PayrollController@allowance_list');

//Delete Allowance
Route::get('/delete_allowance/{id}', 'PayrollController@delete_allowance');

//Allowance Status Change
Route::post('/allowance_status_change', 'PayrollController@allowance_status_change');

//List of Employees to add or edit Payroll
Route::get('/payroll-list', 'PayrollController@payroll_list');

Route::get('/payroll-list/{month_year}', 'PayrollController@payroll_list');
Route::get('/payrollchart', 'PayrollController@generate_chart');

//Get payroll details to save in admin panel
Route::get('/get-payroll/{id}', array('uses' => 'PayrollController@fill_payroll_form'));

//Get payroll details for employee to download
Route::get('/emp-payroll-list','PayrollController@emp_payroll_list');

//Get payroll details from table to show in admin panel
Route::get('/get-saved-payroll/{id}', array('uses' => 'PayrollController@fill_payroll_view'));
Route::get('/payroll/savecompupdate', array('uses' => 'PayrollController@update_comp_member'));


//Updating Payroll of employee by admin
Route::post('/payroll-update', 'PayrollController@payroll_update');

//Generating payslip by admin
Route::post('/issue_ps/{id}', 'PayrollController@create_ps');

//fill in the update CL form
Route::get('/edit-casual_leaves', 'PayrollController@fill_casual_leaves');

//update CL form
Route::post('/update_casual_leaves', 'PayrollController@update_casual_leaves');

//payslip list view of previous months for admin
Route::get('/view-previous-payslip', 'PayrollController@view_previous_payslip');

//fill in the update Payslip logo / watermark form
Route::get('/edit-payslip-details', 'PayrollController@fill_payslip_details');

//update CL form
Route::post('/update_payslip_details', 'PayrollController@update_payslip_details');

//List of all the expenses added by an employee
Route::get('/my-expenses', 'PayrollController@my_expenses_list');

//Add expense by an employee
Route::post('/add-expense', 'PayrollController@add_expense');

//Delete own expense added by the employee
Route::get('/delete_expense/{id}', 'PayrollController@delete_expense');

//Edit expense form filling DB data
Route::get('/edit-expense/{id}', array('uses' => 'PayrollController@fill_expense_form'));

//Updating expense
Route::post('/update-expense', array('uses' => 'PayrollController@update_expense'));

//List of all the expenses added by all the employees
Route::get('/employee-expenses', 'PayrollController@employee_expenses_list');

//Expense Status Change
Route::post('/update-expense-status', 'PayrollController@expense_status_change');



/*************Payroll Module End******************/

/***************Leave Management Module Start***********/

//Apply Leave Form
Route::get('/apply-leave', 'LeaveController@apply_leave_form');

//Get the number of working days within the date range selected while applying for casual leave
Route::post('/getavailleave', 'LeaveController@fnGetLeaves');

//Save Leave Form
Route::post('/apply-leave', 'LeaveController@add_leave');

//List of leaves of logged in employee
Route::get('/my-leaves', 'LeaveController@cl_list');

//Cancel Leave
Route::get('/cancel_leave/{id}', 'LeaveController@cancel_leave');

//Holiday Status Change
Route::post('/leave_status_change', 'LeaveController@leave_status_change');

//List of leaves applied by all employees
Route::get('/employee-leaves', 'LeaveController@all_cl_list');

//Leave Status Change
Route::post('/update-leave-status', 'LeaveController@status_change');
/***************Leave Management Module End***********/


/*************Attendance and Leave Module Start******************/

//Daily attendance individual view for admin and people manager
//Route::get('/view-daily-attendance/{id}', 'PayrollController@view_daily_attendance');
//Route::get('/my-attendance', 'PayrollController@view_daily_attendance');

//Daily attendance individual view of employee for admin and people manager
Route::post('/view-emp-daily-attendance', 'PayrollController@view_emp_daily_attendance');

//Calendar function for Daily attendance individual view of employee for admin and people manager without employee id
Route::get('/view-emp-daily-attendance', 'PayrollController@daily_attendance_list');

//
//Calendar function for Daily attendance individual view of employee for admin and people manager
Route::get('/view-emp-daily-attendance-calendar', 'PayrollController@view_emp_daily_attendance_calendar');

//Daily attendance calendar view for employee
Route::get('/view-daily-attendance', 'PayrollController@view_daily_attendance_calendar');

//calendar function for Daily attendance calendar view of employee
Route::get('/daily-attendance-calendar', 'PayrollController@daily_attendance_calendar');

//Daily attendance list view for admin all employees
//Route::get('/daily-attendance-list', 'PayrollController@daily_attendance_list');

//Daily attendance settings by admin
Route::get('/daily-attendance-settings', 'PayrollController@daily_attendance_settings');

//Daily attendance settings individual for edit by admin
Route::get('/edit-daily-attendance-settings/{id}', 'PayrollController@daily_attendance_settings_edit');

//Daily attendance settings new add by admin
Route::post('/add-daily-attendance-settings', 'PayrollController@daily_attendance_settings_add');

//Daily attendance settings to save by admin
Route::post('/update-daily-attendance-settings', array('uses' => 'PayrollController@daily_attendance_settings_update'));

//Daily attendance settings status change by admin
Route::post('/daily-attendance-settings-status-change', array('uses' => 'PayrollController@daily_attendance_settings_status_change'));

//Daily attendance list view of employees for people manager under him
Route::get('/day-att-emp-list', 'PayrollController@day_att_emp_list');

//Delete Daily attendance settings
Route::get('/delete_daily_attendance_settings/{id}', 'PayrollController@delete_daily_attendance_settings');


//Updating daily attendance by employee
//Route::get('/daily-attendance', 'PayrollController@daily_attendance');

//Updating daily attendance in the database
Route::post('/daily-attendance-update', 'PayrollController@daily_attendance_update');

//Updating daily attendance in the database by people manager of employee
Route::post('/daily-attendance-emp-update', 'PayrollController@daily_attendance_emp_update');

//Saving daily attendance in the database by people manager of employee
Route::post('/daily-attendance-emp-save', 'PayrollController@daily_attendance_emp_save');



/*************Attendance and Leave Module End******************/

//Route::get("/test_pdf", 'PdfController@generate_pdf');

Route::get("/test_leave_calc", 'TestController@fnGetLeaves');


Route::get('/payroll-approval-settings', 'PayrollController@get_payroll_approval_settings');
Route::post('/update-payroll-approval-settings', 'PayrollController@update_payroll_approval_settings');
Route::get('/payroll_approval', 'PayrollController@payroll_approval_overview');
Route::get('/approve_or_reject_payroll', 'PayrollController@approve_or_reject_payroll');
Route::get('/allowance_status_change/{id}', 'PayrollController@allowance_status_change');

Route::get('/allowances', 'PayrollController@allowances');
Route::get('/specific_components', 'PayrollController@specific_components');
Route::post('/specific_components/add', 'PayrollController@add_specific_components');
Route::get('/delete_specific_component/{id}', 'PayrollController@delete_specific_component');
Route::post('/allowances/add', 'PayrollController@add_component');
Route::post('/allowances/edit', 'PayrollController@edit_component');

Route::get('/add-all-payroll', 'PayrollController@add_payroll_for_all_employees');
Route::get('/add-all-payslip', 'PayrollController@issue_payslip_for_all_employees');

Route::get('/disable_late', 'PayrollController@disable_late_charge');
Route::get('/enable_late', 'PayrollController@enable_late_charge');

Route::get('/disable_tax', 'PayrollController@disable_tax');
Route::get('/enable_tax', 'PayrollController@enable_tax');
Route::get('/generate_payroll_excel/{month_year}', 'PayrollController@generate_payroll_excel');

Route::get('/get_payroll_policy', 'PayrollController@get_payroll_policy');
Route::get('/payroll_month_settings/{when}', 'PayrollController@payroll_month_settings');
Route::get('/generate_payroll_excel/{month_year}', 'PayrollController@generate_payroll_excel');

Route::get('/get_payroll_policy', 'PayrollController@get_payroll_policy');
Route::get('/payrolltest', 'PayrollController@get_salary_components');

Route::get('/payroll/clear/{monthyear}', 'PayrollController@clearpayroll');

Route::post('/addapplicant', 'EmployeeController@addapplicant');
Route::get('/payroll_month_settings/{when}/{percent}', 'PayrollController@payroll_month_settings');

Route::get('/payment/{month_year}', 'MoneywaveController@reinburse');
Route::get('/readprogress/{month_year}', 'MoneywaveController@readprogress');

Route::get('/create_wallet', 'MoneywaveController@createwallet');
Route::post('/fundwallet', 'MoneywaveController@fundwallet');
Route::get('/wallet', 'MoneywaveController@get_funding_history');
Route::get('/fixpayment', 'MoneywaveController@fixpayment');

Route::get('/validatetransaction/{auth}', 'MoneywaveController@validatetransaction');
Route::get('/retract_fund/{amount}', 'MoneywaveController@retract_fund');
Route::get('/success', 'MoneywaveController@validatetransaction');
Route::get('/mypayslip/{empnum}/{month}/{payrollid}', 'PayrollController@create_payslip');


Route::get('testrev', 'PayrollController@leaves');

Route::get('financial/report/monthly', 'FinancialReportController@monthly')->name('monthly.financial')->middleware(['auth']);
Route::get('financial/report/station', 'FinancialReportController@station')->name('station.financial')->middleware(['auth']);
Route::get('financial/report/station/{date}/{station}', 'FinancialReportController@showstation')->name('show.station.financial')->middleware(['auth']);
Route::post('financial/report/run', 'FinancialReportController@run')->name('run.financial.report')->middleware(['auth']);

Route::get('verify/staff', 'UserController@verifyuser')->name('verify.staff')->middleware(['permission:verify_user','auth']);
Route::post('verify/staff', 'UserController@postVerifyUser')->name('verify.staff.post')->middleware(['permission:verify_user','auth']);

//face recognition
Route::get('facial/create/list/{id}', 'FaceMatchController@createFaceListM')->name('facial.create.list')->middleware(['auth']);


//Company api
// Route::get('/api/stations', 'CompanySettingController@apicompanies');

