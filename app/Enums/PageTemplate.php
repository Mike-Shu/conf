<?php declare(strict_types=1);

namespace App\Enums;

use BenSampo\Enum\Contracts\LocalizedEnum;
use BenSampo\Enum\Enum;

class PageTemplate extends Enum implements LocalizedEnum
{
    public const DEFAULT = "Default";
    public const PLAYER = "Player";
    public const PLAYER_WITH_CHAT = "PlayerWithChat";
    public const PLAYER_WITH_CHAT_AND_UPLOAD_FORM = "PlayerWithChatAndUploadForm";
//    public const CONTENT_WITH_VIDEO_UPLOAD = "ContentWithVideoUpload";
//    public const CONTENT_WITH_PICTURE_UPLOAD = "ContentWithPictureUpload";
//    public const PICTURES_WITH_LIKES = "PicturesWithLikes";
//    public const VIDEOS_WITH_LIKES = "VideosWithLikes";
}
