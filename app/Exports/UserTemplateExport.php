<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserTemplateExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Trả về một collection với dữ liệu mẫu
        return collect([
            ['Nguyen Van A', 'nguyenvana@example.com', '12345678', '0123456789', 'Phòng HHH', 'Nhân viên tạm thời', 'Nam', '1990-01-01', 'Bậc lương 1'],
            ['Tran Thi B', 'tranthib@example.com', '12345678', '0987654321', 'Phòng Kế toán', 'Nhân viên chính thức', 'Nữ', '1985-05-15', 'Bậc lương 2'],
            ['Le Van C', 'levanc@example.com', '12345678', '0912345678', 'Phòng 333', 'Nhân viên chính thức', 'Nam', '1992-07-20', 'Bậc lương 3'],
        ]);
    }

    /**
     * Đặt tiêu đề cho file mẫu.
     */
    public function headings(): array
    {
        return [
            'Tên',           // Tên của người dùng
            'Email',         // Địa chỉ email
            'Mật khẩu',      // Mật khẩu (sẽ mã hóa khi lưu)
            'Số điện thoại', // Số điện thoại
            'Phòng ban',     // Tên phòng ban
            'Chức vụ',       // Vị trí công tác
            'Giới tính',     // Giới tính
            'Ngày sinh',     // Ngày sinh
            'Bậc lương',     // Bậc lương
        ];
    }
}
