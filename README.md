# GestionnaireStockMat-rielNucl-aire

## Installation des nécessaire

### Docker network

```bash
docker network create app
```

### Passé en https de force en local (optionnel)

> Le container doit déjà etre allumer

1. Ce co au container (en roots)

```bash
docker compose exec -itu root app bash
```

2. Executer cette url donnée dans la doc [Symfony](https://symfony.com/download)

```bash
curl -sS https://get.symfony.com/cli/installer | bash
```

3. Installer ainsi que mettre l'alias dans le bash Symfony ainsi que le Https forcé dans Symfony 6

```bash
export PATH="$HOME/.symfony5/bin:$PATH" # mettre dans la path de l'utilisateur (en local et non enregistrer)
symfony server:ca:install
```

### Configuré le container pour l'allumé

La configuration va se faire en 2 étapes:

- Modifier le fichier [`.env`](docker/.env)
- Build l'image qui se situe dans le dossier [`$pwd\GestionnaireStockMat-rielNucl-aire\docker\web`](docker/web/Dockerfile) (optionnel puisqu'il le fait seul au début de l'allumage)

Une fois que c'est allumé il faudra fair la migration de la base de donnée

```bash
bin/console doctrine:migrations:migrate # il faudra accepter en ecrivant 'yes'
```

apres que la migration sois passé, il faudra allez sur la route suivante :

```url
http://hostname/init # hostname => ip ou domaine ou il y a docker
```

Une fois fait vous pourrez vous co avec les comptes suivant :

- username : gior <br>
  password : gior <br>
  droit    : USER

- username : admin <br>
  password : admin <br>
  droit    : USER et ADMIN

Vous seraiez alors connecter a un des deux compte suivant

## Affichage

<details>
  <summary>Les Vue Utilisateur</summary>
  <br>

  ### Page d'acceuil utilisateur
  <figure>
    <img src="public/imagesDocs/acceuil_user.png"/>
    <figcaption>Il y a les infos de l'utilisateur ainsi que c'est tache ainsi qu'un menu</figcaption>
  </figure>
  <br>

</details>

<details>
  <summary>Les Vue Administrateur</summary>
  
  ### Page d'acceuil admina
  <figure>
    <img src="public/imagesDocs/acceuil_user.png"/>
    <figcaption>Il y a les infos de l'utilisateur ainsi que c'est tache ainsi qu'un menu. il y a aussi un moyen de crée un utilisateur avec un ou les deux role</figcaption>
  </figure>
</details>

<details>
  <summary>Vue de tout les roles</summary>

  ### Table de recherche vide
  <figure>
    <img src="public/imagesDocs/tab_recherche.png"/>
  </figure>

  ### Table de recherche remplie
  <figure>
    <img src="public/imagesDocs/tab_recherche_remplie.png"/>
  </figure>

  ### Liste des tache remplie
  
</details>
