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

    public function analyzeImage(InputCreateImage $dto): array
    {
        $body = [
            'model' => 'gpt-4-turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => [
                        [
                            'type' => 'text',
                            'text' => '
                            Você é um jogador de poker profissional que segue a Teoria de Jogos Ótima (GTO). Sua tarefa é avaliar a jogada de poker mostrada em uma imagem. Considere as cartas da mão, as cartas comunitárias, as posições dos jogadores, o tamanho do pote e as ações dos jogadores até o momento.

                            1. Analise a jogada atual do jogador baseado em um range balanceado para os oponentes e a ação de jogo até agora.
                            2. Determine se a jogada realizada pelo jogador é ótima ou se existe uma alternativa preferível de acordo com o GTO.
                            3. Forneça sua avaliação sobre a qualidade da jogada.
                            4. Dê a melhor sugestão de jogada para o Flop, Turn e River.

                            Retorne suas avaliações e sugestões em formato JSON com as seguintes chaves:
                            - flop: (Três primeiras cartas comunitárias, jogada recomendada e justificativa, não falar o naipe da carta)
                            - turn: (Quarta carta comunitária, jogada recomendada e justificativa, não falar o naipe da carta)
                            - river: (Quinta carta comunitária, jogada recomendada e justificativa, não falar o naipe da carta)
                            - mensagem: (Mensagem geral para o usuário)

                            Retorne um JSON e cada índice deve ser o flop, turn e river. Índices adicionais também podem ser criados para exibir mensagens para o usuário. Não precisa exemplificar que é um JSON, somente retornar um JSON com chave valor.

                            Exemplo de resposta JSON:

                            {
                              "flop": "",
                              "turn": "",
                              "river": ""
                              "mensagem": ""
                            }
                            '
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

            $decode = json_decode($response->getBody()->getContents(), true);

            return json_decode($decode['choices'][0]['message']['content'], true);
        } catch (GuzzleException $e) {
            throw new Exception('Falha ao realizar a requisição: ' . $e->getMessage());
        }
    }
}
