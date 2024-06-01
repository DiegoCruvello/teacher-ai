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
                            'text' => 'Limite a resposta a 1500 caracteres. Utilizando uma abordagem de GTO, avalie a jogada de poker mostrada na imagem. Considere as cartas da mão, as cartas comunitárias, as posições dos jogadores, o tamanho do pote, e as ações dos jogadores até o momento. Baseando-se em um range balanceado para os oponentes e a ação de jogo até agora, determine se a jogada realizada pelo jogador é ótima ou se existe uma alternativa preferível de acordo com o GTO. Forneça sua avaliação apenas sobre a qualidade da jogada. Ao final, dê-me a melhor sugestão para ser usada no Flop, Turn e River. Retorne um JSON e cada índice deve ser o flop, turn e river. Índices adicionais também podem ser criados para exibir mensagens para o usuário. Não precisa exemplificar que é um JSON, somente retornar um JSON com chave valor. Você vai fazer a leitura sempre da esquerda para a direita, o flop você vai mandar como as 3 primeiras cartas, turn sempre a quarta e river a quinta carta. Lembre que as cartas são coloridas ou preto e branco, que vão de A,2,3,4,5,6,7,8,9,10,J,Q,K. As unicas letras que fazer parte do baralho são A,J,T,Q e K qualquer outra letra não é o parte do jogo e deve ser ignorada na analise, indo para a proxima carta.'
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
