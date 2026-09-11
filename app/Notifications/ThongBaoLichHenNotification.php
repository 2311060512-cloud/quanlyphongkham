<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Modules\LichHen\Models\LichHen;

class ThongBaoLichHenNotification extends Notification
{
    use Queueable;

    public LichHen $lichHen;
    public string $loaiThongBao;
    public ?string $ghiChu;

    /**
     * Create a new notification instance.
     */
    public function __construct(LichHen $lichHen, string $loaiThongBao = 'DAT_LICH', ?string $ghiChu = null)
    {
        $this->lichHen = $lichHen;
        $this->loaiThongBao = $loaiThongBao;
        $this->ghiChu = $ghiChu;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $lh = $this->lichHen;
        $tenBN = $lh->benhNhan?->ho_ten ?? 'Quý khách';
        $tenBS = $lh->bacSi?->ho_ten ?? 'Bác sĩ phụ trách';
        $tenKhoa = $lh->bacSi?->chuyenKhoa?->ten_khoa ?? 'Đa khoa';
        $phongKham = $lh->bacSi?->phong_kham ?? 'Phòng khám tổng quát';
        $ngayKham = date('d/m/Y', strtotime($lh->ngay_kham));
        $gioKham = $lh->gio_kham;
        $maLK = $lh->ma_lich_hen;

        $mail = (new MailMessage)
            ->greeting("Kính gửi $tenBN,")
            ->line("Cảm ơn bạn đã tin tưởng dịch vụ tại **Phòng Khám Đa Khoa Đại Việt**.");

        switch ($this->loaiThongBao) {
            case 'DAT_LICH':
                $mail->subject("【Đại Việt Clinic】Xác nhận đặt lịch khám #$maLK")
                    ->line("Yêu cầu đặt lịch khám của bạn đã được ghi nhận thành công và đang chờ bác sĩ xác nhận:")
                    ->line("• **Mã lịch hẹn:** $maLK")
                    ->line("• **Bác sĩ phụ trách:** $tenBS ($tenKhoa)")
                    ->line("• **Phòng khám:** $phongKham")
                    ->line("• **Thời gian khám:** $gioKham ngày $ngayKham")
                    ->line("• **Triệu chứng:** " . ($lh->trieu_chung ?? 'Khám tổng quát'))
                    ->line("Vui lòng có mặt trước giờ hẹn 10-15 phút tại bàn tiếp đón để hoàn tất thủ tục.");
                break;

            case 'XAC_NHAN':
                $mail->subject("【Đại Việt Clinic】Lịch khám #$maLK đã được phê duyệt")
                    ->line("Bác sĩ **$tenBS** đã xác nhận tiếp nhận ca khám của bạn:")
                    ->line("• **Mã lịch hẹn:** $maLK")
                    ->line("• **Thời gian:** $gioKham ngày $ngayKham tại $phongKham")
                    ->line("Trạng thái lịch hẹn hiện tại: **ĐÃ XÁC NHẬN**.");
                break;

            case 'HUY_LICH':
                $mail->subject("【Đại Việt Clinic】Thông báo hủy lịch khám #$maLK")
                    ->line("Lịch hẹn **#$maLK** ngày $ngayKham ($gioKham) đã được chuyển sang trạng thái **ĐÃ HỦY**.")
                    ->line("• **Lý do:** " . ($this->ghiChu ?? 'Theo yêu cầu của người bệnh hoặc phòng khám.'))
                    ->line("Nếu cần hỗ trợ đặt lại lịch hẹn, quý khách vui lòng liên hệ tổng đài 1900 1234.");
                break;

            case 'HOAN_THANH':
                $mail->subject("【Đại Việt Clinic】Cảm ơn bạn đã thăm khám #$maLK")
                    ->line("Buổi khám bệnh của bạn với Bác sĩ **$tenBS** đã hoàn tất:")
                    ->line("• **Chẩn đoán:** " . ($lh->chuan_doan ?? 'Khám hoàn tất'))
                    ->line("• **Lời khuyên:** " . ($lh->loi_khuyen ?? 'Tuân thủ đơn thuốc và chế độ nghỉ ngơi'))
                    ->line("Chúc quý khách sớm hồi phục sức khỏe!");
                break;
        }

        return $mail;
    }

    /**
     * Get the array representation for UI preview / Log / SMS simulation.
     */
    public function toArray(object $notifiable): array
    {
        $lh = $this->lichHen;
        return [
            'ma_lich_hen' => $lh->ma_lich_hen,
            'loai' => $this->loaiThongBao,
            'benh_nhan' => $lh->benhNhan?->ho_ten,
            'so_dien_thoai' => $lh->benhNhan?->so_dien_thoai,
            'bac_si' => $lh->bacSi?->ho_ten,
            'chuyen_khoa' => $lh->bacSi?->chuyenKhoa?->ten_khoa,
            'ngay_kham' => $lh->ngay_kham,
            'gio_kham' => $lh->gio_kham,
            'trang_thai' => $lh->trang_thai,
            'thoi_gian_gui' => now()->format('H:i:s d/m/Y'),
            'noi_dung_sms' => $this->taoNoiDungSms(),
        ];
    }

    public function taoNoiDungSms(): string
    {
        $lh = $this->lichHen;
        $maLK = $lh->ma_lich_hen;
        $ngay = date('d/m/Y', strtotime($lh->ngay_kham));
        $gio = $lh->gio_kham;
        $bs = $lh->bacSi?->ho_ten ?? 'Bác sĩ';

        switch ($this->loaiThongBao) {
            case 'DAT_LICH':
                return "[DaiViet Clinic] Dat lich $maLK thanh cong. Gio: $gio ngay $ngay voi BS $bs. Vui long den truoc 15 phut.";
            case 'XAC_NHAN':
                return "[DaiViet Clinic] Lich hen $maLK da duoc BS $bs XAC NHAN tiep don vao $gio ngay $ngay.";
            case 'HUY_LICH':
                return "[DaiViet Clinic] Lich hen $maLK ngay $ngay da HUY. Chi tiet LH 19001234.";
            case 'HOAN_THANH':
                return "[DaiViet Clinic] Hoan tat ca kham $maLK. Chuc quy khach som binh phuc!";
            default:
                return "[DaiViet Clinic] Thong bao lich hen $maLK.";
        }
    }
}
