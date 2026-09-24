<?php

namespace App\Notifications;

use App\Models\LichHen;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ThongBaoLichHenNotification extends Notification
{
    use Queueable;

    public LichHen $lichHen;
    public string $loaiThongBao;

    public function __construct(LichHen $lichHen, string $loaiThongBao = 'DAT_LICH_THANH_CONG')
    {
        $this->lichHen = $lichHen;
        $this->loaiThongBao = $loaiThongBao;
    }

    public function via($notifiable): array
    {
        $channels = [];
        // Chi gui qua mail neu MAIL_HOST da duoc cau hinh hop le
        if (config('mail.mailers.smtp.host') && config('mail.mailers.smtp.host') !== '127.0.0.1') {
            $channels[] = 'mail';
        }
        return $channels;
    }

    public function toMail($notifiable): MailMessage
    {
        $benhNhan = $this->lichHen->benhNhan;
        $tenBn = $benhNhan ? $benhNhan->ho_ten : 'Quý khách';

        return (new MailMessage)
            ->subject("[Phòng Khám Đa Khoa] Xác Nhận Lịch Hẹn Khám - Mã: {$this->lichHen->ma_lich_hen}")
            ->greeting("Kính chào {$tenBn},")
            ->line("Lịch hẹn khám của bạn đã được tiếp nhận và xử lý thành công.")
            ->line("Mã lịch hẹn: **{$this->lichHen->ma_lich_hen}**")
            ->line("Ngày khám: **" . date('d/m/Y', strtotime($this->lichHen->ngay_kham)) . "**")
            ->line("Khung giờ: **{$this->lichHen->gio_bat_dau} - {$this->lichHen->gio_ket_thuc}**")
            ->line("Bác sĩ phụ trách: Bác sĩ ID #{$this->lichHen->bac_si_id}")
            ->line("Trạng thái hiện tại: **{$this->lichHen->trang_thai}**")
            ->line("Vui lòng có mặt trước 15 phút tại Quầy tiếp tân để hoàn tất thủ tục khám.")
            ->salutation("Trân trọng,\nĐội ngũ Bác sĩ Phòng Khám Đa Khoa");
    }

    public function toArray($notifiable): array
    {
        return [
            'lich_hen_id' => $this->lichHen->id,
            'ma_lich_hen' => $this->lichHen->ma_lich_hen,
            'ngay_kham' => $this->lichHen->ngay_kham,
            'gio_bat_dau' => $this->lichHen->gio_bat_dau,
            'trang_thai' => $this->lichHen->trang_thai,
            'loai' => $this->loaiThongBao,
        ];
    }
}
