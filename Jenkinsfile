pipeline {
    agent any

    stages {
        stage('Rodando docker-compose') {
            steps {
                sh 'docker-compose -f docker-compose.yaml up -d'
            }
        }

        stage('Rodando composer') {
            steps {
                sh 'docker exec -t app composer install'
            }
        }

        stage('Copiando .env') {
            steps {
                sh 'docker exec -t app cp .env.example .env'
            }
        }

        stage('Rodando key:generate') {
            steps {
                sh 'docker exec -t app php /var/www/artisan key:generate'
            }
        }

        stage('Rodando migrations') {
            steps {
                sh 'docker exec -t app php /var/www/artisan migrate'
            }
        }

        stage('Rodando pestPHP') {
            steps {
                sh 'docker exec -t app php ./vendor/bin/pest'
            }
        }
    }
}