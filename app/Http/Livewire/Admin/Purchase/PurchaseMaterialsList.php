<?php

namespace App\Http\Livewire\Admin\Purchase;

use App\Models\Material;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PurchaseMaterialsList extends Component
{
    public $name,$price,$is_active=1,$search,$material,$materials,$unit,$opening_stock;

    //render the page
    public function render()
    {
        $query = Material::latest();
        if($this->search != '')
        {
            $query->where('name','like','%'.$this->search.'%');
        }
        $this->materials = $query->get();
        return view('livewire.admin.purchase.purchase-materials-list');
    }

    //create material
    public function create()
    {
        if($this->opening_stock == '' || $this->opening_stock == null)
        {
            $this->opening_stock = 0;
        }
        $this->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'unit'  => 'required',
            'opening_stock'  => 'nullable|numeric'
        ]);
        Material::create([
            'name'  => $this->name,
            'price' => $this->price,
            'unit'  => $this->unit,
            'opening_stock' => $this->opening_stock,
            'is_active' => $this->is_active,
            'created_by'    => Auth::user()->id
        ]);
        $this->resetFields();
        $this->emit('closemodal');
        $this->dispatchBrowserEvent(
            'alert', ['type' => 'success',  'message' => 'Material has been created!']);
    }

    //load input fields and prepare material for edit
    public function edit($id)
    {
        $this->resetErrorBag();
        $this->material = Material::find($id);
        if($this->material)
        {
            $this->name = $this->material->name;
            $this->price = $this->material->price;
            $this->unit = $this->material->unit;
            $this->opening_stock = $this->material->opening_stock;
            $this->is_active = $this->material->is_active;
        }
    }

    //update material
    public function update()
    {
        $this->validate([
            'name'  => 'required',
            'price' => 'required|numeric',
            'unit'  => 'required',
            'opening_stock'  => 'nullable|numeric'
        ]);
        if($this->opening_stock == '' || $this->opening_stock == null)
        {
            $this->opening_stock = 0;
        }
        if($this->material)
        {
            $this->material->name = $this->name;
            $this->material->price = $this->price;
            $this->material->unit  = $this->unit;
            $this->material->opening_stock = $this->opening_stock;
            $this->material->is_active = $this->is_active;
            $this->material->save();
            $this->resetFields();
            $this->emit('closemodal');
            $this->dispatchBrowserEvent(
                'alert', ['type' => 'success',  'message' => 'Material has been updated!']);
        }
    }

    //reset input fields
    public function resetFields()
    {
        $this->name = '';
        $this->price = '';
        $this->unit = '';
        $this->opening_stock = '';
        $this->is_active = 1;
        $this->resetErrorBag();
    }

    //toggle material is active status
    public function toggle($id)
    {
        $material = Material::find($id);
        if($material->is_active == 1)
        {
            $material->is_active = 0;
        }
        else{
            $material->is_active = 1;
        }
        $material->save();
    }

    /* process on mount */
    public function mount()
    {
        if(session()->has('selected_language'))
        {   /*if session has selected language */
            $this->lang = \App\Models\Translation::where('id',session()->get('selected_language'))->first();
        }
        else{
            /* if session has no selected language */
            $this->lang = \App\Models\Translation::where('default',1)->first();
        }
    }
}