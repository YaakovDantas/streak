# 📊 Diagrama de Banco de Dados - Streak Button

## 🧑‍💼 Tabela `users`
| Campo      | Tipo     | Descrição                                      |
|------------|----------|------------------------------------------------|
| id         | INTEGER  | ID do usuário (PK)                             |
| name       | TEXT     | Nome                                           |
| email      | TEXT     | Email (único)                                  |
| password   | TEXT     | Senha criptografada                            |
| nickname   | TEXT     | nick do usuario para display                   |
| country    | TEXT     | Pais para metodo de pagamento                  |
| is_vip     | BOOLEAN  | Indica se o usuário é VIP (padrão: false)      |
| created_at | DATETIME | Data de criação                                |
| updated_at | DATETIME | Data de atualização                            |

## 🖱️ Tabela `clicks`
| Campo      | Tipo     | Descrição                     |
|------------|----------|-------------------------------|
| id         | INTEGER  | ID do clique (PK)             |
| user_id    | INTEGER  | FK para `users`               |
| clicked_at | DATETIME | Data/hora do clique           |
| created_at | DATETIME | Data de criação               |
| updated_at | DATETIME | Data de atualização           |

## 🔥 Tabela `streaks`
| Campo           | Tipo     | Descrição                         |
|-----------------|----------|-----------------------------------|
| id              | INTEGER  | ID do streak (PK)                 |
| user_id         | INTEGER  | FK para `users`                   |
| current_streak  | INTEGER  | Contador de streak atual          |
| highest_streak  | INTEGER  | Maior streak registrado           |
| last_clicked_at | DATETIME | Último clique registrado          |
| created_at      | DATETIME | Data de criação                   |
| updated_at      | DATETIME | Data de atualização               |

## ♻️ Tabela `refill_balances`
| Campo      | Tipo     | Descrição                                 |
|------------|----------|--------------------------------------------|
| id         | INTEGER  | ID do saldo (PK)                           |
| user_id    | INTEGER  | FK para `users`                            |
| free       | INTEGER  | Refis grátis disponíveis                   |
| ad         | INTEGER  | Refis por anúncio disponíveis              |
| paid       | INTEGER  | Refis pagos disponíveis                    |
| created_at | DATETIME | Data de criação                            |
| updated_at | DATETIME | Data de atualização                        |

## 💊 Tabela `refills`
| Campo        | Tipo     | Descrição                                             |
|--------------|----------|-------------------------------------------------------|
| id           | INTEGER  | ID do refil (PK)                                      |
| user_id      | INTEGER  | FK para `users`                                       |
| type         | TEXT     | Tipo do refil: `free`, `ad`, `paid`                   |
| refilled_for | DATE     | Dia que foi compensado com o refil                   |
| used_at      | DATETIME | Data/hora de uso do refil (nullable)                 |
| created_at   | DATETIME | Data de criação                                       |
| updated_at   | DATETIME | Data de atualização                                   |

## 🏅 Tabela `badges`
| Campo      | Tipo    | Descrição                                    |
|------------|---------|----------------------------------------------|
| id         | INTEGER | ID da badge (PK)                             |
| name       | STRING  | Nome da badge                                |
| slug       | STRING  | Identificador único (ex: `first_click`)      |
| icon_path  | STRING  | Caminho do ícone SVG (opcional)              |
| created_at | DATETIME| Data de criação                              |
| updated_at | DATETIME| Data de atualização                          |

## 🧾 Tabela `user_badges`
| Campo      | Tipo      | Descrição                                     |
|------------|-----------|-----------------------------------------------|
| id         | INTEGER   | ID do vínculo (PK)                            |
| user_id    | INTEGER   | FK para `users`                               |
| badge_id   | INTEGER   | FK para `badges`                              |
| earned_at  | TIMESTAMP | Data/hora em que o usuário recebeu a badge    |
| created_at | DATETIME  | Data de criação                               |
| updated_at | DATETIME  | Data de atualização                           |

## Tabela `badge_requirements`
| Campo       | Tipo     | Descrição                                                              |
| ----------- | -------- | ---------------------------------------------------------------------- |
| id          | INTEGER  | ID do requisito (PK)                                                   |
| badge\_id   | INTEGER  | FK para `badges` (identifica a badge à qual o requisito pertence)      |
| type        | STRING   | Tipo de requisito (ex: `clicks`, `streak`, `refills`)                  |
| target      | INTEGER  | Valor alvo necessário para conquistar a badge (ex: 7 dias, 10 cliques) |
| created\_at | DATETIME | Data de criação                                                        |
| updated\_at | DATETIME | Data de atualização                                                    |

## 💳 Tabela payments
| Campo         | Tipo     | Descrição                                                  |
| ------------- | -------- | ---------------------------------------------------------- |
| id            | INTEGER  | ID do pagamento (PK)                                       |
| user\_id      | INTEGER  | FK para `users`                                            |
| amount        | DECIMAL  | Valor pago (ex: 2.00 para \$2)                             |
| payment\_type | TEXT     | Tipo do pagamento (ex: `paid_refill`)                      |
| status        | TEXT     | Status do pagamento (ex: `pending`, `completed`, `failed`) |
| payment\_date | DATETIME | Data/hora da realização do pagamento                       |
| created\_at   | DATETIME | Data de criação                                            |
| updated\_at   | DATETIME | Data de atualização                                        |



## 🔗 Relacionamentos entre tabelas

- `users` 1 --- N `clicks`  
- `users` 1 --- 1 `streaks`  
- `users` 1 --- 1 `refill_balances`  
- `users` 1 --- N `refills`  
- `users` 1 --- N `user_badges`  
- `badges` 1 --- N `user_badges`
- `badges` 1 --- 1 `badge_requirements`
- `users` 1 --- N `payments`


### Notas

- A tabela `refill_balances` mantém o saldo atual de refis de cada tipo por usuário.  
- A tabela `refills` registra o uso dos refis e para qual dia do streak ele foi aplicado (para compensar dias perdidos).  
- A coluna `is_vip` em `users` indica se o usuário tem direito a acúmulo ilimitado de refis grátis.  
