<?php

namespace App\Http\Livewire\Admin\Reports\Prints;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;


class PrintIncomeReport extends Component
{
    public $branches,$branch,$start_date,$end_date,$expenses,$payment_mode='',$gross_sales,$gross_expense,$net_income,$mycollection;
    public $expensecollect;

    //render the page
    public function render()
    {
        return view('livewire.admin.reports.prints.print-income-report')->extends('layouts.print-layout')
        ->section('content');
    }

    //apply filters and load content for print
    public function mount($start_date,$end_date)
    {
        $this->start_date = Carbon::parse($start_date)->toDateString();
        $this->end_date = Carbon::parse($end_date)->toDateString();
        $this->mycollection = [];
        $this->expensecollect = [];
        $start_date = Carbon::parse($this->start_date);
        $end_date = Carbon::parse($this->end_date);
        $period = CarbonPeriod::create($start_date,$end_date);
        $this->gross_expense = 0;
        $this->gross_sales = 0;
        $this->net_income = 0;
        foreach($period as $row)
        {
            $no_of_invoices = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_sales = Invoice::whereDate('date',$row->toDateString())->latest();
            $total_expense = Expense::whereDate('date',$row->toDateString())->latest();
            $no_of_expense = Expense::whereDate('date',$row->toDateString())->latest()->count();
            $no_of_invoices1 = $no_of_invoices->count();
            $total_sales1 = $total_sales->sum('total');
            $total_expense1 = $total_expense->sum('amount');
            $this->gross_expense += $total_expense1;
            $this->gross_sales += $total_sales1;
            if($no_of_expense!= 0 || $total_expense1 != 0  )
            {
                $items = [
                    'no'  => $no_of_expense,
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $this->expensecollect[$row->format('d/m/Y')] = $items;
            }
            if($no_of_invoices1!= 0 || $total_sales1 != 0  )
            {
                $items = [
                    'invoices'  => $no_of_invoices1,
                    'sales' => getFormattedCurrency($total_sales1),
                    'expense'   => getFormattedCurrency($total_expense1)
                ];
                $this->mycollection[$row->format('d/m/Y')] = $items;
            }
        }
        $this->net_income = $this->gross_sales - $this->gross_expense;
    }
}