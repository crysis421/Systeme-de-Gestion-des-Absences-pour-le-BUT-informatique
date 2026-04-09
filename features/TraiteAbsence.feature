Feature: Traitement d'un justificatif par le responsable
  En tant que responsable
  Je veux prendre une décision sur le justificatif d'un élève
  Afin de valider, refuser ou redemander un justificatif d'absence

  Scenario Outline: Valider une absence avec un motif valide
    Given je suis un responsable connecté
    And il existe un justificatif en attente avec l'id "<idJustificatif>"
    When je valide le justificatif avec le motif "<motif>"
    Then le système met l'absence en statut "valide"
    And le système verrouille l'absence
    And le système m'informe que le traitement est "<valide>"

    Examples:
      | idJustificatif | motif     | valide  |
      | 1           | malade    | valide  |
      | 1           | transport | valide  |

  Scenario Outline: Refuser une absence avec un motif
    Given je suis un responsable connecté
    And il existe un justificatif en attente avec l'id "<idJustificatif>"
    When je refuse le justificatif avec le motif "<motif>"
    Then le système m'informe que le traitement est "<resultat>"

    Examples:
      | idJustificatif | motif                     | resultat |
      | 1              | N'est pas venu en cours   | valide   |
      | 1              | <a>test</a>               | invalide |

  Scenario Outline: Redemander un justificatif avec un motif
    Given je suis un responsable connecté
    And il existe un justificatif en attente avec l'id "<idJustificatif>"
    When je redemande le justificatif avec le motif "<motif>"
    Then le système m'informe que le traitement est "<resultat>"

    Examples:
      | idJustificatif | motif                                       | resultat |
      | 1              | Le justificatif n'est pas bon, a renvoyer   | valide   |
      | 1              | <a>test</a>                                 | invalide |

  Scenario Outline: Rechercher des justificatifs avec des filtres
    Given je suis un responsable connecté
    When je cherche les justificatifs du "<dateDebut>" au "<dateFin>" pour la matière "<matiere>"
    Then le système me retourne un résultat "<resultat>"

    Examples:
      | dateDebut  | dateFin    | matiere | resultat |
      | -1         | 2026-03-04 | R2.06   | invalide |
      | 2024-02-07 | -1         | R2.02   | invalide |
      | 2024-01-21 | 2026-03-04 | -1      | invalide |
      | 2023-06-17 | 2026-03-04 | R2.02   | valide   |