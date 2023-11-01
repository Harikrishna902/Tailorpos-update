<?php

namespace App\Http\Livewire\Admin\Expense;

use App\Models\Expense;
use App\Models\ExpenseHead;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use App\Models\Translation;

class Expenses extends Component
{
    public $categories,$date,$amount,$category_id,$payment_mode=1,$notes,$expenses,$expense,$search = '',$tax_included = 0,$title;

    //render the page
    public function render()
    {
        $query = Expense::latest();
        if($this->search != '')
        {
            $search = $this->search;
            $query->where(function($querymain) use ($search){
                $querymain->where('amount','like','%'.$this->search.'%');
                $querymain->orwhere('title','like','%'.$this->search.'%');
                $querymain->orwhereHas('head',function($query2) use ($search){
                    $query2->where('name','like','%'.$search.'%');
                });
            });
        }
        $this->expenses = $query->where('created_by',Auth::user()->id)->get();
        return view('livewire.admin.expense.expenses');
    }

    //validation rules
    protected $rules = [
        'title' => 'required',
        'date' => 'required',
        'amount'=> 'required|numeric',
        'category_id' => 'required',
        'payment_mode'  => 'required'
    ];

    //reset all input fields
    public function resetFields()
    {
        $this->date = Carbon::today()->toDateString();
        $this->amount = '';
        $this->payment_mode = 1;
        $this->notes = '';
        $this->title = '';
        $this->category_id = '';
        $this->tax_included = 0;
        $this->resetErrorBag();
    }

    //load expense categories and filter data
    public function mount()
    {
        $this->categories = ExpenseHead::where('is_active',1)->where('created_by',Auth::user()->id)->latest()->get();
        $this->date = Carbon::today()->toDateString();
    }

    //create expense
    public function create()
    {
        $this->validate();
        Expense::create([
            'date'  => $this->date,
            'title'  => $this->title,
            'amount' => $this->amount,
            'expense_head_id'   => $this->category_id,
            'payment_mode'  => $this->payment_mode,
            'note' => $this->notes,
            'created_by'    => Auth::user()->id,
            'tax_percentage'    => getTaxPercentage(),
            'tax_included'  => $this->tax_included ?? 0,
            'financial_year_id' => getFinancialYearID(),
        ]);
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Expense has been created!']);
    }

    //load expense and prepare input fields for edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->expense = Expense::find($id);
        if($this->expense)
        {
            $this->date = $this->expense->date->toDateString();
            $this->amount = $this->expense->amount;
            $this->title = $this->expense->title;
            $this->notes = $this->expense->note;
            $this->category_id = $this->expense->expense_head_id;
            $this->payment_mode = $this->expense->payment_mode;
            $this->tax_included = $this->expense->tax_included;
        }
    }

    //update the expense
    public function update()
    {
        $this->validate();
        if($this->expense)
        {
            $this->expense->date = $this->date;
            $this->expense->title = $this->title;
            $this->expense->amount = $this->amount;
            $this->expense->note = $this->notes;
            $this->expense->expense_head_id = $this->category_id;
            $this->expense->payment_mode = $this->payment_mode;
            $this->expense->tax_included = $this->tax_included;
            $this->expense->save();
            $this->resetFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Expense has been updated!']);
        }
    }

    //load expense for viewing
    public function view($id)
    {
        $this->expense = Expense::find($id);
    }

    //delete expense
    public function delete()
    {
        if($this->expense)
        {
            $this->expense->delete();
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Expense has been deleted!']);
            $this->expense = null;
            $this->emit('closemodal');
        }
    }

    //load expense for delete confirmation
    public function confirmDelete($id)
    {
        $this->expense = Expense::find($id);
    }
}