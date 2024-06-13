<?php

namespace App\Livewire;

use App\Models\EmailList;
use Illuminate\Validation\Rule;
use Livewire\Component;

class EmailListComponent extends Component
{
    public $filteremail = '', $email = '', $id = '', $BtnText = 'Save';
    public function render()
    {
        $data = EmailList::get();
        return view('livewire.email-list-component', compact('data'))->extends('layouts/master')->section('content');

    }
    public function showFrm()
    {
        $this->showForm = true;
        $this->BtnText = 'Save';
    }
    protected $rules = [
        'email' => ['unique:email_lists', 'required', 'email'],
        'filteremail' => ['required', 'email']
    ];
    public function updated($field)
    {
        $this->validateOnly($field, $this->rules);
    }
    public function store()
    {
        if ($this->id) {
            $this->rules = [
                'email' => ['required', 'email', Rule::unique('email_lists')->ignore($this->id)],
                'filteremail' => ['required', 'email'],
            ];
        } else {
            $this->rules = [
                'email' => ['required', 'email', 'unique:email_lists'],
                'filteremail' => ['required', 'email'],
            ];
        }
        $this->validate($this->rules);
        if (empty($this->id)) {
            $success = EmailList::create([
                'email' => $this->email,
                'filteremail' => $this->filteremail
            ]);
            $this->reset();
            $this->dispatch('closemodel');
            flash()->success('Your Email and filter added');
        } else {
            $success = EmailList::where('id', $this->id)->update([
                'email' => $this->email,
                'filteremail' => $this->filteremail
            ]);
            $this->reset();
            $this->dispatch('closemodel');
            flash()->success('Record Updated');
        }
    }
    public function edit($id)
    {
        $this->reset();
        $data = EmailList::where('id', $id)->first();
        $this->email = $data->email;
        $this->filteremail = $data->filteremail;
        $this->id = $id;
        $this->BtnText = 'Update';
        $this->update = true;
        $this->dispatch('openmodel');
    }
    public function delete($id)
    {
        EmailList::where('id', $id)->delete();
        flash()->warning('Record deleted');
    }
}
