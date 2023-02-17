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
        UserGender::MALE => 'Мужской',
        UserGender::FEMALE => 'Женский',
    ],
    WalletTransactionType::class => [
        WalletTransactionType::DEPOSIT => 'Начисление',
        WalletTransactionType::WITHDRAW => 'Расход',
    ],
    WalletReasonMain::class => [
        WalletReasonMain::CORRECTION => 'Коррекция',
        WalletReasonMain::CARD => 'Открытка',
        WalletReasonMain::QUIZ => 'Викторина',
        WalletReasonMain::INSTAGRAM => 'Инстаграм',
        WalletReasonMain::VKONTAKTE => 'ВКонтакте',
        WalletReasonMain::CROSSWORD => 'Кроссворд',
        WalletReasonMain::FILLWORD => 'Филворд',
        WalletReasonMain::CHECKERS => 'Шашки',
        WalletReasonMain::CHESS => 'Шахматы',
        WalletReasonMain::SPORTS_CHALLENGE => 'Спортивный челлендж',
        WalletReasonMain::MIND_FITNESS => 'Майнд фитнес',
        WalletReasonMain::GIFT_WRAPPING => 'Упаковка подарков',
        WalletReasonMain::BIRTHDAY => 'День варенья',
        WalletReasonMain::FOOTBALL_FREESTYLE => 'Футбольный фристайл',
        WalletReasonMain::BELLY_DANCE => 'Танец живота',
        WalletReasonMain::TRAVELS => 'Путешествия',
        WalletReasonMain::QUILLING => 'Квиллинг',
        WalletReasonMain::POWER_TRAINING => 'Power Training', // No translation required
        WalletReasonMain::FASHION_SCIENCE => 'Фэшн-наука',
        WalletReasonMain::JUMP_STYLE => 'Jump style', // No translation required
        WalletReasonMain::FLORISTICS => 'Флористика',
        WalletReasonMain::GRAFFITI => 'Граффити',
        WalletReasonMain::FIT_XIKI => 'Fit-xiki', // No translation required
        WalletReasonMain::CHILD_SUIT => 'Детский костюм',
        WalletReasonMain::VOLUNTEERING => 'Волонтерство',
        WalletReasonMain::SPORT => 'Спорт',
        WalletReasonMain::CREATIVITY => 'Творчество',
        WalletReasonMain::GTO => 'ГТО',
        WalletReasonMain::BUSINESS => 'Бизнес',
        WalletReasonMain::MIND => 'Ум',
        WalletReasonMain::REGISTRATION => 'Регистрация',
        WalletReasonMain::RESULT_UPLOAD => 'Загрузка результата',
        WalletReasonMain::PHOTO => 'Загрузка фото',
        WalletReasonMain::VIDEO => 'Загрузка видео',
        WalletReasonMain::RECOMMENDATIONS => 'Рекомендации',
        WalletReasonMain::FUNFAIR => 'Ярмарка Добра',
        WalletReasonMain::DISCREPANCY => 'Несоответствие заданию',
        // TODO: Borjomi
        WalletReasonMain::BRJ_OFFLINE => 'Оффлайн',
        WalletReasonMain::BRJ_TELEGRAM => 'Телеграм',
        WalletReasonMain::BRJ_PHOTO_CONTEST => 'Фотоконкурс',
        WalletReasonMain::BRJ_MASTER_CLASS => 'Мастеркласс',
    ],
    WalletReasonSystem::class => [
        WalletReasonSystem::TOTALIZATOR_BET => 'Ставка в тотализаторе',
        WalletReasonSystem::TOTALIZATOR_WIN => 'Выигрыш в тотализаторе',
        WalletReasonSystem::TOTALIZATOR_REFUND => 'Возврат ставки в тотализаторе',
        WalletReasonSystem::BONUS_ONE => 'Bonus one', // TODO
        WalletReasonSystem::GLOBAL_POLL => 'Global poll', // TODO
        WalletReasonSystem::FIRST_LOGIN => 'Первый вход',
        WalletReasonSystem::TIMETABLE_ACTIVATED => 'Timetable activated', // TODO
        WalletReasonSystem::PURPOSE => 'Purpose', // TODO
        WalletReasonSystem::IMPORT => 'Import', // TODO
        WalletReasonSystem::SEEDER => 'Seeder',
        WalletReasonSystem::CORRECT_REGISTRATION => 'Correct registration', // TODO
        WalletReasonSystem::LIKES => 'Лайки',
        WalletReasonSystem::FILLED_PHONE => 'Указан телефон',
        WalletReasonSystem::DAILY_VISIT => 'Ежедневное посещение',
        WalletReasonSystem::TOAST_CLICK => 'Поймай Енота',
        WalletReasonSystem::TRANSFER => 'Перевод',
        WalletReasonSystem::PURCHASE => 'Покупка',
        WalletReasonSystem::BONUS => 'Бонус',
    ],
    PageTemplate::class => [
        PageTemplate::DEFAULT => 'По умолчанию',
        PageTemplate::PLAYER => 'Плеер',
        PageTemplate::PLAYER_WITH_CHAT => 'Плеер с чатом',
        PageTemplate::PLAYER_WITH_CHAT_AND_UPLOAD_FORM => 'Плеер с чатом и формой загрузки',
//        PageTemplate::CONTENT_WITH_VIDEO_UPLOAD => 'Контент с загрузкой видео',
//        PageTemplate::CONTENT_WITH_PICTURE_UPLOAD => 'Контент с загрузкой изображений',
//        PageTemplate::PICTURES_WITH_LIKES => 'Стена изображений с лайками',
//        PageTemplate::VIDEOS_WITH_LIKES => 'Стена видео с лайками',
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
        ExportType::DATA => "Данные",
        ExportType::FILES => "Файлы",
    ],
    ExportEntity::class => [
        ExportEntity::USERS => "Пользователи",
        ExportEntity::SHOP_STOCK => "Магазин: склад",
        ExportEntity::SHOP_ORDERS => "Магазин: заказы",
    ],
    MediaStatus::class => [
        MediaStatus::PENDING => 'В ожидании',
        MediaStatus::ACCEPTED => 'Принято',
        MediaStatus::REJECTED => 'Отклонено',
    ],
    MediaType::class => [
        MediaType::IMAGE => 'Изображение',
        MediaType::VIDEO => 'Видео',
    ],
];
