Feature:
  En tant qu'etudiant
  Je souhaite soumettre un justificatif avec accuse de reception
  Afin de justifier mon absence

  Scenario: soumettre le justificatif
  Given je suis un etudiant
  When je soumets un justificatif avec tous les champs requis etant remplis
  Then le systeme va l’ajouter dans la liste des absences avec justificatifs en attente et me notifie de la reception

  Scenario: Recevoir mail de confirmation
  Given je suis un etudiant
  When je soumets un justificatif sans tous les champs requis etant remplis
  Then le systeme va m’envoyer un message d’erreur en me disant de preciser les champs requise et me notifie de la reception