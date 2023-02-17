<?php

namespace App\Http\Livewire;

use App\Models\Media;
use App\Settings\MediaSettings;
use Filament\Forms\Components;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Notifications\Notification;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Livewire\Component;

class MediaUploadForm extends Component implements HasForms
{
    use InteractsWithForms;

    private MediaSettings $settings;

    public ?string $header;
    public ?string $comment;
    public ?string $successText;
    public ?string $buttonSubmitText;

    public ?array $file = null;
    public ?string $description = null;

    /**
     * @param null $id
     */
    public function __construct($id = null)
    {
        parent::__construct($id);

        $this->messages = [
            'file.required' => __('File required'),
        ];

        $this->settings = app(MediaSettings::class);
    }

    public function mount(): void
    {
        $this->header = $this->settings->form_header;
        $this->comment = $this->settings->form_comment;
        $this->successText = $this->settings->form_success_text ?: __('File sent successfully');
        $this->buttonSubmitText = $this->settings->form_button_submit_text ?: __('Submit');
    }

    /**
     * @return View
     */
    public function render(): View
    {
        return view('livewire.media-upload-form');
    }

    /**
     * @return array
     */
    protected function getFormSchema(): array
    {
        return [
            Components\FileUpload::make('file')
                ->disableLabel()
                ->placeholder($this->getFormPlaceholder())
                ->acceptedFileTypes($this->settings->form_accepted_file_types)
                ->minSize($this->settings->form_min_file_size)
                ->maxSize($this->settings->form_max_file_size)
                ->disk('local')
                ->directory('form-attachments-tmp')
                ->required(),

            Components\RichEditor::make('description')
                ->disableLabel()
                ->disableAllToolbarButtons()
                ->visible($this->settings->form_allow_description)
                ->placeholder($this->settings->form_description_placeholder)
                ->nullable(),
        ];
    }

    /**
     * @return string
     */
    private function getFormPlaceholder(): string
    {
        $placeholder = $this->settings->form_panel_placeholder ?: __('Media upload form placeholder');

        $placeholder .= " | <span class=\"filepond--label-action font-medium\">" . __('Select a file') . "</span>";

        if ($this->settings->form_min_file_size) {
            $placeholder .= " | > " . formatFileSize($this->settings->form_min_file_size * 1024);
        }

        if ($this->settings->form_max_file_size) {
            $placeholder .= " | < " . formatFileSize($this->settings->form_max_file_size * 1024);
        }

        return $placeholder;
    }

    public function submit(): void
    {
        $data = $this->form->getState();
        $mimeType = Storage::disk('local')->mimeType($data['file']);

        Media::forceCreate([
            'user_id' => Request::user()->id,
            'type' => Str::before($mimeType, "/"),
            'tmp_file' => $data['file'],
            'description' => $data['description'] ?? null,
        ]);

        Notification::make()
            ->title($this->successText)
            ->success()
            ->send();

        $this->reset(['file', 'description']);
    }
}
