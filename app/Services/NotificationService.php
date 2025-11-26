<?php

namespace App\Services;

use App\Enums\NotificationType;
use App\Models\Notification;
use App\Models\User;
use Illuminate\Support\Collection;

class NotificationService
{
    /**
     * Broadcast notification to all active users using bulk insert.
     * This replaces the N+1 query pattern with a single insert.
     *
     * @param NotificationType $type
     * @param string $title
     * @param string $message
     * @param string|null $link
     * @param array $excludeUserIds Users to exclude from notification
     * @return int Number of notifications created
     */
    public function broadcastToAll(
        NotificationType $type,
        string $title,
        string $message,
        ?string $link = null,
        array $excludeUserIds = []
    ): int {
        $users = User::where('is_active', true)
            ->when(!empty($excludeUserIds), function ($query) use ($excludeUserIds) {
                $query->whereNotIn('id', $excludeUserIds);
            })
            ->pluck('id');

        return $this->bulkCreate($users, $type, $title, $message, $link);
    }

    /**
     * Create notifications for specific users using bulk insert.
     *
     * @param Collection|array $userIds
     * @param NotificationType $type
     * @param string $title
     * @param string $message
     * @param string|null $link
     * @return int Number of notifications created
     */
    public function bulkCreate(
        Collection|array $userIds,
        NotificationType $type,
        string $title,
        string $message,
        ?string $link = null
    ): int {
        if ($userIds instanceof Collection) {
            $userIds = $userIds->toArray();
        }

        if (empty($userIds)) {
            return 0;
        }

        $now = now();
        $notifications = [];

        foreach ($userIds as $userId) {
            $notifications[] = [
                'user_id' => $userId,
                'type' => $type->value,
                'title' => $title,
                'message' => $message,
                'link' => $link,
                'icon' => $type->icon(),
                'is_read' => false,
                'read_at' => null,
                'data' => null,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        // Use chunked insert for large datasets
        $chunks = array_chunk($notifications, 500);
        foreach ($chunks as $chunk) {
            Notification::insert($chunk);
        }

        return count($notifications);
    }

    /**
     * Create notification for incoming letter.
     */
    public function notifyIncomingLetter(string $referenceNumber, int $letterId): int
    {
        return $this->broadcastToAll(
            NotificationType::INCOMING,
            'Surat Masuk Baru',
            "Surat masuk baru dengan nomor {$referenceNumber}",
            route('incoming.show', $letterId)
        );
    }

    /**
     * Create notification for outgoing letter.
     */
    public function notifyOutgoingLetter(string $referenceNumber, int $letterId): int
    {
        return $this->broadcastToAll(
            NotificationType::OUTGOING,
            'Surat Keluar Baru',
            "Surat keluar baru dengan nomor {$referenceNumber}",
            route('outgoing.show', $letterId)
        );
    }

    /**
     * Create notification for disposition.
     */
    public function notifyDisposition(string $referenceNumber, int $letterId): int
    {
        return $this->broadcastToAll(
            NotificationType::DISPOSITION,
            'Disposisi Baru',
            "Disposisi baru untuk surat {$referenceNumber}",
            route('incoming.show', $letterId)
        );
    }
}
