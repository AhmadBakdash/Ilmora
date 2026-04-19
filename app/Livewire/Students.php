<?php

namespace App\Livewire;

use App\Http\Requests\StoreStudentRequest;
use App\Models\User;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Livewire\Component;

class Students extends Component
{
    use AuthorizesRequests;

    public string $name = '';
    public string $age = '';
    public string $phone = '';
    public string $guardian_name = '';
    public string $email = '';
    public string $password = '';
    public array $siblingIds = [];
    public bool $showForm = false;
    public ?int $editingId = null;

    protected function rules(): array
    {
        return StoreStudentRequest::rulesFor($this->editingId);
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'name'          => $this->name,
            'age'           => $this->age ? (int) $this->age : null,
            'phone'         => $this->phone,
            'guardian_name' => $this->guardian_name ?: null,
            'email'         => $this->email ?: null,
            'school_id'     => auth()->user()->school_id,
            'role'          => 'student',
        ];

        $data['password'] = $this->password
            ? Hash::make($this->password)
            : Hash::make(Str::random(32));

        if ($this->editingId) {
            $student = User::findOrFail($this->editingId);
            $this->authorize('updateStudent', $student);
            $student->update($data);
        } else {
            $this->authorize('createStudent', User::class);
            $student = User::create($data);
            $student->assignRole('student');
        }

        $this->syncSiblings($student);

        $this->reset(['name', 'age', 'phone', 'guardian_name', 'email', 'password', 'siblingIds', 'showForm', 'editingId']);
    }

    public function edit(int $id): void
    {
        $student = User::with('siblings')->findOrFail($id);
        $this->authorize('updateStudent', $student);
        $this->editingId     = $id;
        $this->name          = $student->name;
        $this->age           = $student->age ? (string) $student->age : '';
        $this->phone         = $student->phone ?? '';
        $this->guardian_name = $student->guardian_name ?? '';
        $this->email         = $student->email ?? '';
        $this->siblingIds    = $student->siblings->pluck('id')->toArray();
        $this->showForm      = true;
    }

    public function delete(int $id): void
    {
        $student = User::findOrFail($id);
        $this->authorize('deleteStudent', $student);
        foreach ($student->siblings as $sibling) {
            $sibling->siblings()->detach($id);
        }
        $student->delete();
    }

    public function render()
    {
        return view('livewire.students', [
            'students' => User::where('school_id', auth()->user()->school_id)
                ->where('role', 'student')
                ->with('siblings')
                ->get(),
        ])->layout('components.layouts.app');
    }

    private function syncSiblings(User $student): void
    {
        $oldIds = $student->siblings()->pluck('sibling_id')->toArray();
        $newIds = $this->siblingIds;

        $removed = array_diff($oldIds, $newIds);
        $added   = array_diff($newIds, $oldIds);

        $student->siblings()->sync($newIds);

        foreach ($removed as $id) {
            User::find($id)?->siblings()->detach($student->id);
        }

        foreach ($added as $id) {
            User::find($id)?->siblings()->syncWithoutDetaching([$student->id]);
        }
    }
}
