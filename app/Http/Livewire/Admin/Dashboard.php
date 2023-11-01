<?php

namespace App\Http\Livewire\Admin;

use App\Models\Expense;
use App\Models\Invoice;
use App\Models\InvoicePayment;
use App\Models\Purchase;
use App\Models\User;
use Livewire\Component;
use Auth;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class Dashboard extends Component
{
    public $total_sales,$total_expense,$total_branches,$total_purchase,$total_payments,$data=[],$payments_data=[],$recent_sales;
    //render the page
    public function render()
    {
        return view('livewire.admin.dashboard');
    }

    // process on mount
    public function mount()
    {
        $this->total_sales = Invoice::sum('total');
        $this->total_expense = Expense::sum('amount');
        $this->total_branches = User::whereUserType(3)->count();
        $this->total_purchase = Purchase::sum('total');
        $this->total_payments = InvoicePayment::sum('paid_amount');
        $end = Carbon::today();
        $start =  Carbon::today()->subDays(7);
        $datePeriod = CarbonPeriod::create($start,$end);
        $output = [];
        $output_payments = [];
        foreach ($datePeriod as $date) {
            $arrayElement = [
                'x' => $date->toDateString(),
                'y' => Invoice::whereDate('date',$date->toDateString())->sum('total')
            ];
            array_push($output,$arrayElement);
        }
        foreach ($datePeriod as $date) {
            $arrayElement = [
                'x' => $date->toDateString(),
                'y' => InvoicePayment::whereDate('date',$date->toDateString())->sum('paid_amount')
            ];
            array_push($output_payments,$arrayElement);
        }
        $this->data = $output;
        $this->payments_data = $output_payments;
        $this->recent_sales = Invoice::latest()->limit(5)->get();
    }
}