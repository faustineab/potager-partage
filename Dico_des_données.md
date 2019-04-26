# Dictionnaire de données

## Potager (`garden`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
| id | INT | PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT | L'identifiant du potager |
| name | VARCHAR(255)| NOT NULL | nom du potager |
| adress | TEXT | NOT NULL | adresse du potager |
| meters | INT | NULL| m2 du potager |
| numbers_plots_row | INT | NOT NULL | Nombre de parcelles sur le potager |
| numbers_plots_column | INT | NOT NULL | Nombre de parcelles sur le potager |
| created_at | TIMESTAMP | NOT NULL, DEFAULT CURRENT_TIMESTAMP | La date de création du potager |
| updated_at | TIMESTAMP | NULL | La date de la dernière mise à jour du potager |
| user_id | INT|NOT NULL | id de l'admin |


## Parcelles (`plots`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la parcelle|
| status | VARCHAR(32)| NOT NULL | free or occupied |
| water_irrigation | VARCHAR(32)| NULL | arrosé ou pas encore|
| irrigation_date | TIME | NULL | date de l'arrosage|
| garden_id | INT|NOT NULL|id du potager|
| user_id | INT|NOT NULL|id du user|

## Fruits&Légumes (`vegetable`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant du fruit ou légume|
| name | VARCHAR(255)| NOT NULL | nom du vegetable|
| water_irrigation_interval | INT | NOT NULL | temps entre arrossage |
| growing_interval | INT | NOT NULL | nom du vegetable|


## Quels fruits sur quelles parcelles (`vegetables_plots`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
| vegetable_id | INT|NOT NULL|id du vegetable|
| plots_id | INT|NOT NULL|id de la parcelle|
| seedling_date | TIME | NOT NULL | date de semis|
| reaping_date | TIME | NULL | date de récolte|

## Offre (`offer`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'offre|
| vegetable_id | INT|NOT NULL| id du vegetable ou fruit |
| total | INT | NOT NULL | Total à donner |
| Quantity | INT | NOT NULL | quantité à donner|
| user_id | INT|NOT NULL|id du user qui offre|

## Receveur (`command`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'offre|
| quantity | INT|NOT NULL| quantité prise |
| offre_id | INT|NOT NULL| id de l'offre |
| user_id | INT|NOT NULL|id du user qui reçoit|

## Question (`question`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la question|
| title | VARCHAR(255)| NOT NULL | titre de la question|
| text | TEXT | NOT NULL | corps de la question|
|created_at | TIMESTAMP |NOT NULL, DEFAULT CURRENT_TIMESTAMP |La date de création de la question|
|updated_at | TIMESTAMP |NULL | La date de la dernière mise à jour de la question |
| user_id | INT |NOT NULL | L'identifiant du createur de la question |

## Tag (`tag`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la question|
| name | VARCHAR(64) | NOT NULL | nom du tag|

## Question correspondant aux tags (`question_tag`)

|Champ|Type|Spécificités|Description|
|-|-|-|-|
|question_id|INT|NOT NULL|L'identifiant de la question|
| tag_id |INT |NOT NULL | L'identifiant du tag|

## Reponses (`answer`)

Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la réponse|
| text | TEXT | NOT NULL | réponse|
| created_at | TIMESTAMP |NOT NULL, DEFAULT CURRENT_TIMESTAMP |La date de création de la réponse|
| updated_at | TIMESTAMP |NULL |La date de la dernière mise à jour de la réponse|
| question_id |INT|NOT NULL|L'identifiant de la réponse|
| user_id |INT |NOT NULL | L'identifiant du createur de la réponse|

## Utilisateurs (`user`)

Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de l'utilisateur|
| Name | VARCHAR(32)| NOT NULL , UNIQUE| pseudo de l'utilisateur|
| email | VARCHAR(64)| NOT NULL , UNIQUE| email de l'utilisateur|
| password | VARCHAR(255)| NOT NULL| MDP de l'utilisateur|
| phone | VARCHAR(64)| NOT NULL , UNIQUE| téléphone de l'utilisateur|
| adress | VARCHAR(255)| NOT NULL | adresse de l'utilisateur|
| created_at | TIMESTAMP |NOT NULL, DEFAULT CURRENT_TIMESTAMP |La date de création du user|
| updated_at | TIMESTAMP |NULL |La date de la dernière mise à jour du user|
| role_id |INT |NOT NULL | L'identifiant du role de l'utilisateur|

## Role (`role`)

Champ|Type|Spécificités|Description|
|-|-|-|-|
|id|INT|PRIMARY KEY, NOT NULL, UNSIGNED, AUTO_INCREMENT|L'identifiant de la réponse|
| name | VARCHAR(255) | NOT NULL | ROLE_USER ou ROLE_ADMIN|
| label | VARCHAR(255) | NOT NULL | Administrateur ou utilisateur|