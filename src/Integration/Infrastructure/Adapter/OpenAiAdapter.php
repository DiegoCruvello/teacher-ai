<?php

namespace TeacherAi\Integration\Infrastructure\Adapter;

use Exception;
use GuzzleHttp\Exception\GuzzleException;
use TeacherAi\Integration\Application\DTO\InputCreateImage;
use TeacherAi\Integration\Domain\Adapter\SendImageAdapterInterface;
use TeacherAi\Integration\Infrastructure\Client\OpenAi;

class OpenAiAdapter implements SendImageAdapterInterface
{
    public function __construct(
        public readonly OpenAi $client,
    ) {
    }

    public function analyzeImage(InputCreateImage $dto): string
    {
        $body = [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => 'Limite a resposta a 1500 caracteres, utilizando uma abordagem de GTO, avalie a jogada de poker mostrada na imagem. Considere as cartas da mão, as cartas comunitárias, as posições dos jogadores, o tamanho do pote, e as ações dos jogadores até o momento. Baseando-se em um range balanceado para os oponentes e a ação de jogo até agora, determine se a jogada realizada pelo jogador é ótima ou se existe uma alternativa preferível de acordo com o GTO. Forneça sua avaliação apenas sobre a qualidade da jogada.'
                        ],
                        [
                            'type' => 'image_url',
                            'image_url' => [
                                'url' => $dto->image,
                            ]
                        ]
                    ]
                ]
            ],
            'max_tokens' => 450
        ];

        try {
            $response = $this->client->getClient()->post('v1/chat/completions', [
                'json' => $body
            ]);

            return json_decode($response->getBody()->getContents(), true)['choices'][0]['message']['content'];
        } catch (GuzzleException $e) {
            throw new Exception('Falha ao realizar a requisição: ' . $e->getMessage());
        }
    }
}
