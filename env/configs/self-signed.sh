# Em shell; Nota: IP a usar no certificado é o IP da VM (192.168.86.100 atualmente)
# Não faz parte do script de configuração da máquina por obrigar a preenchimento manual de informação
# Executar os comandos como "root" ou colocar "sudo" antes de cada um
#
# Criar o certificado e o par de chaves necessário para o assinar; indicar que não é um "certificate request" mas sim a geração final
#
  openssl req -x509 -nodes -days 365 -newkey rsa:2048 -keyout /etc/ssl/private/nginx-selfsigned.key -out /etc/ssl/certs/nginx-selfsigned.crt
# Criar um valor para forte para processo de FS (Forward Secrecy, https://en.wikipedia.org/wiki/Forward_secrecy) usado pelo Diffie-Hellman do HTTPS (TLS)
#
openssl dhparam -out /etc/ssl/certs/dhparam.pem 2048