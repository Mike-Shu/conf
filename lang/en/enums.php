<?php

use App\Enums\ExportEntity;
use App\Enums\ExportType;
use App\Enums\MediaStatus;
use App\Enums\MediaType;
use App\Enums\PageTemplate;
use App\Enums\TimetableSlotGradient;
use App\Enums\TimetableSlotWidth;
use App\Enums\UserGender;
use App\Enums\VideoProvider;
use App\Enums\WalletReasonMain;
use App\Enums\WalletReasonSystem;
use App\Enums\WalletTransactionType;

return [
    UserGender::class => [
        UserGender::MALE => 'Male',
        UserGender::FEMALE => 'Female',
    ],
    WalletTransactionType::class => [
        WalletTransactionType::DEPOSIT => 'Deposit',
        WalletTransactionType::WITHDRAW => 'Withdraw',
    ],
    WalletReasonMain::class => [
        WalletReasonMain::CORRECTION => 'Correction',
        WalletReasonMain::CARD => 'Card',
        WalletReasonMain::QUIZ => 'Quiz',
        WalletReasonMain::INSTAGRAM => 'Instagram',
        WalletReasonMain::VKONTAKTE => 'VKontakte',
        WalletReasonMain::CROSSWORD => 'Crossword',
        WalletReasonMain::FILLWORD => 'Fillword',
        WalletReasonMain::CHECKERS => 'Checkers',
        WalletReasonMain::CHESS => 'Chess',
        WalletReasonMain::SPORTS_CHALLENGE => 'Sports challenge',
        WalletReasonMain::MIND_FITNESS => 'Mind fitness',
        WalletReasonMain::GIFT_WRAPPING => 'Gift wrapping',
        WalletReasonMain::BIRTHDAY => 'Birthday',
        WalletReasonMain::FOOTBALL_FREESTYLE => 'Football freestyle',
        WalletReasonMain::BELLY_DANCE => 'Belly dance',
        WalletReasonMain::TRAVELS => 'Travels',
        WalletReasonMain::QUILLING => 'Quilling',
        WalletReasonMain::POWER_TRAINING => 'Power Training',
        WalletReasonMain::FASHION_SCIENCE => 'Fashion science',
        WalletReasonMain::JUMP_STYLE => 'Jump style',
        WalletReasonMain::CHILD_SUIT => 'Child suit',
        WalletReasonMain::FLORISTICS => 'Floristics',
        WalletReasonMain::GRAFFITI => 'Graffiti',
        WalletReasonMain::FIT_XIKI => 'Fit-xiki',
        WalletReasonMain::VOLUNTEERING => 'Volunteering',
        WalletReasonMain::SPORT => 'Sport',
        WalletReasonMain::CREATIVITY => 'Creativity',
        WalletReasonMain::GTO => 'GTO', // TODO
        WalletReasonMain::BUSINESS => 'Business',
        WalletReasonMain::MIND => 'Mind',
        WalletReasonMain::REGISTRATION => 'Registration',
        WalletReasonMain::RESULT_UPLOAD => 'Result upload',
        WalletReasonMain::PHOTO => 'Photo upload',
        WalletReasonMain::VIDEO => 'Video upload',
        WalletReasonMain::RECOMMENDATIONS => 'Recommendations',
        WalletReasonMain::FUNFAIR => 'Funfair',
        WalletReasonMain::DISCREPANCY => 'Discrepancy',
        // TODO: Borjomi
        WalletReasonMain::BRJ_OFFLINE => 'Offline',
        WalletReasonMain::BRJ_TELEGRAM => 'Telegram',
        WalletReasonMain::BRJ_PHOTO_CONTEST => 'Photo contest',
        WalletReasonMain::BRJ_MASTER_CLASS => 'Master class',
    ],
    WalletReasonSystem::class => [
        WalletReasonSystem::TOTALIZATOR_BET => 'Bet in the totalizator',
        WalletReasonSystem::TOTALIZATOR_WIN => 'Winning in the totalizator',
        WalletReasonSystem::TOTALIZATOR_REFUND => 'Refund of bet in the totalizator',
        WalletReasonSystem::BONUS_ONE => 'Bonus one', // TODO
        WalletReasonSystem::GLOBAL_POLL => 'Global poll', // TODO
        WalletReasonSystem::FIRST_LOGIN => 'First login',
        WalletReasonSystem::TIMETABLE_ACTIVATED => 'Timetable activated', // TODO
        WalletReasonSystem::PURPOSE => 'Purpose', // TODO
        WalletReasonSystem::IMPORT => 'Import', // TODO
        WalletReasonSystem::SEEDER => 'Seeder',
        WalletReasonSystem::CORRECT_REGISTRATION => 'Correct registration', // TODO
        WalletReasonSystem::LIKES => 'Likes',
        WalletReasonSystem::FILLED_PHONE => 'Filled phone',
        WalletReasonSystem::DAILY_VISIT => 'Daily visit',
        WalletReasonSystem::TOAST_CLICK => 'Toast click',
        WalletReasonSystem::TRANSFER => 'Transfer',
        WalletReasonSystem::PURCHASE => 'Purchase',
        WalletReasonSystem::BONUS => 'Bonus',
    ],
    PageTemplate::class => [
        PageTemplate::DEFAULT => 'Default',
        PageTemplate::PLAYER => 'Player',
        PageTemplate::PLAYER_WITH_CHAT => 'Player with chat',
        PageTemplate::PLAYER_WITH_CHAT_AND_UPLOAD_FORM => 'Player with chat and media upload',
//        PageTemplate::CONTENT_WITH_VIDEO_UPLOAD => 'Content with video upload',
//        PageTemplate::CONTENT_WITH_PICTURE_UPLOAD => 'Content with picture upload',
//        PageTemplate::PICTURES_WITH_LIKES => 'Pictures with likes',
//        PageTemplate::VIDEOS_WITH_LIKES => 'Videos with likes',
    ],
    VideoProvider::class => [
        VideoProvider::YOUTUBE => 'YouTube',
        VideoProvider::VIMEO => 'Vimeo',
        VideoProvider::MUX => 'Mux',
        VideoProvider::RUTUBE => 'Rutube',
        VideoProvider::FACECAST => 'Facecast',
    ],
    TimetableSlotWidth::class => [
        TimetableSlotWidth::WIDTH_1_4 => '1/4',
        TimetableSlotWidth::WIDTH_2_4 => '2/4',
        TimetableSlotWidth::WIDTH_3_4 => '3/4',
        TimetableSlotWidth::WIDTH_4_4 => '4/4',
    ],
    TimetableSlotGradient::class => [
        TimetableSlotGradient::TRACK_MAIN => 'Track-main',
        TimetableSlotGradient::TRACK_SPORT => 'Track-sport',
        TimetableSlotGradient::TRACK_CREATIVITY => 'Track-creativity',
        TimetableSlotGradient::TRACK_MIND => 'Track-mind',
        TimetableSlotGradient::MOJITO => 'Mojito',
        TimetableSlotGradient::YODA => 'Yoda',
        TimetableSlotGradient::QUEPAL => 'Quepal',
        TimetableSlotGradient::ELECTRIC_VIOLET => 'Electric-violet',
        TimetableSlotGradient::JUICY_ORANGE => 'Juicy-orange',
        TimetableSlotGradient::CELESTIAL => 'Celestial',
        TimetableSlotGradient::CHERRY => 'Cherry',
        TimetableSlotGradient::STRIPE => 'Stripe',
        TimetableSlotGradient::MANGO_PULP => 'Mango-pulp',
        TimetableSlotGradient::AUBERGINE => 'Aubergine',
        TimetableSlotGradient::ROSE_WATER => 'Rose-water',
        TimetableSlotGradient::COOL_BROWN => 'Cool-brown',
        TimetableSlotGradient::YOUTUBE => 'Youtube',
    ],
    ExportType::class => [
        ExportType::DATA => "Data",
        ExportType::FILES => "Files",
    ],
    ExportEntity::class => [
        ExportEntity::USERS => "Users",
        ExportEntity::SHOP_STOCK => "Shop stock",
        ExportEntity::SHOP_ORDERS => "Shop orders",
    ],
    MediaStatus::class => [
        MediaStatus::PENDING => 'Pending',
        MediaStatus::ACCEPTED => 'Accepted',
        MediaStatus::REJECTED => 'Rejected',
    ],
    MediaType::class => [
        MediaType::IMAGE => 'Image',
        MediaType::VIDEO => 'Video',
    ],
];
