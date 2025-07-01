<?php

namespace App\Notifications;

use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Log;

class OrderStatusUpdated extends Notification
{
    protected $order;

    /**
     * Hàm khởi tạo để gán đối tượng đơn hàng
     * @param mixed $order Đối tượng đơn hàng chứa thông tin chi tiết
     */
    public function __construct($order)
    {
        $this->order = $order;
    }

    /**
     * Xác định kênh thông báo (chỉ sử dụng email trong trường hợp này)
     * @param mixed $notifiable Đối tượng nhận thông báo
     * @return array Mảng các kênh thông báo
     */
    public function via($notifiable): array
    {
        return ['mail'];
    }

    /**
     * Tạo nội dung email thông báo
     * @param mixed $notifiable Đối tượng nhận thông báo
     * @return MailMessage Đối tượng email đã được định dạng
     */
    public function toMail($notifiable): MailMessage
    {
        try {
            // Kiểm tra dữ liệu đơn hàng cần thiết
            if (!$this->order?->id || !$this->order?->recipient_name) {
                throw new \Exception('Dữ liệu đơn hàng không hợp lệ');
            }

            $message = (new MailMessage)
                ->subject(__('Cập nhật trạng thái đơn hàng #:id', ['id' => $this->order->id]))
                ->greeting(__('Xin chào :name,', ['name' => $this->order->recipient_name]))
                ->line(__('Đơn hàng của bạn đã được cập nhật sang trạng thái: **:status**', [
                    'status' => $this->order->orderStatus?->status_name ?? 'Không xác định'
                ]))
                ->line(__('**Thông tin đơn hàng:**'))
                ->line(__('Người nhận: :name', ['name' => $this->order->recipient_name]))
                ->line(__('Địa chỉ: :address', ['address' => $this->order->recipient_address ?? 'Không có']))
                ->line(__('Số điện thoại: :phone', ['phone' => $this->order->recipient_phone ?? 'Không có']))
                ->line(__('Tổng tiền: :amount VNĐ', [
                    'amount' => number_format($this->order->total_amount ?? 0, 0, ',', '.')
                ]))
                ->line(__('Phí giao hàng: :fee VNĐ', [
                    'fee' => number_format($this->order->shipping_fee ?? 0, 0, ',', '.')
                ]))
                ->line(__('**Chi tiết sản phẩm:**'));

            // Thêm thông tin chi tiết sản phẩm
            foreach ($this->order->orderDetails ?? [] as $detail) {
                $message->line(
                    ($detail->productVariant->product->name ?? 'Sản phẩm không xác định') . ' (' .
                    ($detail->productVariant->ram->value ?? 'N/A') . ', ' .
                    ($detail->productVariant->storage->value ?? 'N/A') . ', ' .
                    ($detail->productVariant->color->name ?? 'N/A') . ') - ' .
                    __('Số lượng: :quantity', ['quantity' => $detail->quantity ?? 0]) . ' - ' .
                    __('Giá: :price VNĐ', ['price' => number_format($detail->unit_price ?? 0, 0, ',', '.')]) . ' - ' .
                    __('Tổng: :total VNĐ', ['total' => number_format($detail->total_price ?? 0, 0, ',', '.')])
                );
            }

            // Thêm nút hành động và lời cảm ơn
            $message->action(__('Xem chi tiết đơn hàng'), route('orders.show', $this->order->id))
                    ->line(__('Cảm ơn bạn đã mua sắm tại cửa hàng của chúng tôi!'));

            return $message;

        } catch (\Exception $e) {
            // Ghi log lỗi và trả về thông báo lỗi
            Log::error('Lỗi khi tạo email thông báo đơn hàng: ' . $e->getMessage());
            return (new MailMessage)
                ->error()
                ->subject(__('Lỗi cập nhật trạng thái đơn hàng'))
                ->line(__('Đã xảy ra lỗi khi gửi thông báo cập nhật trạng thái đơn hàng.'))
                ->line(__('Vui lòng liên hệ bộ phận hỗ trợ để biết thêm chi tiết.'));
        }
    }
}
