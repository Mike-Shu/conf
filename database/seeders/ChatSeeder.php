<?php

namespace Database\Seeders;

use App\Models\ChatConversation;
use App\Models\Tenant;
use App\Models\User;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Seeder;

class ChatSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     * @throws Exception
     */
    public function run(): void
    {
        $this->getTenant("first")->run(function () {
            $user = User::first();

            $chatConversations = collect([
                [
                    'title' => "Chat for home page",
                    'messages' => collect([
                        "Hi all!",
                        "It's a good weather today",
                        "How do I get to the library?",
                    ]),
                ],
                [
                    'title' => "Chat on the second page",
                    'messages' => collect([
                        "Hello world!",
                        "Happy holiday everyone",
                        "What movie to watch on the weekend?",
                    ]),
                ],
            ]);

            $chatConversations->each(function ($_conversation) use ($user) {
                /** @var ChatConversation $chat */
                $chat = ChatConversation::factory([
                    'title' => $_conversation['title'],
                ])->create();

                $_conversation['messages']->each(function ($_message) use ($user, $chat) {
                    $chat->messages()->create([
                        'user_id' => $user->id,
                        'text' => $_message,
                    ]);
                });
            });
        });
    }

    /**
     * @param string $subdomain
     * @return Tenant
     */
    private function getTenant(string $subdomain): Tenant
    {
        return Tenant::whereHas('domains', static function (Builder $query) use ($subdomain) {
            $query->where('domain', $subdomain . '.' . config('app.domain'));
        })
            ->with('domains')
            ->first();
    }
}
