<?php
namespace App\Services;
use App\Interfaces\EmployeeRepositoryInterface;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;

class EmployeeService
{
    protected $employeeRepository;
    public function __construct(EmployeeRepositoryInterface $employeeRepository)
    {
        $this->employeeRepository = $employeeRepository;
    }

    public function index($perPage): LengthAwarePaginator
    {
        return $this->employeeRepository->index($perPage);
    }

    public function show($id)
    {
        return $this->employeeRepository->show($id);
    }

   public function store(array $data)
    {
        if (isset($data['photo'])) {
            $data['photo'] = $this->uploadImage($data['photo']);
        }
        return $this->employeeRepository->store($data);
    }
    public function update($id, array $data)
    {
        if (isset($data['photo'])) {
            $employee = $this->employeeRepository->show($id);
            // Delete old image if exists
            if ($employee->image) {
                Storage::delete('public/' . $employee->image);
            }
           $data['photo'] = $this->uploadImage($data['photo']);
        }
        
        return $this->employeeRepository->update($id, $data);
    }
protected function uploadImage($image)
{
    $imageName = time() . '_' . $image->getClientOriginalName();
    $image->storeAs('public/employees', $imageName);
    return 'employees/' . $imageName;
}

    public function delete($id): bool
    {
        return $this->employeeRepository->delete($id);
    }
}