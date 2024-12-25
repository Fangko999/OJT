<?php

namespace App\Imports;

use App\Models\Department;
use App\Models\User;
use App\Models\SalaryLevel;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class UsersImport implements ToCollection
{
    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        if ($rows->count() <= 1) {
            throw new \Exception("No data rows found in the file.");
        }

        $dataRows = $rows->skip(1);
        $updatedById = auth()->id();

        if (!$updatedById) {
            throw new \Exception("No authenticated user found for updating/creating records.");
        }

        foreach ($dataRows as $row) {
            try {
                $department = Department::where('name', $row[4])->first();
                if (!$department) {
                    throw new \Exception("Department not found: " . $row[4]);
                }

                if (!filter_var($row[1], FILTER_VALIDATE_EMAIL)) {
                    throw new \Exception("Invalid email format: " . $row[1]);
                }

                $dateOfBirth = \Carbon\Carbon::createFromFormat('Y-m-d', $row[7])->format('Y-m-d');
                $role = $this->getRole($row[5]);
                $gender = $this->getGender($row[6]);

                $salaryLevel = SalaryLevel::where('level_name', $row[8])->first();
                if (!$salaryLevel) {
                    throw new \Exception("Salary level not found: " . $row[8]);
                }

                $user = User::where('email', $row[1])->first();
                if ($user) {
                    $user->update([
                        'name' => $row[0],
                        'email' => $row[1],
                        'password' => bcrypt($row[2]),
                        'phone_number' => $row[3],
                        'department_id' => $department->id,
                        'status' => 1,
                        'role' => $role,
                        'gender' => $gender,
                        'date_of_birth' => $dateOfBirth,
                        'salary_grade' => $row[8],
                        'salary_level_id' => $salaryLevel->id,
                        'updated_at' => now(),
                        'updated_by' => $updatedById,
                    ]);
                } else {
                    User::create([
                        'name' => $row[0],
                        'email' => $row[1],
                        'password' => bcrypt($row[2]),
                        'phone_number' => $row[3],
                        'department_id' => $department->id,
                        'status' => 1,
                        'role' => $role,
                        'gender' => $gender,
                        'date_of_birth' => $dateOfBirth,
                        'salary_level_id' => $salaryLevel->id,
                        'created_at' => now(),
                        'created_by' => $updatedById,
                    ]);
                }
            } catch (\Exception $e) {
                \Log::error("Import error at row: " . json_encode($row) . " - " . $e->getMessage());
                continue;
            }
        }
    }

    private function getRole($role)
    {
        switch (strtolower($role)) {
            case 'admin':
                return 1;
            case 'nhân viên chính thức':
                return 2;
            case 'nhân viên tạm thời':
                return 3;
            default:
                throw new \Exception("Invalid role: " . $role);
        }
    }

    private function getGender($gender)
    {
        switch (strtolower($gender)) {
            case 'nam':
                return 1;
            case 'nữ':
                return 0;
            default:
                throw new \Exception("Invalid gender: " . $gender);
        }
    }
}
