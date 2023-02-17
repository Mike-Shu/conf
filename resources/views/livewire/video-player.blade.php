@if($player)
    @if($player->provider === 'youtube')
        <div class="embed-video rounded">
            <iframe src="https://www.youtube.com/embed/{{ $player->video_id }}" frameborder="0" uk-video="automute: true" allowfullscreen uk-responsive></iframe>
        </div>
    @endif
@endif
