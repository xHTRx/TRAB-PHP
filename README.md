# Nome dos integrantes:
- Heitor Auagusto Andrade
- Jhanny Aparecida Rebeiko Pianovski
## Tema Escolhido: Sistema Interno de Gestão de Agendamentos para Clínica de Estética

O projeto desenvolve um Sistema Interno de Gestão de Agendamentos, feito especificamente para atender às necessidades de uma clínica de estética que precisem otimizar e controlar seus horários de serviço de forma eficiente. A ferramenta visa centralizar as informações de horários, permitindo que a equipe interna realize cadastros, visualize, edite e exclua agendamentos de forma segura e organizada, otimizando o fluxo de trabalho e o atendimento ao público.

# Resumo do Funcionamento:

O sistema é uma ferramenta de uso exclusivo para a equipe interna da clínica, operando de maneira lógica e segura. O acesso se dá através de uma página de login (index.php), onde novos colaboradores podem ser cadastrados (cadastro_usuario.php) ou fazer login com suas credenciais. Uma vez autenticados, os funcionários são direcionados para uma área segura onde podem registrar novos agendamentos de pacientes (via salvarDB.php). A funcionalidade central reside na página clientes.php (que na verdade lista os agendamentos dos pacientes), onde cada usuário pode visualizar todos os horários que foram registrados por ele ou que estão sob sua responsabilidade. É possível editar um agendamento (editar.php) ou excluí-lo (excluir.php), com validações que asseguram que cada funcionário apenas manipule os agendamentos para os quais possui permissão, mantendo a integridade e a organização dos dados. O logout.php garante o encerramento seguro da sessão.

# Usuário/Senha de Teste:
Para testar o sistema como um funcionário da clínica, você precisará criar uma conta, pois não há usuários pré-cadastrados por padrão (por conta da utilização de hash). Siga estes passos:

1. Acesse a página inicial do sistema (index.php).
2. Clique no link **Cadastre-se** para ir para a tela de registro de novo colaborador (cadastro_usuario.php).
3. Preencha o formulário de cadastro com os dados de um funcionário de teste, por exemplo:
    Nome de Usuário: admin
    E-mail: admin@gmail.com
    Senha:admin123 (ou outra de sua preferência, seguindo a regra de no mínimo 6 caracteres).
    Confirmar Senha: Repita admin123.
4. Clique em **Cadastrar**.
Após o cadastro bem-sucedido, você será redirecionado para a página de login.

Use as credenciais recém-criadas para acessar o sistema como um funcionário:
Usuário: admin 
Senha: admin123

# Passos para Instalação do Banco de Dados:
1. Dentro da pasta htdocs: **Clone** ou faça **dowload** do repositório.
2. Acesse o **xampp-controll** na pasta **XAMPP** do seu computador.
3. No Painel de Controle do XAMPP, clique em **start** no **Apache** e no **MySQL**.
4. Após os dois estarem rodando, clique em **Admin** ao lado do MySQL.
5. No cabeçalho, vá em **importar**.
6. Vá em escolher arquivo, e escolha o arquivo **criar_banco.sql**, que esta dentro da pasta **sql**.
7. Clique em **Importar**.
8. **Divirta-se :)**
