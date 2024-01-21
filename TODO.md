# Ciphered Lock

## Descrição da ideia/princípio

- servidor que guarda/gere passwords em "vaults" cifrados
- cada **vault** regista *metadados* (url, username, observações, etc.) e a password de acesso ao serviço relacionado;
  ex.: \
  `{ descricao: "Dropbox", url: https://dropbox.com, username: slopes; token|data: <password cifrada> }`

- o **token** (ou **data**) é o valor crifrado com chave simétrica (posteriormente codificado em base64 para
  armazenamento), da password
- a chave simétrica para cifrar os dados é gerada aleatoriamente pelo servidor
- depois de gerada e usada para cifrar a password a chave simétrica é cifrada com a chave pública do utilizador e também
  guardada no servidor
- o servidor não consegue decrifar o **token** porque deixa de ter a chave, está cifrada com a pública do utilizador e o
  servidor não tem a privada
- o servidor precisa enviar ao utilizador os dados cifrados (da chave simétrica) para que o utilizador decifre com a sua
  privada e devolva ao servidor a chave decifrada
- o servidor usa a chave decrifrada para decifrar o **token** e devolver o conteúdo decifrado ao utilizador
- o utilizador usa a chave simétrica para ações no servidor que atuam sobre os **vaults** de que é dono

## Funcionalidades:

NOTA: Podemos passar isto para issues no github como funcionalidades a desenvolver.

- **[POR FAZER]** Register - Criar uma nova conta de utilizador no sistema, enviado o par de Chaves Pub/Priv
- Chave Pública e privada fica do lado do cliente
- Chave pública fica do lado do servidor \
  INPUT: email \
  OUTPUT: Par de chaves pública/privada

- **[POR FAZER]** Login - Chaves Pública/privada
- o servidor assina um valor conhecido com a chave pública do utilizador, preferencialmente derivado dos dados do
  utilizador (ex.: hash do e-mail+username), e envia para o cliente decifrar;
- o cliente envia a hash decifrada para o servidor, que a compara com o valor guardado na base de dados
- implica um processo de autenticação em duas etapas (dois pedidos HTTP) \
  INPUT1: email
  INPUT2: hash decifrada

- **[POR FAZER]** Criar Vault (Combinação de apenas 1 user:password:dominio)
- **[POR FAZER]** Partilhar Vault

## Estrutura de Dados

* DB - Sqlite
* Cifra - RSA (Diffie-Helman) + AES (stream)

## Sitemap:

```
/app/index                  [GET] Ecrã inicial (indefinido)
/app/login                  [GET] Ecrã com UI de login
/app/bootstrap-login        [POST-AJAX] Etapa 1 do processo de login
/app/confirm-login          [POST-AJAX] Etapa 2 do processo de login
/app/logout                 [POST] Ação de fecho de sessão, sem UI, redireciona para /app/index
/app/profile                [GET|POST] Ecrã de dados de utilizador, detalhes e edição
/app/settings               [GET|POST] Ecrã de configurações de utilizador, detalhes e edição
/app/documentation          [GET] Ecrã com manual (placeholder)
/app/copyright              [GET] Ecrã com informação do projecto
/app/changelog              [GET] Ecrã com informação de alterações (placeholder)

/register/index             [GET]
/register/store             [POST-AJAX]

/vault/index                [GET] Lista de cofres do utilizador autenticado
/vault/create               [POST-AJAX] Permite criar um novo cofre, UI é dada pela modal existente no tema  
/vault/update/<id>          [POST-AJAX] Permite editar os dados de um cofre existente, UI é dada pela modal existente no tema
/vault/delete/<id>          [POST-AJAX] Remove um cofre pertencente ao utilizador atual
/vault/revoke-access/<id>   [POST-AJAX] Remove o acesso de um utilizador ao cofre detido pelo utilizador que executa a ação
/vault/share/<id>           [POST-AJAX] Partilha um cofre com um utilizador destinatário
```

## Exemplos de Nomes da Aplicação

Gerados pelo ChatGPT.

- SecurePassVault
- CryptoGuardian
- CipherKeySafe
- SafeLockVault
- EncrypTrust
- FortifyPass
- ShieldedKeyKeeper
- SafeCipherHub
- GuardedVault
- EncryptShield
- SecureKeyArmory
- CipherGuard Fortress
- VaultArmor
- PassDefender
- CryptoSecureKeeper
- SecureKeyCrypt
- GuardianPassLock
- EncryptedArmor
- CipherFortress
- PassSafeGuard
- VaultCrypt
- EncryptionVault
- PasswordlessGuard
- SecureVaultCipher
- CipherLockVault
- PasswordlessShield
- VaultGuardian
- EncryptionFortress
- CipherKeyVault
- PasswordlessArmor
- VaultDefender
- SecureEncryptionHub
- CipherShieldVault
- PasswordlessCryptKeeper
- VaultGuarded
- EncryptionArmory
- PasswordlessFortify
- SecureVaultCipher
- CipherDefender
- PasswordlessGuardianVault

## Other

Logo maker: https://www.brandcrowd.com/