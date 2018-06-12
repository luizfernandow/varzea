# varzea

docker run -it --rm \
-v docker_varzea_certs:/etc/letsencrypt \
-v docker_varzea_certs-data:/data/letsencrypt \
certbot/certbot \
certonly --webroot \
--email admin@asfl.com.br --agree-tos --no-eff-email \
--webroot-path=/data/letsencrypt \
-d varzea.asfl.com.br 

0 23 * * * docker run --rm -it --name certbot -v docker_varzea_certs:/etc/letsencrypt  -v "docker_varzea_certs-data:/data/letsencrypt  certbot/certbot renew --quiet
