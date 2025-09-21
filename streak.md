# 🔥 Projeto: Streak Button

Um site minimalista onde o usuário entra diariamente e clica em um único botão. Cada clique por dia conta como parte de uma sequência (streak). Se o usuário perder um dia, o streak é reiniciado, mas o maior streak alcançado é armazenado.

---

## 🚀 Tecnologias Utilizadas

- **Backend**: Laravel
- **Frontend**: Blade + Tailwind CSS
- **Banco de dados**: SQLite
- **Autenticação**: Laravel Breeze

---

## ✅ Funcionalidades do MVP

- [ ] Autenticação de usuário (utilizando gmail)
- [ ] Botão clicável 1x por dia
- [ ] Registro da data e hora dos cliques
- [ ] Contador de streaks consecutivos
- [ ] Armazenamento do maior streak atingido
- [ ] Bloqueio de múltiplos cliques no mesmo dia
- [ ] Tela de dashboard com botão e contadores
- [ ] Adicionar leaderboard e rankings semana, mes e all time
- [] Criar conquistas e recompensas simbólicas 

---

## Opção de Cores
#272643
#ffffff
#e3f6f5
#bae8e8
#2c698d

---

## 🧩 Lógica de streak

- Ao clicar:
  - Se for o **mesmo dia** do último clique: mostrar mensagem "Você já clicou hoje"
  - Se for o **dia seguinte** ao último clique: streak atual += 1
  - Se não for consecutivo: streak atual = 1
- O `max_streak` é atualizado se `current_streak` for maior que o valor anterior

---

## 💰 Estratégias de Monetização

### 1. Assinatura Premium
- Histórico visual do streak
- Notificações automáticas
- Skins/temas exclusivos
- Estatísticas avançadas
- Perfil público personalizado

### 2. Moedas Virtuais (Click Coins)
- Ganhas moedas por streaks mantidos
- Moedas servem para desbloquear:
  - Sons personalizados
  - Botões temáticos
  - Efeitos visuais no clique

### 3. Leaderboards Pagas
- Participação em rankings semanais/mensais
- Destaque do nome/avatar na lista global
- Concursos pagos com premiações reais ou simbólicas

### 4. Venda de Produtos Digitais
- Pacotes de temas, ícones, efeitos visuais
- Sons de clique especiais (ex: 8-bit, explosão, sinos)

### 5. Aplicativo Mobile
- Versão PWA grátis
- Aplicativo pago nas lojas (R$3–10)
- Ou compras internas no app

### 6. Publicidade (leve)
- Banner não invasivo apenas para usuários gratuitos
- Espaço patrocinado: “Este streak é patrocinado por…”

### 7. Parcerias e Patrocínios
- Marcas podem patrocinar streaks longos com brindes
- Comunidades podem criar streaks temáticos (ex: "streak da leitura")

---

## 🧪 Próximos Passos


2. Criar sistema de assinatura com Stripe (USA) AbacatePay pagamento pix (Brasil) (não executado)
3. Implementar sistema de moedas virtuais e loja  (não executado)
4. Gerar histórico de streaks em visual de calendário (apenas ultimos 7 dias)
5. Criar versão PWA para uso em celular (não executado)
6. Adicionar skins/temas e efeitos visuais no clique (não executado)
7. Implementar notificações de lembrete por e-mail (não executado)
9. Lançar plano gratuito e premium (não executado)
10. Compartilhamento social de streaks (Twitter/X, Instagram) (não executado)
11. Notificações por e-mail, push, WhatsApp ou Telegram (não executado)

---


---

## Gerar clicks para usuario
(new \Database\Seeders\ClickSeeder)->run(1, 29); // usuário ID=1, 29 dias clicados

sail artisan db:seed --class=StreakWithOneMissedDayRefilSeeder

sail artisan db:seed --class=StreakWithOneMissedDayNoRefilSeeder

sail artisan db:seed --class=StreakResetSeeder

sail artisan db:seed --class=UserWithStreakSeeder
