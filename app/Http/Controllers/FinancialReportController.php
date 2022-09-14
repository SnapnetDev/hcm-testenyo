<?php

namespace App\Http\Controllers;

use App\AttendanceReport;
use App\Company;
use App\FinancialReport;
use App\FinancialReportDetail;
use App\Jobs\CalculatePaymentJob;
use App\Setting;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class FinancialReportController extends Controller
{

    public function monthly(Request $request)
    {
        $ids=['5','64','65'];
        $all_stations= Company::where('biometric_serial','!=',null)->whereNotIn('id',$ids)->orderBy('name','asc')->get();
        $stations=[];
        if ($request->filled('date')){
            $date = Carbon::createFromFormat('m-Y', $request->date);
            $report=FinancialReport::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        }
        else{
            //last report
            $report=FinancialReport::orderBy('id','DESC')->first();
        }
        if ($report){
            $reports=FinancialReportDetail::where('finance_report_id',$report->id)->orderBy('company_id','DESC')->get();
            foreach ($all_stations as $station){
                $sta_reports=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$station->id)->get();

                if (count($reports)>0 && count($sta_reports)>0){
                    if ($request->filled('roles')){
                        $users=User::whereIn('role_id',$request->roles)->pluck('id')->toArray();
                        $station['reports']=$sta_reports->whereIn('user_id',$users);
                        $station['amount_received']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$station->id)->whereIn('user_id',$users)->sum('amount_received');
                        $station['amount_expected']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$station->id)->whereIn('user_id',$users)->sum('amount_expected');
                        $stations[]=$station;
                    }
                    else{
                        $station['reports']=$sta_reports;
                        $station['amount_received']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$station->id)->sum('amount_received');
                        $station['amount_expected']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$station->id)->sum('amount_expected');
                        $stations[]=$station;
                    }
                }

            }
            $date=Carbon::create($report->year,$report->month,$report->day);
        }
        else{
            $reports=[];
            if (!$request->filled('date')){$date=Carbon::today();}
        }

        if ($request->type=='excel'){
            $view = 'financial.excelmonthly';
            $name=$date->format('M, Y').' Finance report';
            return \Excel::create($name, function ($excel) use ($view, $reports,$report, $date,$name,$stations) {

                $excel->sheet($name, function ($sheet) use ($view, $reports, $report,$date,$stations) {
                    $sheet->loadView("$view", compact('reports','report', 'date','stations'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('financial.monthly',compact('reports','report','date'));

    }

    public function station(Request $request)
    {
        $company_id = companyId();
        if ($request->filled('date')){
            $date = Carbon::createFromFormat('m-Y', $request->date);
            $report=FinancialReport::where('month',$date->format('m'))->where('year',$date->format('Y'))->first();
        }
        else{
            //last report
            $report=FinancialReport::orderBy('id','DESC')->first();
        }
        if ($report){
            $ids=['5','64','65'];
            $companies=Company::where('biometric_serial','!=',null)->whereNotIn('id',$ids)->orderBy('name','asc')->get();
            foreach ($companies as $comp){
                if ($request->filled('roles')){
                    $users=User::whereIn('role_id',$request->roles)->pluck('id')->toArray();
                    $comp['amount_received']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->whereIn('user_id',$users)->sum('amount_received');
                    $comp['amount_expected']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->whereIn('user_id',$users)->sum('amount_expected');
                    $comp['no_of_staff']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->whereIn('user_id',$users)->count();
                }
                else{
                    $comp['amount_received']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->sum('amount_received');
                    $comp['amount_expected']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->sum('amount_expected');
                    $comp['no_of_staff']=FinancialReportDetail::where('finance_report_id',$report->id)->where('company_id',$comp->id)->count();
                }

            }
            $date=Carbon::create($report->year,$report->month,$report->day);
        }
        else{
            $companies=[];
            if (!$request->filled('date')){$date=Carbon::today();}
        }

        if ($request->type=='excel'){
            $view = 'financial.excelstation';
            $name=$date->format('M, Y').' Station report';
            return \Excel::create($name, function ($excel) use ($view, $companies,$report, $date,$name) {

                $excel->sheet($name, function ($sheet) use ($view, $companies, $report,$date) {
                    $sheet->loadView("$view", compact('companies','report', 'date'))
                        ->setOrientation('landscape');
                });
            })->export('xlsx');
        }
        return view('financial.station',compact('companies','report','date'));

    }

    public function run(Request $request){
        $company_id = companyId();
        if ($request->filled('month') &&$request->filled('year')){
            $user=Auth::user()->id;
            $start = Carbon::createFromFormat('m/d/Y', $request->start)->format('Y-m-d');
            $end = Carbon::createFromFormat('m/d/Y', $request->end)->format('Y-m-d');
            $days=0;
            if ($request->filled('days')){
                $days=$request->days;
            }
            Setting::where('name','payroll_running')->update(['value'=>'yes']);
            CalculatePaymentJob::dispatch($request->year,$request->month,$start,$end,$user,$days);
        }
        return Redirect::back();
    }




    public function index()
    {
        //
    }


    public function create()
    {
        //
    }


    public function store(Request $request)
    {
        //
    }

    public function show(FinancialReport $financialReport)
    {
        //
    }

    public function edit(FinancialReport $financialReport)
    {
        //
    }

    public function update(Request $request, FinancialReport $financialReport)
    {
        //
    }

    public function destroy(FinancialReport $financialReport)
    {
        //
    }
}
