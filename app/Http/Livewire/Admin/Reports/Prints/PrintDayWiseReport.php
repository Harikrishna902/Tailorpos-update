<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PrintDayWiseReport extends Component
{
    public $mycollection,$branch,$start_date,$end_date,$invoices,$no_of_invoices,$total_sales,$payment_received,$total_expense,$branches;
    public $gross_expense,$gross_payments,$gross_sales,$gross_invoices;

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-day-wise-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date ,$end_date,$branch=null)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->branches = User::whereIn('user_type',[3,2])->get();
        $this->branch = $branch;
        /* if the user is not admin */
        if(Auth::user()->user_type != 2)
        {
            $this->branch = Auth::user()->id;
        }
        $this->mycollection = [];
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        $this->gross_expense = 0;
        $this->gross_payments = 0;
        $this->gross_sales = 0;
        $this->gross_invoices = 0;
        foreach($period as $row)
        {
            $no_of_invoices = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = Invoice::whereDate('date',$row->toDateString())->latest();
            $payment_received = InvoicePayment::whereDate('date',$row->toDateString())->latest();
            $total_expense = Expense::whereDate('date',$row->toDateString())->latest();
            if($this->branch != '')
            {
                $no_of_invoices->where('created_by',$this->branch);
                $total_sales->where('created_by',$this->branch);
                $payment_received->where('created_by',$this->branch);
                $total_expense->where('created_by',$this->branch);
            }
            $this->no_of_invoices = $no_of_invoices->count();
            $this->total_sales = $total_sales->sum('total');
            $this->payment_received = $payment_received->sum('paid_amount');
            $this->total_expense = $total_expense->sum('amount');
            $this->gross_expense += $this->total_expense;
            $this->gross_payments += $this->payment_received;
            $this->gross_sales += $this->total_sales;
            $this->gross_invoices += $this->no_of_invoices;
            if($this->no_of_invoices!= 0 || $this->total_sales != 0 || $this->payment_received != 0 || $this->total_expense != 0 )
            {
                $items = [
                    'invoices'  => $this->no_of_invoices,
                    'sales' => getFormattedCurrency($this->total_sales),
                    'payments'  => getFormattedCurrency($this->payment_received),
                    'expense'   => getFormattedCurrency($this->total_expense)
                ];
                $this->mycollection[$row->format('d/m/Y')] = $items;
            }
        }
    }
}