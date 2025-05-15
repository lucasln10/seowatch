# SeoWatch

SeoWatch é um SaaS de auditoria SEO On-Page desenvolvido em Laravel e MySQL, com frontend feito em JavaScript, HTML e CSS. A ferramenta permite escanear páginas web para identificar problemas e sugerir melhorias, ajudando empresas e profissionais de marketing a otimizar seus sites para mecanismos de busca.

---

## Objetivo

O SeoWatch tem como finalidade oferecer uma auditoria automática e prática para SEO On-Page, focando em pontos como meta tags, velocidade da página, análise de palavras-chave, monitoramento de ranking e geração de relatórios detalhados. É ideal para agências de marketing, desenvolvedores e times internos de SEO que precisam de insights rápidos e contínuos para melhorar o desempenho orgânico de seus sites.

---

## Funcionalidades Principais

- **Análise de Meta Tags:** Verificação de título, meta description, OG tags e canonicals, com sugestões para melhorias.
- **Velocidade da Página:** Integração com Google PageSpeed Insights para medir Core Web Vitals.
- **Análise de Palavras-chave:** Identificação de gaps de keywords e recomendações de densidade ideal.
- **Monitoramento de Ranking:** Rastreamento semanal da posição no Google para palavras-chave específicas, com gráficos de evolução.
- **Geração de Relatórios:** Relatórios em PDF enviados automaticamente por e-mail.
- **Cadastro e Gestão de Sites:** Interface para adicionar, listar e gerenciar sites para auditoria.

---

## Tecnologias Utilizadas

- **Backend:** Laravel Framework + PHP 8+
- **Banco de Dados:** MySQL
- **Frontend:** JavaScript, HTML e CSS (puro e com Bootstrap 5 para estilos)
- **APIs Externas:** Google PageSpeed Insights, SerpAPI (opcional)
- **Outros:** Composer, Laravel Jobs para filas e envio de e-mails

---

## Como Rodar o Projeto

1. Clone o repositório:
    ```bash
    git clone https://github.com/seu-usuario/seowatch.git
    cd seowatch
    ```

2. Instale as dependências PHP:
    ```bash
    composer install
    ```

3. Configure o arquivo `.env` com suas credenciais do banco de dados e outras variáveis.

4. Gere a chave do aplicativo:
    ```bash
    php artisan key:generate
    ```

5. Rode as migrations para criar as tabelas:
    ```bash
    php artisan migrate
    ```

6. Inicie o servidor local:
    ```bash
    php artisan serve
    ```

7. Acesse no navegador:
    ```
    http://localhost:8000
    ```

---

## Rotas Principais

| Método | URL             | Descrição                           |
|--------|-----------------|-----------------------------------|
| GET    | /site/adicionar | Exibe formulário para adicionar site |
| POST   | /site/adicionar | Processa o envio e salva o site   |
| GET    | /index          | Lista sites cadastrados (em desenvolvimento) |

---

## Estrutura Frontend

- O front-end é composto por views Blade com HTML e CSS.
- Utiliza JavaScript para interação dinâmica onde necessário.
- Bootstrap 5 é usado para estilização rápida e responsiva.
- O objetivo é manter o frontend simples, leve e funcional, podendo ser expandido para frameworks JS no futuro.

---

## Próximos Passos / Evoluções

- Implementar autenticação e cadastro de usuários.
- Integrar crawler para auditorias automáticas.
- Criar dashboards interativos com gráficos (ex: Chart.js).
- Adicionar fila para processamento em background.
- Gerar e enviar relatórios em PDF automaticamente.

---

## Contribuindo

Contribuições são bem-vindas!  
Por favor, abra issues para discutir melhorias antes de enviar pull requests.

---

## Licença

MIT License
