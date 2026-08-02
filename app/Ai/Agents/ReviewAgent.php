<?php

namespace App\Ai\Agents;
use App\AI\Prompts\ReviewPrompt;
use App\Ai\Tools\DetectLanguageTool;
use Laravel\Ai\Contracts\Agent;
use Laravel\Ai\Contracts\Conversational;
use Laravel\Ai\Contracts\HasTools;
use Laravel\Ai\Contracts\Tool;
use Laravel\Ai\Messages\Message;
use Laravel\Ai\Promptable;
use Stringable;

class ReviewAgent implements Agent, Conversational, HasTools
{
    use Promptable;

    /**
     * Instructions système de l'agent.
     */
   public function instructions(): Stringable|string
{
    return ReviewPrompt::system(
        \App\Services\ReviewPersistenceService::AVAILABLE_TAGS
    );
}

    /**
     * Historique de conversation.
     *
     * @return Message[]
     */
    public function messages(): iterable
    {
        return [];
    }

    /**
     * Outils disponibles.
     *
     * @return Tool[]
     */
    public function tools(): iterable
    {
        return [
            // DetectLanguageTool::class,
        ];
    }
}