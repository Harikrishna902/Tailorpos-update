<?php

namespace App\Http\Livewire\Admin\Expense;

use Livewire\Component;
use App\Models\CustomerGroup;
use App\Models\Expense;
use App\Models\ExpenseHead;
use Illuminate\Support\Facades\Auth;
use App\Models\Translation;

class ExpenseHeads extends Component
{
    public $name,$type,$description,$categories,$expense,$search,$is_active=1,$lang,$category;

    //Render the page
    public function render()
    {
        $query = ExpenseHead::latest();
        if($this->search != '')
        {
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->categories = $query->where('created_by',Auth::user()->id)->get();
        return view('livewire.admin.expense.expense-heads');
    }

    //validation rules
    protected $rules = [
        'name'  => 'required',
        'type'  => 'required'
    ];

    //reset input fields
    public function resetFields()
    {
        $this->name = '';
        $this->type = 1;
        $this->is_active=1;
        $this->resetErrorBag();
    }

    //create expense head
    public function create()
    {
        $this->validate();
        ExpenseHead::create([
            'name'  => $this->name,
            'type'  => $this->type,
            'created_by'   => Auth::user()->id,
            'is_active' => $this->is_active ?? 0
        ]);
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Expense Head has been created!']);
    }

    //preload and set input fields to editing expense head
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->category = ExpenseHead::find($id);
        if($this->category)
        {
            $this->name = $this->category->name;
            $this->type = $this->category->type;
            $this->is_active = $this->category->is_active;
        }
    }

    //update expense head
    public function update()
    {
        $this->validate();
        if($this->category)
        {
            $this->category->name = $this->name;
            $this->category->type = $this->type;
            $this->category->is_active = $this->is_active;
            $this->category->save();
            $this->resetFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Expense Head has been updated!']);
        }
    }

    //delete expense head
    public function delete()
    {
        $category = $this->category;
        $expenses = Expense::where('expense_head_id',$this->category->id)->get();
        if($expenses && count($expenses) > 0)
        {
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'error',  'message' => 'Expense Head deletion was restricted!']);
            return false;
        }
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Expense Head was deleted!']);
        $category->delete();
        $this->category = null;
        $this->emit('closemodal');
    }

    //toggle expense head's activity
    public function toggle($id)
    {
        $expense_head = ExpenseHead::find($id);
        if($expense_head->is_active == 1)
        {
            $expense_head->is_active = 0;
        }
        else{
            $expense_head->is_active = 1;
        }
        $expense_head->save();
    }

    //preload expense head for delete confirmation
    public function deleteConfirm($id)
    {
        $this->category = ExpenseHead::find($id);
    }
}