<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Exception;

class UsersExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [];
        $chunkSize = 7; // Kích thước mỗi chunk là 7 bản ghi
        try {
            $totalUsers = User::count(); // Đếm tổng số bản ghi
        } catch (Exception $e) {
            // Nếu có lỗi trong việc truy vấn dữ liệu
            // Lỗi có thể là kết nối database hoặc cấu hình sai
            \Log::error("Lỗi khi truy vấn dữ liệu người dùng: " . $e->getMessage());
            throw new Exception("Lỗi khi truy vấn dữ liệu người dùng: " . $e->getMessage());
        }

        if ($totalUsers == 0) {
            throw new Exception("Không có dữ liệu người dùng để xuất.");
        }

        $sheetsCount = ceil($totalUsers / $chunkSize); // Tính số lượng sheet

        for ($i = 0; $i < $sheetsCount; $i++) {
            try {
                $sheets[] = new UserSheet($i * $chunkSize, $chunkSize);
            } catch (Exception $e) {
                // Lỗi khi tạo sheet cho các bản ghi hiện tại
                // Bạn có thể lưu thông tin lỗi hoặc tiếp tục với phần tiếp theo của dữ liệu
                // Tùy vào yêu cầu ứng dụng mà có thể quyết định cách xử lý
                // Ví dụ: Log lỗi vào một mảng hoặc file log
                \Log::error("Lỗi khi tạo sheet cho bản ghi từ $i: " . $e->getMessage());
                // Bạn có thể tiếp tục hoặc kết thúc tùy theo yêu cầu
            }
        }

        return $sheets;
    }
}

class UserSheet implements FromCollection, WithHeadings, WithEvents
{
    protected $offset;
    protected $limit;

    public function __construct($offset, $limit)
    {
        $this->offset = $offset;
        $this->limit = $limit;
    }

    public function collection()
    {
        try {
            return User::with(['department', 'salaryLevel']) // Ensure relationships are loaded
                ->offset($this->offset)
                ->limit($this->limit)
                ->get()
                ->map(function ($user) {
                    return [
                        'Họ và tên' => $user->name,
                        'Email' => $user->email,
                        'Số điện thoại' => $user->phone_number,
                        'Phòng ban' => $user->department ? $user->department->name : 'N/A',
                        'Giới tính' => $user->gender == 1 ? 'Nam' : 'Nữ',
                        'Ngày sinh' => $user->date_of_birth,
                        'Bậc lương' => $user->salaryLevel ? $user->salaryLevel->level_name : 'N/A', // Access salaryLevel correctly
                    ];
                });
        } catch (Exception $e) {
            // Nếu có lỗi trong việc lấy dữ liệu từ database
            \Log::error("Lỗi khi lấy dữ liệu người dùng: " . $e->getMessage());
            throw new Exception("Lỗi khi lấy dữ liệu người dùng: " . $e->getMessage());
        }
    }

    public function headings(): array
    {
        return [
            'Họ và tên',
            'Email',
            'Số điện thoại',
            'Phòng ban',
            'Giới tính',
            'Ngày sinh',
            'Bậc lương',
        ];
    }

    // Đăng ký sự kiện để tự động điều chỉnh kích thước cột
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // Lặp qua các cột để set autosize
                foreach (range('A', 'G') as $columnID) {
                    $event->sheet->getDelegate()->getColumnDimension($columnID)->setAutoSize(true);
                }
            },
        ];
    }
}
