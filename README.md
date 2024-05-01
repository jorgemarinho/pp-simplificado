## Pp Simplificado


Instalação Docker Engine (Docker Nativo) diretamente instalado no WSL2

```
https://github.com/codeedu/wsl2-docker-quickstart#docker-engine-docker-nativo-diretamente-instalado-no-wsl2

```

Subir o serviço docker

```
service docker start
```


Primeira vez, fazer um build

```
docker-compose up --build 
```

Outras vezes
```
docker-compose up -d
```

Serviço disponivel na porta

```
http://localhost:8000
```

Executando teste
```
composer test
```

Test coverage %
```
composer test:coverage

```