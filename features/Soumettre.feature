Feature: Soumission de justificatif d'absence
  En tant qu'étudiant
  Je souhaite soumettre un justificatif avec accusé de réception
  Afin de justifier mon absence

  Scenario Outline: echec de soumission du justificatif
    Given je suis un etudiant avec un id
    When je soumets un justificatif avec tous les champs requis etant remplis invalides "<dateDebut>" "<HeureDebut>" "<DateFin>" "<HeureFin>"
    Then le systeme me signale que je n'ai pas d'absence entre ces dates
    And le systeme ne m'envoie pas de mail de confirmation

    Examples:
      | dateDebut  | HeureDebut | DateFin    | HeureFin |
      | 2023-10-01 | 08:00      | 2024-10-01 | 12:00    |


  Scenario Outline: soumettre le justificatif
    Given je suis un etudiant avec un id
    When je soumets un justificatif avec tous les champs requis etant remplis invalides "<dateDebut>" "<HeureDebut>" "<DateFin>" "<HeureFin>"
    Then le systeme m'envoie un mail de confirmation de dêpot


    Examples:
      | dateDebut  | HeureDebut | DateFin    | HeureFin |
      | 2023-10-01 | 08:00      | 2024-10-01 | 12:00    |